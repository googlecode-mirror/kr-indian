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
 * @version 		$Id: sponsored.class.php 3990 2012-03-09 15:28:08Z Raymond_Benc $
 */
class fevent_Component_Block_Sponsored extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{
		if (!Phpfox::isModule('ad'))
		{
			return false;
		}	    
		
		if (defined('PHPFOX_IS_GROUP_VIEW'))
	    {
			return false;
	    }
	    
	    $aSponsorfevents = Phpfox::getService('fevent')->getRandomSponsored();
	    
	    if (empty($aSponsorfevents))
	    {
			return false;
	    }
	    
	    Phpfox::getService('ad.process')->addSponsorViewsCount($aSponsorfevents['sponsor_id'], 'fevent');
		
	    $this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.sponsored_fevent'),
				'aSponsorfevents' => $aSponsorfevents,
				'aFooter' => array(Phpfox::getPhrase('fevent.encourage_sponsor') => $this->url()->makeUrl('profile.fevent', array('sponsor' => 1)))
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
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_sponsored_clean')) ? eval($sPlugin) : false);
	}
}

?>