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
    class Community_Service_Process extends Phpfox_Service 
    {    
        public function add($aVals)
        {
            if(!isset($aVals['title']) || empty($aVals['title']))
            {
                return false;
            }
            if(!isset($aVals['country_iso']) || empty($aVals['country_iso']))
            {
                return false;
            }
            $aInsert = array(
                'title' => $aVals['title'],
                'country_iso' => $aVals['country_iso'],
                'country_child_id' => (isset($aVals['country_child_id']) ? $aVals['country_child_id'] : 0),
                
                'time_stamp' => PHPFOX_TIME
            );
            $aVals['name'] = $aVals['title'];
            $iCityId = $this->addCity($aVals);
            $aInsert['city_id'] = $iCityId;
            return $this->database()->insert(Phpfox::getT('community'),$aInsert);
        }
        
        public function addCity($aVals)
        {
            if(!isset($aVals['name']) || empty($aVals['name']))
            {
                return false;
            }
            if(!isset($aVals['country_iso']) || empty($aVals['country_iso']))
            {
                return false;
            }
            
            $aInsert = array(
                'name' => $aVals['name'],
                'country_iso' => $aVals['country_iso'],
                'country_child_id' => (isset($aVals['country_child_id']) ? $aVals['country_child_id'] : 0),
                
                'time_stamp' => PHPFOX_TIME
            );
            return $this->database()->insert(Phpfox::getT('community_city'),$aInsert);
        }
    }
?>
