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
 * @version 		$Id: block.class.php 103 2009-01-27 11:32:36Z Raymond_Benc $
 */
class organization_Component_Block_Photo extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if (!defined('PHPFOX_IS_ORGANIZATION_VIEW'))
		{
			return false;
		}
		$aOrganization = $this->getParam('aOrganization');
		if (empty($aOrganization))
        {
            return false;
        }
		$this->template()->assign(array(
				'aOrganizationLinks' => Phpfox::getService('organization')->getMenu($aOrganization)
			)
		);
		
		if (defined("PHPFOX_IN_DESIGN_MODE") || Phpfox::getService('theme')->isInDndMode())
		{
			return 'block';
		}
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('organization.component_block_photo_clean')) ? eval($sPlugin) : false);
	}
}

?>