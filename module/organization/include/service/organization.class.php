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
 * @package 		Phpfox_Service
 * @version 		$Id: organization.class.php 7234 2014-03-27 14:40:29Z Fern $
 */
class organization_Service_organization extends Phpfox_Service 
{
	private $_bIsInViewMode = false;
	
	private $_aPage = null;
	
	private $_aRow = array();
	
	private $_bIsInPage = false;
	
	private $_aWidgetMenus = array();
	private $_aWidgetUrl = array();
	private $_aWidgetBlocks = array();
	private $_aWidgets = array();
	private $_aWidgetEdit = array();
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('organization');
	}
	
	public function isTimelinePage($iPageId)
	{
		return ((int) $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('organization'))
			->where('organization_id = ' . (int) $iPageId . ' AND use_timeline = 1')
			->execute('getSlaveField') ? true : false);	
	}
	
	public function setMode($bMode = true)
	{
		$this->_bIsInViewMode = $bMode;
	}
	
	public function isViewMode()
	{
		return (bool) $this->_bIsInViewMode;
	}
	
	public function setIsInPage()
	{
		$this->_bIsInPage = true;		
	}
	
	public function isInPage()
	{
		return $this->_bIsInPage;
	}
	
	public function buildWidgets($iId)
	{		
		if (!Phpfox::getService('organization')->hasPerm($iId, 'organization.view_browse_widgets'))
		{
			return;
		}
		
		$aWidgets = $this->database()->select('pw.*, pwt.text_parsed AS text')
			->from(Phpfox::getT('organization_widget'), 'pw')
			->join(Phpfox::getT('organization_widget_text'), 'pwt', 'pwt.widget_id = pw.widget_id')
			->where('pw.organization_id = ' . (int) $iId)
			->execute('getSlaveRows');

		foreach ($aWidgets as $aWidget)
		{
			$this->_aWidgetEdit[] = array(
				'widget_id' => $aWidget['widget_id'],
				'title' => $aWidget['title']
			);
			
			$this->_aWidgetMenus[] = array(
				'phrase' => $aWidget['menu_title'],
				'url' => $this->getUrl($aWidget['organization_id'], $this->_aRow['title'], $this->_aRow['vanity_url']) . $aWidget['url_title'] . '/',
				'landing' => $aWidget['url_title'],
				'icon_pass' => (empty($aWidget['image_path']) ? false : true),
				'icon' => $aWidget['image_path'],
				'icon_server' => $aWidget['image_server_id']
			);
			
			$this->_aWidgetUrl[$aWidget['url_title']] = $aWidget['widget_id'];
			
			if ($aWidget['is_block'])
			{
				$this->_aWidgetBlocks[] = $aWidget;
			}
			else
			{
				$this->_aWidgets[$aWidget['url_title']] = $aWidget;
			}			
		}
	}	
	
	public function getForEditWidget($iId)
	{
		$aWidget = $this->database()->select('pw.*, pwt.text_parsed AS text')
			->from(Phpfox::getT('organization_widget'), 'pw')
			->join(Phpfox::getT('organization_widget_text'), 'pwt', 'pwt.widget_id = pw.widget_id')
			->where('pw.widget_id = ' . (int) $iId)
			->execute('getSlaveRow');	
		
		if (!isset($aWidget['widget_id']))
		{
			return false;
		}
		
		$aPage = $this->getPage($aWidget['organization_id']);
		
		if (!isset($aPage['organization_id']))
		{
			return false;
		}
		
		if (!$this->isAdmin($aPage))
		{
			if (!Phpfox::getUserParam('organization.can_moderate_organization'))
			{
				return false;
			}
		}

		$aWidget['text'] = str_replace(array('<br />', '<br>', '<br/>'), "\n", $aWidget['text']);
		
		return $aWidget;
	}
	
	public function getWidgetsForEdit()
	{
		return $this->_aWidgetEdit;
	}
	
	public function isWidget($sUrl)
	{
		return (isset($this->_aWidgetUrl[$sUrl]) ? true : false);
	}
	
	public function getWidget($sUrl)
	{
		return $this->_aWidgets[$sUrl];
	}
	
	public function getWidgetBlocks()
	{
		return $this->_aWidgetBlocks;
	}
	
	public function getForProfile($iUserId)
	{		
		$aorganization = $this->database()->select('p.*, pu.vanity_url, ' . Phpfox::getUserField())
			->from(Phpfox::getT('like'), 'l')			
			->join(Phpfox::getT('organization'), 'p', 'p.organization_id = l.item_id AND p.view_id = 0')
			->join(Phpfox::getT('user'), 'u', 'u.profile_organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
			->where('l.type_id = \'organization\' AND l.user_id = ' . (int) $iUserId)
			->group('p.organization_id') // fixes displaying duplicate organization if there are duplicate likes
			->order('l.time_stamp DESC')
			->execute('getSlaveRows');		
		
		foreach ($aorganization as $iKey => $aPage)
		{
			$aorganization[$iKey]['is_app'] = false;
			if ($aPage['app_id'])
			{
				if ($aorganization[$iKey]['aApp'] = Phpfox::getService('apps')->getForPage($aPage['app_id']))
				{
					$aorganization[$iKey]['is_app'] = true;
					$aorganization[$iKey]['title'] = $aorganization[$iKey]['aApp']['app_title'];
					$aorganization[$iKey]['category_name'] = 'App';
				}			
			}
			else
			{
				if (strpos($aPage['image_path'], 'GROUP') !== false)
				{
					$sPath = Phpfox::getLib('phpfox.image.helper')->display(array(
						'user' => $aPage,
						'return_url' => true,
						'suffix' => '_75_square'
						));
					$sParsedPath = str_replace(Phpfox::getLib('url')->makeUrl(''),'',$sPath);
					if (!file_exists($sParsedPath))
					{
						$sPath = $sPath = Phpfox::getLib('phpfox.image.helper')->display(array(
						'user' => $aPage,
						'return_url' => true,
						'suffix' => '_120'
						));
						$sParsedPath = str_replace(Phpfox::getLib('url')->makeUrl(''),'',$sPath);
						if (file_exists($sParsedPath))
						{
							$aorganization[$iKey]['image_overwrite'] = $sPath;
						}
					}					
				}				
			}
			$aorganization[$iKey]['url'] = $this->getUrl($aPage['organization_id'], $aPage['title'], $aPage['vanity_url']);
		}
		
		return $aorganization;
	}
	
	public function getForView($mId)
	{
		if ($this->_aPage !== null)
		{
			$mId = $this->_aPage['organization_id'];
		}
		
		if (Phpfox::isModule('friend'))
		{
			$this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = p.user_id AND f.friend_user_id = " . Phpfox::getUserId());					
		}			
		
		if(Phpfox::isModule('like'))
		{
			$this->database()->select('l.like_id AS is_liked, ')
					->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'organization\' AND l.item_id = p.organization_id AND l.user_id = ' . Phpfox::getUserId());
		}
		
		$this->_aRow = $this->database()->select('p.*, u.user_image as image_path, p.image_path as organization_image_path, u.user_id as organization_user_id, p.use_timeline, pc.claim_id, pu.vanity_url, pg.name AS category_name, pg.organization_type, pt.text_parsed AS text, u.full_name, ts.style_id AS designer_style_id, ts.folder AS designer_style_folder, t.folder AS designer_theme_folder, t.total_column, ts.l_width, ts.c_width, ts.r_width, t.parent_id AS theme_parent_id, ' . Phpfox::getUserField('u2', 'owner_'))
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('organization_text'), 'pt', 'pt.organization_id = p.organization_id')
			->join(Phpfox::getT('user'), 'u', 'u.profile_organization_id = p.organization_id')
			->join(Phpfox::getT('user'), 'u2', 'u2.user_id = p.user_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('organization_category'), 'pg', 'pg.category_id = p.category_id')
			->leftJoin(Phpfox::getT('theme_style'), 'ts', 'ts.style_id = p.designer_style_id')
			->leftJoin(Phpfox::getT('theme'), 't', 't.theme_id = ts.theme_id')				
			->leftJoin(Phpfox::getT('organization_claim'), 'pc','pc.organization_id = p.organization_id AND pc.user_id = ' . Phpfox::getUserId())
			->where('p.organization_id = ' . (int) $mId)			
			->execute('getSlaveRow');

		/*
		if (!$this->_aRow['use_timeline'] && !file_exists(Phpfox::getParam('organization.dir_image') . sprintf($this->_aRow['image_path'], '')) && file_exists(Phpfox::getParam('organization.dir_image') . sprintf($this->_aRow['organization_image_path'], '')))
		{
		    $this->_aRow['image_path'] = $this->_aRow['organization_image_path'];
			unset($this->_aRow['organization_image_path']);
		}
		*/
		if (!isset($this->_aRow['organization_id']))
		{
			return false;
		}
		$this->_aRow['is_organization'] = true;
		$this->_aRow['is_admin'] = $this->isAdmin($this->_aRow);		
		$this->_aRow['link'] = Phpfox::getService('organization')->getUrl($this->_aRow['organization_id'], $this->_aRow['title'], $this->_aRow['vanity_url']);		
		
		if ($this->_aRow['organization_type'] == '1' && $this->_aRow['reg_method'] == '1')
		{
			$this->_aRow['is_reg'] = (int) $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('organization_signup'))
				->where('organization_id = ' . (int) $this->_aRow['organization_id'] . ' AND user_id = ' . Phpfox::getUserId())
				->execute('getSlaveField');
		}
		
		if ($this->_aRow['reg_method'] == '2' && Phpfox::isUser())
		{
			$this->_aRow['is_invited'] = (int) $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('organization_invite'))
				->where('organization_id = ' . (int) $this->_aRow['organization_id'] . ' AND invited_user_id = ' . Phpfox::getUserId())
				->execute('getSlaveField');
			
			if (!$this->_aRow['is_invited'])
			{
				unset($this->_aRow['is_invited']);
			}
		}	
		
		if ($this->_aRow['organization_id'] == Phpfox::getUserBy('profile_organization_id'))
		{
			$this->_aRow['is_liked'] = true;
		}
		
		// Issue with like/join button
		// Still not defined
		if (!isset($this->_aRow['is_liked']))
		{
			// make it false: not liked or joined yet
			$this->_aRow['is_liked'] = false;
		}
		
		if ($this->_aRow['app_id'])
		{			
			if ($this->_aRow['aApp'] = Phpfox::getService('apps')->getForPage($this->_aRow['app_id']))
			{
				$this->_aRow['is_app'] = true;
				$this->_aRow['title'] = $this->_aRow['aApp']['app_title'];
				$this->_aRow['category_name'] = 'App';
			}
		}
		else
		{
			$this->_aRow['is_app'] = false;
		}		
		
		return $this->_aRow;
	}
	
	public function isMember($iPage)
	{
		if (empty($this->_aRow))
		{
			$this->_aRow = $this->getForView($iPage);
		}
		
		if (!isset($this->_aRow['organization_id']))
		{
			return false;
		}

		if ($this->_aRow['organization_id'] == Phpfox::getUserBy('profile_organization_id'))
		{
			return true;
		}		
		
		return ((isset($this->_aRow['is_liked']) && $this->_aRow['is_liked']) ? true : false);
	}
	
	public function getPageAdmins()
	{
		$aOwnerAdmin = array();
		foreach ($this->_aRow as $sKey => $mValue)
		{
			if (substr($sKey, 0, 6) == 'owner_')
			{
				$aOwnerAdmin[0][str_replace('owner_', '', $sKey)] = $mValue;
			}
		}
		
		$aPageAdmins = $this->database()->select(Phpfox::getUserField())
			->from(Phpfox::getT('organization_admin'), 'pa')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = pa.user_id')
			->where('pa.organization_id = ' . (int) $this->_aRow['organization_id'])
			->execute('getSlaveRows');
		
		$aAdmins = array_merge($aOwnerAdmin, $aPageAdmins);	
		
		return $aAdmins;
	}
	
	public function isAdmin($aPage)
	{		
		if (!Phpfox::isUser() || empty($aPage))
		{
			return false;
		}
		
		if (is_numeric($aPage))
		{
			$aPage = $this->getPage($aPage);
		}

		if (empty($aPage))
		{
			$aPage = $this->getPage();
		}
		
        if (!isset($aPage['organization_id']))
        {
            return false;
        }
        
		if (isset($aPage['organization_id']) && $aPage['organization_id'] == Phpfox::getUserBy('profile_organization_id'))
		{
			return true;
		}

		if ($aPage['user_id'] == Phpfox::getUserId())
		{
			return true;
		}
		
		$iAdmin = (int) $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('organization_admin'))
			->where('organization_id = ' . (int) $aPage['organization_id'] . ' AND user_id = ' . (int) Phpfox::getUserId())
			->execute('getSlaveField');
		
		if ($iAdmin)
		{
			return true;
		}
		
		return false;
	}
	
	public function getPage($iId = null)
	{
		static $aRow = null;
		
		if (is_array($aRow) && $iId === null)
		{
			return $aRow;
		}
		
		if(Phpfox::isModule('like'))
		{
			$this->database()->select('l.like_id AS is_liked, ')
					->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'organization\' AND l.item_id = p.organization_id AND l.user_id = ' . Phpfox::getUserId());
		}
		
		$aRow = $this->database()->select('p.*, pu.vanity_url, pg.name AS category_name, pg.organization_type')
			->from($this->_sTable, 'p')			
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('organization_category'), 'pg', 'pg.category_id = p.category_id')		
			->where('p.organization_id = ' . (int) $iId)			
			->execute('getSlaveRow');
		
		if (empty($aRow) && $iId === null)
		{
			return false;
		}

		if (!isset($aRow['organization_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('organization.unable_to_find_the_organization_you_are_looking_for'));
		}
		
		if (empty($this->_aRow))
		{
			$this->_aRow = $aRow;
		}
		
		if ($this->_aRow['organization_id'] == Phpfox::getUserBy('profile_organization_id'))
		{
			$this->_aRow['is_liked'] = true;
		}
		
		// Issue with like/join button
		// Still not defined
		if (!isset($this->_aRow['is_liked']))
		{
			// make it false: not liked or joined yet
			$this->_aRow['is_liked'] = false;
		}
		
		return $aRow;
	}
	
	public function getMyorganization()
	{
		$aRows = $this->database()->select('p.*, pu.vanity_url, ' . Phpfox::getUserField())
			->from($this->_sTable, 'p')			
			->join(Phpfox::getT('user'), 'u', 'u.profile_organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')
			->where('p.view_id = 0 AND p.user_id = ' . Phpfox::getUserId())			
			->order('p.time_stamp DESC')
			->execute('getSlaveRows');
		
		foreach ($aRows as $iKey => $aRow)
		{
			$aRows[$iKey]['link'] = $this->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
		}
		
		return $aRows;
	}
	
	public function getUrl($iPageId, $sTitle = null, $sVanityUrl = null)
	{
		if ($sTitle === null && $sVanityUrl === null)
		{
			$aPage = $this->getPage($iPageId);
			$sTitle = $aPage['title'];
			$sVanityUrl = $aPage['vanity_url'];
		}
		
		if (!empty($sVanityUrl))
		{
			return Phpfox::getLib('url')->makeUrl($sVanityUrl);
		}

		// return Phpfox::permalink('organization', $iPageId, $sTitle);
		return Phpfox::getLib('url')->makeUrl('organization', $iPageId);
	}
	
	public function isPage($sUrl)
	{
		$aPage = $this->database()->select('*')
			->from(Phpfox::getT('organization_url'))
			->where('vanity_url = \'' . $this->database()->escape($sUrl) . '\'')
			->execute('getSlaveRow');
		
		if (!isset($aPage['organization_id']))
		{
			return false;
		}
		
		$this->_aPage = $aPage;
		
		return true;
	}
	
	public function getMenu($aOrganization)
	{
		$sHomeUrl = Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']);
		$sCurrentModule = Phpfox::getLib('module')->getModuleName();
		
		$aMenus = array();
		if ($this->isAdmin($aOrganization))
		{
			$iTotalPendingMembers = $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('organization_signup'))
				->where('organization_id = ' . (int) $aOrganization['organization_id'])
				->execute('getSlaveField');
			
			if ($iTotalPendingMembers > 0)
			{
				$aMenus[] = array(
					'phrase' => '' . Phpfox::getPhrase('organization.pending_memberships') . '<span class="pending">' . $iTotalPendingMembers . '</span>',
					'url' => Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']) . 'pending/',
					'icon' => 'misc/comment.png'	
				);
			}
		}
		
		$aMenus[] = array(
			'phrase' => Phpfox::getPhrase('organization.wall'),
			'url' => Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']) . (empty($aOrganization['landing_organization']) ? '' : 'wall/'),
			'icon' => 'misc/comment.png',
			'landing' => ''
		);
		
		$aMenus[] = array(
			'phrase' => Phpfox::getPhrase('organization.info'),
			'url' => Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']) . 'info/',
			'icon' => 'misc/application_view_list.png',
			'landing' => 'info'
		);		
		
		$aModuleCalls = Phpfox::massCallback('getOrganizationMenu', $aOrganization);
		foreach ($aModuleCalls as $sModule => $aModuleCall)
		{			
			if (!is_array($aModuleCall))
			{
				continue;
			}
			$aMenus[] = $aModuleCall[0];
		}
		
		if (count($this->_aWidgetMenus))
		{
			$aMenus = array_merge($aMenus, $this->_aWidgetMenus);
		}
		
		// http://www.phpfox.com/tracker/view/15190/
		if(!empty($aMenus) && is_array($aMenus))
		{
			foreach ($aMenus as $iKey => $aMenu)
			{
				$sSubUrl = rtrim(str_replace($sHomeUrl, '', $aMenu['url']), '/');
				if(!empty($sSubUrl))
				{
					$aMenus[$iKey]['url'] = $sHomeUrl . Phpfox::getLib('url')->doRewrite($sSubUrl) . '/';
				}
			}
		}
		
		if ($sCurrentModule == 'organization')
		{
			foreach ($aMenus as $iKey => $aMenu)
			{
				$sSubUrl = rtrim(str_replace($sHomeUrl, '', $aMenu['url']), '/');			
								
				if ((Phpfox::getLib('request')->get('req3') == 'info' || Phpfox::getLib('request')->get('req2') == 'info') && $sSubUrl == 'info')
				{
					$aMenus[$iKey]['is_selected'] = true;
					break;					
				}
				
				if ((Phpfox::getLib('request')->get('req3') == 'wall' || Phpfox::getLib('request')->get('req2') == 'wall') && $sSubUrl == 'wall')
				{
					$aMenus[$iKey]['is_selected'] = true;
					break;					
				}				
				
				if ((Phpfox::getLib('request')->get('req3') == 'pending' || Phpfox::getLib('request')->get('req2') == 'pending') && $sSubUrl == 'pending')
				{
					$aMenus[$iKey]['is_selected'] = true;
					break;					
				}				
				
				if (empty($sSubUrl) && Phpfox::getLib('request')->get((empty($aOrganization['vanity_url']) ? 'req3' : 'req2')) == '')
				{
					$aMenus[$iKey]['is_selected'] = true;
					break;										
				}
				
				if ($sSubUrl == 'info' && $aOrganization['landing_organization'] == 'info' && Phpfox::getLib('request')->get((empty($aOrganization['vanity_url']) ? 'req3' : 'req2')) == '')
				{
					$aMenus[$iKey]['is_selected'] = true;
					break;										
				}			
				
				if (!empty($sSubUrl) && $sSubUrl == Phpfox::getLib('request')->get((empty($aOrganization['vanity_url']) ? 'req3' : 'req2')))
				{
					$aMenus[$iKey]['is_selected'] = true;
					break;						
				}
			}
		}
		else
		{			
			foreach ($aMenus as $iKey => $aMenu)
			{
				$sSubUrl = rtrim(str_replace($sHomeUrl, '', $aMenu['url']), '/');			

				if ($sCurrentModule == $sSubUrl)
				{
					$aMenus[$iKey]['is_selected'] = true;
					break;
				}
			}
		}
		
		return $aMenus;	
	}	
	
	public function getForEdit($iId)
	{
		static $aRow = null;
		
		if (is_array($aRow))
		{
			return $aRow;
		}
		
		$aRow = $this->database()->select('p.*, pu.vanity_url, pt.text, pc.organization_type')
			->from($this->_sTable, 'p')			
			->join(Phpfox::getT('organization_text'), 'pt', 'pt.organization_id = p.organization_id')
			->leftJoin(Phpfox::getT('organization_category'), 'pc', 'p.category_id = pc.category_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = p.organization_id')			
			->where('p.organization_id = ' . (int) $iId)			
			->execute('getSlaveRow');
		
		if (!isset($aRow['organization_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('organization.unable_to_find_the_organization_you_are_trying_to_edit'));
		}
		
		if (!$this->isAdmin($aRow))
		{
			if (!Phpfox::getUserParam('organization.can_moderate_organization'))
			{
				return Phpfox_Error::set(Phpfox::getPhrase('organization.you_are_unable_to_edit_this_organization'));
			}
		}
		
		$this->_aRow = $aRow;
		
		Phpfox::getService('organization')->buildWidgets($aRow['organization_id']);
		
		$aRow['admins'] = $this->database()->select(Phpfox::getUserField())
			->from(Phpfox::getT('organization_admin'), 'pa')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = pa.user_id')
			->where('pa.organization_id = ' . (int) $aRow['organization_id'])
			->execute('getSlaveRows');
		
		$aMenus = $this->getMenu($aRow);		
		foreach ($aMenus as $iKey => $aMenu)
		{
			$aMenus[$iKey]['is_selected'] = false;
		}		
		if (!empty($aRow['landing_organization']))
		{
			foreach ($aMenus as $iKey => $aMenu)
			{
				if ($aMenu['landing'] == $aRow['landing_organization'])
				{
					$aMenus[$iKey]['is_selected'] = true;
				}
			}
		}

		$aRow['landing_organization'] = $aMenus;
		
		if ($aRow['app_id'])
		{			
			if ($aRow['aApp'] = Phpfox::getService('apps')->getForPage($aRow['app_id']))
			{
				$aRow['is_app'] = true;
				$aRow['title'] = $aRow['aApp']['app_title'];				
			}
		}
		else
		{
			$aRow['is_app'] = false;
		}			
		if ($sPlugin = Phpfox_Plugin::get('organization.service_organization_getforedit_1')){eval($sPlugin);if (isset($mReturnFromPlugin)){return $mReturnFromPlugin;}}
		
		define('PHPFOX_organization_EDIT_ID', $aRow['organization_id']);
		
		
		$aRow['location']['name'] = $aRow['location_name'];
		return $aRow;		
	}
	
	public function getCurrentInvites($iPageId)
	{
		$aRows = $this->database()->select('*')
			->from(Phpfox::getT('organization_invite'))
			->where('organization_id = ' . (int) $iPageId . ' AND type_id = 0 AND user_id = ' . Phpfox::getUserId())
			->execute('getSlaveRows');
		
		$aInvites = array();
		foreach ($aRows as $aRow)
		{
			$aInvites[$aRow['invited_user_id']] = $aRow;
		}
		
		return $aInvites;
	}
	
	public function getMembers($iOrganization)
	{
		if (!Phpfox::isModule('like'))
		{
			return false;
		}
		return Phpfox::getService('like')->getForMembers('organization', $iOrganization);
	}
	
	public function getPerms($iPage)
	{
		$aCallbacks = Phpfox::massCallback('getPagePerms');
		$aPerms = array();
		$aUserPerms = $this->getPermsForPage($iPage);
			
		foreach ($aCallbacks as $aCallback)
		{
			foreach ($aCallback as $sId => $sPhrase)
			{
				$aPerms[] = array(
					'id' => $sId,
					'phrase' => $sPhrase,
					'is_active' => (isset($aUserPerms[$sId]) ? $aUserPerms[$sId] : '0')
				);	
			}			
		}	
		
		return $aPerms;
	}
	
	public function getPermsForPage($iPage)
	{
		static $aPerms = null;
		
		if (is_array($aPerms))
		{
			return $aPerms;
		}
		
		$aPerms = array();
		$aRows = $this->database()->select('*')
			->from(Phpfox::getT('organization_perm'))
			->where('organization_id = ' . (int) $iPage)
			->execute('getSlaveRows');
		
		foreach ($aRows as $aRow)
		{
			$aPerms[$aRow['var_name']] = (int) $aRow['var_value'];
		}
		
		return $aPerms;
	}
	
	public function getPendingUsers($iPage)
	{
		$aUsers = $this->database()->select('ps.*, ' . Phpfox::getUserField())
			->from(Phpfox::getT('organization_signup'), 'ps')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = ps.user_id')
			->where('ps.organization_id = ' . (int) $iPage)
			->execute('getSlaveRows');
		
		return $aUsers;
	}
	
	public function hasPerm($iPage = null, $sPerm)
	{
		static $aCheck = array();
		static $aPerms = array();
		
		if (Phpfox::getUserParam('core.can_view_private_items') || defined('PHPFOX_POSTING_AS_PAGE'))
		{
			return true;
		}
		
		if ($iPage === null)
		{
			$iPage = $this->_aRow['organization_id'];
		}

		if (isset($aCheck[$iPage][$sPerm]))
		{
			return $aCheck[$iPage][$sPerm];
		}
		
		if (isset($this->_aRow['organization_id']) && $this->_aRow['user_id'] == Phpfox::getUserId())
		{
			$aCheck[$iPage][$sPerm] = true;
			
			return $aCheck[$iPage][$sPerm];
		}
		
		$bReturn = true;		
		
		if (!isset($aPermsCheck[$iPage]))
		{
			$aPerms = $this->getPermsForPage($iPage);
			$aPermsCheck[$iPage] = true;
		}
		
		if (isset($aPerms[$sPerm]))		
		{
			switch ((int) $aPerms[$sPerm])
			{
				case 1:					
					if (!$this->isMember($iPage))
					{
						$bReturn = false;
					}
					break;
				case 2:
					if (!$this->isAdmin($this->_aRow))
					{
						$bReturn = false;
					}
					break;
			}
		}
		
		$aCheck[$iPage][$sPerm] = $bReturn;
		
		return $aCheck[$iPage][$sPerm];
	}
	
	public function getPendingTotal()
	{
		return (int) $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('organization'))
			->where('app_id = 0 AND view_id = 1')
			->execute('getSlaveField');		
	}		
	
	public function getLastLogin()
	{
		static $aUser = null;
		
		if ($aUser !== null)
		{
			return $aUser;
		}
		
		$this->database()->join(Phpfox::getT('user'), 'u', 'u.user_id = pl.user_id');
		
		if (($sPlugin = Phpfox_Plugin::get('organization.service_organization_getlastlogin')))
		{
			eval($sPlugin);
		}		
		
		$aUser = $this->database()->select(Phpfox::getUserField() . ', u.email, u.style_id, u.password')
			->from(Phpfox::getT('organization_login'), 'pl')			
			->where('pl.login_id = ' . (int) Phpfox::getCookie('page_login') . ' AND pl.organization_id = ' . Phpfox::getUserBy('profile_organization_id'))
			->execute('getSlaveRow');
		
		if (!isset($aUser['user_id']))
		{
			$aUser = false;
			
			return false;
		}
		
		return $aUser;
	}
	
	public function getMyAdminorganization($iLimit = 0)
	{
		$sCacheId = $this->cache()->set(array('organization', Phpfox::getUserId()));
        
		if (!($aRows = $this->cache()->get($sCacheId)))
		{				
				$iCntAdmins = $this->database()->select('COUNT(*)')
					->from(Phpfox::getT('organization_admin'), 'pa')
					->leftJoin(Phpfox::getT('organization'), 'organization', 'organization.organization_id = pa.organization_id')
					->where('pa.user_id = ' . Phpfox::getUserId())
					->execute('getSlaveField');		
				
			
				$this->database()->select('organization.*')
					->from(Phpfox::getT('organization'), 'organization')				
					->where('organization.app_id = 0 AND organization.view_id = 0 AND organization.user_id = ' . Phpfox::getUserId())							
					->union();		
	
	            if ($iCntAdmins > 0)
	            {
	                $this->database()->select('organization.*')
						->from(Phpfox::getT('organization_admin'), 'pa')
						->leftJoin(Phpfox::getT('organization'), 'organization', 'organization.organization_id = pa.organization_id')				
						->where('pa.user_id = ' . Phpfox::getUserId())							
						->union();
	            }					
				
				if ($iLimit > 0)
				{
					$this->database()->limit($iLimit);
				}
	
				$aRows = $this->database()->select('organization.*, pu.vanity_url, ' . Phpfox::getUserField())
					->unionFrom('organization')
					->join(Phpfox::getT('user'), 'u', 'u.profile_organization_id = organization.organization_id')
					->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = organization.organization_id')	
					->group('organization.organization_id')
					->execute('getSlaveRows');	
	
				foreach ($aRows as $iKey => $aRow)
				{
					$aRows[$iKey]['link'] = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
				}

				$this->cache()->save($sCacheId, $aRows);
		}
		
		if (!is_array($aRows))
		{
			$aRows = array();
		}		
		
		return $aRows;
	}
	
	public function getMyLoginorganization($iPage, $iorganizationize)
	{
		$sCacheId = $this->cache()->set(array('login_organization', Phpfox::getUserId()));
		
		$iCntAdmins = $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('organization_admin'), 'pa')
			->leftJoin(Phpfox::getT('organization'), 'organization', 'organization.organization_id = pa.organization_id')
			->where('pa.user_id = ' . Phpfox::getUserId())
			->execute('getSlaveField');		
		
		$iCnt = $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('organization'))
			->where('view_id = 0 AND app_id = 0 AND user_id = ' . Phpfox::getUserId())
			->execute('getSlaveField');	

		$iCnt += $iCntAdmins;
	
		$this->database()->select('organization.*')
			->from(Phpfox::getT('organization'), 'organization')				
			->where('organization.app_id = 0 AND organization.view_id = 0 AND organization.user_id = ' . Phpfox::getUserId())							
			->union();		

        if ($iCntAdmins > 0)
        {
            $this->database()->select('organization.*')
				->from(Phpfox::getT('organization_admin'), 'pa')
				->leftJoin(Phpfox::getT('organization'), 'organization', 'organization.organization_id = pa.organization_id')				
				->where('pa.user_id = ' . Phpfox::getUserId())							
				->union();
        }					
		
		$this->database()->limit($iPage, $iorganizationize, $iCnt);

		$aRows = $this->database()->select('organization.*, pu.vanity_url, ' . Phpfox::getUserField())
			->unionFrom('organization')
			->join(Phpfox::getT('user'), 'u', 'u.profile_organization_id = organization.organization_id')
			->leftJoin(Phpfox::getT('organization_url'), 'pu', 'pu.organization_id = organization.organization_id')	
			->group('organization.organization_id')
			->order('organization.time_stamp DESC')
			->execute('getSlaveRows');	

		foreach ($aRows as $iKey => $aRow)
		{
			$aRows[$iKey]['link'] = Phpfox::getService('organization')->getUrl($aRow['organization_id'], $aRow['title'], $aRow['vanity_url']);
		}
				
		return array($iCnt, $aRows);
	}
	
	public function getClaims()
	{
		$aClaims = $this->database()->select('pc.*, u.full_name, u.user_name, p1.organization_id, p1.title, curruser.user_id as curruser_user_id, curruser.full_name as curruser_full_name, curruser.user_name as curruser_user_name')
			->from(Phpfox::getT('organization_claim'), 'pc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = pc.user_id')			
			->join(Phpfox::getT('organization'), 'p1', 'p1.organization_id = pc.organization_id')
			->join(Phpfox::getT('user'), 'curruser', 'curruser.user_id = p1.user_id')
			->where('pc.status_id = 1')
			->order('pc.time_stamp')
			->execute('getSlaveRows');
		
		foreach ($aClaims as $iIndex => $aClaim)
		{
			$aClaims[$iIndex]['url'] = Phpfox::permalink('organization', $aClaim['organization_id'], $aClaim['title']);
		}
		return $aClaims;
	}
	
	public function getInfoForAction($aItem)
	{
		if (is_numeric($aItem))
		{
			$aItem = array('item_id' => $aItem);
		}
		$aRow = $this->database()->select('p.organization_id, p.title, p.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('organization'), 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->where('p.organization_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');
				
		$aRow['link'] = Phpfox::getLib('url')->permalink('organization', $aRow['organization_id'], $aRow['title']);
		return $aRow;
	}
	
	public function getorganizationByLocation($fLat, $fLng)
	{
		$aorganization = $this->database()->select('organization_id, title, location_latitude, location_longitude, (3956 * 2 * ASIN(SQRT( POWER(SIN((' . $fLat . ' - location_latitude) *  pi()/180 / 2), 2) + COS(' . $fLat . ' * pi()/180) * COS(location_latitude * pi()/180) * POWER(SIN((' . $fLng . ' - location_longitude) * pi()/180 / 2), 2) ))) as distance')
		->from(Phpfox::getT('organization'))
		->having('distance < 1') // distance in kilometers
		->limit(10)
		->execute('getSlaveRows');
		
		return $aorganization;
	}
	
	public function timelineEnabled($iId)
	{
	    return $this->database()->select('use_timeline')
		    ->from(Phpfox::getT('organization'))
		    ->where('organization_id = ' . (int)$iId)
		    ->execute('getSlaveField');
	}
    
    /**
     * Gets the count of organization Without the organization created by apps. 
     * @param int $iUser
     * @return int
     */
    public function getorganizationCount($iUser)
    {
		if ($iUser == Phpfox::getUserId())
		{
			return Phpfox::getUserBy('total_organization');
		}
		
        $iCount = $this->database()->select('count(*)')
                ->from(Phpfox::getT('organization'))
                ->where('app_id = 0 AND user_id = ' . (int)$iUser)
                ->execute('getSlaveField');
        
        return $iCount;
    }
    
	/**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing 
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('organization.service_organization__call'))
		{
			eval($sPlugin);
			return;
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>
