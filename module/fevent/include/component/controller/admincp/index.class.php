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
 * @version 		$Id: index.class.php 6219 2013-07-09 06:43:36Z Raymond_Benc $
 */
class fevent_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if ($aOrder = $this->request()->getArray('order'))
		{
			if (Phpfox::getService('fevent.category.process')->updateOrder($aOrder))
			{
				$this->url()->send('admincp.fevent', null, Phpfox::getPhrase('fevent.category_order_successfully_updated'));
			}
		}		
		
		if ($iDelete = $this->request()->getInt('delete'))
		{
			if (Phpfox::getService('fevent.category.process')->delete($iDelete))
			{
				$this->url()->send('admincp.fevent', null, Phpfox::getPhrase('fevent.category_successfully_deleted'));
			}
		}
	
		$this->template()->setTitle(Phpfox::getPhrase('fevent.manage_categories'))
			->setBreadcrumb(Phpfox::getPhrase('fevent.manage_categories'), $this->url()->makeUrl('admincp.fevent'))
			->setPhrase(array(
					'fevent.are_you_sure_this_will_delete_all_fevents_that_belong_to_this_category_and_cannot_be_undone'
				)
			)
			->setHeader(array(
					'jquery/ui.js' => 'static_script',
					'admin.js' => 'module_fevent',
					'<script type="text/javascript">$Behavior.loadfeventUrl = function() { $Core.fevent.url(\'' . $this->url()->makeUrl('admincp.fevent') . '\'); }</script>'
				)
			)
			->assign(array(
					'sCategories' => Phpfox::getService('fevent.category')->display('admincp')->get()
				)
			);	
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>