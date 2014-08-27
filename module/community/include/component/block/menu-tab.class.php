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
    class Community_Component_Block_Menu_Tab extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {   
            $aUser = Phpfox::getService('user')->get(Phpfox::getUserId());
            if($aUser['community_id'])
            {
                $aCommunity = Phpfox::getService('community')->getCommunity($aUser['community_id']);
                if(isset($aCommunity['community_id']))
                {
                    $this->template()->assign(array(
                        'aCommunity' => $aCommunity
                    ));
                }
            }
            
            $this->template()->assign(array(
                'sHeader' => 'Menu',
            ));
            return 'block';
        }
    }
?>
