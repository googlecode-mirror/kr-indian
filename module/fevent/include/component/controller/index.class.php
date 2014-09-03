<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_fevent
 * @version 		$Id: index.class.php 7268 2014-04-11 18:04:29Z Fern $
 */
class fevent_Component_Controller_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		Phpfox::getUserParam('fevent.can_access_fevent', true);
		
		$aParentModule = $this->getParam('aParentModule');	
			
		if ($aParentModule === null && $this->request()->getInt('req2') > 0)
		{
			return Phpfox::getLib('module')->setController('fevent.view');
		}		
		
		if (($sLegacyTitle = $this->request()->get('req2')) && !empty($sLegacyTitle))
		{
			if ($this->request()->get('req3') != '')
			{
				$sLegacyTitle = $this->request()->get('req3');
			}
			
			$aLegacyItem = Phpfox::getService('core')->getLegacyItem(array(
					'field' => array('category_id', 'name'),
					'table' => 'fevent_category',		
					'redirect' => 'fevent.category',
					'title' => $sLegacyTitle,
					'search' => 'name_url'
				)
			);		
		}		
		
		if (($iRedirectId = $this->request()->getInt('redirect')) 
			&& ($afevent = Phpfox::getService('fevent')->getfevent($iRedirectId, true)) 
			&& $afevent['module_id'] != 'fevent'
			&& Phpfox::hasCallback($afevent['module_id'], 'getfeventRedirect')
		)
		{
			if (($sForward = Phpfox::callback($afevent['module_id'] . '.getfeventRedirect', $afevent['fevent_id'])))
			{	
				Phpfox::getService('notification.process')->delete('fevent_invite', $afevent['fevent_id'], Phpfox::getUserId());
				
				$this->url()->forward($sForward);
			}
		}			
		
		if (($iDeleteId = $this->request()->getInt('delete')))
		{
			if (($mDeleteReturn = Phpfox::getService('fevent.process')->delete($iDeleteId)))
			{
				if (is_bool($mDeleteReturn))
				{
					$this->url()->send('fevent', null, Phpfox::getPhrase('fevent.fevent_successfully_deleted'));
				}
				else
				{
					$this->url()->forward($mDeleteReturn, Phpfox::getPhrase('fevent.fevent_successfully_deleted'));
				}
			}
		}
		
		if (($iRedirectId = $this->request()->getInt('redirect')) && ($afevent = Phpfox::getService('fevent')->getfevent($iRedirectId, true)))
		{
			Phpfox::getService('notification.process')->delete('fevent_invite', $afevent['fevent_id'], Phpfox::getUserId());
			
			$this->url()->permalink('fevent', $afevent['fevent_id'], $afevent['title']);	
		}
		
		$bIsUserProfile = false;
		if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
		{
			$bIsUserProfile = true;
			$aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
			$this->setParam('aUser', $aUser);
		}		
		
		if (defined('PHPFOX_IS_USER_PROFILE'))
		{
			$bIsUserProfile = true;
			$aUser = $this->getParam('aUser');
		}		
		
		$oServicefeventBrowse = Phpfox::getService('fevent.browse');
		$sCategory = null;
		$sView = $this->request()->get('view', false);
		$aCallback = $this->getParam('aCallback', false);			
		
		$this->search()->set(array(				
				'type' => 'fevent',
				'field' => 'm.fevent_id',				
				'search_tool' => array(
					'default_when' => 'upcoming',
					'when_field' => 'start_time',
					'when_upcoming' => true,
					'table_alias' => 'm',
					'search' => array(
						'action' => ($aParentModule === null ? ($bIsUserProfile === true ? $this->url()->makeUrl($aUser['user_name'], array('fevent', 'view' => $this->request()->get('view'))) : $this->url()->makeUrl('fevent', array('view' => $this->request()->get('view')))) : $aParentModule['url'] . 'fevent/view_' . $this->request()->get('view') . '/'),
						'default_value' => Phpfox::getPhrase('fevent.search_fevents'),
						'name' => 'search',
						'field' => 'm.title'
					),
					'sort' => array(
						'latest' => array('m.start_time', Phpfox::getPhrase('fevent.latest'), 'ASC'),
						//'most-viewed' => array('m.total_view', Phpfox::getPhrase('fevent.most_viewed')),
						'most-liked' => array('m.total_like', Phpfox::getPhrase('fevent.most_liked')),
						'most-talked' => array('m.total_comment', Phpfox::getPhrase('fevent.most_discussed'))
					),
					'show' => array(12, 15, 18, 21)
				)
			)
		);
		
		$aBrowseParams = array(
			'module_id' => 'fevent',
			'alias' => 'm',
			'field' => 'fevent_id',
			'table' => Phpfox::getT('fevent'),
			'hide_view' => array('pending', 'my')
		);		
		
		switch ($sView)
		{
			case 'pending':
				if (Phpfox::getUserParam('fevent.can_approve_fevents'))
				{
					$this->search()->setCondition('AND m.view_id = 1');
				}
				break;
			case 'my':
				Phpfox::isUser(true);
				$this->search()->setCondition('AND m.user_id = ' . Phpfox::getUserId());
				break;
			default:
				if ($bIsUserProfile)
				{					
					$this->search()->setCondition('AND m.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND m.module_id = "fevent" AND m.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND m.user_id = ' . (int) $aUser['user_id']);
				}
				elseif ($aParentModule !== null)
				{
					$this->search()->setCondition('AND m.view_id = 0 AND m.privacy IN(%PRIVACY%) AND m.module_id = \'' . Phpfox::getLib('database')->escape($aParentModule['module_id']) . '\' AND m.item_id = ' . (int) $aParentModule['item_id'] . '');
				}
				else
				{			
					switch ($sView)
					{
						case 'attending':				
							$oServicefeventBrowse->attending(1);
							break;
						case 'may-attend':				
							$oServicefeventBrowse->attending(2);
							break;	
						case 'not-attending':				
							$oServicefeventBrowse->attending(3);
							break;
						case 'invites':				
							$oServicefeventBrowse->attending(0);
							break;							
					}						
					
					if ($sView == 'attending')
					{
						$this->search()->setCondition('AND m.view_id = 0 AND m.privacy IN(%PRIVACY%)');
					}
					else
					{
						$this->search()->setCondition('AND m.view_id = 0 AND m.privacy IN(%PRIVACY%) AND m.item_id = ' . ($aCallback !== false ? (int) $aCallback['item'] : 0) . '');
					}
					
					if ($this->request()->getInt('user') && ($aUserSearch = Phpfox::getService('user')->getUser($this->request()->getInt('user'))))
					{
						$this->search()->setCondition('AND m.user_id = ' . (int) $aUserSearch['user_id']);
						$this->template()->setBreadcrumb($aUserSearch['full_name'] . '\'s fevents', $this->url()->makeUrl('fevent', array('user' => $aUserSearch['user_id'])), true);
					}
				}
				break;
		}
		
		if ($this->request()->getInt('sponsor') == 1)
		{
		    $this->search()->setCondition('AND m.is_sponsor != 1');
		    Phpfox::addMessage(Phpfox::getPhrase('fevent.sponsor_help'));
		}			
		
		if ($this->request()->get('req2') == 'category')
		{
			$sCategory = $this->request()->getInt('req3');
			$this->search()->setCondition('AND mcd.category_id = ' . (int) $sCategory);
		}
		
		if ($sView == 'featured')
		{
			$this->search()->setCondition('AND m.is_featured = 1');
		}		
		
		$this->setParam('sCategory', $sCategory);		
		
		$oServicefeventBrowse->callback($aCallback)->category($sCategory);	
			
		$this->search()->browse()->params($aBrowseParams)->execute();
		
		$aFilterMenu = array();
		$bSetFilterMenu = (!defined('PHPFOX_IS_USER_PROFILE') && !defined('PHPFOX_IS_PAGES_VIEW') );
		if ($sPlugin = Phpfox_Plugin::get('fevent.component_controller_index_set_filter_menu_1'))
		{
			eval($sPlugin);
			if (isset($mReturnFromPlugin))
			{
				return $mReturnFromPlugin;
			}
		}
		
		if ($bSetFilterMenu)
		{
			$aFilterMenu = array(
				Phpfox::getPhrase('fevent.all_fevents') => '',
				Phpfox::getPhrase('fevent.my_fevents') => 'my'
			);							
				
			if (Phpfox::isModule('friend') && !Phpfox::getParam('core.friends_only_community'))
			{
				$aFilterMenu[Phpfox::getPhrase('fevent.friends_fevents')] = 'friend';	
			}			
			
			list($iTotalFeatured, $aFeatured) = Phpfox::getService('fevent')->getFeatured();
			if ($iTotalFeatured)
			{
				$aFilterMenu[Phpfox::getPhrase('fevent.featured_fevents') . '<span class="pending">' . $iTotalFeatured . '</span>'] = 'featured';
			}				
			
			if (Phpfox::getUserParam('fevent.can_approve_fevents'))
			{
				$iPendingTotal = Phpfox::getService('fevent')->getPendingTotal();
				
				if ($iPendingTotal)
				{
					$aFilterMenu[Phpfox::getPhrase('fevent.pending_fevents') . '<span class="pending">' . $iPendingTotal . '</span>'] = 'pending';
				}
			}
			
			$aFilterMenu[] = true;
			
			$aFilterMenu[Phpfox::getPhrase('fevent.fevents_i_m_attending')] = 'attending';
			$aFilterMenu[Phpfox::getPhrase('fevent.fevents_i_may_attend')] = 'may-attend';
			$aFilterMenu[Phpfox::getPhrase('fevent.fevents_i_m_not_attending')] = 'not-attending';
			$aFilterMenu[Phpfox::getPhrase('fevent.fevent_invites')] = 'invites';
			
			$this->template()->buildSectionMenu('fevent', $aFilterMenu);	
		}							
		
		$this->template()->setTitle(($bIsUserProfile ? Phpfox::getPhrase('fevent.full_name_s_fevents', array('full_name' => $aUser['full_name'])) : Phpfox::getPhrase('fevent.fevents')))->setBreadcrumb(Phpfox::getPhrase('fevent.fevents'), ($aCallback !== false ? $this->url()->makeUrl($aCallback['url_home'][0], array_merge($aCallback['url_home'][1], array('fevent'))) : ($bIsUserProfile ? $this->url()->makeUrl($aUser['user_name'], 'fevent') : $this->url()->makeUrl('fevent'))))
			->setHeader('cache', array(
					'pager.css' => 'style_css',
					'country.js' => 'module_core',
					'comment.css' => 'style_css',
					'browse.css' => 'module_fevent',
					'feed.js' => 'module_feed'				
				)
			)
			->assign(array(
					'afevents' => $this->search()->browse()->getRows(),
					'sView' => $sView,
					'aCallback' => $aCallback,
					'sParentLink' => ($aCallback !== false ? $aCallback['url_home'][0] . '.' . implode('.', $aCallback['url_home'][1]) . '.fevent' : 'fevent'),
					'sApproveLink' => $this->url()->makeUrl('fevent', array('view' => 'pending'))
				)
			);  
		if ($sCategory !== null)
		{
			$aCategories = Phpfox::getService('fevent.category')->getParentBreadcrumb($sCategory);			
			$iCnt = 0;
			foreach ($aCategories as $aCategory)
			{
				$iCnt++;
				
				$this->template()->setTitle($aCategory[0]);
				
				if ($aCallback !== false)
				{
					$sHomeUrl = '/' . Phpfox::getLib('url')->doRewrite($aCallback['url_home'][0]) . '/' . implode('/', $aCallback['url_home'][1]) . '/' . Phpfox::getLib('url')->doRewrite('fevent') . '/';
					$aCategory[1] = preg_replace('/^http:\/\/(.*?)\/' . Phpfox::getLib('url')->doRewrite('fevent') . '\/(.*?)$/i', 'http://\\1' . $sHomeUrl . '\\2', $aCategory[1]);
				}
				
				$this->template()->setBreadcrumb($aCategory[0], $aCategory[1], (empty($sView) ? true : false));
			}			
		}
		
		if ($aCallback !== false)
		{
			$this->template()->rebuildMenu('fevent.index', $aCallback['url_home']);			
		}

		Phpfox::getLib('pager')->set(array('page' => $this->search()->getPage(), 'size' => $this->search()->getDisplay(), 'count' => $this->search()->browse()->getCount()));
		
		$this->setParam('global_moderation', array(
				'name' => 'fevent',
				'ajax' => 'fevent.moderation',
				'menu' => array(
					array(
						'phrase' => Phpfox::getPhrase('fevent.delete'),
						'action' => 'delete'
					),
					array(
						'phrase' => Phpfox::getPhrase('fevent.approve'),
						'action' => 'approve'
					)					
				)
			)
		);			
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_controller_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
