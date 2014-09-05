<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Service
 * @version 		$Id: callback.class.php 7255 2014-04-07 17:39:00Z Fern $
 */
class organization_Service_Callback extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		
	}
	
	public function getProfileLink()
	{
		return 'profile.organization';
	}	
	
	public function getProfileMenu($aUser)
	{
        // $aUser['total_organization'] = Phpfox::getService('organization')->getorganizationCount($aUser['user_id']);
		if (Phpfox::getParam('profile.show_empty_tabs') == false)
		{
			if (!isset($aUser['total_organization']))
			{
				return false;
			}

			if (isset($aUser['total_organization']) && (int) $aUser['total_organization'] === 0)
			{
				return false;
			}
		}		
		
		$aMenus[] = array(
			'phrase' => Phpfox::getPhrase('organization.organization'),
			'url' => 'profile.organization',
			'total' => (int) (isset($aUser['total_organization']) ? $aUser['total_organization'] : 0),
			'icon' => 'feed/blog.png'
		);	
		
		return $aMenus;
	}	
	
	public function canShareItemOnFeed(){}
	
	public function getActivityFeed($aItem, $aCallback = null, $bIsChildItem = false)
	{
		if ($bIsChildItem)
		{
			$this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = p.user_id');
		}		

		$aRow = $this->database()->select('p.*, pc.organization_type, pu.vanity_url')
			->from(Phpfox::getT('organization'), 'p')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('organization_category'), 'pc', 'pc.category_id = p.category_id')
			->where('p.organization_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['organization_id']))
		{
			return false;
		}
		
		if ($bIsChildItem)
		{
			$aItem = $aRow;
		}			
		
		$aReturn = array(
			'feed_title' => $aRow['title'],
			'no_user_show' => true,
			'feed_content' => ($aRow['organization_type'] == '1' ? ($aRow['total_like'] == '1' ? Phpfox::getPhrase('organization.1_member') : Phpfox::getPhrase('organization.total_like_members', array('total_like' => $aRow['total_like']))) : ($aRow['total_like'] == '1' ? Phpfox::getPhrase('organization.1_like') : Phpfox::getPhrase('organization.total_like_likes', array('total_like' => $aRow['total_like'])))),
			'feed_link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']),
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/marketplace.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],
			'enable_like' => false,
		);
		
		if (!empty($aRow['image_path']))
		{
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aRow['server_id'],
					'path' => (($aRow['app_id'] != 0) ? 'app.url_image' : 'organization.url_image'),
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
				
		return $aReturn;		
	}
	
	public function getCommentNotificationTag($aNotification)
	{
		$aRow = $this->database()->select('b.organization_id, b.title, pu.vanity_url, u.full_name, fc.feed_comment_id')
				->from(Phpfox::getT('comment'), 'c')
				->join(Phpfox::getT('organization_feed_comment'), 'fc', 'fc.feed_comment_id = c.item_id')
				->join(Phpfox::getT('organization'), 'b', 'b.organization_id = fc.parent_user_id')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
				->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = b.organization_id')
				->where('c.comment_id = ' . (int) $aNotification['item_id'])
				->execute('getSlaveRow');
				
		$sPhrase = Phpfox::getPhrase('organization.full_name_tagged_you_on_a_organization', array('full_name' => $aRow['full_name']));
		
		return array(
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']) . 'comment-id_' . $aRow['feed_comment_id'] . '/',
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}	
	
	public function getSiteStatsForAdmin($iStartTime, $iEndTime)
	{
		$aCond = array();
		$aCond[] = 'app_id = 0 AND view_id = 0';
		if ($iStartTime > 0)
		{
			$aCond[] = 'AND time_stamp >= \'' . $this->database()->escape($iStartTime) . '\'';
		}	
		if ($iEndTime > 0)
		{
			$aCond[] = 'AND time_stamp <= \'' . $this->database()->escape($iEndTime) . '\'';
		}			
		
		$iCnt = (int) $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('organization'))
			->where($aCond)
			->execute('getSlaveField');
		
		return array(
			'phrase' => 'organization.organization',
			'total' => $iCnt
		);
	}	
	
	public function getShoutboxData()
	{
		/*
		$aGroup = $this->database()->select('group_id')
			->from($this->_sTable)
			->where('group_id = ' . (int) $iGroup . ' AND view_id = 0')
			->execute('getSlaveRow');
			
		if (!isset($aGroup['group_id']))
		{
			return Phpfox_Error::set('This group not longer exists.');
		}
		*/
		return array(
			'table' => 'organization_shoutbox'
		);
	}	
	
	public function mobileMenu()
	{
		return array(
			'phrase' => Phpfox::getPhrase('organization.organization'),
			'link' => Phpfox::getLib('url')->makeUrl('organization'),
			'icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'mobile/small_custom-fields.png'))
		);
	}	
	
	public function addPhoto($iId)
	{
		Phpfox::getService('organization')->setIsInPage();
		
		return array(
			'module' => 'organization',
			'item_id' => $iId,
			'table_prefix' => 'organization_'
		);
	}
	
	public function getDashboardActivity()
	{
		$aUser = Phpfox::getService('user')->get(Phpfox::getUserId(), true);
		
		return array(
			Phpfox::getPhrase('organization.organization') => $aUser['activity_organization']
		);
	}
	
	public function getCommentNotification($aNotification)
	{
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.organization_id, e.title, pu.vanity_url')
			->from(Phpfox::getT('organization_feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->join(Phpfox::getT('organization'), 'e', 'e.organization_id = fc.parent_user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = e.organization_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['feed_comment_id']))
		{
			return false;
		}
		
		if ($aNotification['user_id'] == $aRow['user_id'] && isset($aNotification['extra_users']) && count($aNotification['extra_users']))
		{
			$sUsers = Phpfox::getService('notification')->getUsers($aNotification, true);
		}
		else
		{
			$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		}
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');		
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
			{
				$sPhrase = Phpfox::getPhrase('organization.users_commented_on_full_name_comment', array('users' => $sUsers, 'full_name' => $aRow['full_name'], 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('organization.users_commented_on_gender_own_comment', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));	
			}
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('organization.users_commented_on_one_of_your_comments', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('organization.users_commented_on_one_of_full_name_comments', array('users' => $sUsers, 'full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
		
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
			
		return array(
			'link' => $sLink . 'wall/comment-id_' . $aRow['feed_comment_id'],
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}
	
	public function getPhotoDetails($aPhoto)
	{
		Phpfox::getService('organization')->setIsInPage();
		
		$aRow = Phpfox::getService('organization')->getPage($aPhoto['group_id']);

		if (!isset($aRow['organization_id']))
		{
			return false;
		}

		Phpfox::getService('organization')->setMode();
		
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
			
		return array(
			'breadcrumb_title' => Phpfox::getPhrase('organization.organization'),
			'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('organization'),
			'module_id' => 'organization',
			'item_id' => $aRow['organization_id'],
			'title' => $aRow['title'],
			'url_home' => $sLink,
			'url_home_photo' => $sLink . 'photo/',
			'theater_mode' => Phpfox::getPhrase('organization.in_the_organization_link_title', array('link' => $sLink, 'title' => $aRow['title']))
		);
	}
	
	public function getPhotoCount($iPageId)
	{
		$iCnt = $this->database()->select('COUNT(*)')
					->from(Phpfox::getT('photo'))
					->where("module_id = 'organization' AND group_id = " . $iPageId)
					->execute('getSlaveField');
		
		return ($iCnt > 0) ? $iCnt : 0;
	}
	
	public function getAlbumCount($iPageId)
	{
		$iCnt = $this->database()->select('COUNT(*)')
					->from(Phpfox::getT('photo_album'))
					->where("module_id = 'organization' AND group_id = " . $iPageId)
					->execute('getSlaveField');
		
		return ($iCnt > 0) ? $iCnt : 0;
	}
	
	public function uploadVideo($aVals)
	{
		Phpfox::getService('organization')->setIsInPage();
		
		return array(
			'module' => 'organization',
			'item_id' => (is_array($aVals) && isset($aVals['callback_item_id']) ? $aVals['callback_item_id'] : (int) $aVals)
		);
	}	
	
	public function convertVideo($aVideo)
	{
		return array(
			'module' => 'organization',
			'item_id' => $aVideo['item_id'],
			'table_prefix' => 'organization_'
		);			
	}	
	
	public function addLink($aVals)
	{
		return array(
			'module' => 'organization',
			'item_id' => $aVals['callback_item_id'],
			'table_prefix' => 'organization_'
		);		
	}	
	
	public function getFeedDisplay($iEvent)
	{
		return array(
			'module' => 'organization',
			'table_prefix' => 'organization_',
			'ajax_request' => 'event.addFeedComment',
			'item_id' => $iEvent
		);
	}

	public function getActivityFeedCustomChecksComment($aRow)
	{
		if ((defined('PHPFOX_IS_organization_VIEW') && !Phpfox::getService('organization')->hasPerm(null, 'organization.view_browse_updates'))
			|| (!defined('PHPFOX_IS_organization_VIEW') && !Phpfox::getService('organization')->hasPerm($aRow['custom_data_cache']['organization_id'], 'organization.view_browse_updates'))
		)
		{
			return false;
		}

		if (// !PHPFOX_IS_AJAX && /* Bug report 13383 */
			$aRow['custom_data_cache']['reg_method'] == 2 &&
			(
				(Phpfox::getLib('request')->get('req1') != 'organization') &&
				(Phpfox::getLib('request')->get('req1') != $aRow['custom_data_cache']['vanity_url'])
			)
		)
		{
			return false;
		}

		return $aRow;
	}
	
	public function getActivityFeedComment($aItem)
	{		
		$aRow = $this->database()->select('fc.*, l.like_id AS is_liked, e.reg_method, e.organization_id, apps.image_path  AS app_image_path, e.title, e.app_id AS is_app, pu.vanity_url, ' . Phpfox::getUserField('u', 'parent_'))
			->from(Phpfox::getT('organization_feed_comment'), 'fc')
			->join(Phpfox::getT('organization'), 'e', 'e.organization_id = fc.parent_user_id')
			->join(Phpfox::getT('user'), 'u', 'u.profile_organization_id = e.organization_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = e.organization_id')
			->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = e.app_id')
			->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'organization_comment\' AND l.item_id = fc.feed_comment_id AND l.user_id = ' . Phpfox::getUserId())
			->where('fc.feed_comment_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');		

		if (!isset($aRow['organization_id']))
		{
			return false;
		}		

			if ((defined('PHPFOX_IS_organization_VIEW') && !Phpfox::getService('organization')->hasPerm(null, 'organization.view_browse_updates'))
				|| (!defined('PHPFOX_IS_organization_VIEW') && !Phpfox::getService('organization')->hasPerm($aRow['organization_id'], 'organization.view_browse_updates'))
				)
			{
				return false;
			}

			if (// !PHPFOX_IS_AJAX && /* Bug report 13383 */
				$aRow['reg_method'] == 2 &&
					(
						(Phpfox::getLib('request')->get('req1') != 'organization') &&
						(Phpfox::getLib('request')->get('req1') != $aRow['vanity_url'])
					)
				)
			{
				return false;
			}

		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']) . 'wall/comment-id_' . $aItem['item_id'] . '/';		

		$aReturn = array(
			'no_share' => true,
			'feed_status' => $aRow['content'],
			'feed_link' => $sLink,
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => $aRow['is_liked'],
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => true,			
			'comment_type_id' => 'organization',
			'like_type_id' => 'organization_comment',
			'is_custom_app' => $aRow['is_app'],
			'app_image_path' => $aRow['app_image_path'],
			'custom_data_cache' => $aRow
		);

		$aReturn['parent_user_name'] = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
		
		if ($aRow['user_id'] == $aRow['parent_user_id'])
		{
			
		}
		else
		{
			if (!defined('PHPFOX_IS_organization_VIEW') && empty($_POST))
			{
				$aReturn['parent_user'] = Phpfox::getService('user')->getUserFields(true, $aRow, 'parent_');
			}		
		}
				
		return $aReturn;		
	}	
	
	public function getActivityFeedItemLiked($aItem)
	{
		$aRow = $this->database()->select('p.organization_id, p.title, p.total_like, pu.vanity_url, l.like_id AS is_liked, p.image_path, p.image_server_id')
			->from(Phpfox::getT('organization'), 'p')
			->where('p.organization_id = ' . (int) $aItem['item_id'])
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'organization\' AND l.item_id = p.organization_id AND l.user_id = ' . Phpfox::getUserId())
			->execute('getSlaveRow');
        if(!isset($aRow['organization_id']))
        {
            return false;
        }
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
		
		$aReturn = array(
			'feed_title' => '',
			'feed_info' => Phpfox::getPhrase('organization.liked_the_organization_link_title_title', array('link' => $sLink, 'link_title' => Phpfox::getLib('parse.output')->clean($aRow['title']), 'title' => Phpfox::getLib('parse.output')->clean(Phpfox::getLib('parse.output')->shorten($aRow['title'], 50, '...')))),
			'feed_link' => $sLink,
			'no_target_blank' => true,			
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => $aRow['is_liked'],
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/comment.png', 'return_url' => true)),
			'time_stamp' => $aItem['time_stamp'],			
			//'enable_like' => false,
			'like_type_id' => 'organization'
		);		
		
		if (!empty($aRow['image_path']))
		{
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aRow['image_server_id'],
					'path' => 'organization.url_image',
					'file' => $aRow['image_path'],
					'suffix' => '_120',
					'max_width' => 120,
					'max_height' => 120					
				)
			);
			
			$aReturn['feed_image'] = $sImage;
		}		
		
		return $aReturn;
	}	
	
	public function addEvent($iItem)
	{		
		Phpfox::getService('organization')->setIsInPage();
		
		$aRow = Phpfox::getService('organization')->getPage($iItem);
		
		if (!isset($aRow['organization_id']))
		{
			return false;
		}
		
		return $aRow;
	}
	
	public function viewEvent($iItem)
	{		
		$aRow = $this->addEvent($iItem);		
		
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
			
		return array(
			'breadcrumb_title' => Phpfox::getPhrase('organization.organization'),
			'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('organization'),
			'module_id' => 'organization',
			'item_id' => $aRow['organization_id'],
			'title' => $aRow['title'],
			'url_home' => $sLink,
			'url_home_pages' => $sLink . 'event/'
		);		
	}
    
    public function ViewFevent($iItem)
    {        
        $aRow = $this->addFevent($iItem);        
        
        $sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
            
        return array(
            'breadcrumb_title' => Phpfox::getPhrase('organization.organization'),
            'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('organization'),
            'module_id' => 'organization',
            'item_id' => $aRow['organization_id'],
            'title' => $aRow['title'],
            'url_home' => $sLink,
            'url_home_pages' => $sLink . 'event/'
        );        
    }
    
    public function addFevent($iItem)
    {        
        Phpfox::getService('organization')->setIsInPage();
        
        $aRow = Phpfox::getService('organization')->getPage($iItem);
        
        if (!isset($aRow['organization_id']))
        {
            return false;
        }
        
        return $aRow;
    }
	
	public function getFeedDetails($iItemId)
	{
		return array(
			'module' => 'organization',
			'table_prefix' => 'organization_',
			'item_id' => $iItemId
		);		
	}	
	
	public function deleteFeedItem($iItemId)
	{
		$aFeedComment = $this->database()->select('*')
			->from(Phpfox::getT('organization_feed_comment'))
			->where('feed_comment_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
		
		$iTotalComments = $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('organization_feed'))
			->where('type_id = \'organization_comment\' AND parent_user_id = ' . $aFeedComment['parent_user_id'])
			->execute('getSlaveField');
		
		$this->database()->update(Phpfox::getT('organization'), array('total_comment' => $iTotalComments), 'organization_id = ' . (int) $aFeedComment['parent_user_id']);
	}
	
	public function getNotificationInvite($aNotification)
	{
		$aRow = Phpfox::getService('organization')->getPage($aNotification['item_id']);
			
		if (!isset($aRow['organization_id']))
		{
			return false;
		}			
			
		$sPhrase = Phpfox::getPhrase('organization.users_invited_you_to_check_out_the_organization_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
			
		return array(
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}	
	
	public function deleteLike($iItemId, $iUserId = 0)
	{
		
		
		// Get the threads from this page
		$aRows = $this->database()->select('thread_id')
			->from(Phpfox::getT('forum_thread'))
			->where('group_id = ' . (int)$iItemId)
			->execute('getSlaveRows');
		
		$aThreads = array();
		foreach ($aRows as $sKey => $aRow)
		{
		    $aThreads[] = $aRow['thread_id'];
		}
        if (!empty($aThreads))
		{
            $this->database()->delete(Phpfox::getT('forum_subscribe'), 'user_id = ' . Phpfox::getUserId() . ' AND thread_id IN (' . implode($aThreads, ',') . ')');
        }

		$aRow = Phpfox::getService('organization')->getPage($iItemId);
		if (!isset($aRow['organization_id']))
		{
			return false;
		}		
		
		$this->database()->updateCount('like', 'type_id = \'organization\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'organization', 'organization_id = ' . (int) $iItemId);			
		$iFriendId = (int) $this->database()->select('user_id')
			->from(Phpfox::getT('user'))
			->where('profile_organization_id = ' . (int) $aRow['organization_id'])
			->execute('getSlaveField');		
		
		$this->database()->delete(Phpfox::getT('friend'), 'user_id = ' . (int) $iFriendId . ' AND friend_user_id = ' . ($iUserId > 0 ? $iUserId : Phpfox::getUserId()));
		$this->database()->delete(Phpfox::getT('friend'), 'friend_user_id = ' . (int) $iFriendId . ' AND user_id = ' . ($iUserId > 0 ? $iUserId : Phpfox::getUserId()));
		
		// $this->_loadLikeBlock($iItemId);
		if (!$iUserId)
		{
			$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
			if (!defined('PHPFOX_CANCEL_ACCOUNT') || PHPFOX_CANCEL_ACCOUNT != true)
			{
				Phpfox::getLib('ajax')->call('window.location.href = \'' . $sLink. '\';');
			}
		}
        
        /* Remove invites */
        if ($iUserId != Phpfox::getUserId()) // Its not the user willingly leaving the page
        {
            $this->database()->delete(Phpfox::getT('organization_invite'), 'organization_id = ' . (int)$iItemId . ' AND invited_user_id =' . (int)$iUserId);
        }
	}	
	
	public function addLike($iItemId, $bDoNotSendEmail = false, $iUserId = null)
	{
		$aRow = Phpfox::getService('organization')->getPage($iItemId);

		if (!isset($aRow['organization_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'organization\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'organization', 'organization_id = ' . (int) $iItemId);
		
		// if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);

			if ($iUserId === null)
			{
				if (!$aRow['organization_type'])
				{
					Phpfox::getLib('mail')->to($aRow['user_id'])
						->subject(array('organization.full_name_liked_your_organization_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
						->message(array('organization.full_name_liked_your_organization', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['title'])))
						->notification('like.new_like')
						->send();				

					Phpfox::getService('notification.process')->add('organization_like', $aRow['organization_id'], $aRow['user_id']);					

					(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('organization_itemLiked', $aRow['organization_id']) : null);
				}
			}
			else
			{
				Phpfox::getLib('mail')->to($iUserId)
					->subject(array('organization.membership_accepted_to_title', array('title' => $aRow['title'])))
					->message(array('organization.your_membership_to_the_organization_link', array('link' => $sLink, 'title' => $aRow['title'])))
					->send();				
				
				$iPageUserId = $this->database()->select('user_id')
					->from(Phpfox::getT('user'))
					->where('profile_organization_id = ' . (int) $aRow['organization_id'])
					->execute('getSlaveField');				
				
				Phpfox::getService('notification.process')->add('organization_joined', $aRow['organization_id'], $iUserId, ($iPageUserId > 0 ? $iPageUserId : null));
			}
		}		
		
		$iFriendId = (int) $this->database()->select('user_id')
			->from(Phpfox::getT('user'))
			->where('profile_organization_id = ' . (int) $aRow['organization_id'])
			->execute('getSlaveField');
		
		$bIsApprove = true;
		if ($iUserId === null)
		{
			$iUserId = Phpfox::getUserId();
			$bIsApprove = false;
		}
		
		$this->database()->insert(Phpfox::getT('friend'), array(
				'is_organization' => 1,
				'list_id' => 0,
				'user_id' => $iUserId,
				'friend_user_id' => $iFriendId,
				'time_stamp' => PHPFOX_TIME
			)
		);
		
		$this->database()->insert(Phpfox::getT('friend'), array(
				'is_organization' => 1,
				'list_id' => 0,
				'user_id' => $iFriendId,
				'friend_user_id' => $iUserId,
				'time_stamp' => PHPFOX_TIME
			)
		);		
		
		if (!$bIsApprove)
		{
			// $this->_loadLikeBlock($iItemId);
			Phpfox::getLib('ajax')->call('window.location.href = \'' . $sLink. '\';');
			
			// $this->database()->delete(Phpfox::getT('organization_invite'), 'organization_id = ' . (int) $aRow['organization_id'] . ' AND invited_user_id = ' . Phpfox::getUserId());
		}
	}	
	
	public function getVideoDetails($aItem)
	{		
		Phpfox::getService('organization')->setIsInPage();
		
		$aRow = Phpfox::getService('organization')->getPage($aItem['item_id']);
			
		if (!isset($aRow['organization_id']))
		{
			return false;
		}
		
		Phpfox::getService('organization')->setMode();
		
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
			
		return array(
			'breadcrumb_title' => Phpfox::getPhrase('organization.organization'),
			'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('organization'),
			'module_id' => 'organization',
			'item_id' => $aRow['organization_id'],
			'title' => $aRow['title'],
			'url_home' => $sLink,
			'url_home_photo' => $sLink . 'video/',
			'theater_mode' => Phpfox::getPhrase('organization.in_the_organization_link_title', array('link' => $sLink, 'title' => $aRow['title']))
		);
	}
	
	public function getMusicDetails($aItem)
	{		
		Phpfox::getService('organization')->setIsInPage();
		
		$aRow = Phpfox::getService('organization')->getPage($aItem['item_id']);
			
		if (!isset($aRow['organization_id']))
		{
			return false;
		}
		
		Phpfox::getService('organization')->setMode();
		
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
			
		return array(
			'breadcrumb_title' => Phpfox::getPhrase('organization.organization'),
			'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('organization'),
			'module_id' => 'organization',
			'item_id' => $aRow['organization_id'],
			'title' => $aRow['title'],
			'url_home' => $sLink,
			'url_home_photo' => $sLink . 'music/',
			'theater_mode' => Phpfox::getPhrase('organization.in_the_organization_link_title', array('link' => $sLink, 'title' => $aRow['title']))
		);
	}	
	
	public function getBlogDetails($aItem)
	{
	    Phpfox::getService('organization')->setIsInPage();
	    $aRow = Phpfox::getService('organization')->getPage($aItem['item_id']);
	    if (!isset($aRow['organization_id']))
	    {
		return false;
	    }
	    $sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
	    return array(
		'breadcrumb_title' => Phpfox::getPhrase('organization.organization'),
		'breadcrumb_home' => Phpfox::getLib('url')->makeUrl('organization'),
		'module_id' => 'organization',
		'item_id' => $aRow['organization_id'],
		'title' => $aRow['title'],
		'url_home' => $sLink,
		'url_home_photo' => $sLink . 'blog/',
		'theater_mode' => Phpfox::getPhrase('organization.in_the_organization_link_title', array('link' => $sLink, 'title' => $aRow['title']))
	    );
	}
	
	public function uploadSong($iItemId)
	{
		Phpfox::getService('organization')->setIsInPage();
		
		return array(
			'module' => 'organization',
			'item_id' => $iItemId,
			'table_prefix' => 'organization_'
		);			
	}		
	
	public function getNotificationJoined($aNotification)
	{
		$aRow = Phpfox::getService('organization')->getPage($aNotification['item_id']);
		
		if (!isset($aRow['organization_id']))
		{
			return false;
		}	
		
		return array(
			// 'no_profile_image' => true,
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']),
			'message' => Phpfox::getPhrase('organization.your_membership_has_been_accepted_to_join_the_organization_title', array('title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...'))),
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);		
	}
	
	public function getNotificationRegister($aNotification)
	{
		$aRow = $this->database()->select('p.*, pu.vanity_url, ' . Phpfox::getUserField())
			->from(Phpfox::getT('organization_signup'), 'ps')
			->join(Phpfox::getT('organization'), 'p', 'p.organization_id = ps.organization_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = ps.user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
            ->where('ps.signup_id = ' . (int)$aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['organization_id']))
		{
			return false;
		}	
		
		return array(
			// 'no_profile_image' => true,
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']),
			'message' => Phpfox::getPhrase('organization.full_name_is_requesting_to_join_your_organization_title', array('full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...'))),
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);		
	}	
	
	public function getNotificationLike($aNotification)
	{
		$aRow = Phpfox::getService('organization')->getPage($aNotification['item_id']);
		
		if (!isset($aRow['organization_id']))
		{
			return false;
		}	
		
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		if (!isset($aRow['gender']))
		{
			$sGender = 'their';
		}
		else
		{
			$sGender = Phpfox::getService('user')->gender($aRow['gender'], 1);
		}
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aRow['organization_type'] == '1')
		{
			if ($aNotification['user_id'] == $aRow['user_id'])
			{
				$sPhrase = Phpfox::getPhrase('organization.users_joined_gender_own_organization_title', array('users' => $sUsers, 'gender' => $sGender, 'title' => $sTitle));
			}
			elseif ($aRow['user_id'] == Phpfox::getUserId())		
			{
				$sPhrase = Phpfox::getPhrase('organization.users_joined_your_organization_title', array('users' => $sUsers, 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('organization.users_joined_full_names_organization_title', array('users' => $sUsers, 'full_name' => Phpfox::getLib('parse.output')->shorten($aRow['full_name'], Phpfox::getParam('user.maximum_length_for_full_name')), 'title' => $sTitle));
			}			
		}
		else
		{		
			if ($aNotification['user_id'] == $aRow['user_id'])
			{
				$sPhrase = Phpfox::getPhrase('organization.users_liked_gender_own_organization_title', array('users' => $sUsers, 'gender' => $sGender, 'title' => $sTitle));	
			}
			elseif ($aRow['user_id'] == Phpfox::getUserId())		
			{
				$sPhrase = Phpfox::getPhrase('organization.users_liked_your_organization_title', array('users' => $sUsers, 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('organization.users_liked_full_names_organization_title', array('users' => $sUsers, 'full_name' => Phpfox::getLib('parse.output')->shorten($aRow['full_name'], Phpfox::getParam('user.maximum_length_for_full_name')), 'title' => $sTitle));
			}
		}
			
		return array(
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}	
	
	public function addForum($iId)
	{
		Phpfox::getService('organization')->setIsInPage();
		
		$aRow = Phpfox::getService('organization')->getPage($iId);
			
		if (!isset($aRow['organization_id']))
		{
			return false;
		}			
		
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
			
		return array(
			'module' => 'organization',
			'item' => $aRow['organization_id'],
			'group_id' => $aRow['organization_id'],
			'url_home' => $sLink,
			'title' => $aRow['title'],
			'table_prefix' => 'organization_',
			'item_id' => $aRow['organization_id']			
		);
	}
	
	public function getPagePerms()
	{
		$aPerms = array();
		
		$aPerms['organization.share_updates'] = Phpfox::getPhrase('organization.who_can_post_a_comment');
		$aPerms['organization.view_browse_updates'] = Phpfox::getPhrase('organization.who_can_view_browse_comments');
		$aPerms['organization.view_browse_widgets'] = Phpfox::getPhrase('organization.can_view_widgets');
		
		return $aPerms;
	}
	
	public function checkFeedShareLink()
	{
		return false;
	}	
	
	public function getAjaxCommentVar()
	{
		return null;
	}
	
	public function getRedirectComment($iId)
	{
		$aListing = $this->database()->select('pfc.feed_comment_id AS comment_item_id, pfc.privacy_comment, pfc.user_id AS comment_user_id, m.*, pu.vanity_url, pfc.parent_user_id AS item_id')
			->from(Phpfox::getT('organization_feed_comment'), 'pfc')
			->join(Phpfox::getT('organization'), 'm', 'm.organization_id = pfc.parent_user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = m.organization_id')
			->where('pfc.feed_comment_id = ' . (int) $iId)
			->execute('getSlaveRow');

		if (!isset($aListing['organization_id']))
		{
			return false;
		}

		return Phpfox::getService('organization')->getUrl($aListing['organization_id'], $aListing['title'], $aListing['vanity_url']) . 'comment-id_' . $aListing['comment_item_id'] . '/';
	}	
	
	public function getFeedRedirect($iId, $iChild = 0)
	{
		$aListing = $this->database()->select('m.organization_id, m.title, pu.vanity_url, pf.item_id')
			->from(Phpfox::getT('organization_feed'), 'pf')
			->join(Phpfox::getT('organization'), 'm', 'm.organization_id = pf.parent_user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = m.organization_id')
			->where('pf.feed_id = ' . (int) $iId)
			->execute('getSlaveRow');

		if (!isset($aListing['organization_id']))
		{
			return false;
		}
		
		return Phpfox::getService('organization')->getUrl($aListing['organization_id'], $aListing['title'], $aListing['vanity_url']) . 'comment-id_' . $aListing['item_id'] . '/';
	}	
	
	public function getItemName($iId, $sName)
	{
		return '<a href="' . Phpfox::getLib('url')->makeUrl('comment.view', array('id' => $iId)) . '">' . Phpfox::getPhrase('organization.organization_group_name', array('name' => $sName)) . '</a>';
	}		
	
	public function getCommentItem($iId)
	{		
		$aRow = $this->database()->select('feed_comment_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
			->from(Phpfox::getT('organization_feed_comment'))
			->where('feed_comment_id = ' . (int) $iId)
			->execute('getSlaveRow');		
		
		$aRow['comment_view_id'] = '0';
		
		if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
		{
			Phpfox_Error::set(Phpfox::getPhrase('organization.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));
			
			unset($aRow['comment_item_id']);
		}		
		
		$aRow['parent_module_id'] = 'organization';
			
		return $aRow;
	}	
	
	public function addComment($aVals, $iUserId = null, $sUserName = null)
	{		
		$aRow = $this->database()->select('fc.feed_comment_id, fc.user_id, e.organization_id, e.title, u.full_name, u.gender, pu.vanity_url')
			->from(Phpfox::getT('organization_feed_comment'), 'fc')
			->join(Phpfox::getT('organization'), 'e', 'e.organization_id = fc.parent_user_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = e.organization_id')
			->where('fc.feed_comment_id = ' . (int) $aVals['item_id'])
			->execute('getSlaveRow');
			
		// Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
		if (empty($aVals['parent_id']))
		{
			$this->database()->updateCounter('organization_feed_comment', 'total_comment', 'feed_comment_id', $aRow['feed_comment_id']);		
		}
		
		// Send the user an email
		$sLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']) . 'wall/comment-id_' . $aRow['feed_comment_id'] . '/';
		$sItemLink = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
		
		Phpfox::getService('comment.process')->notify(array(
				'user_id' => $aRow['user_id'],
				'item_id' => $aRow['feed_comment_id'],
				'owner_subject' => Phpfox::getPhrase('organization.full_name_commented_on_a_comment_posted_on_the_organization_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])),
				'owner_message' => Phpfox::getPhrase('organization.full_name_commented_on_one_of_your_comments', array('full_name' => Phpfox::getUserBy('full_name'), 'item_link' => $sItemLink, 'title' => $aRow['title'], 'link' => $sLink)),
				'owner_notification' => 'comment.add_new_comment',
				'notify_id' => 'organization_comment_feed',
				'mass_id' => 'organization',
				'mass_subject' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('organization.full_name_commented_on_one_of_gender_organization_comments', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1))) : Phpfox::getPhrase('organization.full_name_commented_on_one_of_other_full_name_s_organization_comments', array('full_name' => Phpfox::getUserBy('full_name'), 'other_full_name' => $aRow['full_name']))),
				'mass_message' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('organization.full_name_comment_on_one_of_gender', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'item_link' => $sItemLink, 'title' => $aRow['title'], 'link' => $sLink)) : Phpfox::getPhrase('organization.full_name_commented_on_one_of_other_full_name', array('full_name' => Phpfox::getUserBy('full_name'), 'other_full_name' => $aRow['full_name'], 'item_link' => $sItemLink, 'title' => $aRow['title'], 'link' => $sLink)))
			)
		);
	}	
	
	public function getNotificationComment($aNotification)
	{
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.organization_id, e.title, pu.vanity_url')
			->from(Phpfox::getT('organization_feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->join(Phpfox::getT('organization'), 'e', 'e.organization_id = fc.parent_user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = e.organization_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['feed_comment_id']))
		{
			return false;
		}
		
		if ($aNotification['item_user_id'] == $aRow['user_id'] && isset($aNotification['extra_users']) && count($aNotification['extra_users']))
		{
			$sUsers = Phpfox::getService('notification')->getUsers($aNotification, true);
		}
		else
		{
			$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		}
		// $sGender = Phpfox::getService('user')->gender($aRow['gender'], 1);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = Phpfox::getPhrase('organization.users_commented_on_the_organization_title', array('users' => $sUsers, 'title' => $sTitle));			
		
		return array(
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']) . 'wall/comment-id_' . $aRow['feed_comment_id'] . '/',
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}
	
	public function getNotificationComment_Feed($aNotification)
	{
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.organization_id, e.title, pu.vanity_url')
			->from(Phpfox::getT('organization_feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->join(Phpfox::getT('organization'), 'e', 'e.organization_id = fc.parent_user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = e.organization_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['feed_comment_id']))
		{
			return false;
		}
		
		if ($aNotification['user_id'] == $aRow['user_id'] && isset($aNotification['extra_users']) && count($aNotification['extra_users']))
		{
			$sUsers = Phpfox::getService('notification')->getUsers($aNotification, true);
		}
		else
		{
			$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		}		
		$sGender = Phpfox::getService('user')->gender($aRow['gender'], 1);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');		
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
			{
				$sPhrase = Phpfox::getPhrase('organization.users_commented_on_span_class_drop_data_user_full_name_s_span_comment_on_the_organization_title', array('users' => $sUsers, 'full_name' => $aRow['full_name'], 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('organization.users_commented_on_gender_own_comment_on_the_organization_title', array('users' => $sUsers, 'gender' => $sGender, 'title' => $sTitle));	
			}
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('organization.users_commented_on_one_of_your_comments_on_the_organization_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('organization.users_commented_on_one_of_full_name', array('users' => $sUsers, 'full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']) . 'wall/comment-id_' . $aRow['feed_comment_id'] . '/',
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);		
	}
	
	public function getTotalItemCount($iUserId)
	{
		return array(
			'field' => 'total_organization',
			'total' => $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('organization'))
				->where('view_id = 0 AND user_id = ' . (int) $iUserId . ' AND app_id = 0')
				->execute('getSlaveField')		
		);	
	}	
	
	public function globalUnionSearch($sSearch)
	{
		$this->database()->select('item.organization_id AS item_id, item.title AS item_title, item.time_stamp AS item_time_stamp, item.user_id AS item_user_id, \'organization\' AS item_type_id, item.image_path AS item_photo, item.image_server_id 	 AS item_photo_server')
			->from(Phpfox::getT('organization'), 'item')
			->where('item.view_id = 0 AND ' . $this->database()->searchKeywords('item.title', $sSearch) . ' AND item.privacy = 0')
			->union();
	}	
	
	public function getSearchInfo($aRow)
	{
		$aPage = $this->database()->select('p.organization_id, p.title, pu.vanity_url, ' . Phpfox::getUserField())
			->from(Phpfox::getT('organization'), 'p')
			->join(Phpfox::getT('user'), 'u', 'u.profile_organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
			->where('p.organization_id = ' . (int) $aRow['item_id'])
			->execute('getSlaveRow');
		
		$aInfo = array();
		$aInfo['item_link'] = Phpfox::getService('organization')->getUrl($aPage['organization_id'], $aPage['title'], $aPage['vanity_url']);
		$aInfo['item_name'] = Phpfox::getPhrase('organization.organization');
		$aInfo['profile_image'] = $aPage;
		
		return $aInfo;
	}	
	
	public function getSearchTitleInfo()
	{
		return array(
			'name' => Phpfox::getPhrase('organization.organization')
		);
	}		
	
	public function getNotificationApproved($aNotification)
	{
		$aRow = $this->database()->select('v.organization_id, v.title, v.user_id, u.gender, u.full_name, pu.vanity_url')
			->from(Phpfox::getT('organization'), 'v')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = v.organization_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
			->where('v.organization_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');

		if (!isset($aRow['organization_id']))
		{
			return false;
		}
		
		$sPhrase = Phpfox::getPhrase('organization.your_organization_has_been_approved',array('title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));		
			
		return array(
			'link' => Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
			'no_profile_image' => true
		);			
	}	
	
	public function addLikeComment($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('fc.feed_comment_id, fc.content, fc.user_id, e.organization_id, e.title')
			->from(Phpfox::getT('organization_feed_comment'), 'fc')
			->join(Phpfox::getT('organization'), 'e', 'e.organization_id = fc.parent_user_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->where('fc.feed_comment_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
			
		if (!isset($aRow['feed_comment_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'organization_comment\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'organization_feed_comment', 'feed_comment_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::getLib('url')->permalink(array('organization', 'comment-id' => $aRow['feed_comment_id']), $aRow['organization_id'], $aRow['title']);
			$sItemLink = Phpfox::getLib('url')->permalink('organization', $aRow['organization_id'], $aRow['title']);
			
			Phpfox::getLib('mail')->to($aRow['user_id'])
				->subject(array('organization.full_name_liked_a_comment_you_made_on_the_organization_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
				->message(array('organization.full_name_liked_a_comment_you_made_on_the_organization_title_to_view_the_comment_thread_follow_the_link_below_a_href_link_link_a', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'item_link' => $sItemLink, 'title' => $aRow['title'])))
				->notification('like.new_like')
				->send();
					
			Phpfox::getService('notification.process')->add('organization_comment_like', $aRow['feed_comment_id'], $aRow['user_id']);
		}
	}		
	//It is posting feeds for comments made in a Page of type group set to registration method "invide only", this should not happen.
	public function deleteLikeComment($iItemId)
	{
		$this->database()->updateCount('like', 'type_id = \'organization_comment\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'organization_feed_comment', 'feed_comment_id = ' . (int) $iItemId);	
	}
	
	public function deleteComment($iId)
	{
		$this->database()->update(Phpfox::getT('organization_feed_comment'), array('total_comment' => array('= total_comment -', 1)), 'feed_comment_id = ' . (int) $iId);
	}	
	
	public function updateCounterList()
	{
		$aList = array();	

		$aList[] =	array(
			'name' => Phpfox::getPhrase('organization.users_organization_groups_count'),
			'id' => 'organization-total'
		);
		
		return $aList;
	}	
	
	public function updateCounter($iId, $iPage, $iPageLimit)
	{	
			$iCnt = $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('user'))
				->execute('getSlaveField');		
			
			$aRows = $this->database()->select('u.user_id, u.user_name, u.full_name, COUNT(b.organization_id) AS total_items')
				->from(Phpfox::getT('user'), 'u')
				->leftJoin(Phpfox::getT('organization'), 'b', 'b.user_id = u.user_id AND b.view_id = 0 AND b.app_id = 0')
				->limit($iPage, $iPageLimit, $iCnt)
				->group('u.user_id')
				->execute('getSlaveRows');		
				
			foreach ($aRows as $aRow)
			{
				$this->database()->update(Phpfox::getT('user_field'), array('total_organization' => $aRow['total_items']), 'user_id = ' . $aRow['user_id']);
			}
		
		return $iCnt;	
	}	
	
	public function getNotificationComment_Like($aNotification)
	{
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.organization_id, e.title')
			->from(Phpfox::getT('organization_feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->join(Phpfox::getT('organization'), 'e', 'e.organization_id = fc.parent_user_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
				
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
		$sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
			{
				$sPhrase = Phpfox::getPhrase('organization.users_liked_span_class_drop_data_user_row_full_name_s_span_comment_on_the_organization_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('organization.users_liked_gender_own_comment_on_the_organization_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
			}
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('organization.users_liked_one_of_your_comments_on_the_organization_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('organization.users_liked_one_on_span_class_drop_data_user_row_full_name_s_span_comments_on_the_organization_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink(array('organization', 'comment-id' => $aRow['feed_comment_id']), $aRow['organization_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}		
	
	public function getBlocksView()
	{
		return array(
			'table' => 'organization_design_order',
			'field' => 'organization_id'
		);
	}
	
	public function getDetailOnThemeUpdate($iGroup)
	{
		if (!$iGroup)
		{
			return false;
		}
		
		$aGroup = $this->database()->select('*')
			->from(Phpfox::getT('organization'))
			->where('organization_id = ' . (int) $iGroup . '')
			->execute('getSlaveRow');		
			
		if (!isset($aGroup['organization_id']))
		{
			return false;
		}
		
		if (Phpfox::getService('organization')->isAdmin($aGroup))
		{
			return array(
				'table' => 'organization',
				'field' => 'designer_style_id',
				'action' => 'organization_id',
				'value' => $aGroup['organization_id'],
				'javascript' => '$(\'.style_submit_box_theme\').hide(); $(\'.style_box\').removeClass(\'style_box_active\'); $(\'.style_box\').each(function(){ if($(this).hasClass(\'style_box_test\')) $(this).removeClass(\'style_box_test\').addClass(\'style_box_active\');  {} });'
			);
		}
		
		return false;
	}		
	
	public function getDetailOnOrderUpdate($aVals)
	{		
		if (!isset($aVals['param']['item_id']))
		{
			return false;
		}		
		
		$aGroup = $this->database()->select('*')
			->from(Phpfox::getT('organization'))
			->where('organization_id = ' . (int) $aVals['param']['item_id'] . '')
			->execute('getSlaveRow');		
			
		if (!isset($aGroup['organization_id']))
		{
			return false;
		}
		
		if (Phpfox::getService('organization')->isAdmin($aGroup))
		{
			return array(
				'table' => 'organization_design_order',
				'field' => 'organization_id',
				'value' => $aGroup['organization_id']
			);
		}
		
		return false;	
	}	
	
	public function getDetailOnBlockUpdate($aVals)
	{
		if (!isset($aVals['item_id']))
		{
			return false;
		}
		
		$aGroup = $this->database()->select('*')
			->from(Phpfox::getT('organization'))
			->where('organization_id = ' . (int) $aVals['item_id'] . '')
			->execute('getSlaveRow');		
			
		if (!isset($aGroup['organization_id']))
		{
			return false;
		}
		
		if (Phpfox::getService('organization')->isAdmin($aGroup))
		{
			return array(
				'table' => 'organization_design_order',
				'field' => 'organization_id',
				'value' => $aGroup['organization_id']
			);
		}
		
		return false;
	}		
	
	public function getActions()
	{
		return array(
			'dislike' => array(
				'enabled' => true,
				'action_type_id' => 2, // 2 = dislike
				'phrase' => Phpfox::getPhrase('like.dislike'),
				'phrase_in_past_tense' => 'disliked',
				'item_type_id' => 'organization', // used to differentiate between photo albums and photos for example.
				'table' => 'organization',
				'item_phrase' => Phpfox::getPhrase('organization.item_phrase'),
				'column_update' => 'total_dislike',
				'column_find' => 'organization_id',
				'where_to_show' => array('organization', 'apps', 'core')			
				)
		);
	}
	
	/* Used to get a page when there is no certainty of the module */
	public function getItem($iId)
	{
		Phpfox::getService('organization')->setIsInPage();
		$aItem = $this->database()->select('*')->from(Phpfox::getT('organization'))->where('organization_id = ' . (int)$iId)->execute('getSlaveRow');
		$aItem['module'] = 'organization';
		$aItem['item_id'] = $iId;
		return $aItem;
	}
	
	public function onDeleteUser($iUser)
	{
		$aRows = $this->database()->select('*')
			->from(Phpfox::getT('organization'))
			->where('user_id = ' . (int) $iUser)
			->execute('getSlaveRows');		
		
		foreach ($aRows as $aRow)
		{
			Phpfox::getService('organization.process')->delete($aRow['organization_id'], true);
		}
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
		if ($sPlugin = Phpfox_Plugin::get('organization.service_callback__call'))
		{
			eval($sPlugin);
			return;
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
	
	private function _loadLikeBlock($iPage)
	{
		$aPage = Phpfox::getService('organization')->getForView($iPage);
		
		$oAjax = Phpfox::getLib('ajax');		
		
		Phpfox::getLib('template')->assign('aPage', $aPage);
		Phpfox_Component::setPublicParam('aPage', $aPage);
		
		Phpfox::getBlock('organization.like');
		
		$oAjax->html('#js_organization_like_join_holder', $oAjax->getContent(false));
	}
	
	public function inheritPrivacy($aParams)
	{
		$iPerms = $this->database()->select('var_value')
			->from(Phpfox::getT('organization_perm'))
			->where('organization_id = ' . (int)$aParams['iPageId'] . ' AND var_name = "' . Phpfox::getLib('parse.input')->clean($aParams['sParam']) . '"')
			->execute('getSlaveField');
		
		return $iPerms;
	}
}

?>
