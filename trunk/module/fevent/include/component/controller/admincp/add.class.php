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
 * @version 		$Id: add.class.php 5538 2013-03-25 13:20:22Z Miguel_Espinoza $
 */
class fevent_Component_Controller_Admincp_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$bIsEdit = false;
		if ($iEditId = $this->request()->getInt('id'))
		{
			if ($aCategory = Phpfox::getService('fevent.category')->getForEdit($iEditId))
			{
				$bIsEdit = true;
				
				$this->template()->setHeader('<script type="text/javascript">$Behavior.fevent_add_set_attr = function(){$(\'#js_mp_category_item_' . $aCategory['parent_id'] . '\').attr(\'selected\', true);};</script>')->assign('aForms', $aCategory);
			}
		}		
		
		if ($aVals = $this->request()->getArray('val'))
		{
			if ($bIsEdit)
			{
				if (Phpfox::getService('fevent.category.process')->update($aCategory['category_id'], $aVals))
				{
					$this->url()->send('admincp.fevent.add', array('id' => $aCategory['category_id']), Phpfox::getPhrase('fevent.category_successfully_updated'));
				}
			}
			else 
			{
				if (Phpfox::getService('fevent.category.process')->add($aVals))
				{
					$this->url()->send('admincp.fevent.add', null, Phpfox::getPhrase('fevent.category_successfully_added'));
				}
			}
		}
		
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('fevent.edit_a_category') : Phpfox::getPhrase('fevent.create_a_new_category')))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('fevent.edit_a_category') : Phpfox::getPhrase('fevent.create_a_new_category')), $this->url()->makeUrl('admincp.fevent'))
			->assign(array(
					'sOptions' => Phpfox::getService('fevent.category')->display('option')->get(),
					'bIsEdit' => $bIsEdit
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_controller_admincp_add_clean')) ? eval($sPlugin) : false);
	}
}

?>