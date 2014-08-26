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
 * @version 		$Id: invite.class.php 3533 2011-11-21 14:07:21Z Raymond_Benc $
 */
class fevent_Component_Block_Invite extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if (!Phpfox::isUser())
		{
			return false;
		}
		
		$afeventInvites = Phpfox::getService('fevent')->getInviteForUser();
		
		if (!count($afeventInvites))
		{
			return false;
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.invites'),
				'afeventInvites' => $afeventInvites
			)
		);
		
		return 'block';	
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_invite_clean')) ? eval($sPlugin) : false);
	}
}

?>