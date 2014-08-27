<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond Benc
    * @package          Module_Comment
    * @version         $Id: comment.class.php 7059 2014-01-22 14:20:10Z Fern $
    */
    class Community_Service_Community extends Phpfox_Service 
    {    
        public function getAllCity()
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('community_city'))
            ->execute('getRows');
        }
        
        public function getCityFromId($iCityId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('community_city'))
            ->where('city_id='.(int)$iCityId)
            ->execute('getRow');
        }
        
        public function getCommunityFromCity($iCityId)
        {
            return $this->database()->select('*')
            ->from(Phpfox::getT('community'))
            ->where('city_id='.(int)$iCityId)
            ->execute('getRow');
        }
        
        public function getCityFromLocation($iCountryIso,$iCountryChildId = 0)
        {
            if(!$iCountryChildId)
            {
                return $this->database()->select('*')
                ->from(Phpfox::getT('community_city'))
                ->where("country_iso='".$iCountryIso."'")
                ->execute('getRows');
            }
            else
            {
                return $this->database()->select('*')
                ->from(Phpfox::getT('community_city'))
                ->where("country_child_id=".$iCountryChildId)
                ->execute('getRows');
            }
        }
    }
?>
