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
 * @version 		$Id: profile.class.php 2542 2011-04-18 08:52:03Z Raymond_Benc $
 */
class fevent_Component_Block_Profile extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aUser = $this->getParam('aUser');
		
		if (!Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'fevent.display_on_profile'))
		{
			return false;
		}			
		
		$afevents = Phpfox::getService('fevent')->getForProfileBlock($aUser['user_id']);

		if (!count($afevents) && !defined('PHPFOX_IN_DESIGN_MODE'))
		{
			return false;
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.fevents_i_m_attending'),
				'sBlockJsId' => 'profile_fevent',
				'afevents' => $afevents
			)
		);
		
		if (count($afevents) == 1)
		{
			$this->template()->assign('aFooter', array(
					'View More' => $this->url()->makeUrl('fevent', array('user' => $aUser['user_id']))
				)
			);
		}
		
		if (Phpfox::getUserId() == $aUser['user_id'])
		{
			$this->template()->assign('sDeleteBlock', 'profile');
		}		
		
		return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_profile_clean')) ? eval($sPlugin) : false);
	}	
}

?>