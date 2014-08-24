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
 * @version 		$Id: index.class.php 6113 2013-06-21 13:58:40Z Raymond_Benc $
 */
class organization_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$bSubCategory = false;
		if (($iId = $this->request()->getInt('sub')))
		{
			$bSubCategory = true;
			if (($iDelete = $this->request()->getInt('delete')))
			{
				if (Phpfox::getService('organization.process')->deleteCategory($iDelete, true))
				{
					$this->url()->send('admincp.organization', array('sub' => $iId), Phpfox::getPhrase('organization.successfully_deleted_the_category'));
				}
			}
		}
		else
		{
			if (($iDelete = $this->request()->getInt('delete')))
			{
				if (Phpfox::getService('organization.process')->deleteCategory($iDelete))
				{
					$this->url()->send('admincp.organization', null, Phpfox::getPhrase('organization.successfully_deleted_the_category'));
				}
			}			
		}
		
		$this->template()->setTitle(($bSubCategory ?  Phpfox::getPhrase('organization.manage_sub_categories') : Phpfox::getPhrase('organization.manage_categories')))
			->setBreadcrumb(($bSubCategory ?  Phpfox::getPhrase('organization.manage_sub_categories') : Phpfox::getPhrase('organization.manage_categories')))
			->setHeader(array(
					'drag.js' => 'static_script',
					'<script type="text/javascript">$Behavior.coreDragInit = function() { Core_drag.init({table: \'#js_drag_drop\', ajax: \'' . ($bSubCategory ? 'organization.categorySubOrdering' : 'organization.categoryOrdering' ) . '\'}); }</script>'
				)
			)			
			->assign(array(
					'bSubCategory' => $bSubCategory,
					'aCategories' => ($bSubCategory ? Phpfox::getService('organization.category')->getForAdmin($iId) : Phpfox::getService('organization.type')->getForAdmin())
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('organization.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>