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
 * @package 		Phpfox_Component
 * @version 		$Id: add.class.php 3402 2011-11-01 09:07:31Z Miguel_Espinoza $
 */
class organization_Component_Controller_Admincp_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsEdit = false;
		$bIsSub = false;
		if (($iEditId = $this->request()->getInt('id')))
		{
			$aRow = Phpfox::getService('organization.type')->getForEdit($iEditId);
			$bIsEdit = true;
			$this->template()->assign(array(			
					'aForms' => $aRow,
					'iEditId' => $iEditId
				)
			);
		}
		
		if (($iSubtEditId = $this->request()->getInt('sub')))
		{
			$aRow = Phpfox::getService('organization.category')->getForEdit($iSubtEditId);
			$iEditId = $iSubtEditId;
			$bIsEdit = true;
			$bIsSub = true;
			$this->template()->assign(array(			
					'aForms' => $aRow,
					'iEditId' => $iEditId
				)
			);
		}		
		
		if (($aVals = $this->request()->getArray('val')))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('organization.process')->updateCategory($iEditId, $aVals))
				{
					if ($bIsSub)
					{
						$this->url()->send('admincp.organization', array('sub' => $aVals['type_id']), Phpfox::getPhrase('organization.successfully_updated_the_category'));
					}
					else
					{
						$this->url()->send('admincp.organization', null, Phpfox::getPhrase('organization.successfully_updated_the_category'));
					}					
				}				
			}
			else
			{
				if (Phpfox::getService('organization.process')->addCategory($aVals))
				{
					$this->url()->send('admincp.organization', null, Phpfox::getPhrase('organization.successfully_created_a_new_category'));
				}
			}
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('organization.add_category'))
			->setBreadcrumb(Phpfox::getPhrase('organization.add_category'))
			->assign(array(
				'bIsEdit' => $bIsEdit,
				'aTypes' => Phpfox::getService('organization.type')->get()
			)
		)		
			->setHeader(array(
				'add.js' => 'module_organization'
			));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('organization.component_controller_admincp_add_clean')) ? eval($sPlugin) : false);
	}
}

?>