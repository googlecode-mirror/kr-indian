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
    * @version 		$Id: block.class.php 103 2009-01-27 11:32:36Z Raymond_Benc $
    */
    class Organization_Component_Block_Mheader extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $bIsOrganization = ((defined('PHPFOX_IS_ORGANIZATION_VIEW') && Phpfox::isModule('organization')) ? true : false);
            // Used in the template to set the ajax call
            $sModule = 'user';
            $aUser = $this->getParam('aUser');
            if (empty($aUser) && $bIsOrganization)
            {
                $aUser = $this->getParam('aOrganization');
            }
            //isset($aUser['cover_photo_id'])
            //echo $aUser['category_name'];die();
            if ($bIsOrganization && !defined('loadedLogo'))
            {            
                $aUser['cover_photo'] = $aUser['cover_photo_id'];
                $aUser['cover_photo_top'] = isset($aUser['cover_photo_position']) ? $aUser['cover_photo_position'] : 0;

                $this->template()->assign(array(
                    // 'bNoPrefix' => true,
                    'sLogoPosition' => $aUser['cover_photo_top']
                ));
                $sModule = 'organization';
                define('loadedLogo', true);

            }
            else
            {                        
                if (!defined('PHPFOX_IS_USER_PROFILE'))
                {
                    return false;
                }
            }        
            $this->template()->assign(array('sAjaxModule' => $sModule));

            if (empty($aUser['cover_photo']))
            {
                //return false;
                //$aUser['cover_photo'] = '';
            }

            $aCoverPhoto = Phpfox::getService('photo')->getCoverPhoto($aUser['cover_photo']);
            if (!isset($aCoverPhoto['photo_id']))
            {
                //return false;
                //$aCoverPhoto['photo_id'] ='';
            }

            if (!$bIsOrganization && !Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'profile.view_profile'))
            {
                //return false;
            }        

            $sOrganizationUrl = '';
            if ($bIsOrganization)
            {
                $aOrganization = $this->getParam('aOrganization');

                $this->template()->assign('sOrganizationLink', $aOrganization['link']);
            }

            //Add profile logo
            $aOrganization = $this->getParam('aOrganization');
            if (empty($aOrganization))
            {
                //return false;
            }
            $iMember = Phpfox::getService('organization')->isMember($aOrganization['organization_id']);
            d($iMember);
            $bRefreshPhoto = ($this->request()->getInt('coverupdate') ? true : false);
            $this->template()->assign(array(
                'aCoverPhoto' => $aCoverPhoto,
                'bRefreshPhoto' => $bRefreshPhoto,
                'bNewCoverPhoto' => ($this->request()->getInt('newcoverphoto') ? true : false),
                'sLogoPosition' => $aUser['cover_photo_top'],
                'bIsOrganization' => $bIsOrganization,
                'aOrganizationLinks' => Phpfox::getService('organization')->getMenu($aOrganization),
                'aUser'   =>$aUser,
                'iMember' =>$iMember
                )
            );
        }
}