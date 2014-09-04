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
        
        public function addMemberToCommunity($iCommunityId,$iUserId)
        {
            $aCommunity = $this->database()->select('*')
            ->from(Phpfox::getT('community'))
            ->where('community_id='.(int)$iCommunityId)
            ->execute('getRow');
            if(!isset($aCommunity['community_id']))
            {
                return false;
            }
            
            $aMember = $this->database()->select('*')
            ->from(Phpfox::getT('community_member'))
            ->where('community_id='.(int)$iCommunityId.' AND user_id = '.(int)$iUserId)
            ->execute('getRow');
            if(isset($aMember['member_id']))
            {
                return true;
            }
            $iMemberId = $this->database()->insert(Phpfox::getT('community_member'),array(
                'user_id' => $iUserId,
                'community_id' => $iCommunityId,
                'time_stamp' => PHPFOX_TIME
            ));
            if($iMemberId)
            {
                $this->database()->update(Phpfox::getT('community'),array(
                    'total_member' => $aCommunity['total_member'] + 1
                ),'community_id='.(int)$iCommunityId);
            }
            return $iMemberId;
        }
        
        public function uploadPhoto($aVals)
        {
            if (isset($_FILES['image']['name']) && ($_FILES['image']['name'] != ''))
            {
                $aImage = Phpfox::getLib('file')->load('image', array('jpg','gif','png'),null);

                if ($aImage === false)
                {
                    return false;
                }

                $this->_bHasImage = true;
            }

            if ($this->_bHasImage)
            {            
                $oImage = Phpfox::getLib('image');

                $sFileName = Phpfox::getLib('file')->upload('image', Phpfox::getParam('community.dir_image'), $aImage['name']);

                $aInsert = array(
                    'community_id' => $aVals['community_id'],
                    'user_id' => Phpfox::getUserId(),
                    'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID'),
                    'image_path' => $sFileName,
                    'description' => (isset($aVals['description']) ? $aVals['description'] : ''),
                    'time_stamp' => PHPFOX_TIME
                );
                
                $iId = $this->database()->insert(Phpfox::getT('community_photo'),$aInsert);
                
                $iSize = 200;            
                $oImage->createThumbnail(Phpfox::getParam('community.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('community.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);            
                $oImage->createThumbnail(Phpfox::getParam('community.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('community.dir_image') . sprintf($sFileName, '_' . 132), 132, 66,false);  
                
                $oImage->createThumbnail(Phpfox::getParam('community.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('community.dir_image') . sprintf($sFileName, '_' . 66), 66, 132,false); 
                
                $oImage->createThumbnail(Phpfox::getParam('community.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('community.dir_image') . sprintf($sFileName, '_' . '132_square'), 132, 132,false); 
                  
                $sUrl = Phpfox::getLib('image.helper')->display(array(
                    'return_url' => true,
                    'path' => 'community.url_image',
                    'suffix' => '_200',
                    'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID'),
                    'file' => $sFileName,
                ));
                return $sUrl.'#'.PHPFOX_TIME;
            }        
            return false;
        }
    }
?>
