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
    class Community_Component_Block_City_Register extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {    
            $aCitys = Phpfox::getService('community')->getCityFromLocation($this->request()->get('country_iso'),$this->request()->get('country_child_id'));
            $this->template()->assign(array(
                'aCitys' => $aCitys
            ));
        }
    }
?>
