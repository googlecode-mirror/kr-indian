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
 * @package 		Phpfox_Component
 * @version 		$Id: add.class.php 5481 2013-03-11 08:02:19Z Raymond_Benc $
 */
class fevent_Component_Controller_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('fevent.can_create_fevent', true);
		
		$bIsEdit = false;
		$bIsSetup = ($this->request()->get('req4') == 'setup' ? true : false);
		$sAction = $this->request()->get('req3');
		$aCallback = false;		
		$sModule = $this->request()->get('module', false);
		$iItem =  $this->request()->getInt('item', false);
		
		if ($iEditId = $this->request()->get('id'))
		{
			if (($afevent = Phpfox::getService('fevent')->getForEdit($iEditId)))
			{
				$bIsEdit = true;
				$this->setParam('afevent', $afevent);
				$this->setParam(array(
						'country_child_value' => $afevent['country_iso'],
						'country_child_id' => $afevent['country_child_id']
					)
				);				
				$this->template()->setHeader(array(
							'<script type="text/javascript">$Behavior.feventEditCategory = function(){  var aCategories = explode(\',\', \'' . $afevent['categories'] . '\'); for (i in aCategories) { $(\'#js_mp_holder_\' + aCategories[i]).show(); $(\'#js_mp_category_item_\' + aCategories[i]).attr(\'selected\', true); } }</script>'
						)
					)
					->assign(array(
						'aForms' => $afevent,
						'afevent' => $afevent
					)
				);
				
				if ($afevent['module_id'] != 'fevent')
				{
					$sModule = $afevent['module_id'];
					$iItem = $afevent['item_id'];	
				}
			}
		}		
		
		if ($sModule && $iItem && Phpfox::hasCallback($sModule, 'viewfevent'))
		{
			$aCallback = Phpfox::callback($sModule . '.viewfevent', $iItem);		
			$this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
			$this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);		
			if ($sModule == 'pages' && !Phpfox::getService('pages')->hasPerm($iItem, 'fevent.share_fevents'))
			{
				return Phpfox_Error::display(Phpfox::getPhrase('fevent.unable_to_view_this_item_due_to_privacy_settings'));
			}				
		}		
		
		$aValidation = array(
			'title' => Phpfox::getPhrase('fevent.provide_a_name_for_this_fevent'),
			// 'country_iso' => Phpfox::getPhrase('fevent.provide_a_country_location_for_this_fevent'),			
			'location' => Phpfox::getPhrase('fevent.provide_a_location_for_this_fevent')
		);
		
		$oValidator = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'js_fevent_form',
				'aParams' => $aValidation
			)
		);		
		
		if ($aVals = $this->request()->get('val'))
		{
			if ($oValidator->isValid($aVals))
			{				
				if ($bIsEdit)
				{
					if (Phpfox::getService('fevent.process')->update($afevent['fevent_id'], $aVals, $afevent))
					{
						switch ($sAction)
						{
							case 'customize':
								$this->url()->send('fevent.add.invite.setup', array('id' => $afevent['fevent_id']), Phpfox::getPhrase('fevent.successfully_added_a_photo_to_your_fevent'));	
								break;
							default:
								$this->url()->permalink('fevent', $afevent['fevent_id'], $afevent['title'], true, Phpfox::getPhrase('fevent.successfully_invited_guests_to_this_fevent'));
								break;							
						}	
					}
					else
					{
						$aVals['fevent_id'] = $afevent['fevent_id'];
						$this->template()->assign(array('aForms' => $aVals, 'afevent' => $aVals));
					}
				}
				else 
				{
					if (($iFlood = Phpfox::getUserParam('fevent.flood_control_fevents')) !== 0)
					{
						$aFlood = array(
							'action' => 'last_post', // The SPAM action
							'params' => array(
								'field' => 'time_stamp', // The time stamp field
								'table' => Phpfox::getT('fevent'), // Database table we plan to check
								'condition' => 'user_id = ' . Phpfox::getUserId(), // Database WHERE query
								'time_stamp' => $iFlood * 60 // Seconds);	
							)
						);
							 			
						// actually check if flooding
						if (Phpfox::getLib('spam')->check($aFlood))
						{
							Phpfox_Error::set(Phpfox::getPhrase('fevent.you_are_creating_an_fevent_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());	
						}
					}					
					
					if (Phpfox_Error::isPassed())
					{
						if ($iId = Phpfox::getService('fevent.process')->add($aVals, ($aCallback !== false ? $sModule : 'fevent'), ($aCallback !== false ? $iItem : 0)))
						{
							$afevent = Phpfox::getService('fevent')->getForEdit($iId);
							$this->url()->permalink('fevent', $afevent['fevent_id'], $afevent['title'], true, Phpfox::getPhrase('fevent.fevent_successfully_added'));
						}
					}
				}
			}
			
			$sStep = (isset($aVals['step']) ? $aVals['step'] : '');
			$sAction = (isset($aVals['action']) ? $aVals['action'] : '');	
			$this->template()->assign('aForms', $aVals);		
		}		
		
		if ($bIsEdit)
		{
			$aMenus = array(
				'detail' => Phpfox::getPhrase('fevent.fevent_details'),
				'customize' => Phpfox::getPhrase('fevent.photo'),
				'invite' => Phpfox::getPhrase('fevent.invite_guests')
			);
			// Dont show the photo upload for iOS
			if ($this->request()->isIOS())
			{
				//unset($aMenus['customize']);
			}
			if (!$bIsSetup)
			{
				$aMenus['manage'] = Phpfox::getPhrase('fevent.manage_guest_list');
				$aMenus['email'] = Phpfox::getPhrase('fevent.mass_email');
			}
			
			$this->template()->buildPageMenu('js_fevent_block', 
				$aMenus,
				array(
					'link' => $this->url()->permalink('fevent', $afevent['fevent_id'], $afevent['title']),
					'phrase' => Phpfox::getPhrase('fevent.view_this_fevent')
				)				
			);		
		}
		
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('fevent.managing_fevent') . ': ' . $afevent['title'] : Phpfox::getPhrase('fevent.create_an_fevent')))
			->setFullSite()			
			->setBreadcrumb(Phpfox::getPhrase('fevent.fevents'), ($aCallback === false ? $this->url()->makeUrl('fevent') : $this->url()->makeUrl($aCallback['url_home_pages'])))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('fevent.managing_fevent') . ': ' . $afevent['title'] : Phpfox::getPhrase('fevent.create_new_fevent')), ($bIsEdit ? $this->url()->makeUrl('fevent.add', array('id' => $afevent['fevent_id'])) : $this->url()->makeUrl('fevent.add')), true)
			->setEditor()
			->setPhrase(array(
					'core.select_a_file_to_upload'
				)
			)				
			->setHeader('cache', array(	
					'add.js' => 'module_fevent',
					'pager.css' => 'style_css',
					'progress.js' => 'static_script',					
					'country.js' => 'module_core'					
				)
			)			
			->setHeader(array(
					'<script type="text/javascript">$Behavior.feventProgressBarSettings = function(){ if ($Core.exists(\'#js_fevent_block_customize_holder\')) { oProgressBar = {holder: \'#js_fevent_block_customize_holder\', progress_id: \'#js_progress_bar\', uploader: \'#js_progress_uploader\', add_more: false, max_upload: 1, total: 1, frame_id: \'js_upload_frame\', file_id: \'image\'}; $Core.progressBarInit(); } }</script>'
				)
			)
			->assign(array(
					'sCreateJs' => $oValidator->createJS(),
					'sGetJsForm' => $oValidator->getJsForm(false),
					'bIsEdit' => $bIsEdit,
					'bIsSetup' => $bIsSetup,
					'sCategories' => Phpfox::getService('fevent.category')->get(),
					'sModule' => ($aCallback !== false ? $sModule : ''),
					'iItem' => ($aCallback !== false ? $iItem : ''),
					'aCallback' => $aCallback,
					'iMaxFileSize' => (Phpfox::getUserParam('fevent.max_upload_size_fevent') === 0 ? null : Phpfox::getLib('phpfox.file')->filesize((Phpfox::getUserParam('fevent.max_upload_size_fevent') / 1024) * 1048576)),
					'bCanSendEmails' => ($bIsEdit ? Phpfox::getService('fevent')->canSendEmails($afevent['fevent_id']) : false),
					'iCanSendEmailsTime' => ($bIsEdit ? Phpfox::getService('fevent')->getTimeLeft($afevent['fevent_id']) : false),
					'sJsfeventAddCommand' => (isset($afevent['fevent_id']) ? "if (confirm('" . Phpfox::getPhrase('fevent.are_you_sure', array('phpfox_squote' => true)) . "')) { $('#js_submit_upload_image').show(); $('#js_fevent_upload_image').show(); $('#js_fevent_current_image').remove(); $.ajaxCall('fevent.deleteImage', 'id={$afevent['fevent_id']}'); } return false;" : ''),
					'sTimeSeparator' => Phpfox::getPhrase('fevent.time_separator')
				)
			);		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_controller_add_clean')) ? eval($sPlugin) : false);
	}
}

?>