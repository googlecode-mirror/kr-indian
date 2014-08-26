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
 * @version 		$Id: process.class.php 6938 2013-11-25 09:48:57Z Miguel_Espinoza $
 */
class fevent_Service_Process extends Phpfox_Service 
{
	private $_bHasImage = false;
	
	private $_aInvited = array();
	
	private $_aCategories = array();
	
	private $_bIsEndingInThePast = false;
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{
		$this->_sTable = Phpfox::getT('fevent');
	}
	
	public function add($aVals, $sModule = 'fevent', $iItem = 0)
	{		
		if (!$this->_verify($aVals))
		{
			return false;
		}
		
		if (!isset($aVals['privacy']))
		{
			$aVals['privacy'] = 0;
		}
		
		$oParseInput = Phpfox::getLib('parse.input');	
		Phpfox::getService('ban')->checkAutomaticBan($aVals);
					
		$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);		
		if ($this->_bIsEndingInThePast === true)
		{
			$aVals['end_hour'] = ($aVals['start_hour'] + 1);
			$aVals['end_minute'] = $aVals['start_minute'];
			$aVals['end_day'] = $aVals['start_day'];
			$aVals['end_year'] = $aVals['start_year'];			
		}
		
		$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);				
		
		if ($iStartTime > $iEndTime)
		{
			$iEndTime = $iStartTime;
		}
				
		$aSql = array(
			'view_id' => (($sModule == 'fevent' && Phpfox::getUserParam('fevent.fevent_must_be_approved')) ? '1' : '0'),
			'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
			'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
			'module_id' => $sModule,
			'item_id' => $iItem,
			'user_id' => Phpfox::getUserId(),
			'title' => $oParseInput->clean($aVals['title'], 255),
			'location' => $oParseInput->clean($aVals['location'], 255),
			'country_iso' => (empty($aVals['country_iso']) ? Phpfox::getUserBy('country_iso') : $aVals['country_iso']),
			'country_child_id' => (isset($aVals['country_child_id']) ? (int) $aVals['country_child_id'] : 0),
			'postal_code' => (empty($aVals['postal_code']) ? null : Phpfox::getLib('parse.input')->clean($aVals['postal_code'], 20)),
			'city' => (empty($aVals['city']) ? null : $oParseInput->clean($aVals['city'], 255)),
			'time_stamp' => PHPFOX_TIME,
			'start_time' => Phpfox::getLib('date')->convertToGmt($iStartTime),
			'end_time' => Phpfox::getLib('date')->convertToGmt($iEndTime),
			'start_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iStartTime),
			'end_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iEndTime),
			'address' => (empty($aVals['address']) ? null : Phpfox::getLib('parse.input')->clean($aVals['address']))
		);
		
		if (Phpfox::getUserParam('fevent.can_add_gmap') && isset($aVals['gmap']) 
				&& is_array($aVals['gmap']) && isset($aVals['gmap']['latitude'])
				&& isset($aVals['gmap']['longitude']))
		{
			$aSql['gmap'] = serialize($aVals['gmap']);
		}
		
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_add__start')){return eval($sPlugin);}
		
		if (!Phpfox_Error::isPassed())
		{
			return false;
		}
		
		$iId = $this->database()->insert($this->_sTable, $aSql);
		
		if (!$iId)
		{
			return false;
		}
		
		$this->database()->insert(Phpfox::getT('fevent_text'), array(
				'fevent_id' => $iId,
				'description' => (empty($aVals['description']) ? null : $oParseInput->clean($aVals['description'])),
				'description_parsed' => (empty($aVals['description']) ? null : $oParseInput->prepare($aVals['description']))
			)
		);		
		
		foreach ($this->_aCategories as $iCategoryId)
		{
			$this->database()->insert(Phpfox::getT('fevent_category_data'), array('fevent_id' => $iId, 'category_id' => $iCategoryId));
		}		
		
		$bAddFeed = ($sModule == 'fevent' ? (Phpfox::getUserParam('fevent.fevent_must_be_approved') ? false : true) : true);
		
		if ($bAddFeed === true)
		{
			if ($sModule == 'fevent' && Phpfox::isModule('feed'))
			{
				Phpfox::getService('feed.process')->add('fevent', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0));
			}
			else if (Phpfox::isModule('feed'))
			{
				Phpfox::getService('feed.process')
                        ->callback(Phpfox::callback($sModule . '.getFeedDetails', $iItem))
                        ->add('fevent', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0), $iItem);
			}			
			
			Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'fevent');
		}
		
		$this->addRsvp($iId, 1, Phpfox::getUserId());

		$sCacheId = $this->cache()->set(array('fevents', Phpfox::getUserId()));
		$this->cache()->remove($sCacheId);
		if (Phpfox::getParam('fevent.cache_fevents_per_user'))
		{
			$sCacheId = $this->cache()->set(array('fevents_by_user', Phpfox::getUserId()));
			$this->cache()->remove($sCacheId);
		}

		if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support'))
		{
			Phpfox::getService('tag.process')->add('fevent', $iId, Phpfox::getUserId(), $aVals['description'], true);
		}
		
        // Plugin call
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_add__end')){eval($sPlugin);}

		return $iId;
	}
	
	public function update($iId, $aVals, $afeventPost = null)
	{
		if (!$this->_verify($aVals, true))
		{
			return false;
		}		
		
		if (isset($afeventPost) && isset($afeventPost['is_featured']) && $afeventPost['is_featured'])
		{
			$this->cache()->remove('fevent_featured', 'substr');
		}
		
		if (!isset($aVals['privacy']))
		{
			$aVals['privacy'] = 0;
		}
		
		if (!isset($aVals['privacy_comment']))
		{
			$aVals['privacy_comment'] = 0;
		}		
		
		$oParseInput = Phpfox::getLib('parse.input');
		
		Phpfox::getService('ban')->checkAutomaticBan($aVals['title'] . ' ' . $aVals['description']);
		
		$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);
		$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);	
		
		if ($iStartTime > $iEndTime)
		{
			$iEndTime = $iStartTime;
		}
		$aSql = array(
			'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
			'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
			'title' => $oParseInput->clean($aVals['title'], 255),
			'location' => $oParseInput->clean($aVals['location'], 255),
			'country_iso' => $aVals['country_iso'],
			'country_child_id' => (isset($aVals['country_child_id']) ? Phpfox::getService('core.country')->getValidChildId($aVals['country_iso'], (int) $aVals['country_child_id']) : 0),
			'city' => (empty($aVals['city']) ? null : $oParseInput->clean($aVals['city'], 255)),		
			'postal_code' => (empty($aVals['postal_code']) ? null : Phpfox::getLib('parse.input')->clean($aVals['postal_code'], 20)),
			'start_time' => Phpfox::getLib('date')->convertToGmt($iStartTime),
			'end_time' => Phpfox::getLib('date')->convertToGmt($iEndTime),
			'start_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iStartTime),
			'end_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iEndTime),
			'address' => (empty($aVals['address']) ? null : Phpfox::getLib('parse.input')->clean($aVals['address']))
		);			
		
		if (Phpfox::getUserParam('fevent.can_add_gmap') && isset($aVals['gmap'])
				&& is_array($aVals['gmap']) && isset($aVals['gmap']['latitude'])
				&& isset($aVals['gmap']['longitude']))
		{
			$aSql['gmap'] = serialize($aVals['gmap']);
		}		
		
		if ($this->_bHasImage)
		{			
			$oImage = Phpfox::getLib('image');
			
			$sFileName = Phpfox::getLib('file')->upload('image', Phpfox::getParam('fevent.dir_image'), $iId);
			$iFileSizes = filesize(Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, ''));			
			
			$aSql['image_path'] = $sFileName;
			$aSql['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
			
			$iSize = 50;			
			$oImage->createThumbnail(Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);			
			$iFileSizes += filesize(Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, '_' . $iSize));			
			
			$iSize = 120;			
			$oImage->createThumbnail(Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);			
			$iFileSizes += filesize(Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, '_' . $iSize));

			$iSize = 200;			
			$oImage->createThumbnail(Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);			
			$iFileSizes += filesize(Phpfox::getParam('fevent.dir_image') . sprintf($sFileName, '_' . $iSize));
			
			// Update user space usage
			Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'fevent', $iFileSizes);
		}	
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_update__start')){return eval($sPlugin);}
		$this->database()->update($this->_sTable, $aSql, 'fevent_id = ' . (int) $iId);	
		
		$this->database()->update(Phpfox::getT('fevent_text'), array(				
				'description' => (empty($aVals['description']) ? null : $oParseInput->clean($aVals['description'])),
				'description_parsed' => (empty($aVals['description']) ? null : $oParseInput->prepare($aVals['description']))
			), 'fevent_id = ' . (int) $iId
		);		
		
		$afevent = $this->database()->select('fevent_id, user_id, title, module_id')
			->from($this->_sTable)
			->where('fevent_id = ' . (int) $iId)
			->execute('getSlaveRow');		
		
		if (isset($aVals['emails']) || isset($aVals['invite']))
		{		
			$aInvites = $this->database()->select('invited_user_id, invited_email')
				->from(Phpfox::getT('fevent_invite'))
				->where('fevent_id = ' . (int) $iId)
				->execute('getRows');
			$aInvited = array();
			foreach ($aInvites as $aInvite)
			{
				$aInvited[(empty($aInvite['invited_email']) ? 'user' : 'email')][(empty($aInvite['invited_email']) ? $aInvite['invited_user_id'] : $aInvite['invited_email'])] = true;
			}			
		}
		
		if (isset($aVals['emails']))
		{
			// if (strpos($aVals['emails'], ','))
			{
				$aEmails = explode(',', $aVals['emails']);
				$aCachedEmails = array();
				foreach ($aEmails as $sEmail)
				{
					$sEmail = trim($sEmail);
					if (!Phpfox::getLib('mail')->checkEmail($sEmail))
					{
						continue;
					}
					
					if (isset($aInvited['email'][$sEmail]))
					{
						continue;
					}
					
					$sLink = Phpfox::getLib('url')->permalink('fevent', $afevent['fevent_id'], $afevent['title']);

					$sMessage = Phpfox::getPhrase('fevent.full_name_invited_you_to_the_title', array(
							'full_name' => Phpfox::getUserBy('full_name'),
							'title' => $oParseInput->clean($aVals['title'], 255),
							'link' => $sLink
						)
					);
					if (!empty($aVals['personal_message']))
					{
						$sMessage .= Phpfox::getPhrase('fevent.full_name_added_the_following_personal_message', array(
								'full_name' => Phpfox::getUserBy('full_name')
							)
						) . "\n";
						$sMessage .= $aVals['personal_message'];
					}
					$oMail = Phpfox::getLib('mail');
					if (isset($aVals['invite_from']) && $aVals['invite_from'] == 1)
					{
						$oMail->fromEmail(Phpfox::getUserBy('email'))
								->fromName(Phpfox::getUserBy('full_name'));
					}
					$bSent = $oMail->to($sEmail)
						->subject(array('fevent.full_name_invited_you_to_the_fevent_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $oParseInput->clean($aVals['title'], 255))))
						->message($sMessage)
						->send();
						
					if ($bSent)
					{
						$this->_aInvited[] = array('email' => $sEmail);
						
						$aCachedEmails[$sEmail] = true;
						
						$this->database()->insert(Phpfox::getT('fevent_invite'), array(
								'fevent_id' => $iId,
								'type_id' => 1,
								'user_id' => Phpfox::getUserId(),
								'invited_email' => $sEmail,
								'time_stamp' => PHPFOX_TIME
							)
						);
					}
				}
			}
		}
		
		if (isset($aVals['invite']) && is_array($aVals['invite']))
		{
			$sUserIds = '';
			foreach ($aVals['invite'] as $iUserId)
			{
				if (!is_numeric($iUserId))
				{
					continue;
				}
				$sUserIds .= $iUserId . ',';
			}
			$sUserIds = rtrim($sUserIds, ',');
			
			$aUsers = $this->database()->select('user_id, email, language_id, full_name')
				->from(Phpfox::getT('user'))
				->where('user_id IN(' . $sUserIds . ')')
				->execute('getSlaveRows');
				
			foreach ($aUsers as $aUser)
			{
				if (isset($aCachedEmails[$aUser['email']]))
				{
					continue;
				}	
				
				if (isset($aInvited['user'][$aUser['user_id']]))
				{
					continue;
				}
				
				$sLink = Phpfox::getLib('url')->permalink('fevent', $afevent['fevent_id'], $afevent['title']);

				$sMessage = Phpfox::getPhrase('fevent.full_name_invited_you_to_the_title', array(
						'full_name' => Phpfox::getUserBy('full_name'),
						'title' => $oParseInput->clean($aVals['title'], 255),
						'link' => $sLink
					), false,null, $aUser['language_id']);
				if (!empty($aVals['personal_message']))
				{
					$sMessage .= Phpfox::getPhrase('fevent.full_name_added_the_following_personal_message', array(
							'full_name' => Phpfox::getUserBy('full_name')
						), false, null, $aUser['language_id']
					) .":\n". $aVals['personal_message'];
				}
				$bSent = Phpfox::getLib('mail')->to($aUser['user_id'])						
					->subject(array('fevent.full_name_invited_you_to_the_fevent_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $oParseInput->clean($aVals['title'], 255))))
					->message($sMessage)
					->notification('fevent.invite_to_fevent')
					->send();
						
				if ($bSent)
				{
					$this->_aInvited[] = array('user' => $aUser['full_name']);	
					
					$iInviteId = $this->database()->insert(Phpfox::getT('fevent_invite'), array(
							'fevent_id' => $iId,								
							'user_id' => Phpfox::getUserId(),
							'invited_user_id' => $aUser['user_id'],
							'time_stamp' => PHPFOX_TIME
						)
					);
					
					(Phpfox::isModule('request') ? Phpfox::getService('request.process')->add('fevent_invite', $iId, $aUser['user_id']) : null);
				}
			}
		}
		
		$this->database()->delete(Phpfox::getT('fevent_category_data'), 'fevent_id = ' . (int) $iId);
		foreach ($this->_aCategories as $iCategoryId)
		{
			$this->database()->insert(Phpfox::getT('fevent_category_data'), array('fevent_id' => $iId, 'category_id' => $iCategoryId));
		}
				
		if (empty($afevent['module_id']))
		{
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->update('fevent', $iId, $aVals['privacy'], $aVals['privacy_comment'], 0, $afevent['user_id']) : null);
		}
		
		Phpfox::getService('feed.process')->clearCache('fevent', $iId);
		
		(($sPlugin = Phpfox_Plugin::get('fevent.service_process_update__end')) ? eval($sPlugin) : false);
		
		if (Phpfox::getParam('fevent.cache_fevents_per_user'))
		{
			$sCacheId = $this->cache()->set(array('fevents_by_user', $afevent['user_id']));
			$this->cache()->remove($sCacheId);
		}

		if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support'))
		{
			Phpfox::getService('tag.process')->update('fevent', $afevent['fevent_id'], $afevent['user_id'], $aVals['description'], true);
		}
		
		return true;
	}
	
	public function deleteImage($iId)
	{
		$afevent = $this->database()->select('user_id, image_path')
			->from($this->_sTable)
			->where('fevent_id = ' . (int) $iId)
			->execute('getRow');		
			
		if (!isset($afevent['user_id']))
		{
			return Phpfox_Error::set('Unable to find the fevent.');
		}
			
		if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'fevent_id', $iId, 'fevent.can_edit_own_fevent', 'fevent.can_edit_other_fevent', $afevent['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_modify_this_fevent'));
		}			
		
		if (!empty($afevent['image_path']))
		{
			$aImages = array(
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], ''),
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], '_50'),
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], '_120'),
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], '_200')
			);			
			
			$iFileSizes = 0;
			foreach ($aImages as $sImage)
			{
				if (file_exists($sImage))
				{
					$iFileSizes += filesize($sImage);
					
					Phpfox::getLib('file')->unlink($sImage);
				}
			}
			
			if ($iFileSizes > 0)
			{
				Phpfox::getService('user.space')->update($afevent['user_id'], 'fevent', $iFileSizes, '-');
			}
		}

		$this->database()->update($this->_sTable, array('image_path' => null), 'fevent_id = ' . (int) $iId);	
		
		(($sPlugin = Phpfox_Plugin::get('fevent.service_process_deleteimage__end')) ? eval($sPlugin) : false);
		return true;
	}	
	
	public function addRsvp($ifevent, $iRsvp, $iUserId)
	{
		if (!Phpfox::isUser())
		{
			return false;
		}
		
		if (($iInviteId = $this->database()->select('invite_id')
			->from(Phpfox::getT('fevent_invite'))
			->where('fevent_id = ' . (int) $ifevent . ' AND invited_user_id = ' . (int) $iUserId)
			->execute('getField')))
		{
			$this->database()->update(Phpfox::getT('fevent_invite'), array(
					'rsvp_id' => $iRsvp,					
					'invited_user_id' => $iUserId,
					'time_stamp' => PHPFOX_TIME
				), 'invite_id = ' . $iInviteId
			);
			
			(Phpfox::isModule('request') ? Phpfox::getService('request.process')->delete('fevent_invite', $ifevent, $iUserId) : false);
		}
		else 
		{
			$this->database()->insert(Phpfox::getT('fevent_invite'), array(
					'fevent_id' => $ifevent,			
					'rsvp_id' => $iRsvp,					
					'user_id' => $iUserId,
					'invited_user_id' => $iUserId,
					'time_stamp' => PHPFOX_TIME
				)
			);
		}
		
		return true;
	}
	
	public function deleteGuest($iInviteId)
	{
		$afevent = $this->database()->select('e.fevent_id, e.user_id')
			->from(Phpfox::getT('fevent_invite'), 'ei')
			->join($this->_sTable, 'e', 'e.fevent_id = ei.fevent_id')
			->where('ei.invite_id = ' . (int) $iInviteId)
			->execute('getRow');
			
		if (!isset($afevent['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.unable_to_find_the_fevent'));
		}
			
		if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'fevent_id', $afevent['fevent_id'], 'fevent.can_edit_own_fevent', 'fevent.can_edit_other_fevent', $afevent['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_modify_this_fevent'));
		}	

		$this->database()->delete(Phpfox::getT('fevent_invite'), 'invite_id = ' . (int) $iInviteId);	
			
		return true;
	}
	
	public function delete($iId, &$afevent = null)
	{
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__start')){return eval($sPlugin);}
	
		$mReturn = true;
		if ($afevent === null)
		{
			$afevent = $this->database()->select('user_id, module_id, item_id, image_path, is_sponsor, is_featured')
				->from($this->_sTable)
				->where('fevent_id = ' . (int) $iId)
				->execute('getRow');
			
			if ($afevent['module_id'] == 'pages' && Phpfox::getService('pages')->isAdmin($afevent['item_id']))
			{
				$mReturn = Phpfox::getService('pages')->getUrl($afevent['item_id']) . 'fevent/';
			}
			else
			{
				if (!isset($afevent['user_id']))
				{
					return Phpfox_Error::set(Phpfox::getPhrase('fevent.unable_to_find_the_fevent_you_want_to_delete'));
				}

				if (!Phpfox::getService('user.auth')->hasAccess('fevent', 'fevent_id', $iId, 'fevent.can_delete_own_fevent', 'fevent.can_delete_other_fevent', $afevent['user_id']))
				{
					return Phpfox_Error::set(Phpfox::getPhrase('fevent.you_do_not_have_sufficient_permission_to_delete_this_listing'));
				}
			}
		}
		
		if (!empty($afevent['image_path']))
		{
			$aImages = array(
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], ''),
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], '_50'),
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], '_120'),
				Phpfox::getParam('fevent.dir_image') . sprintf($afevent['image_path'], '_200')
			);			
			
			$iFileSizes = 0;
			foreach ($aImages as $sImage)
			{
				if (file_exists($sImage))
				{
					$iFileSizes += filesize($sImage);
					if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__pre_unlink')){return eval($sPlugin);}
					Phpfox::getLib('file')->unlink($sImage);
				}
			}
			
			if ($iFileSizes > 0)
			{
				if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__pre_space_update')){return eval($sPlugin);}
				Phpfox::getService('user.space')->update($afevent['user_id'], 'fevent', $iFileSizes, '-');
			}
		}
		
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__pre_deletes')){return eval($sPlugin);}
		
		(Phpfox::isModule('comment') ? Phpfox::getService('comment.process')->deleteForItem(null, $iId, 'fevent') : null);		
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('fevent', $iId) : null);
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_fevent', $iId) : null);
		
		$aInvites = $this->database()->select('invite_id, invited_user_id')
			->from(Phpfox::getT('fevent_invite'))
			->where('fevent_id = ' . (int) $iId)
			->execute('getSlaveRows');
		foreach ($aInvites as $aInvite)
		{
			(Phpfox::isModule('request') ? Phpfox::getService('request.process')->delete('fevent_invite', $aInvite['invite_id'], $aInvite['invited_user_id']) : false);			
		}		
		
		$this->database()->delete($this->_sTable, 'fevent_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('fevent_text'), 'fevent_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('fevent_category_data'), 'fevent_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('fevent_invite'), 'fevent_id = ' . (int) $iId);
		$iTotalfevent = $this->database()
                        ->select('total_fevent')
                        ->from(Phpfox::getT('user_field'))
                        ->where('user_id =' . (int)$afevent['user_id'])->execute('getSlaveField');
        $iTotalfevent = $iTotalfevent -1;
        
		if ($iTotalfevent > 0)
		{
			$this->database()->update(Phpfox::getT('user_field'),
                        array('total_fevent' => $iTotalfevent),
                        'user_id = ' . (int)$afevent['user_id']);
		}
        
		if (isset($afevent['is_sponsor']) && $afevent['is_sponsor'] == 1)
		{
			$this->cache()->remove('fevent_sponsored');
		}
		if (isset($afevent['is_featured']) && $afevent['is_featured'])
		{
			$this->cache()->remove('fevent_featured', 'substr');
		}
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_delete__end')){return eval($sPlugin);}
		
		
		$sCacheId = $this->cache()->set(array('fevents', Phpfox::getUserId()));
		$this->cache()->remove($sCacheId);
		
		if (Phpfox::getParam('fevent.cache_fevents_per_user'))
		{
			$sCacheId = $this->cache()->set(array('fevents_by_user', $afevent['user_id']));
			$this->cache()->remove($sCacheId);
		}
		
		return $mReturn;
	}

	public function feature($iId, $iType)
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('fevent.can_feature_fevents', true);
		
		$this->database()->update($this->_sTable, array('is_featured' => ($iType ? '1' : '0')), 'fevent_id = ' . (int) $iId);
		
		$this->cache()->remove('fevent_featured', 'substr');
		
		return true;
	}	

	public function sponsor($iId, $iType)
	{
	    if (!Phpfox::getUserParam('fevent.can_sponsor_fevent') && !Phpfox::getUserParam('fevent.can_purchase_sponsor') && !defined('PHPFOX_API_CALLBACK'))
	    {
			return Phpfox_Error::set('Hack attempt?');
	    }
	    
	    $iType = (int)$iType;
	    if ($iType != 1 && $iType != 0)
	    {
			return false;
	    }
	    
	    $this->database()->update($this->_sTable, array('is_featured' => 0, 'is_sponsor' => $iType), 'fevent_id = ' . (int)$iId);

	    $this->cache()->remove('fevent_sponsored', 'substr');
	    
	    if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_sponsor__end')){return eval($sPlugin);}
	    
	    return true;
	}

	public function approve($iId)
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('fevent.can_approve_fevents', true);
		
		$afevent = $this->database()->select('v.*, ' . Phpfox::getUserField())
			->from($this->_sTable, 'v')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
			->where('v.fevent_id = ' . (int) $iId)
			->execute('getRow');
			
		if (!isset($afevent['fevent_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.unable_to_find_the_fevent_you_want_to_approve'));
		}
		
		$this->database()->update($this->_sTable, array('view_id' => '0'), 'fevent_id = ' . $afevent['fevent_id']);
		
		if (Phpfox::isModule('notification'))
		{
			Phpfox::getService('notification.process')->add('fevent_approved', $afevent['fevent_id'], $afevent['user_id']);
		}
		
		// Send the user an email
		$sLink = Phpfox::getLib('url')->permalink('fevent' , $afevent['fevent_id'], $afevent['title']);
		
		Phpfox::getLib('mail')->to($afevent['user_id'])
			->subject(array('fevent.your_fevent_has_been_approved_on_site_title', array('site_title' => Phpfox::getParam('core.site_title'))))
			->message(array('fevent.your_fevent_has_been_approved_on_site_title_link', array('site_title' => Phpfox::getParam('core.site_title'), 'link' => $sLink)))
			->notification('fevent.fevent_is_approved')
			->send();				

		Phpfox::getService('user.activity')->update($afevent['user_id'], 'fevent');		
		
		$this->addRsvp($afevent['fevent_id'], 1, $afevent['user_id']);
		
		$bAddFeed = true;
		(($sPlugin = Phpfox_Plugin::get('fevent.service_process_approve__1')) ? eval($sPlugin) : false);
		
		if ($bAddFeed)
		{
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('fevent', $afevent['fevent_id'], $afevent['privacy'], $afevent['privacy_comment'], 0, $afevent['user_id']) : null);
		}
		
		return true;	
	}	
	
	public function massEmail($iId, $iPage, $sSubject, $sText)
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('fevent.can_mass_mail_own_members', true);
		
		$afevent = Phpfox::getService('fevent')->getfevent($iId, true);
		
		if (!isset($afevent['fevent_id']))
		{
			return false;
		}
		
		if ($afevent['user_id'] != Phpfox::getUserId())
		{
			return false;
		}
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_massemail__start')){return eval($sPlugin);}
		Phpfox::getService('ban')->checkAutomaticBan($sText);
		list($iCnt, $aGuests) = Phpfox::getService('fevent')->getInvites($iId, 1, $iPage, 20);
		
		$sLink = Phpfox::getLib('url')->permalink('fevent' , $afevent['fevent_id'], $afevent['title']);
		
		$sText = '<br />
		' . Phpfox::getPhrase('fevent.notice_this_is_a_newsletter_sent_from_the_fevent') . ': ' . $afevent['title'] . '<br />
		<a href="' . $sLink . '">' . $sLink . '</a>
		<br /><br />
		' . $sText;
		
		foreach ($aGuests as $aGuest)
		{
			if ($aGuest['user_id'] == Phpfox::getUserId())
			{
				continue;
			}
			
			Phpfox::getLib('mail')->to($aGuest['user_id'])
				->subject($sSubject)
				->message($sText)
				->notification('fevent.mass_emails')
				->send();			
		}
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process_massemail__end')){return eval($sPlugin);}
		$this->database()->update($this->_sTable, array('mass_email' => PHPFOX_TIME), 'fevent_id = ' . $afevent['fevent_id']);
		
		return $iCnt;
	}
	
	public function removeInvite($iId)
	{
		$this->database()->delete(Phpfox::getT('fevent_invite'), 'fevent_id = ' . (int) $iId . ' AND invited_user_id = ' . Phpfox::getUserId());
		
		(Phpfox::isModule('request') ? Phpfox::getService('request.process')->delete('fevent_invite', $iId, Phpfox::getUserId()) : false);
		
		return true;
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
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_process__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}

	private function _verify(&$aVals, $bIsUpdate = false)
	{				
		/*
		if (!isset($aVals['category']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.provide_a_category_this_fevent_will_belong_to'));
		}
		*/	
		if (isset($aVals['category']) && is_array($aVals['category']))
		{
			foreach ($aVals['category'] as $iCategory)
			{		
				if (empty($iCategory))
				{
					continue;
				}

				if (!is_numeric($iCategory))
				{
					continue;
				}			

				$this->_aCategories[] = $iCategory;
			}
		}
		
		/*
		if (!count($this->_aCategories))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.provide_a_category_this_fevent_will_belong_to'));
		}		
		*/
		
		if (isset($_FILES['image']['name']) && ($_FILES['image']['name'] != ''))
		{
			$aImage = Phpfox::getLib('file')->load('image', array(
					'jpg',
					'gif',
					'png'
				), (Phpfox::getUserParam('fevent.max_upload_size_fevent') === 0 ? null : (Phpfox::getUserParam('fevent.max_upload_size_fevent') / 1024))
			);
			
			if ($aImage === false)
			{
				return false;
			}
			
			$this->_bHasImage = true;
		}	
		
		//if ($bIsUpdate === false)
		{			
			$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);
			$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);			
			
			if ($iEndTime < $iStartTime)
			{
				// return Phpfox_Error::set(Phpfox::getPhrase('fevent.your_fevent_is_ending_before_it_starts'));
				$this->_bIsEndingInThePast = true;
			}
			/*
			if (Phpfox::getLib('date')->convertToGmt($iStartTime) < PHPFOX_TIME)
			{
				return Phpfox_Error::set(Phpfox::getPhrase('fevent.your_fevent_is_starting_in_the_past'));
			}
			 * 
			 */
		}

		return true;	
	}
    
    /* Set Cover Photo*/
    public function setCoverPhoto($iFeventId, $iPhotoId, $bIsAjaxPageUpload = false)
    {
        $aFevent = Phpfox::getService('fevent')->getForEdit($iFeventId);
        if(!isset($aFevent['fevent_id']))
        {
            return false;
        }
        if ($aFevent['user_id'] != Phpfox::getUserId() && !Phpfox::isAdmin())
        {
            return Phpfox_Error::set('User is not an admin');
        }
        
        if ($bIsAjaxPageUpload == false)
        {
            // check that this photo belongs to this page
            $iPhotoId = $this->database()->select('photo_id')
                ->from(Phpfox::getT('photo'))
                ->where('module_id = "organization" AND group_id = '. (int)$iFeventId . ' AND photo_id = ' . (int)$iPhotoId)
                ->execute('getSlaveField');
        }        

        if (!empty($iPhotoId))
        {
            $this->database()->update(Phpfox::getT('fevent'), array('cover_photo_position' => '', 'cover_photo_id' => (int)$iPhotoId), 'fevent_id = ' . (int)$iFeventId);
            return true;
        }
        
        return Phpfox_Error::set('The photo does not belong to this event');
    }
    
    public function updateCoverPosition($iFeventId, $iPosition)
    {
        $aFevent = Phpfox::getService('fevent')->getForEdit($iFeventId);
        if(!isset($aFevent['fevent_id']))
        {
            return false;
        }
        if ($aFevent['user_id'] != Phpfox::getUserId() && !Phpfox::isAdmin())
        {
            return Phpfox_Error::set('User is not an admin');
        }
        $this->database()->update(Phpfox::getT('fevent'), array(
                'cover_photo_position' => (int)$iPosition
            ), 'fevent_id = ' . (int)$iFeventId);
            
        return true;
    }
    
}

?>