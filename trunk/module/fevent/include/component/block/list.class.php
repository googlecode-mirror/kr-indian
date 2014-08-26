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
 * @version 		$Id: list.class.php 2592 2011-05-05 18:51:50Z Raymond_Benc $
 */
class fevent_Component_Block_List extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$iRsvp = $this->request()->get('rsvp', 1);
		$iPage = $this->request()->getInt('page');		
		$sModule = $this->request()->get('module', false);
		$iItem =  $this->request()->getInt('item', false);
		$aCallback = $this->getParam('aCallback', false);		
		$iPageSize = 6;
		
		if (PHPFOX_IS_AJAX)
		{
			$aCallback = false;
			if ($sModule && $iItem && Phpfox::hasCallback($sModule, 'getfeventInvites'))
			{
				$aCallback = Phpfox::callback($sModule . '.getfeventInvites', $iItem);				
			}			
			
			$afevent = Phpfox::getService('fevent')->callback($aCallback)->getfevent($this->request()->get('id'), true);
			$this->template()->assign('afevent', $afevent);
		}
		else 
		{
			$afevent = $this->getParam('afevent');			
			$this->template()->assign('afevent', $afevent);
		}
		
		if ($aCallback !== false)
		{
			$sModule = $aCallback['module'];
			$iItem = $aCallback['item'];
		}		
		
		list($iCnt, $aInvites) = Phpfox::getService('fevent')->getInvites($afevent['fevent_id'], $iRsvp, $iPage, $iPageSize);		
				
		Phpfox::getLib('pager')->set(array('ajax' => 'fevent.listGuests', 'page' => $iPage, 'size' => $iPageSize, 'count' => $iCnt, 'aParams' => 
			array(
					'id' => $afevent['fevent_id'],
					'module' => $sModule,
					'item' => $iItem,
					'rsvp' => $iRsvp
				)
			)
		);
		
		$this->template()->assign(array(
				'aInvites' => $aInvites,
				'iRsvp' => $iRsvp				
			)
		);		
		
		if (!PHPFOX_IS_AJAX)
		{			
			$sExtra = '';
			if ($aCallback !== false)
			{
				$sExtra .= '&amp;module=' . $aCallback['module'] . '&amp;item=' . $aCallback['item'];	
			}			
			
			$this->template()->assign(array(
					'sHeader' => Phpfox::getPhrase('fevent.fevent_guests'),
					'aMenu' => array(
						Phpfox::getPhrase('fevent.attending') => '#fevent.listGuests?rsvp=1&amp;id=' . $afevent['fevent_id'] . $sExtra,
						Phpfox::getPhrase('fevent.maybe') => '#fevent.listGuests?rsvp=2&amp;id=' . $afevent['fevent_id'] . $sExtra,
						Phpfox::getPhrase('fevent.can_t_make_it') => '#fevent.listGuests?rsvp=3&amp;id=' . $afevent['fevent_id'] . $sExtra,
						Phpfox::getPhrase('fevent.not_responded') => '#fevent.listGuests?rsvp=0&amp;id=' . $afevent['fevent_id'] . $sExtra
					),
					'sBoxJsId' => 'fevent_guests'
				)
			);
			
			return 'block';
		}		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_list_clean')) ? eval($sPlugin) : false);
	}
}

?>