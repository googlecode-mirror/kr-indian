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
    class Community_Component_Controller_Upload extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {    
            if($aVals = $this->request()->get('val'))
            {
                $sFileName = Phpfox::getService('community.process')->uploadPhoto($aVals);
                if($sFileName)
                {
                    echo '<script type="text/javascript">window.top.location.reload();</script>';
                    die();
                }
                die();
            }
        }
    }
?>
