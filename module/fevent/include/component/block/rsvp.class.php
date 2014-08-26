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
 * @version 		$Id: rsvp.class.php 1245 2009-11-02 16:10:29Z Raymond_Benc $
 */
class fevent_Component_Block_Rsvp extends Phpfox_Component
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
		
		if (PHPFOX_IS_AJAX)
		{
			$sModule = $this->request()->get('module', false);
			$iItem =  $this->request()->getInt('item', false);	
			$aCallback = false;
			if ($sModule && $iItem && Phpfox::hasCallback($sModule, 'getfeventInvites'))
			{
				$aCallback = Phpfox::callback($sModule . '.getfeventInvites', $iItem);				
			}			
		}
		
		$afevent = (PHPFOX_IS_AJAX ? Phpfox::getService('fevent')->callback($aCallback)->getfevent($this->request()->get('id'), true) : $this->getParam('afevent'));		
		
		if (PHPFOX_IS_AJAX)
		{	
			$this->template()->assign(array(
					'afevent' => $afevent,
					'aCallback' => $aCallback
				)	
			);	
		}
		else 
		{	
			$aCallback = $this->getParam('aCallback', false);
			
			$this->template()->assign(array(
					'sHeader' => Phpfox::getPhrase('fevent.your_rsvp'),
					'aCallback' => $aCallback
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
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_rsvp_clean')) ? eval($sPlugin) : false);
	}
}

?>