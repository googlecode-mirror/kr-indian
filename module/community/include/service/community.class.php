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
        
        public function getCommunity($iCommunityId)
        {
            return $this->database()->select('co.*, c.name AS country_name, cc.name AS country_child_name')
            ->from(Phpfox::getT('community'),'co')
            ->leftJoin(Phpfox::getT('country'),'c','c.country_iso=co.country_iso')
            ->leftJoin(Phpfox::getT('country_child'),'cc','cc.child_id=co.country_child_id')
            ->where('community_id='.(int)$iCommunityId)
            ->execute('getRow');
        }
        
        public function getMembers($iCommunitiId)
        {
            $aUsers = $this->database()->select('*')
            ->from(Phpfox::getT('community_member'),'c')
            ->join(Phpfox::getT('user'),'u','u.user_id = c.user_id')
            ->where('u.profile_page_id = 0 AND u.profile_organization_id = 0 AND c.community_id ='.(int)$iCommunitiId)
            ->execute('getRows');
            return $aUsers;
        }
        
        public function getPreview($iCommunityId)
        {
            $aPhotos = $this->database()->select('*')
            ->from(Phpfox::getT('community_photo'),'cp')
            ->where('community_id='.(int)$iCommunityId)
            ->limit(7)
            ->order('time_stamp DESC')
            ->execute('getRows');
            if(isset($aPhotos[0]))
            {
                $aPhotos[0]['suffix'] = '_132';
            }
            else
            {
                return $aPhotos;
            }
            
            if(isset($aPhotos[1]))
            {
                $aPhotos[1]['suffix'] = '_132';
            }
            else
            {
                return $aPhotos;
            }
            
            if(isset($aPhotos[2]))
            {
                $aPhotos[2]['suffix'] = '_66';
            }
            else
            {
                return $aPhotos;
            }
            
            if(isset($aPhotos[3]))
            {
                $aPhotos[3]['suffix'] = '_132_square';
            }
            else
            {
                return $aPhotos;
            }
            
            if(isset($aPhotos[4]))
            {
                $aPhotos[4]['suffix'] = '_66';
            }
            else
            {
                return $aPhotos;
            }
            
            if(isset($aPhotos[5]))
            {
                $aPhotos[5]['suffix'] = '_132';
            }
            else
            {
                return $aPhotos;
            }
            
            if(isset($aPhotos[6]))
            {
                $aPhotos[6]['suffix'] = '_132';
            }
            else
            {
                return $aPhotos;
            }
            
            return $aPhotos;
        }
    }
?>
