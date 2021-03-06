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
    class Community_Component_Block_Header extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {    
            $aCommunity = $this->getParam('aCommunity',false);
            if(!$aCommunity)
            {
                return false;
            }
            $this->template()->assign(array(
                'aCommunity' => $aCommunity,
                'aPreviews' => Phpfox::getService('community')->getPreview($aCommunity['community_id'])
            ));
        }
    }
?>
