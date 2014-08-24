<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: index.class.php 5948 2013-05-24 08:26:41Z Miguel_Espinoza $
 */
class organization_Component_Controller_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$bIsUserProfile = $this->getParam('bIsProfile');
		if ($bIsUserProfile)
		{
			$aUser = $this->getParam('aUser');
		}
		//Phpfox::getUserParam('organization.can_view_browse_organization', true);
		
		if ($this->request()->getInt('req2') > 0)
		{
			Phpfox::getLib('module')->setCacheBlockData(array(
					'table' => 'organization_design_order',
					'field' => 'organization_id',
					'item_id' => $this->request()->getInt('req2'),
					'controller' => 'organization.view'
				)
			);			
			
			return Phpfox::getLib('module')->setController('organization.view');			
		}	
		
		if (($iDeleteId = $this->request()->getInt('delete')) && Phpfox::getService('organization.process')->delete($iDeleteId))
		{
			$this->url()->send('organization', array(), Phpfox::getPhrase('organization.page_successfully_deleted'));
		}
		
		$sView = $this->request()->get('view');
		
		if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
		{
			$bIsProfile = true;
			$aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
			$this->setParam('aUser', $aUser);
		}
		else 
		{		
			$bIsProfile = $this->getParam('bIsProfile');	
			if ($bIsProfile === true)
			{
				$aUser = $this->getParam('aUser');
			}
		}		
		
        if ($bIsProfile)
        {
            $this->template()
                    ->setTitle(Phpfox::getPhrase('organization.full_name_s_organization', array('full_name' => $aUser['full_name'])))
                    ->setBreadcrumb(Phpfox::getPhrase('organization.organization'), $this->url()->makeUrl($aUser['user_name'], array('organization')) );
        }
        else
        {
            $this->template()
                    ->setTitle(Phpfox::getPhrase('organization.organization'))
                    ->setBreadcrumb(Phpfox::getPhrase('organization.organization'), $this->url()->makeUrl('organization'));
        }
            
		$this->search()->set(array(
				'type' => 'organization',
				'field' => 'organization.organization_id',				
				'search_tool' => array(
					'table_alias' => 'organization',
					'search' => array(
						'action' => ($bIsProfile === true ? $this->url()->makeUrl($aUser['user_name'], array('organization', 'view' => $this->request()->get('view'))) : $this->url()->makeUrl('organization', array('view' => $this->request()->get('view')))),
						'default_value' => Phpfox::getPhrase('organization.search_organization'),
						'name' => 'search',
						'field' => 'organization.title'
					),
					'sort' => array(
						'latest' => array('organization.time_stamp', Phpfox::getPhrase('organization.latest')),
						'most-liked' => array('organization.total_like', Phpfox::getPhrase('organization.most_liked'))						
					),
					'show' => array(10, 15, 20)
				)
			)
		);				
		
		$aBrowseParams = array(
			'module_id' => 'organization',
			'alias' => 'organization',
			'field' => 'organization_id',
			'table' => Phpfox::getT('organization'),
			'hide_view' => array('pending', 'my')				
		);			
		
		$aFilterMenu = array();
		if (!defined('PHPFOX_IS_USER_PROFILE'))
		{
			$aFilterMenu = array(
				Phpfox::getPhrase('organization.all_organization') => '',
				Phpfox::getPhrase('organization.my_organization') => 'my'							
			);
			
			if (!Phpfox::getParam('core.friends_only_community') && Phpfox::isModule('friend') && !Phpfox::getUserBy('profile_organization_id'))
			{
				$aFilterMenu[Phpfox::getPhrase('organization.friends_organization')] = 'friend';	
			}	
			
			if (Phpfox::getUserParam('organization.can_moderate_organization'))
			{
				$iPendingTotal = Phpfox::getService('organization')->getPendingTotal();
				
				if ($iPendingTotal)
				{
					$aFilterMenu['' . Phpfox::getPhrase('organization.pending_organization') . '<span class="pending">' . $iPendingTotal . '</span>'] = 'pending';
				}
			}				
		}
		
		switch ($sView)
		{
			case 'my':
				Phpfox::isUser(true);
				$this->search()->setCondition('AND organization.app_id = 0 AND organization.view_id IN(0,1) AND organization.user_id = ' . Phpfox::getUserId());
				break;
			case 'pending':
				Phpfox::isUser(true);
				if (Phpfox::getUserParam('organization.can_moderate_organization'))
				{
					$this->search()->setCondition('AND organization.app_id = 0 AND organization.view_id = 1');
				}				
				break;			
			default:
				if (Phpfox::getUserParam('privacy.can_view_all_items'))
				{
					$this->search()->setCondition('AND organization.app_id = 0 ');  
				}
				else
				{
				    $this->search()->setCondition('AND organization.app_id = 0 AND organization.view_id = 0 AND organization.privacy IN(%PRIVACY%)');
				}
				break;
		}		
		
		$this->template()->buildSectionMenu('organization', $aFilterMenu);
		$bIsValidCategory = false;
		
		if ($this->request()->get('req2') == 'category' && ($iCategoryId = $this->request()->getInt('req3')) && ($aType = Phpfox::getService('organization.type')->getById($iCategoryId)))
		{
			$bIsValidCategory = true;
			$this->setParam('iCategory', $iCategoryId);
			$this->template()->setBreadcrumb(Phpfox::getLib('locale')->convert($aType['name']), Phpfox::permalink('organization.category', $aType['type_id'], $aType['name']) . ($sView ? 'view_' . $sView . '/' . '' : ''), true);
		}
		
		if ($this->request()->get('req2') == 'sub-category' && ($iSubCategoryId = $this->request()->getInt('req3')) && ($aCategory = Phpfox::getService('organization.category')->getById($iSubCategoryId)))
		{
			$bIsValidCategory = true;
			$this->setParam('iCategory', $aCategory['type_id']);
			$this->template()->setBreadcrumb(Phpfox::getLib('locale')->convert($aCategory['type_name']), Phpfox::permalink('organization.category', $aCategory['type_id'], $aCategory['type_name']) . ($sView ? 'view_' . $sView . '/' . '' : ''));
			$this->template()->setBreadcrumb(Phpfox::getLib('locale')->convert($aCategory['name']), Phpfox::permalink('organization.sub-category', $aCategory['category_id'], $aCategory['name']) . ($sView ? 'view_' . $sView . '/' . '' : ''), true);
		}
		
		if (isset($aType['type_id']))
		{
			$this->search()->setCondition('AND organization.type_id = ' . (int) $aType['type_id']);
		}
		
		if (isset($aType['category_id']))
		{
			$this->search()->setCondition('AND organization.category_id = ' . (int) $aType['category_id']);
		}
		elseif	(isset($aCategory['category_id']))
		{
			$this->search()->setCondition('AND organization.category_id = ' . (int) $aCategory['category_id']);
		}		
		
		if ($bIsUserProfile)
		{
			$this->search()->setCondition('AND organization.user_id = ' . (int) $aUser['user_id']);
		}
		
		$this->search()->browse()->params($aBrowseParams)->execute();
		
		$aOrganizations = $this->search()->browse()->getRows();
		
		foreach ($aOrganizations as $iKey => $aOrganization)
		{
			if (!isset($aOrganization['vanity_url']) || empty($aOrganization['vanity_url']))
			{
				$aorganization[$iKey]['url'] = Phpfox::permalink('organization', $aOrganization['organization_id'], $aOrganization['title']);
			}
			else
			{
				$aorganization[$iKey]['url'] = $aOrganization['vanity_url'];
			}
		}
		
		Phpfox::getLib('pager')->set(array('page' => $this->search()->getPage(), 'size' => $this->search()->getDisplay(), 'count' => $this->search()->browse()->getCount()));		
		
		$this->template()->setHeader('cache', array(
					'comment.css' => 'style_css',
					'pager.css' => 'style_css',
					'feed.js' => 'module_feed',
					'organization.js' => 'module_organization'
				)
			)
			->assign(array(
					'sView' => $sView,
					'aOrganizations' => $aOrganizations
				)
			);
			
		
		$this->setParam('global_moderation', array(
				'name' => 'organization',
				'ajax' => 'organization.pageModeration',
				'menu' => array(
					array(
						'phrase' => Phpfox::getPhrase('organization.delete'),
						'action' => 'delete'
					),
					array(
						'phrase' => Phpfox::getPhrase('organization.approve'),
						'action' => 'approve'
					)					
				)
			)
		);
				
				
		$iStartCheck = 0;
		if ($bIsValidCategory == true)
		{
			$iStartCheck = 5;
		}
		$aRediAllow = array('category');
		if (defined('PHPFOX_IS_USER_PROFILE') && PHPFOX_IS_USER_PROFILE)
		{
			$aRediAllow[] = 'organization';
		}
		$aCheckParams = array(
			'url' => $this->url()->makeUrl('organization'),
			'start' => $iStartCheck,
			'reqs' => array(
					'2' => $aRediAllow
				)
			);
		
		if (Phpfox::getParam('core.force_404_check') && !Phpfox::getService('core.redirect')->check404($aCheckParams))
		{
			return Phpfox::getLib('module')->setController('error.404');
		}
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('organization.component_controller_index_clean')) ? eval($sPlugin) : false);
	}
}

?>