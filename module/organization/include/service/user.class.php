<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');

    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond_Benc
    * @package         Phpfox_Service
    * @version         $Id: process.class.php 7230 2014-03-26 21:14:12Z Fern $
    */
    class organization_Service_User extends Phpfox_Service 
    {
        public function add($aVals)
        {
            if(!isset($aVals['agree']))
            {
                Phpfox_Error::set('Check our agreement in order to join our site.');
                return false;
            }

            if(!filter_var($aVals['organization_email'], FILTER_VALIDATE_EMAIL))
            {
                Phpfox_Error::set('Provide a valid email address.');
                return false;
            }

            if(strlen($aVals['organization_password']) < 6)
            {
                Phpfox_Error::set('Not a valid password.');
                return false;
            }
            
            $aInsert = array(
                'view_id' => 0,
                'type_id' => (isset($aVals['type_id']) ? (int) $aVals['type_id'] : 2),
                'app_id' => (isset($aVals['app_id']) ? (int)$aVals['app_id'] : 0),
                'category_id' => (isset($aVals['category_id']) ? (int) $aVals['category_id'] : 2),
                'user_id' => 0,
                'title' => $this->preParse()->clean($aVals['organization_name']),            
                'founder' => $aVals['organization_founder'],                      
                'mission_statement' => $aVals['organization_mission'],                                 
                'phone' => $aVals['organization_phone'],                      
                'website' => $aVals['organization_website'],                      
                'time_stamp' => PHPFOX_TIME
            );

            $iId = $this->database()->insert(Phpfox::getT('organization'), $aInsert);
            
            $aInsertText = array('organization_id' => $iId);
            if (isset($aVals['info']))
            {
                $aInsertText['text'] = $this->preParse()->clean($aVals['info']); 
                $aInsertText['text_parsed'] = $this->preParse()->prepare($aVals['info']);
            }
            $this->database()->insert(Phpfox::getT('organization_text'), $aInsertText);

            $sSalt = $this->_getSalt();
            $iUserId = $this->database()->insert(Phpfox::getT('user'), array(
                'profile_organization_id' => $iId,
                'user_group_id' => NORMAL_USER_ID,
                'view_id' => '7',
                'full_name' => $this->preParse()->clean($aVals['organization_name']),
                'joined' => PHPFOX_TIME,
                'email' => $aVals['organization_email'],
                'password' => Phpfox::getLib('hash')->setHash($aVals['organization_password'], $sSalt),
                'password_salt' => $sSalt,
                )
            );
            
            $this->database()->update(Phpfox::getT('user'),array('user_name' => 'profile-'.$iUserId),'user_id='.$iUserId);
            
            $this->database()->update(Phpfox::getT('organization'),array('user_id' => $iUserId),'organization_id='.$iId);
            $aExtras = array(
                'user_id' => $iUserId
            );

            $this->database()->insert(Phpfox::getT('user_activity'), $aExtras);
            $this->database()->insert(Phpfox::getT('user_field'), $aExtras);
            $this->database()->insert(Phpfox::getT('user_space'), $aExtras);
            $this->database()->insert(Phpfox::getT('user_count'), $aExtras);

            Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'organization');

            Phpfox::getService('like.process')->add('organization', $iId);

            return $iId;
        }

        private function _getSalt($iTotal = 3)
        {
            $sSalt = '';
            for ($i = 0; $i < $iTotal; $i++)
            {
                $sSalt .= chr(rand(33, 91));
            }

            return $sSalt;
        }

        public function getValidation($sStep = null)
        {        
            $aValidation = array();
            $aValidation['organization_name'] = 'Provide your organization name!';
            $aValidation['organization_founder'] = 'Provide your organization founder!';
            $aValidation['organization_mission'] = 'Provide your organization mission!';
            $aValidation['organization_phone'] = 'Provide your organization phone!';
            $aValidation['organization_website'] = 'Provide your organization website!';

            $aValidation['organization_email'] = array(
                'def' => 'organization_email',
                'title' => Phpfox::getPhrase('user.provide_a_valid_email_address')
            );

            $aValidation['organization_password'] = array(
                'def' => 'organization_password',
                'title' => Phpfox::getPhrase('user.provide_a_valid_password')
            );

            return $aValidation;
        }
    }
?>
