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
    class Community_Component_Controller_Browse extends Phpfox_Component
    {
        /**
        * Class process method wnich is used to execute this component.
        */
        public function process()
        {    
            $this->search()->set(array(
                'type' => 'community',
                'field' => 'community.community_id',                
                'search_tool' => array(
                    'table_alias' => 'community',
                    'search' => array(
                        'action' => $this->url()->makeUrl('community.browse', array('view' => $this->request()->get('view'))),
                        'default_value' => Phpfox::getPhrase('community.search_communitys'),
                        'name' => 'search',
                        'field' => array('community.title')
                    ),
                    'sort' => array(
                        'latest' => array('community.time_stamp', Phpfox::getPhrase('community.latest')),
                    ),
                    'show' => array(5, 10, 15)
                )
                )
            );                

            $aBrowseParams = array(
                'module_id' => 'community',
                'alias' => 'community',
                'field' => 'community_id',
                'table' => Phpfox::getT('community'),
                'hide_view' => array('pending', 'my')                
            );    

            $this->search()->browse()->params($aBrowseParams)->execute();

            $aCommunitys = $this->search()->browse()->getRows();
            
            Phpfox::getLib('pager')->set(array('page' => $this->search()->getPage(), 'size' => $this->search()->getDisplay(), 'count' => $this->search()->browse()->getCount()));
            d($aCommunitys);die();
        }
    }
?>
