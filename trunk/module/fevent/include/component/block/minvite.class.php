<?php
    /**
    * [PHPFOX_HEADER]
    */

    defined('PHPFOX') or exit('NO DICE!');
    class fevent_Component_Block_Minvite extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {
            $iFeventId = $this->request()->get('req2');
            $aFriend = Phpfox::getService('friend.friend')->getFromCache();        
            for($i = 0; $i< count($aFriend); $i++)
            {
                 $user_id_invite = $aFriend[$i]['user_id'];
                 $iInvite = Phpfox::getService('fevent') ->CheckSendInvite($user_id_invite);
                 if($iInvite)
                 {
                     unset($aFriend[$i]);
                 }
            }
            
            $this->template()->assign(array(
                'aFriends'=> $aFriend,
                'idFevent'=>$iFeventId
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