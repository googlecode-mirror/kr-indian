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
    * @package         Phpfox_Service
    * @version         $Id: organization.class.php 7234 2014-03-27 14:40:29Z Fern $
    */
    class organization_Service_Clone extends Phpfox_Service 
    {
        public function copyPhrase()
        {
            $aPhrases = $this->database()->select('*')
            ->from(Phpfox::getT('language_phrase'))
            ->where("module_id='event'")
            ->execute('getRows');
            foreach($aPhrases as $key => $aPhrase)
            {
                unset($aPhrase['phrase_id']);
                $aPhrase['module_id'] = 'fevent';
                $aPhrase['version_id'] = '1.0';
                $aPhrase['product_id'] = 'fevent';
                $aPhrase['var_name'] = str_replace('event','fevent',$aPhrase['var_name']);
                $this->database()->insert(Phpfox::getT('language_phrase'),$aPhrase);
            }
        }

        public function copySetting()
        {
            $aSettings = $this->database()->select('*')
            ->from(Phpfox::getT('setting'))
            ->where("module_id='event'")
            ->execute('getRows');
            foreach($aSettings as $key => $aSetting)
            {
                unset($aSetting['setting_id']); 
                $aSetting['module_id'] = 'fevent';
                $aSetting['version_id'] = '1.0';
                $aSetting['product_id'] = 'fevent';
                $aSetting['var_name'] = str_replace('event','fevent',$aSetting['var_name']);
                $aSetting['phrase_var_name'] = str_replace('event','fevent',$aSetting['phrase_var_name']);
                $this->database()->insert(Phpfox::getT('setting'),$aSetting);
            }
        }

        public function copyUserGroupSetting()
        {
            $aSettings = $this->database()->select('*')
            ->from(Phpfox::getT('user_group_setting'))
            ->where("module_id='event'")
            ->execute('getRows');
            foreach($aSettings as $key => $aSetting)
            {
                unset($aSetting['setting_id']); 
                $aSetting['module_id'] = 'fevent';
                $aSetting['product_id'] = 'fevent';
                $aSetting['name'] = str_replace('event','fevent',$aSetting['name']);
                $this->database()->insert(Phpfox::getT('user_group_setting'),$aSetting);
            }
        }

        public function copyBlock()
        {
            $aBlocks = $this->database()->select('*')
            ->from(Phpfox::getT('block'))
            ->where("module_id='event'")
            ->execute('getRows');
            foreach($aBlocks as $key => $aBlock)
            {
                unset($aBlock['block_id']); 
                $aBlock['m_connection'] = str_replace('event','fevent',$aBlock['m_connection']);
                $aBlock['module_id'] = 'fevent';
                $aBlock['product_id'] = 'fevent';
                $this->database()->insert(Phpfox::getT('block'),$aBlock);
            }
        }

        public function copyComponent()
        {
            $aBlocks = $this->database()->select('*')
            ->from(Phpfox::getT('component'))
            ->where("module_id='event'")
            ->execute('getRows');
            foreach($aBlocks as $key => $aBlock)
            {
                unset($aBlock['component_id']); 
                $aBlock['m_connection'] = str_replace('event','fevent',$aBlock['m_connection']);
                $aBlock['module_id'] = 'fevent';
                $aBlock['product_id'] = 'fevent';
                $this->database()->insert(Phpfox::getT('component'),$aBlock);
            }
        }

        public function copyMenu()
        {
            $aMenus = $this->database()->select('*')
            ->from(Phpfox::getT('menu'))
            ->where("module_id='event'")
            ->execute('getRows');
            foreach($aMenus as $key => $aMenu)
            {
                unset($aMenu['menu_id']); 
                $aMenu['m_connection'] = str_replace('event','fevent',$aMenu['m_connection']);
                $aMenu['module_id'] = 'fevent';
                $aMenu['product_id'] = 'fevent';
                $aMenu['version_id'] = '1.0';
                $aMenu['url_value'] = str_replace('event','fevent',$aMenu['url_value']);
                $aMenu['var_name'] = str_replace('event','fevent',$aMenu['var_name']);
                $this->database()->insert(Phpfox::getT('menu'),$aMenu);
            }
        }
    }
?>
