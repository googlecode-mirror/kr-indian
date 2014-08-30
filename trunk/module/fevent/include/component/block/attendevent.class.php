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
 * @version 		$Id: attending.class.php 3342 2011-10-21 12:59:32Z Raymond_Benc $
 */
class fevent_Component_Block_AttendEvent extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iPageSize = 6;
		
		$afevent = $this->getParam('afevent');
		
		list($iCnt, $aInvites) = Phpfox::getService('fevent')->getInvites($afevent['fevent_id'], 1, 1, $iPageSize);
		list($iAwaitingCnt, $aAwaitingInvites) = Phpfox::getService('fevent')->getInvites($afevent['fevent_id'], 0, 1, $iPageSize);
		list($iMaybeCnt, $aMaybeInvites) = Phpfox::getService('fevent')->getInvites($afevent['fevent_id'], 2, 1, $iPageSize);
		list($iNotAttendingCnt, $aNotAttendingInvites) = Phpfox::getService('fevent')->getInvites($afevent['fevent_id'], 3, 1, $iPageSize);
				
		$this->template()->assign(array(
				'sHeader' => ($iCnt ? $iCnt . ' ' .Phpfox::getPhrase('fevent.attending') : Phpfox::getPhrase('fevent.attending')),
				'aInvites' => $aInvites,
				'aAwaitingInvites' => $aAwaitingInvites,
				'iAwaitingCnt' => $iAwaitingCnt,
				'aMaybeInvites' => $aMaybeInvites,
				'iMaybeCnt' => $iMaybeCnt,
				'iNotAttendingCnt' => $iNotAttendingCnt,
				'aNotAttendingInvites' => $aNotAttendingInvites,
				'aFooter' => array(
					Phpfox::getPhrase('fevent.view_guest_list') => '#'
				)
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
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_attending_clean')) ? eval($sPlugin) : false);
	}
}

?>