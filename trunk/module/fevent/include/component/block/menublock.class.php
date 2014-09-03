<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          Raymond Benc
 * @package         Phpfox_Component
 * @version         $Id: menu.class.php 405 2009-04-15 13:10:28Z Raymond_Benc $
 */
class fevent_Component_Block_Menublock extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        if ($this->request()->get('req2') == 'view' && ($sLegacyTitle = $this->request()->get('req3')) && !empty($sLegacyTitle))
        {                
            Phpfox::getService('core')->getLegacyItem(array(
                    'field' => array('fevent_id', 'title'),
                    'table' => 'fevent',        
                    'redirect' => 'fevent',
                    'title' => $sLegacyTitle
                )
            );
        }        
        
        Phpfox::getUserParam('fevent.can_access_fevent', true);        
        
        $sfevent = $this->request()->get('req2');
        
        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            if ($this->request()->getInt('comment-id'))
            {
                Phpfox::getService('notification.process')->delete('fevent_comment', $this->request()->getInt('comment-id'), Phpfox::getUserId());
                Phpfox::getService('notification.process')->delete('fevent_comment_feed', $this->request()->getInt('comment-id'), Phpfox::getUserId());
                Phpfox::getService('notification.process')->delete('fevent_comment_like', $this->request()->getInt('comment-id'), Phpfox::getUserId());
            }
            Phpfox::getService('notification.process')->delete('fevent_like', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('fevent_invite', $this->request()->getInt('req2'), Phpfox::getUserId());
        }        
        
        if (!($afevent = Phpfox::getService('fevent')->getfevent($sfevent)))
        {
            return Phpfox_Error::display(Phpfox::getPhrase('fevent.the_fevent_you_are_looking_for_does_not_exist_or_has_been_removed'), 404);
        }
        
        Phpfox::getService('core.redirect')->check($afevent['title']);
        if (Phpfox::isModule('privacy'))
        {
            Phpfox::getService('privacy')->check('fevent', $afevent['fevent_id'], $afevent['user_id'], $afevent['privacy'], $afevent['is_friend']);
        }
        
        $this->setParam('afevent', $afevent);
        
        $bCanPostComment = true;
        if (isset($afevent['privacy_comment']) && $afevent['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
        {
            switch ($afevent['privacy_comment'])
            {
                // Everyone is case 0. Skipped.
                // Friends only
                case 1:
                    if(!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $afevent['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Friend of friends
                case 2:
                    if (!Phpfox::getService('friend')->isFriendOfFriend($afevent['user_id']))
                    {
                        $bCanPostComment = false;    
                    }
                    break;
                // Only me
                case 3:
                    $bCanPostComment = false;
                    break;
            }
        }
        
        $aCallback = false;
        if ($afevent['item_id'] && Phpfox::hasCallback($afevent['module_id'], 'viewfevent'))
        {
            $aCallback = Phpfox::callback($afevent['module_id'] . '.viewfevent', $afevent['item_id']);    
            $this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
            $this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);            
            if ($afevent['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'fevent.view_browse_fevents'))
            {
                return Phpfox_Error::display('Unable to view this item due to privacy settings.');
            }                
        }        
        
        if (Phpfox::getUserId())
        {
            $bIsBlocked = Phpfox::getService('user.block')->isBlocked($afevent['user_id'], Phpfox::getUserId());
            if ($bIsBlocked)
            {
                $bCanPostComment = false;
            }
        }
        
        $this->setParam('aFeedCallback', array(
                'module' => 'fevent',
                'table_prefix' => 'fevent_',
                'ajax_request' => 'fevent.addFeedComment',
                'item_id' => $afevent['fevent_id'],
                'disable_share' => ($bCanPostComment ? false : true)
            )
        );
        
        if ($afevent['view_id'] == '1')
        {
            $this->template()->setHeader('<script type="text/javascript">$Behavior.feventIsPending = function(){ $(\'#js_block_border_feed_display\').addClass(\'js_moderation_on\').hide(); }</script>');
        }
        
        if (Phpfox::getUserId() == $afevent['user_id'])
        {
            if (Phpfox::isModule('notification'))
            {
                Phpfox::getService('notification.process')->delete('fevent_approved', $this->request()->getInt('req2'), Phpfox::getUserId());            
            }
        }                    
            
        $this->template()->setTitle($afevent['title'])
            ->setMeta('description', $afevent['description'])
            ->setMeta('keywords', $this->template()->getKeywords($afevent['title']))
            ->setBreadcrumb(Phpfox::getPhrase('fevent.fevents'), ($aCallback === false ? $this->url()->makeUrl('fevent') : $this->url()->makeUrl($aCallback['url_home_pages'])))
            ->setBreadcrumb($afevent['title'], $this->url()->permalink('fevent', $afevent['fevent_id'], $afevent['title']), true)
            ->setEditor(array(
                    'load' => 'simple'
                )
            ) 
            ->assign(array(
                    'afevent' => $afevent,
                    'aCallback' => $aCallback,
                    'sMicroPropType' => 'fevent'
                )
            );
            
        (($sPlugin = Phpfox_Plugin::get('fevent.component_controller_view_process_end')) ? eval($sPlugin) : false);
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('fevent.component_block_menublock_clean')) ? eval($sPlugin) : false);
    }
}

?>