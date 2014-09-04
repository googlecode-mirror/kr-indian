<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

define('PHPFOX_IS_ORGANIZATION_VIEW', true);

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: controller.class.php 103 2009-01-27 11:32:36Z Raymond_Benc $
 */
class organization_Component_Controller_View extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::getUserParam('organization.can_view_browse_organization', true);
		
		$mId = $this->request()->getInt('req2');
		
		if (!($aOrganization = Phpfox::getService('organization')->getForView($mId)))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('organization.the_organization_you_are_looking_for_cannot_be_found'));
		}
		if (($this->request()->get('req3')) != '')
		{
			$this->template()->assign(array(
				'bRefreshPhoto' => true
			));
		}
		if (Phpfox::getUserParam('organization.can_moderate_organization') || $aOrganization['is_admin'])
		{
			
		}
		else
		{
			if ($aOrganization['view_id'] != '0')
			{
				return Phpfox_Error::display(Phpfox::getPhrase('organization.the_organization_you_are_looking_for_cannot_be_found'));
			}
		}
		
		if ($aOrganization['view_id'] == '2')
		{
			return Phpfox_Error::display(Phpfox::getPhrase('organization.the_organization_you_are_looking_for_cannot_be_found'));
		}		
		
		if (Phpfox::isMobile())
		{
			$aPageMenus = Phpfox::getService('organization')->getMenu($aOrganization);
			
			$aFilterMenu = array();
			foreach ($aPageMenus as $aPageMenu)
			{
				$aFilterMenu[$aPageMenu['phrase']] = $aPageMenu['url'];
			}
			
			$this->template()->buildSectionMenu('organization', $aFilterMenu);
		}
		
		if (Phpfox::getUserBy('profile_organization_id') <= 0 && Phpfox::isModule('privacy'))
		{
			Phpfox::getService('privacy')->check('organization', $aOrganization['organization_id'], $aOrganization['user_id'], $aOrganization['privacy'], (isset($aOrganization['is_friend']) ? $aOrganization['is_friend'] : 0));		
		}		
		
		$bCanViewPage = true;
		// http://www.phpfox.com/tracker/view/15190/
		$sCurrentModule = Phpfox::getLib('url')->reverseRewrite($this->request()->get(($this->request()->get('req1') == 'organization' ? 'req3' : 'req2')));
		
		Phpfox::getService('organization')->buildWidgets($aOrganization['organization_id']);				
		if ($aOrganization['designer_style_id'])
		{
			$this->template()->setStyle(array(
					'style_id' => $aOrganization['designer_style_id'],
					'style_folder_name' => $aOrganization['designer_style_folder'],
					'theme_folder_name' => $aOrganization['designer_theme_folder'],
					'theme_parent_id' => $aOrganization['theme_parent_id'],
					'total_column' => $aOrganization['total_column'],
					'l_width' => $aOrganization['l_width'],
					'c_width' => $aOrganization['c_width'],
					'r_width' => $aOrganization['r_width']				
				)
			);
		}		
		
		(($sPlugin = Phpfox_Plugin::get('organization.component_controller_view_build')) ? eval($sPlugin) : false);
		
		
		$this->setParam('aParentModule', array(			
				'module_id' => 'organization',
				'item_id' => $aOrganization['organization_id'],
				'url' => Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url'])
			)
		);
		
		if (isset($aOrganization['is_admin']) && $aOrganization['is_admin'])
		{
			define('PHPFOX_IS_ORGANIZATION_ADMIN', true);
		}
		
		$sModule = $sCurrentModule; // http://www.phpfox.com/tracker/view/15190/
		
		if (empty($sModule) && !empty($aOrganization['landing_organization'])/* && $this->request()->getInt('comment-id') < 1*/)
		{
			$sModule = $aOrganization['landing_organization'];
			$sCurrentModule = $aOrganization['landing_organization'];
		}
		
		(($sPlugin = Phpfox_Plugin::get('organization.component_controller_view_assign')) ? eval($sPlugin) : false);
		
		if (isset($aOrganization['use_timeline']) && $aOrganization['use_timeline'])
		{
			$aPageMenus = Phpfox::getService('organization')->getMenu($aOrganization);
			if (!defined('ORGANIZATION_TIME_LINE'))
			{
				define('ORGANIZATION_TIME_LINE', true);
			}
			$aOrganization['user_name'] = $aOrganization['title'];

			$this->template()->setFullSite()
				->assign(array(
				    'aUser' => $aOrganization,
				    'aProfileLinks' => $aPageMenus))
				->setHeader(array(
					'<script type="text/javascript">oParams["keepContent4"] = false;</script>'
					));
		}
		
		$this->setParam('aOrganization', $aOrganization);
		
		$this->template()			
			->assign(array(
					'aOrganization' => $aOrganization,
					'sCurrentModule' => $sCurrentModule,
					'bCanViewOrganization' => $bCanViewPage,
					'iViewCommentId' => $this->request()->getInt('comment-id'),
					'bHasPermToViewOrganizationFeed' => Phpfox::getService('organization')->hasPerm($aOrganization['organization_id'], 'organization.view_browse_updates')
				)
			)
			->setHeader('cache', array(				
				'profile.css' => 'style_css',
				'pages.css' => 'style_css',
				'organization.js' => 'module_organization',
                'player/flowplayer/flowplayer.js' => 'static_script'
			)
		);
		
		if (Phpfox::isMobile())
		{
			$this->template()->setBreadcrumb($aOrganization['title'], Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']), true);
		}
		
		$this->setParam('aCallbackShoutbox', array(
				'module' => 'organization',
				'item' => $aOrganization['organization_id']
			)
		);		
		
		if ($bCanViewPage && $sModule != 'photo' && $sModule && Phpfox::isModule($sModule) && Phpfox::hasCallback($sModule, 'getOrganizationSubMenu') && !$this->request()->getInt('comment-id'))
		{
			if (Phpfox::hasCallback($sModule, 'canViewOrganizationSection') && !Phpfox::callback($sModule . '.canViewOrganizationSection', $aOrganization['organization_id']))
			{
				return Phpfox_Error::display(Phpfox::getPhrase('organization.unable_to_view_this_section_due_to_privacy_settings'));
			}
			
			$this->template()->assign('bIsOrganizationViewSection', true);
			$this->setParam('bIsOrganizationViewSection', true);
			$this->setParam('sCurrentOrganizationModule', $sModule);
			
			Phpfox::getComponent($sModule . '.index', array('bNoTemplate' => true), 'controller');
		}
		elseif ($bCanViewPage && $sModule != 'photo' && $sModule && Phpfox::getService('organization')->isWidget($sModule) && !$this->request()->getInt('comment-id'))
		{
			define('PHPFOX_IS_ORGANIZATION_WIDGET', true);
			$this->template()->assign(array(
					'aWidget' => Phpfox::getService('organization')->getWidget($sModule)
				)
			);
		}
		else
		{
            $sSubController = $this->request()->get('req3');
            if($sSubController == 'info')
            {
                return Phpfox::getComponent('organization.info',array('bNoTemplate' => true), 'controller');
            }
            else if($sSubController == 'volunteer')
            {
                return Phpfox::getComponent('organization.volunteer',array('bNoTemplate' => true), 'controller');
            }
            else if($sSubController == 'photo')
            {
                return Phpfox::getComponent('organization.photo',array('bNoTemplate' => true), 'controller');
            }
			$bCanPostComment = true;
			if ($sCurrentModule == 'pending')
			{
				$this->template()->assign('aPendingUsers', Phpfox::getService('organization')->getPendingUsers($aOrganization['organization_id']));
				$this->setParam('global_moderation', array(
						'name' => 'organization',
						'ajax' => 'organization.moderation',
						'menu' => array(
							array(
								'phrase' => Phpfox::getPhrase('organization.delete'),
								'action' => 'delete'
							),
							array(
								'phrase' => Phpfox::getPhrase('organization.approve'),
								'action' => 'approve'
							)					
						)
					)
				);				
			}
			
			if (Phpfox::getService('organization')->isAdmin($aOrganization))
			{
				define('PHPFOX_FEED_CAN_DELETE', true);
			}
			
			if (Phpfox::getUserId())
			{
				$bIsBlocked = Phpfox::getService('user.block')->isBlocked($aOrganization['user_id'], Phpfox::getUserId());
				if ($bIsBlocked)
				{
					$bCanPostComment = false;
				}
			}			
			
			// http://www.phpfox.com/tracker/view/15316/
			if($sCurrentModule != 'info')
			{
				define('PHPFOX_IS_ORGANIZATION_IS_INDEX', true);
			}

			$this->setParam('aFeedCallback', array(
					'module' => 'organization',
					'table_prefix' => 'organization_',
					'ajax_request' => 'organization.addFeedComment',
					'item_id' => $aOrganization['organization_id'],
					'disable_share' => ($bCanPostComment ? false : true),
					'feed_comment' => 'organization_comment'				
				)
			);			
			if (isset($aOrganization['text']) && !empty($aOrganization['text']))
			{
				$this->template()->setMeta('description', $aOrganization['text']);
			}
			$this->template()->setTitle($aOrganization['title'])
				->setEditor()
				->setHeader('cache', array(
						'jquery/plugin/jquery.highlightFade.js' => 'static_script',	
						'jquery/plugin/jquery.scrollTo.js' => 'static_script',
						'quick_edit.js' => 'static_script',
						'comment.css' => 'style_css',
						'pager.css' => 'style_css',
						'index.css' => 'module_organization',
						'feed.js' => 'module_feed'						
					)
				);

			if (Phpfox::getParam('video.convert_servers_enable'))
			{
				$this->template()->setHeader('<script type="text/javascript">document.domain = "' . Phpfox::getParam('video.convert_js_parent') . '";</script>');
			}

			if ($sModule == 'designer' && $aOrganization['is_admin'])
			{
				Phpfox::getUserParam('organization.can_design_organization', true);
				define('PHPFOX_IN_DESIGN_MODE', true);
				define('PHPFOX_CAN_MOVE_BLOCKS', true);		
				
				if (($iTestStyle = $this->request()->get('test_style_id')))
				{
					if (Phpfox::getLib('template')->testStyle($iTestStyle))
					{
						
					}
				}
				
				$aDesigner = array(
					'current_style_id' => $aOrganization['designer_style_id'],
					'design_header' => 'Customize organization',
					'current_organization' => Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']),
					'design_organization' => Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']) . 'designer/',
					'block' => 'organization.view',				
					'item_id' => $aOrganization['organization_id'],
					'type_id' => 'organization'
				);
				
				$this->setParam('aDesigner', $aDesigner);	
				
				$this->template()->setHeader('cache', array(
								'jquery/ui.js' => 'static_script',
								'sort.js' => 'module_theme',
								'style.css' => 'style_css',
								'select.js' => 'module_theme',
								'design.js' => 'module_theme'							
							)					
						)
						->setHeader(array(
							'<script type="text/javascript">$Behavior.organization_controller_view_designonuptade = function() { function designOnUpdate() { $Core.design.updateSorting(); } };</script>',		
							'<script type="text/javascript">$Behavior.organization_controller_view_design_init = function() { $Core.design.init({type_id: \'organization\', item_id: \'' . $aOrganization['organization_id'] . '\'}); };</script>'
							)
						)
						->assign('sCustomDesignId', $aOrganization['organization_id']
					);
			}				
		}
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('organization.component_controller_view_clean')) ? eval($sPlugin) : false);
	}
}

?>
