<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright		[PHPFOX_COPYRIGHT]
    * @author  		Raymond Benc
    * @package  		Module_fevent
    * @version 		$Id: callback.class.php 7059 2014-01-22 14:20:10Z Fern $
    */
    class fevent_Service_Callback extends Phpfox_Service 
    {
        /**
        * Class constructor
        */	
        public function __construct()
        {	
            $this->_sTable = Phpfox::getT('fevent');
        }

        public function getSiteStatsForAdmin($iStartTime, $iEndTime)
        {
            $aCond = array();
            $aCond[] = 'view_id = 0';
            if ($iStartTime > 0)
            {
                $aCond[] = 'AND time_stamp >= \'' . $this->database()->escape($iStartTime) . '\'';
            }	
            if ($iEndTime > 0)
            {
                $aCond[] = 'AND time_stamp <= \'' . $this->database()->escape($iEndTime) . '\'';
            }			

            $iCnt = (int) $this->database()->select('COUNT(*)')
            ->from($this->_sTable)
            ->where($aCond)
            ->execute('getSlaveField');

            return array(
                'phrase' => 'fevent.fevents',
                'total' => $iCnt
            );
        }	

        public function mobileMenu()
        {
            return array(
                'phrase' => Phpfox::getPhrase('fevent.fevents'),
                'link' => Phpfox::getLib('url')->makeUrl('fevent'),
                'icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'mobile/small_fevents.png'))
            );
        }	

        public function getAjaxCommentVar()
        {
            return 'fevent.can_post_comment_on_fevent';
        }	

        public function getDashboardActivity()
        {
            $aUser = Phpfox::getService('user')->get(Phpfox::getUserId(), true);

            return array(
                Phpfox::getPhrase('fevent.fevents') => $aUser['activity_fevent']
            );
        }

        public function getCommentItem($iId)
        {		
            $aRow = $this->database()->select('feed_comment_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
            ->from(Phpfox::getT('fevent_feed_comment'))
            ->where('feed_comment_id = ' . (int) $iId)
            ->execute('getSlaveRow');		

            $aRow['comment_view_id'] = '0';

            if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
            {
                Phpfox_Error::set(Phpfox::getPhrase('fevent.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));

                unset($aRow['comment_item_id']);
            }		

            $aRow['parent_module_id'] = 'fevent';

            return $aRow;
        }	

        public function getFeedDetails($iItemId)
        {
            return array(
                'module' => 'fevent',
                'table_prefix' => 'fevent_',
                'item_id' => $iItemId
            );		
        }		

        public function addComment($aVals, $iUserId = null, $sUserName = null)
        {		
            $aRow = $this->database()->select('fc.feed_comment_id, fc.user_id, e.fevent_id, e.title, u.full_name, u.gender')
            ->from(Phpfox::getT('fevent_feed_comment'), 'fc')
            ->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = fc.parent_user_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
            ->where('fc.feed_comment_id = ' . (int) $aVals['item_id'])
            ->execute('getSlaveRow');

            // Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
            if (empty($aVals['parent_id']))
            {
                $this->database()->updateCounter('fevent_feed_comment', 'total_comment', 'feed_comment_id', $aRow['feed_comment_id']);		
            }

            // Send the user an email
            $sLink = Phpfox::getLib('url')->permalink(array('fevent', 'comment-id' => $aRow['feed_comment_id']), $aRow['fevent_id'], $aRow['title']);
            $sItemLink = Phpfox::getLib('url')->permalink('fevent', $aRow['fevent_id'], $aRow['title']);

            Phpfox::getService('comment.process')->notify(array(
                'user_id' => $aRow['user_id'],
                'item_id' => $aRow['feed_comment_id'],
                'owner_subject' => Phpfox::getPhrase('fevent.full_name_commented_on_a_comment_posted_on_the_fevent_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])),
                'owner_message' => Phpfox::getPhrase('fevent.full_name_commented_on_one_of_your_comments_you_posted_on_the_fevent', array('full_name' => Phpfox::getUserBy('full_name'), 'item_link' => $sItemLink, 'title' => $aRow['title'], 'link' => $sLink)),
                'owner_notification' => 'comment.add_new_comment',
                'notify_id' => 'fevent_comment_feed',
                'mass_id' => 'fevent',
                'mass_subject' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('fevent.full_name_commented_on_one_of_gender_fevent_comments', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1))) : Phpfox::getPhrase('fevent.full_name_commented_on_one_of_row_full_name_s_fevent_comments', array('full_name' => Phpfox::getUserBy('full_name'), 'row_full_name' => $aRow['full_name']))),
                'mass_message' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('fevent.full_name_commented_on_one_of_gender_own_comments_on_the_fevent', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'item_link' => $sItemLink, 'title' => $aRow['title'], 'link' => $sLink)) : Phpfox::getPhrase('fevent.full_name_commented_on_one_of_row_full_name_s', array('full_name' => Phpfox::getUserBy('full_name'), 'row_full_name' => $aRow['full_name'], 'item_link' => $sItemLink, 'title' => $aRow['title'], 'link' => $sLink)))
                )
            );
        }

        public function getNotificationComment_Feed($aNotification)
        {
            return $this->getCommentNotification($aNotification);	
        }

        public function uploadVideo($aVals)
        {
            return array(
                'module' => 'fevent',
                'item_id' => (is_array($aVals) && isset($aVals['callback_item_id']) ? $aVals['callback_item_id'] : (int) $aVals)
            );
        }

        public function convertVideo($aVideo)
        {
            return array(
                'module' => 'fevent',
                'item_id' => $aVideo['item_id'],
                'table_prefix' => 'fevent_'
            );			
        }

        public function getCommentNotification($aNotification)
        {
            $aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.fevent_id, e.title')
            ->from(Phpfox::getT('fevent_feed_comment'), 'fc')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
            ->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = fc.parent_user_id')
            ->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

            if (!isset($aRow['feed_comment_id']))
            {
                return false;
            }

            $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
            $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

            $sPhrase = '';
            if ($aNotification['user_id'] == $aRow['user_id'])
            {
                if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
                {
                    $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_span_class_drop_data_user_row_full_name_s_span_comment_on_the_fevent_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
                }
                else 
                {
                    $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_gender_own_comment_on_the_fevent_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
                }
            }
            elseif ($aRow['user_id'] == Phpfox::getUserId())		
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_one_of_your_comments_on_the_fevent_title', array('users' => $sUsers, 'title' => $sTitle));
            }
            else 
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_one_of_span_class_drop_data_user_row_full_name_s_span_comments_on_the_fevent_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }

            return array(
                'link' => Phpfox::getLib('url')->permalink(array('fevent', 'comment-id' => $aRow['feed_comment_id']), $aRow['fevent_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );
        }		

        public function getNotificationComment($aNotification)
        {
            $aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.fevent_id, e.title')
            ->from(Phpfox::getT('fevent_feed_comment'), 'fc')			
            ->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = fc.parent_user_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
            ->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

            $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
            $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

            $sPhrase = '';
            if ($aNotification['user_id'] == $aRow['user_id'])
            {
                if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
                {
                    $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_span_class_drop_data_user_row_full_name_s_span_fevent_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' =>  $sTitle));
                }
                else 
                {
                    $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_gender_own_fevent_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
                }
            }
            elseif ($aRow['user_id'] == Phpfox::getUserId())		
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_your_fevent_title', array('users' => $sUsers, 'title' => $sTitle));
            }
            else 
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_commented_on_span_class_drop_data_user_row_full_name_s_span_fevent_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }

            return array(
                'link' => Phpfox::getLib('url')->permalink(array('fevent', 'comment-id' => $aRow['feed_comment_id']), $aRow['fevent_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );
        }

        public function getNotificationComment_Like($aNotification)
        {
            $aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.fevent_id, e.title')
            ->from(Phpfox::getT('fevent_feed_comment'), 'fc')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
            ->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = fc.parent_user_id')
            ->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

            $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
            $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

            $sPhrase = '';
            if ($aNotification['user_id'] == $aRow['user_id'])
            {
                if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
                {
                    $sPhrase = Phpfox::getPhrase('fevent.users_liked_span_class_drop_data_user_row_full_name_s_span_comment_on_the_fevent_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
                }
                else 
                {
                    $sPhrase = Phpfox::getPhrase('fevent.users_liked_gender_own_comment_on_the_fevent_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
                }
            }
            elseif ($aRow['user_id'] == Phpfox::getUserId())		
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_liked_one_of_your_comments_on_the_fevent_title', array('users' => $sUsers, 'title' => $sTitle));
            }
            else 
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_liked_one_on_span_class_drop_data_user_row_full_name_s_span_comments_on_the_fevent_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }

            return array(
                'link' => Phpfox::getLib('url')->permalink(array('fevent', 'comment-id' => $aRow['feed_comment_id']), $aRow['fevent_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );
        }	

        /**
        * Enables a sponsor after being paid for or admin approved
        * @param <type> $iId
        * @param <type> $sSection
        */
        public function enableSponsor($aParams)
        {
            return Phpfox::getService('fevent.process')->sponsor((int)$aParams['item_id'], 1);	    
        }

        public function updateCommentText($aVals, $sText)
        {
            $afevent = $this->database()->select('m.fevent_id, m.title, m.title_url, u.full_name, u.user_id, u.user_name')
            ->from($this->_sTable, 'm')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = m.user_id')
            ->where('m.fevent_id = ' . (int) $aVals['item_id'])
            ->execute('getSlaveRow');

            (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->update('comment_fevent', $aVals['item_id'], serialize(array('content' => $sText, 'title' => $afevent['title'])), $aVals['comment_id']) : null);
        }	

        public function getItemName($iId, $sName)
        {
            return Phpfox::getPhrase('fevent.a_href_link_on_name_s_fevent_a',array('link' => Phpfox::getLib('url')->makeUrl('comment.view', array('id' => $iId)), 'name' => $sName));		
        }	

        public function getLink($aParams)
        {
            // get the owner of this song
            $afevent = $this->database()->select('e.fevent_id, e.title')
            ->from(Phpfox::getT('fevent'),'e')
            ->where('e.fevent_id = ' . (int)$aParams['item_id'])
            ->execute('getSlaveRow');
            if (empty($afevent))
            {
                return false;
            }
            //return Phpfox::getLib('url')->makeUrl('fevent.view.' . $afevent['title_url'] );
            return Phpfox::permalink('fevent', $afevent['fevent_id'], $afevent['title']);
        }

        public function getCommentNewsFeed($aRow)
        {		
            $oUrl = Phpfox::getLib('url');
            $oParseOutput = Phpfox::getLib('parse.output');		

            if (!Phpfox::getLib('parse.format')->isSerialized($aRow['content']))
            {
                return false;
            }

            $aParts = unserialize($aRow['content']);	
            $aRow['text'] = Phpfox::getPhrase('fevent.a_href_user_link_user_name_a_added_a_comment_on_the_fevent_a_href_title_link_title_a', array(
                'user_name' => $aRow['owner_full_name'],
                'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),	
                'title_link' => $aRow['link'],		
                'title' => Phpfox::getService('feed')->shortenTitle($aParts['title'])
                )
            );

            $aRow['text'] .= Phpfox::getService('feed')->quote($aParts['content']);

            return $aRow;
        }	

        public function getFeedRedirectFeedLike($iId, $iChild)
        {
            return $this->getFeedRedirect($iChild);
        }

        public function getFeedRedirect($iId, $iChild = 0)
        {
            $aListing = $this->database()->select('m.fevent_id, m.title')
            ->from($this->_sTable, 'm')
            ->where('m.fevent_id = ' . (int) $iId)
            ->execute('getSlaveRow');

            if (!isset($aListing['fevent_id']))
            {
                return false;
            }

            return Phpfox::permalink('fevent', $aListing['fevent_id'], $aListing['title']);
        }	

        public function deleteComment($iId)
        {
            $this->database()->updateCounter('fevent', 'total_comment', 'fevent_id', $iId, true);
        }	

        public function getReportRedirect($iId)
        {
            return $this->getFeedRedirect($iId);
        }

        public function getNewsFeed($aRow)
        {
            if ($sPlugin = Phpfox_Plugin::get('fevent.service_callback_getnewsfeed_start')){eval($sPlugin);}
            $oUrl = Phpfox::getLib('url');
            $oParseOutput = Phpfox::getLib('parse.output');		

            $aRow['text'] = Phpfox::getPhrase('fevent.owner_full_name_added_a_new_fevent_title', array(
                'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),
                'owner_full_name' => $aRow['owner_full_name'],
                'title_link' => $aRow['link'],
                'title' => Phpfox::getService('feed')->shortenTitle($aRow['content'])
                )
            );

            $aRow['icon'] = 'module/fevent.png';
            $aRow['enable_like'] = true;

            return $aRow;
        }

        public function groupMenu($sGroupUrl, $iGroupId)
        {
            if (!Phpfox::getService('group')->hasAccess($iGroupId, 'can_use_fevent'))
            {
                return false;
            }

            return array(
                Phpfox::getPhrase('fevent.fevents') => array(
                    'active' => 'fevent',
                    'url' => Phpfox::getLib('url')->makeUrl('group', array($sGroupUrl, 'fevent')
                    )
                )
            );
        }

        public function deleteGroup($iId)
        {
            $afevents = $this->database()->select('*')
            ->from($this->_sTable)
            ->where('module_id = \'group\' AND item_id = ' . (int) $iId)
            ->execute('getRows');

            foreach ($afevents as $afevent)
            {
                Phpfox::getService('fevent.process')->delete($afevent['fevent_id'], $afevent);
            }

            return true;
        }

        public function getDashboardLinks()
        {
            return array(
                'submit' => array(
                    'phrase' => Phpfox::getPhrase('fevent.create_an_fevent'),
                    'link' => 'fevent.add',
                    'image' => 'misc/calendar_add.png'
                ),
                'edit' => array(
                    'phrase' => Phpfox::getPhrase('fevent.manage_fevents'),
                    'link' => 'fevent.view_my',
                    'image' => 'misc/calendar_edit.png'
                )
            );
        }	

        public function getBlockDetailsProfile()
        {
            return array(
                'title' => Phpfox::getPhrase('fevent.fevents')
            );
        }

        public function hideBlockProfile($sType)
        {
            return array(
                'table' => 'user_design_order'
            );		
        }	

        /**
        * Action to take when user cancelled their account
        * @param int $iUser
        */
        public function onDeleteUser($iUser)
        {
            $afevents = $this->database()
            ->select('fevent_id')
            ->from($this->_sTable)
            ->where('user_id = ' . (int)$iUser)
            ->execute('getSlaveRows');

            foreach ($afevents as $afevent)
            {
                Phpfox::getService('fevent.process')->delete($afevent['fevent_id']);
            }
        }

        public function getGroupPosting()
        {
            return array(
                Phpfox::getPhrase('fevent.can_create_fevent') => 'can_create_fevent'
            );
        }	

        public function getGroupAccess()
        {
            return array(
                Phpfox::getPhrase('fevent.view_fevents') => 'can_use_fevent'
            );
        }

        public function getNotificationFeedApproved($aRow)
        {
            return array(
                'message' => Phpfox::getPhrase('fevent.your_fevent_title_has_been_approved', array('title' => Phpfox::getLib('parse.output')->shorten($aRow['item_title'], 20, '...'))),
                'link' => Phpfox::getLib('url')->makeUrl('fevent', array('redirect' => $aRow['item_id'])),
                'path' => 'fevent.url_image',
                'suffix' => '_120'
            );		
        }	

        public function getGlobalPrivacySettings()
        {
            return array(
                'fevent.display_on_profile' => array(
                    'phrase' => Phpfox::getPhrase('fevent.fevents'),
                    'default' => '0'				
                )
            );
        }		

        public function pendingApproval()
        {
            return array(
                'phrase' => Phpfox::getPhrase('fevent.fevents'),
                'value' => Phpfox::getService('fevent')->getPendingTotal(),
                'link' => Phpfox::getLib('url')->makeUrl('fevent', array('view' => 'pending'))
            );
        }	

        public function getUserCountFieldInvite()
        {
            return 'fevent_invite';
        }	

        public function getNotificationFeedInvite($aRow)
        {		
            return array(
                'message' => Phpfox::getPhrase('fevent.full_name_invited_you_to_an_fevent', array(
                    'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
                    'full_name' => $aRow['full_name']
                    )
                ),
                'link' => Phpfox::getLib('url')->makeUrl('fevent', array('redirect' => $aRow['item_id']))
            );
        }		

        public function getRequestLink()
        {
            if (!Phpfox::getParam('request.display_request_box_on_empty') && !Phpfox::getUserBy('fevent_invite'))
            {
                return null;
            }

            return '<li><a href="' . Phpfox::getLib('url')->makeUrl('fevent', array('view' => 'invitation')) . '"' . (!Phpfox::getUserBy('fevent_invite') ? ' onclick="alert(\'' . Phpfox::getPhrase('fevent.no_fevent_invites') . '\'); return false;"' : '') . '><img src="' . Phpfox::getLib('template')->getStyle('image', 'module/fevent.png') . '" class="v_middle" /> ' . Phpfox::getPhrase('fevent.fevent_invites_total', array('total' => Phpfox::getUserBy('fevent_invite'))) . '</span></a></li>';
        }

        public function reparserList()
        {
            return array(
                'name' => Phpfox::getPhrase('fevent.fevent_text'),
                'table' => 'fevent_text',
                'original' => 'description',
                'parsed' => 'description_parsed',
                'item_field' => 'fevent_id'
            );
        }		

        public function getSiteStatsForAdmins()
        {
            $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

            return array(
                'phrase' => Phpfox::getPhrase('fevent.fevents'),
                'value' => $this->database()->select('COUNT(*)')
                ->from(Phpfox::getT('fevent'))
                ->where('view_id = 0 AND time_stamp >= ' . $iToday)
                ->execute('getSlaveField')
            );
        }

        /**
        * @param int $iId video_id
        * @return array in the format:
        * array(
        *	'title' => 'item title',		    <-- required
        *  'link'  => 'makeUrl()'ed link',		    <-- required
        *  'paypal_msg' => 'message for paypal'	    <-- required
        *  'item_id' => int			    <-- required
        *  'user_id;   => owner's user id		    <-- required
        *	'error' => 'phrase if item doesnt exit'	    <-- optional
        *	'extra' => 'description'		    <-- optional
        *	'image' => 'path to an image',		    <-- optional
        *	'image_dir' => 'photo.url_photo|...	    <-- optional (required if image)	     
        * )
        */
        public function getToSponsorInfo($iId)
        {
            // check that this user has access to this group
            $afevent = $this->database()->select('e.user_id, e.fevent_id as item_id, e.title, e.privacy, e.location, e.start_time, e.end_time, e.image_path as image, e.server_id,e.user_id')
            ->from($this->_sTable, 'e')
            ->where('e.fevent_id = ' . (int)$iId)
            ->execute('getSlaveRow');

            if (empty($afevent))
            {
                return array('error' => Phpfox::getPhrase('fevent.sponsor_error_not_found'));
            }

            if ($afevent['privacy'] > 0)
            {
                return array('error' => Phpfox::getPhrase('fevent.sponsor_error_privacy'));
            }

            $afevent['title'] = Phpfox::getPhrase('fevent.sponsor_title', array('sfeventTitle' => $afevent['title']));
            $afevent['paypal_msg'] = Phpfox::getPhrase('fevent.sponsor_paypal_message', array('sfeventTitle' => $afevent['title']));
            //$afevent['link'] = Phpfox::getLib('url')->makeUrl('fevent.view.'.$afevent['title_url']);	    
            $afevent['link'] = Phpfox::permalink('fevent', $afevent['item_id'], $afevent['title']);
            $afevent['extra'] = '<b>'.Phpfox::getPhrase('fevent.date').'</b> ' . Phpfox::getTime('l, F j, Y g:i a', $afevent['start_time']) . ' - ';

            if (date('dmy', $afevent['start_time']) === date('dmy', $afevent['end_time']))
            {
                $afevent['extra'] .= Phpfox::getTime('g:i a', $afevent['end_time']);
            }
            else
            {
                $afevent['extra'] .= Phpfox::getTime('l, F j, Y g:i a', $afevent['end_time']);
            }

            if (isset($afevent['image']) && $afevent['image'] != '')
            {
                $afevent['image_dir'] = 'fevent.url_image';
                $afevent['image'] = sprintf($afevent['image'],'_200');
            }

            return $afevent;
        }

        public function getNewsFeedFeedLike($aRow)
        {
            if ($aRow['owner_user_id'] == $aRow['viewer_user_id'])
            {
                $aRow['text'] = Phpfox::getPhrase('fevent.a_href_user_link_full_name_a_liked_their_own_a_href_link_fevent_a', array(
                    'full_name' => Phpfox::getLib('parse.output')->clean($aRow['owner_full_name']),
                    'user_link' => Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']),
                    'link' => $aRow['link']
                    )
                );
            }
            else 
            {
                $aRow['text'] = Phpfox::getPhrase('fevent.a_href_user_link_full_name_a_liked_a_href_view_user_link_view_full_name_a_s_a_href_link_fevent_a', array(
                    'full_name' => Phpfox::getLib('parse.output')->clean($aRow['owner_full_name']),
                    'user_link' => Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']),
                    'view_full_name' => Phpfox::getLib('parse.output')->clean($aRow['viewer_full_name']),
                    'view_user_link' => Phpfox::getLib('url')->makeUrl($aRow['viewer_user_name']),
                    'link' => $aRow['link']			
                    )
                );
            }

            $aRow['icon'] = 'misc/thumb_up.png';

            return $aRow;				
        }	

        public function getNotificationFeedNotifyLike($aRow)
        {		
            return array(
                'message' => Phpfox::getPhrase('fevent.a_href_user_link_full_name_a_liked_your_a_href_link_fevent_a', array(
                    'full_name' => Phpfox::getLib('parse.output')->clean($aRow['full_name']),
                    'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
                    'link' => Phpfox::getLib('url')->makeUrl('fevent', array('redirect' => $aRow['item_id']))
                    )
                ),
                'link' => Phpfox::getLib('url')->makeUrl('fevent', array('redirect' => $aRow['item_id'])),
                'path' => 'fevent.url_image',
                'suffix' => '_120'
            );				
        }

        public function sendLikeEmail($iItemId)
        {
            return Phpfox::getPhrase('fevent.a_href_user_link_full_name_a_liked_your_a_href_link_fevent_a', array(
                'full_name' => Phpfox::getLib('parse.output')->clean(Phpfox::getUserBy('full_name')),
                'user_link' => Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name')),
                'link' => Phpfox::getLib('url')->makeUrl('fevent', array('redirect' => $iItemId))
                )
            );
        }

        public function getRedirectComment($iId)
        {
            return $this->getFeedRedirect($iId);
        }	

        public function getSqlTitleField()
        {
            return array(
                'table' => 'fevent',
                'field' => 'title',
                'has_index' => 'title'
            );
        }		

        public function updateCounterList()
        {
            $aList = array();				

            $aList[] =	array(
                'name' => Phpfox::getPhrase('fevent.fevent_invite_count'),
                'id' => 'fevent-invite-count'
            );

            return $aList;
        }		

        public function updateCounter($iId, $iPage, $iPageLimit)
        {
            if ($iId == 'fevent-invite-count')
            {
                $iCnt = $this->database()->select('COUNT(*)')
                ->from(Phpfox::getT('user'))
                ->execute('getSlaveField');

                $aRows = $this->database()->select('u.user_id, COUNT(gi.invite_id) AS total_invites')
                ->from(Phpfox::getT('user'), 'u')
                ->leftJoin(Phpfox::getT('fevent_invite'), 'gi', 'gi.invited_user_id = u.user_id')
                ->group('u.user_id')
                ->limit($iPage, $iPageLimit, $iCnt)
                ->execute('getSlaveRows');

                foreach ($aRows as $aRow)
                {
                    $this->database()->update(Phpfox::getT('user_count'), array('fevent_invite' => $aRow['total_invites']), 'user_id = ' . (int) $aRow['user_id']);
                }

                return $iCnt;			
            }		
        }	

        public function getActivityFeedComment($aItem)
        {
            if (Phpfox::isModule('like'))
            {
                $this->database()->select('l.like_id AS is_liked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'fevent_comment\' AND l.item_id = fc.feed_comment_id AND l.user_id = ' . Phpfox::getUserId());
            }

            $aRow = $this->database()->select('fc.*, e.fevent_id, e.title')
            ->from(Phpfox::getT('fevent_feed_comment'), 'fc')
            ->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = fc.parent_user_id')
            ->where('fc.feed_comment_id = ' . (int) $aItem['item_id'])
            ->execute('getSlaveRow');		

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }

            $sLink = Phpfox::getLib('url')->permalink(array('fevent', 'comment-id' => $aRow['feed_comment_id']), $aRow['fevent_id'], $aRow['title']);

            $aReturn = array(
                'no_share' => true,
                'feed_status' => $aRow['content'],
                'feed_link' => $sLink,
                'total_comment' => $aRow['total_comment'],
                'feed_total_like' => $aRow['total_like'],
                'feed_is_liked' => (isset($aRow['is_liked']) ? $aRow['is_liked'] : false),
                'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
                'time_stamp' => $aRow['time_stamp'],
                'enable_like' => true,			
                'comment_type_id' => 'fevent',
                'like_type_id' => 'fevent_comment',
                // http://www.phpfox.com/tracker/view/14689/
                'parent_user_id' => 0	
            );

            return $aReturn;		
        }	

        public function addLikeComment($iItemId, $bDoNotSendEmail = false)
        {
            $aRow = $this->database()->select('fc.feed_comment_id, fc.content, fc.user_id, e.fevent_id, e.title')
            ->from(Phpfox::getT('fevent_feed_comment'), 'fc')
            ->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = fc.parent_user_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
            ->where('fc.feed_comment_id = ' . (int) $iItemId)
            ->execute('getSlaveRow');

            if (!isset($aRow['feed_comment_id']))
            {
                return false;
            }

            $this->database()->updateCount('like', 'type_id = \'fevent_comment\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'fevent_feed_comment', 'feed_comment_id = ' . (int) $iItemId);	

            if (!$bDoNotSendEmail)
            {
                $sLink = Phpfox::getLib('url')->permalink(array('fevent', 'comment-id' => $aRow['feed_comment_id']), $aRow['fevent_id'], $aRow['title']);
                $sItemLink = Phpfox::getLib('url')->permalink('fevent', $aRow['fevent_id'], $aRow['title']);

                Phpfox::getLib('mail')->to($aRow['user_id'])
                ->subject(array('fevent.full_name_liked_a_comment_you_posted_on_the_fevent_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
                ->message(array('fevent.full_name_liked_your_comment_message_fevent', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'content' => Phpfox::getLib('parse.output')->shorten($aRow['content'], 50, '...'), 'item_link' => $sItemLink, 'title' => $aRow['title'])))
                ->notification('like.new_like')
                ->send();

                Phpfox::getService('notification.process')->add('fevent_comment_like', $aRow['feed_comment_id'], $aRow['user_id']);
            }
        }		

        public function deleteLikeComment($iItemId)
        {
            $this->database()->updateCount('like', 'type_id = \'fevent_comment\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'fevent_feed_comment', 'feed_comment_id = ' . (int) $iItemId);	
        }	

        public function addPhoto($iId)
        {
            return array(
                'module' => 'fevent',
                'item_id' => $iId,
                'table_prefix' => 'fevent_'
            );
        }	

        public function addLink($aVals)
        {
            return array(
                'module' => 'fevent',
                'item_id' => $aVals['callback_item_id'],
                'table_prefix' => 'fevent_'
            );		
        }

        public function getFeedDisplay($ifevent)
        {
            return array(
                'module' => 'fevent',
                'table_prefix' => 'fevent_',
                'ajax_request' => 'fevent.addFeedComment',
                'item_id' => $ifevent
            );
        }

        public function addLike($iItemId, $bDoNotSendEmail = false)
        {
            $aRow = $this->database()->select('fevent_id, title, user_id')
            ->from(Phpfox::getT('fevent'))
            ->where('fevent_id = ' . (int) $iItemId)
            ->execute('getSlaveRow');		

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }

            $this->database()->updateCount('like', 'type_id = \'fevent\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'fevent', 'fevent_id = ' . (int) $iItemId);	

            if (!$bDoNotSendEmail)
            {
                $sLink = Phpfox::permalink('fevent', $aRow['fevent_id'], $aRow['title']);

                Phpfox::getLib('mail')->to($aRow['user_id'])
                ->subject(array('fevent.full_name_liked_your_fevent_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
                ->message(array('fevent.full_name_liked_your_fevent_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['title'])))
                ->notification('like.new_like')
                ->send();

                Phpfox::getService('notification.process')->add('fevent_like', $aRow['fevent_id'], $aRow['user_id']);				
            }		
        }

        public function deleteLike($iItemId)
        {
            $this->database()->updateCount('like', 'type_id = \'fevent\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'fevent', 'fevent_id = ' . (int) $iItemId);
        }

        public function getNotificationLike($aNotification)
        {
            $aRow = $this->database()->select('e.fevent_id, e.title, e.user_id, u.gender, u.full_name')	
            ->from(Phpfox::getT('fevent'), 'e')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
            ->where('e.fevent_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }			

            $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
            $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

            $sPhrase = '';
            if ($aNotification['user_id'] == $aRow['user_id'])
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_liked_gender_own_fevent_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
            }
            elseif ($aRow['user_id'] == Phpfox::getUserId())		
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_liked_your_fevent_title', array('users' => $sUsers, 'title' => $sTitle));
            }
            else 
            {
                $sPhrase = Phpfox::getPhrase('fevent.users_liked_span_class_drop_data_user_row_full_name_s_span_fevent_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }

            return array(
                'link' => Phpfox::getLib('url')->permalink('fevent', $aRow['fevent_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );	
        }	

        public function canShareItemOnFeed(){}

        public function getActivityFeedCustomChecks($aRow)
        {
            if ((defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages')->hasPerm(null, 'fevent.view_browse_fevents'))
                || (!defined('PHPFOX_IS_PAGES_VIEW') && $aRow['custom_data_cache']['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aRow['custom_data_cache']['item_id'], 'fevent.view_browse_fevents'))
            )
            {
                return false;
            }

            return $aRow;
        }

        public function getActivityFeed($aItem, $aCallback = null, $bIsChildItem = false)
        {				
            if ($bIsChildItem)
            {
                $this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = e.user_id');
            }			
            $sSelect = 'e.fevent_id, e.module_id, e.item_id, e.title, e.time_stamp, e.image_path, e.server_id, e.total_like, e.total_comment, et.description_parsed';
            if (Phpfox::isModule('like'))
            {
                $sSelect .= ', l.like_id AS is_liked';
                $this->database()->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'fevent\' AND l.item_id = e.fevent_id AND l.user_id = ' . Phpfox::getUserId());
            }
            $aRow = $this->database()->select($sSelect)
            ->from(Phpfox::getT('fevent'), 'e')
            ->leftJoin(Phpfox::getT('fevent_text'), 'et', 'et.fevent_id = e.fevent_id')
            ->where('e.fevent_id = ' . (int) $aItem['item_id'])
            ->execute('getSlaveRow');

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }

            if ($bIsChildItem)
            {
                $aItem = $aRow;
            }			

            if (((defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages')->hasPerm(null, 'fevent.view_browse_fevents'))
                || (!defined('PHPFOX_IS_PAGES_VIEW') && $aRow['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aRow['item_id'], 'fevent.view_browse_fevents')))
            )
            {
                return false;
            }

            $aReturn = array(
                'feed_title' => $aRow['title'],
                'feed_info' => Phpfox::getPhrase('feed.created_an_fevent'),
                'feed_link' => Phpfox::permalink('fevent', $aRow['fevent_id'], $aRow['title']),
                'feed_content' => $aRow['description_parsed'],
                'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/fevent.png', 'return_url' => true)),
                'time_stamp' => $aRow['time_stamp'],	
                'feed_total_like' => $aRow['total_like'],
                'feed_is_liked' => isset($aRow['is_liked']) ? $aRow['is_liked'] : false,
                'enable_like' => true,			
                'like_type_id' => 'fevent',
                'total_comment' => $aRow['total_comment'],
                'custom_data_cache' => $aRow
            );

            if (!empty($aRow['image_path']))
            {
                $sImage = Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aRow['server_id'],
                    'path' => 'fevent.url_image',
                    'file' => $aRow['image_path'],
                    'suffix' => '_120',
                    'max_width' => 120,
                    'max_height' => 120					
                    )
                );

                $aReturn['feed_image'] = $sImage;
            }		

            if ($bIsChildItem)
            {
                $aReturn = array_merge($aReturn, $aItem);
            }		

            (($sPlugin = Phpfox_Plugin::get('fevent.component_service_callback_getactivityfeed__1')) ? eval($sPlugin) : false);

            return $aReturn;
        }		

        public function getNotificationApproved($aNotification)
        {
            $aRow = $this->database()->select('e.fevent_id, e.title, e.user_id, u.gender, u.full_name')	
            ->from(Phpfox::getT('fevent'), 'e')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
            ->where('e.fevent_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');	

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }

            $sPhrase = Phpfox::getPhrase('fevent.your_fevent_title_has_been_approved', array('title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));

            return array(
                'link' => Phpfox::getLib('url')->permalink('fevent', $aRow['fevent_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
                'no_profile_image' => true
            );			
        }	

        public function getNotificationInvite($aNotification)
        {
            $aRow = $this->database()->select('e.fevent_id, e.title, e.user_id, u.full_name')	
            ->from(Phpfox::getT('fevent'), 'e')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
            ->where('e.fevent_id = ' . (int) $aNotification['item_id'])
            ->execute('getSlaveRow');

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }			

            $sPhrase = Phpfox::getPhrase('fevent.users_invited_you_to_the_fevent_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));

            return array(
                'link' => Phpfox::getLib('url')->permalink('fevent', $aRow['fevent_id'], $aRow['title']),
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );	
        }

        public function getProfileMenu($aUser)
        {
            if (!Phpfox::getParam('profile.show_empty_tabs'))
            {		
                if (!isset($aUser['total_fevent']))
                {
                    return false;
                }

                if (isset($aUser['total_fevent']) && (int) $aUser['total_fevent'] === 0)
                {
                    return false;
                }
            }
            $aTotal = $this->getTotalItemCount($aUser['user_id']);
            $aMenus[] = array(
                'phrase' => Phpfox::getPhrase('profile.fevents'),
                'url' => 'profile.fevent',
                'total' => $aTotal['total'],
                'icon' => 'module/fevent.png'	
            );	

            return $aMenus;
        }	

        public function getTotalItemCount($iUserId)
        {
            $iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            // get the fevents for this user that apply to the future
            if (Phpfox::getParam('fevent.cache_fevents_per_user'))
            {
                $sCacheId = $this->cache()->set(array('fevents_by_user', (int)$iUserId));
                if ( !($afevents = $this->cache()->get($sCacheId)) )
                {
                    $afevents = $this->database()->select('/* getTotalItemCount */ e.*')
                    ->from(Phpfox::getT('fevent'), 'e')
                    ->where('user_id = ' . (int)$iUserId)
                    ->execute('getSlaveRows');

                    $sCacheId = $this->cache()->set(array('fevents_by_user', (int)$iUserId));
                    $this->cache()->save($sCacheId, $afevents);
                }

                $iTotal = 0;
                if (is_array($afevents)) // This happens if the array was stored empty
                {
                    foreach ($afevents as $afevent)
                    {
                        if ($afevent['start_time'] > $iToday)
                        {
                            $iTotal = $iTotal + 1;
                        }
                    }
                }

            }

            // this happens if it was not cached for this user
            if (!isset($iTotal))
            {
                $iTotal = $this->database()->select('COUNT(*)')
                ->from(Phpfox::getT('fevent'))
                ->where('view_id = 0 AND item_id = 0 AND user_id = ' . (int) $iUserId . ' AND start_time > ' . $iToday)
                ->execute('getSlaveField');
            }
            return array(
                'field' => 'total_fevent',
                'total' => $iTotal
            );	
        }	

        public function getProfileLink()
        {
            return 'profile.marketplace';
        }

        public function getPhotoDetails($aPhoto)
        {
            $aRow = $this->database()->select('fevent_id, title')
            ->from(Phpfox::getT('fevent'))
            ->where('fevent_id = ' . (int) $aPhoto['group_id'])
            ->execute('getSlaveRow');

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }

            $sLink = Phpfox::permalink('fevent', $aRow['fevent_id'], $aRow['title']);

            return array(
                'breadcrumb_title' => Phpfox::getPhrase('fevent.fevents'),
                'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('fevent'),
                'module_id' => 'fevent',
                'item_id' => $aRow['fevent_id'],
                'title' => $aRow['title'],
                'url_home' => $sLink,
                'url_home_photo' => Phpfox::permalink(array('fevent', 'photo'), $aRow['fevent_id'], $aRow['title']),
                'theater_mode' => Phpfox::getPhrase('fevent.in_the_fevent_a_href_link_title_a', array('link' => $sLink, 'title' => $aRow['title']))
            );
        }

        public function globalUnionSearch($sSearch)
        {
            $this->database()->select('item.fevent_id AS item_id, item.title AS item_title, item.time_stamp AS item_time_stamp, item.user_id AS item_user_id, \'fevent\' AS item_type_id, item.image_path AS item_photo, item.server_id AS item_photo_server')
            ->from(Phpfox::getT('fevent'), 'item')
            ->where('item.view_id = 0 AND item.privacy = 0 AND item.item_id = 0 AND ' . $this->database()->searchKeywords('item.title', $sSearch))
            ->union();
        }

        public function getSearchInfo($aRow)
        {
            $aInfo = array();
            $aInfo['item_link'] = Phpfox::getLib('url')->permalink('fevent', $aRow['item_id'], $aRow['item_title']);
            $aInfo['item_name'] = Phpfox::getPhrase('fevent.fevents');

            $aInfo['item_display_photo'] = Phpfox::getLib('image.helper')->display(array(
                'server_id' => $aRow['item_photo_server'],
                'file' => $aRow['item_photo'],
                'path' => 'fevent.url_image',
                'suffix' => '_120',
                'max_width' => '120',
                'max_height' => '120'				
                )
            );		

            return $aInfo;
        }

        public function getSearchTitleInfo()
        {
            return array(
                'name' => Phpfox::getPhrase('fevent.fevents')
            );
        }

        public function getPageMenu($aPage)
        {
            if (!Phpfox::getService('pages')->hasPerm($aPage['page_id'], 'fevent.view_browse_fevents'))
            {
                return null;
            }		

            $aMenus[] = array(
                'phrase' => Phpfox::getPhrase('pages.fevents'),
                'url' => Phpfox::getService('pages')->getUrl($aPage['page_id'], $aPage['title'], $aPage['vanity_url']) . 'fevent/',
                'icon' => 'module/fevent.png',
                'landing' => 'fevent'
            );

            return $aMenus;
        }

        public function getPageSubMenu($aPage)
        {
            if (!Phpfox::getService('pages')->hasPerm($aPage['page_id'], 'fevent.share_fevents'))
            {
                return null;
            }		

            return array(
                array(
                    'phrase' => Phpfox::getPhrase('fevent.create_new_fevent'),
                    'url' => Phpfox::getLib('url')->makeUrl('fevent.add', array('module' => 'pages', 'item' => $aPage['page_id']))
                )
            );
        }	

        public function getPagePerms()
        {
            $aPerms = array();

            $aPerms['fevent.share_fevents'] = Phpfox::getPhrase('fevent.who_can_share_fevents');
            $aPerms['fevent.view_browse_fevents'] = Phpfox::getPhrase('fevent.who_can_view_browse_fevents');

            return $aPerms;
        }

        public function canViewPageSection($iPage)
        {		
            if (!Phpfox::getService('pages')->hasPerm($iPage, 'fevent.view_browse_fevents'))
            {
                return false;
            }

            return true;
        }	

        public function getVideoDetails($aItem)
        {		
            $aRow = $this->database()->select('fevent_id, title')
            ->from(Phpfox::getT('fevent'))
            ->where('fevent_id = ' . (int) $aItem['item_id'])
            ->execute('getSlaveRow');

            if (!isset($aRow['fevent_id']))
            {
                return false;
            }		

            $sLink = Phpfox::permalink(array('fevent', 'video'), $aRow['fevent_id'], $aRow['title']);

            return array(
                'breadcrumb_title' => Phpfox::getPhrase('fevent.fevent'),
                'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('fevent'),
                'module_id' => 'fevent',
                'item_id' => $aRow['fevent_id'],
                'title' => $aRow['title'],
                'url_home' => $sLink,
                'url_home_photo' => $sLink,
                //	'theater_mode' => 'In the page <a href="' . $sLink . '">' . $aRow['title'] . '</a>'
            );
        }

        public function getCommentNotificationTag($aNotification)
        {
            $aRow = $this->database()->select('e.fevent_id, e.title, u.user_name, u.full_name')
            ->from(Phpfox::getT('comment'), 'c')
            ->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = c.item_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
            ->where('c.comment_id = ' . (int)$aNotification['item_id'])
            ->execute('getSlaveRow');

            $sPhrase = Phpfox::getPhrase('fevent.user_name_tagged_you_in_a_comment_in_an_fevent', array('user_name' => $aRow['full_name']));

            return array(
                'link' => Phpfox::getLib('url')->permalink('fevent', $aRow['fevent_id'], $aRow['title']) .'comment_' . $aNotification['item_id'],
                'message' => $sPhrase,
                'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
            );
        }

        public function getActions()
        {
            return array(
                'dislike' => array(
                    'enabled' => true,
                    'action_type_id' => 2, // 2 = dislike
                    'phrase' => Phpfox::getPhrase('like.dislike'),
                    'phrase_in_past_tense' => 'disliked',
                    'item_type_id' => 'fevent', // used to differentiate between photo albums and photos for example.
                    'table' => 'fevent',
                    'item_phrase' => Phpfox::getPhrase('fevent.item_phrase'),
                    'column_update' => 'total_dislike',
                    'column_find' => 'fevent_id'				
                )
            );
        }

        /**
        * If a call is made to an unknown method attempt to connect
        * it to a specific plug-in with the same name thus allowing 
        * plug-in developers the ability to extend classes.
        *
        * @param string $sMethod is the name of the method
        * @param array $aArguments is the array of arguments of being passed
        */
        public function __call($sMethod, $aArguments)
        {
            /**
            * Check if such a plug-in exists and if it does call it.
            */
            if ($sPlugin = Phpfox_Plugin::get('fevent.service_callback__call'))
            {
                return eval($sPlugin);
            }

            /**
            * No method or plug-in found we must throw a error.
            */
            Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
        }	

        // Get Menu for organization
        public function getOrganizationMenu($aOrganization)
        {
            if (!Phpfox::getService('organization')->hasPerm($aOrganization['organization_id'], 'fevent.view_browse_fevents'))
            {
                return null;
            }        

            $aMenus[] = array(
                'phrase' => Phpfox::getPhrase('organization.fevents'),
                'url' => Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']) . 'fevent/',
                'icon' => 'module/event.png',
                'landing' => 'fevent'
            );
            
            return $aMenus;
        }

        public function getOrganizationSubMenu($aOrganization)
        {
            if (!Phpfox::getService('organization')->hasPerm($aOrganization['organization_id'], 'fevent.share_fevents'))
            {
                return null;
            }        

            return array(
                array(
                    'phrase' => Phpfox::getPhrase('fevent.create_new_fevent'),
                    'url' => Phpfox::getLib('url')->makeUrl('fevent.add', array('module' => 'organization', 'item' => $aOrganization['organization_id']))
                )
            );
        }    
    }

?>
