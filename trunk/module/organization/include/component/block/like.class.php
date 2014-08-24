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
 * @version 		$Id: like.class.php 3333 2011-10-20 13:34:25Z Miguel_Espinoza $
 */
class organization_Component_Block_Like extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aOrganization = $this->getParam('aOrganization');
		
		$aMembers = array();
		if ($aOrganization['organization_type'] == '1')
		{
			list($iTotalMembers, $aMembers) = Phpfox::getService('organization')->getMembers($aOrganization['organization_id']);
		}
		
		$this->template()->assign(array(
				'sHeader' => ($aOrganization['organization_type'] == '1' ? '<a href="#" onclick="return $Core.box(\'like.browse\', 400, \'type_id=organization&amp;item_id=' . $aOrganization['organization_id'] . '\');">' . Phpfox::getPhrase('organization.members_total', array('total' => $iTotalMembers)) . '</a>' : Phpfox::getPhrase('organization.likes')),
				'aMembers' => $aMembers
			)
		);
		
		if (!PHPFOX_IS_AJAX || defined("PHPFOX_IN_DESIGN_MODE") || Phpfox::getService('theme')->isInDndMode())
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
		(($sPlugin = Phpfox_Plugin::get('organization.component_block_like_clean')) ? eval($sPlugin) : false);
	}
}

?>