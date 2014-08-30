<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Profile Block Header
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Profile
 * @version 		$Id: logo.class.php 6203 2013-07-04 08:42:30Z Raymond_Benc $
 */
class Fevent_Component_Block_Logo extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
        //$aCoverPhoto = Phpfox::getService('photo')->getCoverPhoto($aUser['cover_photo']);
        //'sLogoPosition' => $aUser['cover_photo_top'],
        $aFevent = $this->getParam('afevent',false);
        if(!$aFevent)
        {
            return false;
        }
        $cover_photo= $aFevent['cover_photo_id'];
		$aCoverPhoto = Phpfox::getService('photo')->getCoverPhoto($cover_photo);
		$cover_photo_top = $aFevent['cover_photo_position'];
		if (!isset($aCoverPhoto['photo_id']))
		{
			return false;
		}
        $iAdmin = false;
        if($aFevent['user_id'] == Phpfox::getUserId())
        {
            $iAdmin =true;
        }
        $dTime =$php_timestamp_date = date("d F Y", $aFevent['end_time']);
        $aTime = explode(" ", $dTime);
        $iMonthEvent = $aTime[1];
        $iDayEvent = $aTime[0]; 
        $FeventLink=Phpfox::getLib('url')->permalink('fevent', $aFevent['fevent_id'],"");	
        $this->template()->assign('sFeventLink', $FeventLink);
		$this->template()->assign(array(
				'aCoverPhoto' => $aCoverPhoto,
				'bRefreshPhoto' => ($this->request()->getInt('coverupdate') ? true : false),
				'bNewCoverPhoto' => ($this->request()->getInt('newcoverphoto') ? true : false),
				'sLogoPosition' => $cover_photo_top,
                'tMonth' => $iMonthEvent,
                'tDay' => $iDayEvent,
                'iAdmin' =>$iAdmin
                
			)
		);
        
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('profile.component_block_logo_clean')) ? eval($sPlugin) : false);
	}
}

?>