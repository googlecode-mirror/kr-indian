<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');
    class Organization_Component_Block_Minvite extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $aFriend = Phpfox::getService('friend.friend')->getFromCache();
            $this->template()->assign(array(
                'aFriends'=> $aFriend
            ));
        }

        public function randomFriend(){
            $aFriends = Phpfox::getService("pages.process")->getFriend();
            $iTotalFriends = count($aFriends) > 4 ? 4:count($aFriends);
            $aSixRandFriends = array();

            $aSequence = range(0,$iTotalFriends -1); 
            shuffle($aSequence);

            for($i = 0; $i<$iTotalFriends;$i++){
                $aSixRandFriends[$i] = $aFriends[$aSequence[$i]];
            }

            return $aSixRandFriends;
        }
}