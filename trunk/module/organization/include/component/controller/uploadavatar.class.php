<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');
    define('PHPFOX_IS_PAGES_ADD', true);
    /**
    * 
    * 
    * @copyright        [PHPFOX_COPYRIGHT]
    * @author          Raymond_Benc
    * @package         Phpfox_Component
    * @version         $Id: add.class.php 7101 2014-02-11 13:47:16Z Fern $
    */
    class Organization_Component_Controller_UploadAvatar extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $iOrganizationId = $this->request()->getInt('organization_id');
            $type = $this->request()->getInt('type');
            $aOrganization = Phpfox::getService('organization')->getForEdit($iOrganizationId);
            if(!isset($aOrganization['organization_id']))
            {
                die();
            }
            
            if($sUrl = Phpfox::getService('organization.process')->uploadAvatar($aOrganization))
            {
                die('<script type="text/javascript">window.top.uploadAvatarSuccess("'.$sUrl.'");</script>');
            }
        }
    }
?>