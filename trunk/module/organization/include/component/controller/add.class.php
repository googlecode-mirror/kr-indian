<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
define('PHPFOX_IS_ORGANIZATION_ADD', true);
/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: add.class.php 7101 2014-02-11 13:47:16Z Fern $
 */
class organization_Component_Controller_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('organization.can_add_new_organization', true);
		
		Phpfox::getService('organization')->setIsInPage();
		
		$bIsEdit = false;
		$bIsNewPage = $this->request()->getInt('new');
		$sStep = $this->request()->get('req3');
		if (($iEditId = $this->request()->getInt('id')) && ($aPage = Phpfox::getService('organization')->getForEdit($iEditId)))			
		{
			$bIsEdit = true;
			$this->template()->assign('aForms', $aPage);
			
			$aMenus = array(
				'detail' => Phpfox::getPhrase('organization.details'),
				'info' => Phpfox::getPhrase('organization.information')				
			);
			
			if (!$aPage['is_app'])
			{
				$aMenus['photo'] = Phpfox::getPhrase('organization.photo');
			}
			$aMenus['permissions'] = Phpfox::getPhrase('organization.permissions');
			if (Phpfox::isModule('friend') && Phpfox::getUserBy('profile_organization_id') == 0)
			{
				$aMenus['invite'] = Phpfox::getPhrase('organization.invite');
			}			
			if (!$bIsNewPage)
			{
				$aMenus['url'] = Phpfox::getPhrase('organization.url');
				$aMenus['admins'] = Phpfox::getPhrase('organization.admins');
				$aMenus['widget'] = Phpfox::getPhrase('organization.widgets');
			}
			
			if (Phpfox::getParam('core.google_api_key'))
			{
			    $aMenus['location'] = Phpfox::getPhrase('organization.location');
			}
			
			if ($bIsNewPage)
			{
				$iCnt = 0;
				foreach ($aMenus as $sMenuName => $sMenuValue)
				{
					$iCnt++;
					$aMenus[$sMenuName] = Phpfox::getPhrase('organization.step_count', array('count' => $iCnt)) . ': ' . $sMenuValue;
				}
			}
			
			
			$this->template()->buildPageMenu('js_pages_block', 
				$aMenus,
				array(
					'link' => Phpfox::getService('organization')->getUrl($aPage['organization_id'], $aPage['title'], $aPage['vanity_url']),
					'phrase' => ($bIsNewPage ? Phpfox::getPhrase('organization.skip_view_this_organization') : Phpfox::getPhrase('organization.view_this_organization'))
				)				
			);					

			if (($aVals = $this->request()->getArray('val')))
			{
				if (Phpfox::getService('organization.process')->update($aPage['organization_id'], $aVals, $aPage))
				{
					if ($bIsNewPage && $this->request()->getInt('action') == '1')
					{
                        
						switch ($sStep)
						{
							case 'invite':
								if (Phpfox::isModule('friend'))
								{
									$this->url()->send('organization.add.url', array('id' => $aPage['organization_id'], 'new' => '1'));
								}
								break;							
							case 'permissions':
								$this->url()->send('organization.add.invite', array('id' => $aPage['organization_id'], 'new' => '1'));
								break;									
							case 'photo':
								$this->url()->send('organization.add.permissions', array('id' => $aPage['organization_id'], 'new' => '1'));
								break;						
							case 'info':
								$this->url()->send('organization.add.photo', array('id' => $aPage['organization_id'], 'new' => '1'));
								break;
							default:
								$this->url()->send('organization.add.info', array('id' => $aPage['organization_id'], 'new' => '1'));
								break;
						}
					}
					
					$aNewPage = Phpfox::getService('organization')->getForEdit($aPage['organization_id']);
					
					$this->url()->forward(Phpfox::getService('organization')->getUrl($aNewPage['organization_id'], $aNewPage['title'], $aNewPage['vanity_url']));
				}
			}
		}		
		
		
		if (Phpfox::getParam('core.google_api_key') != '' && $this->request()->get('id') != '')
		{
			$this->template()->setHeader(array(
				'<script type="text/javascript">oParams["core.google_api_key"] = "'.Phpfox::getParam('core.google_api_key') .'";</script>',
				'places.js' => 'module_organization',				
			));
			//d($aPage);
			if (isset($aPage['location']) && ( (int)$aPage['location_latitude'] != 0 || (int)$aPage['location_longitude'] != 0))
			{
				$this->template()->setHeader(array(
					'<script type="text/javascript">$Behavior.setLocation = function(){ $Core.organizationLocation.setLocation("'. $aPage['location_latitude'] .'","' . $aPage['location_longitude'] .'","'. $aPage['location']['name'] . '");};</script>'
				));
			}
		}
		$this->template()->setTitle(($bIsEdit ? '' . Phpfox::getPhrase('organization.editing_organization') . ': ' . $aPage['title']: Phpfox::getPhrase('organization.creating_a_organization')))
			->setBreadcrumb(Phpfox::getPhrase('organization.organization'), $this->url()->makeUrl('organization'))
			->setBreadcrumb(($bIsEdit ? '' . Phpfox::getPhrase('organization.editing_organization') . ': ' . $aPage['title']: Phpfox::getPhrase('organization.creating_a_organization')), $this->url()->makeUrl('organization.add'), true)
			->setEditor()
			->setFullSite()
			->setPhrase(array(
					'core.select_a_file_to_upload'
				)
			)			
			->setHeader(array(
					'pages.css' => 'style_css',
					'privacy.css' => 'module_user',
					'progress.js' => 'static_script',
					'organization.js' => 'module_organization'
				)
			)
			->setHeader(array('<script type="text/javascript">$Behavior.organizationProgressBarSettings = function(){ if ($Core.exists(\'#js_pages_block_customize_holder\')) { oProgressBar = {holder: \'#js_pages_block_customize_holder\', progress_id: \'#js_progress_bar\', uploader: \'#js_progress_uploader\', add_more: false, max_upload: 1, total: 1, frame_id: \'js_upload_frame\', file_id: \'image\'}; $Core.progressBarInit(); } }</script>'))			
			->assign(array(
					'aPermissions' => (isset($aPage) ? Phpfox::getService('organization')->getPerms($aPage['organization_id']) : array()),
					'aTypes' => Phpfox::getService('organization.type')->get(),
					'bIsEdit' => $bIsEdit,
					'iMaxFileSize' => Phpfox::getLib('phpfox.file')->filesize((Phpfox::getUserParam('organization.max_upload_size_organization') / 1024) * 1048576),
					'aWidgetEdits' => Phpfox::getService('organization')->getWidgetsForEdit(),
					'bIsNewPage' => $bIsNewPage,
					'sStep' => $sStep
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('organization.component_controller_add_clean')) ? eval($sPlugin) : false);
	}
}

?>
