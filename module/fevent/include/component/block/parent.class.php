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
 * @version 		$Id: parent.class.php 1121 2009-10-01 12:59:13Z Raymond_Benc $
 */
class fevent_Component_Block_Parent extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$afeventParent = $this->getParam('afeventParent');
		
		$afevents = Phpfox::getService('fevent')->getForParentBlock($afeventParent['module'], $afeventParent['item']);

		if (!count($afevents) && !defined('PHPFOX_IN_DESIGN_MODE'))
		{
			return false;
		}
		
		if (!Phpfox::getService('group')->hasAccess($afeventParent['item'], 'can_use_fevent'))
		{
			return false;
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.upcoming_fevents'),
				'sBlockJsId' => 'parent_fevent',
				'afevents' => $afevents,
				'afeventParent' => $afeventParent
			)
		);
		
		if (count($afevents) == 5)
		{
			$this->template()->assign('aFooter', array(
					'View More' => $this->url()->makeUrl($afeventParent['url'][0], $afeventParent['url'][1])
				)
			);
		}
		
		return 'block';		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_parent_clean')) ? eval($sPlugin) : false);
	}		
}

?>