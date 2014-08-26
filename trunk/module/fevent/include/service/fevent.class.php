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
 * @version 		$Id: fevent.class.php 6139 2013-06-24 15:02:48Z Raymond_Benc $
 */
class fevent_Service_fevent extends Phpfox_Service 
{
	private $_aCallback = false;
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('fevent');
	}
	
	public function callback($aCallback)
	{
		$this->_aCallback = $aCallback;
		
		return $this;
	}
	
	public function getfevent($sfevent, $bUseId = false, $bNoCache = false)
	{		
		static $afevent = null;
		
		if ($afevent !== null && $bNoCache === false)
		{
			return $afevent;
		}
		
		$bUseId = true;
		
		if (Phpfox::isUser())
		{
			$this->database()->select('ei.invite_id, ei.rsvp_id, ')->leftJoin(Phpfox::getT('fevent_invite'), 'ei', 'ei.fevent_id = e.fevent_id AND ei.invited_user_id = ' . Phpfox::getUserId());
		}
		
		if (Phpfox::isModule('friend'))
		{
			$this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = e.user_id AND f.friend_user_id = " . Phpfox::getUserId());					
		}				
		else
		{
			$this->database()->select('0 as is_friend, ');
		}
		
		$afevent = $this->database()->select('e.*, ' . (Phpfox::getParam('core.allow_html') ? 'et.description_parsed' : 'et.description') . ' AS description, ' . Phpfox::getUserField())
			->from($this->_sTable, 'e')		
			->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
			->join(Phpfox::getT('fevent_text'), 'et', 'et.fevent_id = e.fevent_id')				
			->where('e.fevent_id = ' . (int) $sfevent)
			->execute('getRow');
		
		if (!isset($afevent['fevent_id']))
		{
			return false;
		}
		
		if (!Phpfox::isUser())
		{
			$afevent['invite_id'] = 0;	
			$afevent['rsvp_id'] = 0;
		}
		
		if ($afevent['view_id'] == '1')
		{
			if ($afevent['user_id'] == Phpfox::getUserId() || Phpfox::getUserParam('fevent.can_approve_fevents') || Phpfox::getUserParam('fevent.can_view_pirvate_fevents'))
			{
				
			}
			else 
			{
				return false;
			}
		}
		
		$afevent['fevent_date'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $afevent['start_time']);
		if ($afevent['start_time'] < $afevent['end_time'])
		{
			$afevent['fevent_date'] .= ' - ';
			if (date('dmy', $afevent['start_time']) === date('dmy', $afevent['end_time']))
			{
				$afevent['fevent_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time_short'), $afevent['end_time']);
			}
			else 
			{
				$afevent['fevent_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $afevent['end_time']);
			}		
		}
		
		if (isset($afevent['gmap']) && !empty($afevent['gmap']))
		{
			$afevent['gmap'] = unserialize($afevent['gmap']);
		}		
		
		$afevent['categories'] = Phpfox::getService('fevent.category')->getCategoriesById($afevent['fevent_id']);
		
		if (!empty($afevent['address']))
		{
			$afevent['map_location'] = $afevent['address'];
			if (!empty($afevent['city']))
			{
				$afevent['map_location'] .= ',' . $afevent['city'];
			}
			if (!empty($afevent['postal_code']))
			{
				$afevent['map_location'] .= ',' . $afevent['postal_code'];
			}	
			if (!empty($afevent['country_child_id']))
			{
				$afevent['map_location'] .= ',' . Phpfox::getService('core.country')->getChild($afevent['country_child_id']);
			}			
			if (!empty($afevent['country_iso']))
			{
				$afevent['map_location'] .= ',' . Phpfox::getService('core.country')->getCountry($afevent['country_iso']);
			}			
			
			$afevent['map_location'] = urlencode($afevent['map_location']);
		}
		
		$afevent['start_time_micro'] = Phpfox::getTime('Y-m-d', $afevent['start_time']);
				
		return $afevent;
	}
	
	public function getTimeLeft($iId)
	{
		$afevent = $this->getfevent($iId, true);
		
		return ($afevent['mass_email'] + (Phpfox::getUserParam('fevent.total_mass_emails_per_hour') * 60));		
	}
	
	public function canSendEmails($iId, $bNoCache = false)
	{
		if (Phpfox::getUserParam('fevent.total_mass_emails_per_hour') === 0)
		{
			return true;
		}
		
		$afevent = $this->getfevent($iId, true, $bNoCache);
		
		return (($afevent['mass_email'] + (Phpfox::getUserParam('fevent.total_mass_emails_per_hour') * 60) > PHPFOX_TIME) ? false : true);
	}
	
	public function getForEdit($iId, $bForce = false)
	{
		$afevent = $this->database()->select('e.*, et.description')
			->from($this->_sTable, 'e')		
			->join(Phpfox::getT('fevent_text'), 'et', 'et.fevent_id = e.fevent_id')	
			->where('e.fevent_id = ' . (int) $iId)
			->execute('getRow');
			
                if (empty($afevent))
                {
                    return false;
                }
		if ((($afevent['user_id'] == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')) || $bForce === true)
		{			
			$afevent['start_time'] = Phpfox::getLib('date')->convertFromGmt($afevent['start_time'], $afevent['start_gmt_offset']);
			$afevent['end_time'] = Phpfox::getLib('date')->convertFromGmt($afevent['end_time'], $afevent['end_gmt_offset']);
			
			$afevent['start_month'] = date('n', $afevent['start_time']);
			$afevent['start_day'] = date('j', $afevent['start_time']);
			$afevent['start_year'] = date('Y', $afevent['start_time']);
			$afevent['start_hour'] = date('H', $afevent['start_time']);
			$afevent['start_minute'] = date('i', $afevent['start_time']);
			
			$afevent['end_month'] = date('n', $afevent['end_time']);
			$afevent['end_day'] = date('j', $afevent['end_time']);
			$afevent['end_year'] = date('Y', $afevent['end_time']);
			$afevent['end_hour'] = date('H', $afevent['end_time']);
			$afevent['end_minute'] = date('i', $afevent['end_time']);
			
			$afevent['categories'] = Phpfox::getService('fevent.category')->getCategoryIds($afevent['fevent_id']);
				
			return $afevent;
		}
		
		return false;
	}
	
	public function getInvites($ifevent, $iRsvp, $iPage = 0, $iPageSize = 8)
	{
		$aInvites = array();
		$iCnt = $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('fevent_invite'))
			->where('fevent_id = ' . (int) $ifevent . ' AND rsvp_id = ' . (int) $iRsvp)
			->execute('getSlaveField');
		
		if ($iCnt)
		{			
			$aInvites = $this->database()->select('ei.*, ' . Phpfox::getUserField())
				->from(Phpfox::getT('fevent_invite'), 'ei')
				->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = ei.invited_user_id')
				->where('ei.fevent_id = ' . (int) $ifevent . ' AND ei.rsvp_id = ' . (int) $iRsvp)
				->limit($iPage, $iPageSize, $iCnt)
				->order('ei.invite_id DESC')
				->execute('getSlaveRows');
		}
			
		return array($iCnt, $aInvites);
	}
	
	public function getInviteForUser($iLimit = 6)
	{
		$aRows = $this->database()->select('e.*')
			->from(Phpfox::getT('fevent_invite'), 'ei')
			->join(Phpfox::getT('fevent'), 'e', 'e.fevent_id = ei.fevent_id')
			->where('ei.rsvp_id = 0 AND ei.invited_user_id = ' . Phpfox::getUserId())
			->limit($iLimit)
			->execute('getRows');
			
		foreach ($aRows as $iKey => $aRow)
		{
			$aRows[$iKey]['start_time_phrase'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_browse_time_stamp'), $aRow['start_time']);
			$aRows[$iKey]['start_time_phrase_stamp'] = Phpfox::getTime('g:sa', $aRow['start_time']);
		}
			
		return $aRows;
	}
	
	public function getForProfileBlock($iUserId, $iLimit = 5)
	{
		$iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
		
		$afevents = $this->database()->select('m.*')
			->from($this->_sTable, 'm')
			->join(Phpfox::getT('fevent_invite'), 'ei', 'ei.fevent_id = m.fevent_id AND ei.rsvp_id = 1 AND ei.invited_user_id = ' . (int) $iUserId)
			->where('m.view_id = 0 AND m.start_time >= \'' . $iTimeDisplay . '\'')
			->limit($iLimit)
			->order('m.start_time ASC')
			->execute('getSlaveRows');
		
		foreach ($afevents as $iKey => $afevent)
		{			
			$afevents[$iKey]['url'] = Phpfox::getLib('url')->permalink('fevent', $afevent['fevent_id'], $afevent['title']);
			$afevents[$iKey]['start_time_stamp'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_view_time_stamp_profile'), $afevent['start_time']);
			$afevents[$iKey]['location_clean'] = Phpfox::getLib('parse.output')->split(Phpfox::getLib('parse.output')->clean($afevent['location']), 10);			
		}
			
		return $afevents;
	}	
	
	public function getForParentBlock($sModule, $iItemId, $iLimit = 5)
	{
		$iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
		
		$afevents = $this->database()->select('m.fevent_id, m.title, m.tag_line, m.image_path, m.server_id, m.start_time, m.location, m.country_iso, m.city, m.module_id, m.item_id')
			->from($this->_sTable, 'm')
			->where('m.view_id = 0 AND m.module_id = \'' . $this->database()->escape($sModule) . '\' AND m.item_id = ' . (int) $iItemId . ' AND m.start_time >= \'' . $iTimeDisplay . '\'')
			->limit($iLimit)
			->order('m.start_time ASC')
			->execute('getSlaveRows');
			
		foreach ($afevents as $iKey => $afevent)
		{
			$afevents[$iKey]['url'] = Phpfox::getLib('url')->makeUrl('fevent', array('redirect' => $afevent['fevent_id']));
			$afevents[$iKey]['start_time_stamp'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_view_time_stamp_profile'), $afevent['start_time']);
			$afevents[$iKey]['location_clean'] = Phpfox::getLib('parse.output')->split(Phpfox::getLib('parse.output')->clean($afevent['location']), 10);
		}
			
		return $afevents;
	}	
	
	public function getPendingTotal()
	{
		$iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
		
		return $this->database()->select('COUNT(*)')
			->from($this->_sTable)
			->where('view_id = 1 AND start_time >= \'' . $iTimeDisplay . '\'')
			->execute('getSlaveField');
	}

	public function getRandomSponsored()
	{
		$iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		
		$sCacheId = $this->cache()->set('fevent_sponsored_' . $iToday);
		if (!($afevents = $this->cache()->get($sCacheId)))
		{
			$afevents = $this->database()->select('s.*, s.country_iso AS sponsor_country_iso, e.*')
				->from($this->_sTable, 'e')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
				->join(Phpfox::getT('ad_sponsor'),'s','s.item_id = e.fevent_id')
				->where('e.view_id = 0 AND e.privacy = 0 AND e.is_sponsor = 1 AND s.module_id = "fevent" AND e.start_time >= \'' . $iToday . '\'')
				->execute('getRows');

			foreach ($afevents as $iKey => $afevent)
			{
				$afevents[$iKey]['categories'] = Phpfox::getService('fevent.category')->getCategoriesById($afevent['fevent_id']);
				$afevents[$iKey]['fevent_date'] = Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $afevent['start_time']) . ' - ';
				if (date('dmy', $afevent['start_time']) === date('dmy', $afevent['end_time']))
				{
					$afevents[$iKey]['fevent_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time_short'), $afevent['end_time']);
				}
				else
				{
					$afevents[$iKey]['fevent_date'] .= Phpfox::getTime(Phpfox::getParam('fevent.fevent_basic_information_time'), $afevent['end_time']);
				}
			}

			$this->cache()->save($sCacheId, $afevents);
		}

		/*
		$afevents = Phpfox::getService('ad')->filterSponsor($afevents);
		if ($afevents === true || (is_array($afevents) && !count($afevents)))
		{
			return false;
		}
		*/
		if ($afevents === true || (is_array($afevents) && !count($afevents)))
		{
			return false;
		}

		// Randomize to get a fevent
		return $afevents[rand(0, (count($afevents) - 1))];
	}

	public function isAlreadyInvited($iItemId, $aFriends)
	{
		if ((int) $iItemId === 0)
		{
			return false;
		}
		
		if (is_array($aFriends))
		{
			if (!count($aFriends))
			{
				return false;
			}
			
			$sIds = '';
			foreach ($aFriends as $aFriend)
			{
				if (!isset($aFriend['user_id']))
				{
					continue;
				}
				
				$sIds[] = $aFriend['user_id'];
			}			
			
			$aInvites = $this->database()->select('invite_id, rsvp_id, invited_user_id')
				->from(Phpfox::getT('fevent_invite'))
				->where('fevent_id = ' . (int) $iItemId . ' AND invited_user_id IN(' . implode(', ', $sIds) . ')')
				->execute('getSlaveRows');
			
			$aCache = array();
			foreach ($aInvites as $aInvite)
			{
				$aCache[$aInvite['invited_user_id']] = ($aInvite['rsvp_id'] > 0 ? Phpfox::getPhrase('fevent.responded') : Phpfox::getPhrase('fevent.invited'));
			}
			
			if (count($aCache))
			{
				return $aCache;
			}
		}
		
		return false;
	}

	public function getSiteStatsForAdmins()
	{
		$iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		
		return array(
			'phrase' => Phpfox::getPhrase('fevent.fevents'),
			'value' => $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('fevent'))
				->where('view_id = 0 AND time_stamp >= ' . $iToday)
				->execute('getSlaveField')
		);
	}	
	
	public function getFeatured()
	{
		static $aFeatured = null;
		static $iTotal = null;
		
		if ($aFeatured !== null)
		{
			return array($iTotal, $aFeatured);
		}
		
		$iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		
		$aFeatured = array();
		$sCacheId = $this->cache()->set('fevent_featured_' . $iToday);		
		if (!($aRows = $this->cache()->get($sCacheId)))
		{
			$aRows = $this->database()->select('v.*, ' . Phpfox::getUserField())
				->from(Phpfox::getT('fevent'), 'v')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
				->where('v.is_featured = 1 AND v.start_time >= \'' . $iToday . '\'')			
				->execute('getSlaveRows');
			
			$this->cache()->save($sCacheId, $aRows);
		}
		
		$iTotal = 0;
		if (is_array($aRows) && count($aRows))
		{
			$iTotal = count($aRows);
			shuffle($aRows);
			foreach ($aRows as $iKey => $aRow)
			{
				if ($iKey === 4)
				{
					break;
				}
				
				$aFeatured[] = $aRow;
			}
		}
		
		return array($iTotal, $aFeatured);
	}	
	
	public function getForRssFeed()
	{
		$iTimeDisplay = Phpfox::getLib('phpfox.date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
		$aConditions = array();
		$aConditions[] = "e.view_id = 0 AND e.module_id = 'fevent' AND e.item_id = 0";
		$aConditions[] = "AND e.start_time >= '" . $iTimeDisplay . "'";		
		
		$aRows = $this->database()->select('e.*, et.description_parsed AS description, ' . Phpfox::getUserField())
			->from(Phpfox::getT('fevent'), 'e')
			->join(Phpfox::getT('fevent_text'), 'et', 'et.fevent_id = e.fevent_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
			->where($aConditions)
			->order('e.time_stamp DESC')
			->execute('getSlaveRows');
		
		foreach ($aRows as $iKey => $aRow)
		{
			$aRows[$iKey]['link'] = Phpfox::permalink('fevent', $aRow['fevent_id'], $aRow['title']);
			$aRows[$iKey]['creator'] = $aRow['full_name'];
		}		
		
		return $aRows;
	}
	
	public function getInfoForAction($aItem)
	{
		if (is_numeric($aItem))
		{
			$aItem = array('item_id' => $aItem);
		}
		$aRow = $this->database()->select('e.fevent_id, e.title, e.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('fevent'), 'e')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
			->where('e.fevent_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');
			
		if (empty($aRow))
		{
			d($aRow);
			d($aItem);
			d(__FILE__ . ':' . __LINE__);
		}
		
		$aRow['link'] = Phpfox::getLib('url')->permalink('fevent', $aRow['fevent_id'], $aRow['title']);
		return $aRow;
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
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_fevent__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>