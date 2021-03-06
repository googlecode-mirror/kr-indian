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
    * @package          Module_Blog
    * @version         $Id: index.class.php 7264 2014-04-09 21:00:49Z Fern $
    */
    class Community_Component_Controller_View extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {    
            $iCommunityId = $this->request()->getInt('id');
            $aCommunity = Phpfox::getService('community')->getCommunity($iCommunityId);
            if(!isset($aCommunity['community_id']))
            {
                Phpfox_Error::display('Community not found!');
            }
            $this->setParam('aCommunity',$aCommunity);
            $this->template()->setHeader(array(
                'community.css' => 'module_community',
                'aCommunity' => $aCommunity
            ));
        }
    }
?>
