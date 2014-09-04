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
    * @package         Phpfox_Component
    * @version         $Id: index.class.php 5948 2013-05-24 08:26:41Z Miguel_Espinoza $
    */
    class Organization_Component_Controller_Info extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {       
            $aOrganization = $this->getParam('aOrganization',false);
            d($aOrganization);
        }
    }
?>
