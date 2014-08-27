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
    * @version         $Id: ajax.class.php 7271 2014-04-14 18:46:05Z Fern $
    */
    class Community_Component_Ajax_Ajax extends Phpfox_Ajax
    {   
        public function getCityLocations()
        {
            Phpfox::getComponent('community.city-register',array(),'block');
            $this->call('$("#js_city_location").html("'.$this->getContent().'");');
            $this->call('$(".loading_city").hide();');
        }
    }
?>
