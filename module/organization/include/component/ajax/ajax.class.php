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
 * @package 		Phpfox_Ajax
 * @version 		$Id: ajax.class.php 7075 2014-01-28 16:04:34Z Fern $
 */
class organization_Component_Ajax_Ajax extends Phpfox_Ajax
{
	public function removeLogo()
	{
		if (($aPage = Phpfox::getService('organization.process')->removeLogo($this->get('organization_id'))) !== false)
		{
			$this->call('window.location.href = \'' . $aPage['link'] . '\';');
		}
	}	
	
	public function deleteWidget()
	{
		if (Phpfox::getService('organization.process')->deleteWidget($this->get('widget_id')))
		{
			$this->slideUp('#js_organization_widget_' . $this->get('widget_id'));
		}
	}
	
	public function addWidget()
	{
		$this->error(false);		
		if (($this->get('widget_id') ? Phpfox::getService('organization.process')->updateWidget($this->get('widget_id'), $this->get('val')) : Phpfox::getService('organization.process')->addWidget($this->get('val'))))
		{
			$aVals = $this->get('val');
			$this->call('window.location.href = \'' . Phpfox::getLib('url')->makeUrl('organization.add.widget', array('id' => $aVals['organization_id'])) . '\';');			
		}
		else
		{
			$this->call('$Core.processForm(\'#js_organization_widget_submit_button\', true);');
			$this->html('#js_organization_widget_error', '<div class="error_message">' . implode('', Phpfox_Error::get()) . '</div>');
			$this->show('#js_organization_widget_error');
		}
	}
	
	public function widget()
	{
		$this->setTitle(Phpfox::getPhrase('organization.widgets'));
		Phpfox::getComponent('organization.widget', array(), 'controller');			
		
		(($sPlugin = Phpfox_Plugin::get('organization.component_ajax_widget')) ? eval($sPlugin) : false);
		
		echo '<script type="text/javascript">$Core.loadInit();</script>';
	}
	
	public function add()
	{
		Phpfox::isUser(true);
		if (($iId = Phpfox::getService('organization.process')->add($this->get('val'))))
		{
			$aPage = Phpfox::getService('organization')->getPage($iId);
			
			$this->call('window.location.href = \'' . Phpfox::getLib('url')->makeUrl('organization.add', array('id' => $aPage['organization_id'], 'new' => '1')) . '\';');
		}
		else
		{
			$sError = Phpfox_Error::get();
			$sError = implode('<br />', $sError);
			$this->alert($sError);
			$this->call('$Core.processForm(\'#js_organization_add_submit_button\', true);');
		}
	}
	
	public function addFeedComment()
	{
		Phpfox::isUser(true);
				
		$aVals = (array) $this->get('val');	
		if (!defined('PAGE_TIME_LINE'))
		{
		    // Check if this item is a page and is using time line
		    if (isset($aVals['callback_module']) && $aVals['callback_module'] == 'organization' && isset($aVals['callback_item_id']) && Phpfox::getService('organization')->timelineEnabled($aVals['callback_item_id']))
		    {
			define('PAGE_TIME_LINE', true);			
		    }
			
		}
		
		if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
		{
			$this->alert(Phpfox::getPhrase('user.add_some_text_to_share'));
			$this->call('$Core.activityFeedProcess(false);');
			return;			
		}
		
		$aOrganization = Phpfox::getService('organization')->getPage($aVals['callback_item_id']);

		if (!isset($aOrganization['organization_id']))
		{
			$this->alert(Phpfox::getPhrase('organization.unable_to_find_the_page_you_are_trying_to_comment_on'));
			$this->call('$Core.activityFeedProcess(false);');
			return;
		}
		
		$sLink = Phpfox::getService('organization')->getUrl($aOrganization['organization_id'], $aOrganization['title'], $aOrganization['vanity_url']);
		$aCallback = array(
			'module' => 'organization',
			'table_prefix' => 'organization_',
			'link' => $sLink,
			'email_user_id' => $aOrganization['user_id'],
			'subject' => Phpfox::getPhrase('organization.full_name_wrote_a_comment_on_your_page_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aOrganization['title'])),
			'message' => Phpfox::getPhrase('organization.full_name_wrote_a_comment_link', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aOrganization['title'])),
			'notification' => ($this->get('custom_organization_post_as_page') ? null : 'organization_comment'),
			'feed_id' => 'organization_comment',
			'item_id' => $aOrganization['organization_id']
		);
		
		$aVals['parent_user_id'] = $aVals['callback_item_id'];
		
		if (isset($aVals['user_status']) && ($iId = Phpfox::getService('feed.process')->callback($aCallback)->addComment($aVals)))
		{
			Phpfox::getLib('database')->updateCounter('organization', 'total_comment', 'organization_id', $aOrganization['organization_id']);		
			//die($iId);
			Phpfox::getService('feed')->callback($aCallback)->processAjax($iId);
		}
		else 
		{
			$this->call('$Core.activityFeedProcess(false);');
		}		
	}	
	
	public function changeUrl()
	{
		Phpfox::isUser(true);
		
		if (($aPage = Phpfox::getService('organization')->getForEdit($this->get('id'))))
		{
			$aVals = $this->get('val');
			
			$sNewTitle = Phpfox::getLib('parse.input')->cleanTitle($aVals['vanity_url']);
			
			if (Phpfox::getLib('parse.input')->allowTitle($sNewTitle, Phpfox::getPhrase('organization.page_name_not_allowed_please_select_another_name')))
			{
				if (Phpfox::getService('organization.process')->updateTitle($this->get('id'), $sNewTitle))
				{
					$this->alert(Phpfox::getPhrase('organization.successfully_updated_your_organization_url'), Phpfox::getPhrase('organization.url_updated'), 300, 150, true);
				}
			}		
		}
		
		$this->call('$Core.processForm(\'#js_organization_vanity_url_button\', true);');
	}
	
	public function signup()
	{
		Phpfox::isUser(true);
		if (Phpfox::getService('organization.process')->register($this->get('organization_id')))
		{
			$this->alert(Phpfox::getPhrase('organization.successfully_registered_for_this_page'));
		}
	}
	
	public function moderation()
	{
		Phpfox::isUser(true);
		if (Phpfox::getService('organization.process')->moderation($this->get('item_moderate'), $this->get('action')))
		{
			foreach ((array) $this->get('item_moderate') as $iId)
			{
				$this->remove('#js_organization_user_entry_' . $iId);	
			}
			
			$this->updateCount();
			
			$this->alert(Phpfox::getPhrase('organization.successfully_moderated_user_s'), Phpfox::getPhrase('organization.moderation'), 300, 150, true);
		}		
		
		$this->hide('.moderation_process');			
	}	
	
	public function logBackUser()
	{
		$this->error(false);
		Phpfox::isUser(true);
		$aUser = Phpfox::getService('organization')->getLastLogin();
		list ($bPass, $aReturn) = Phpfox::getService('user.auth')->login($aUser['email'], $this->get('password'), true, $sType = 'email');
		if ($bPass)			
		{
			Phpfox::getService('organization.process')->clearLogin($aUser['user_id']);
			
			$this->call('window.location.href = \'' . Phpfox::getLib('url')->makeUrl('') . '\';');
		}
		else
		{
			$this->html('#js_error_organization_login_user', '<div class="error_message">' . implode('<br />', Phpfox_Error::get()) . '</div>');
		}		
	}
	
	public function logBackIn()
	{
		// Phpfox::isUser(true);
		
		if (($aUser = Phpfox::getService('organization')->getLastLogin()))
		{		
			if (isset($aUser['fb_user_id']) && $aUser['fb_user_id'])
			{
				$bPass = true;
				Phpfox::getService('organization.process')->clearLogin($aUser['user_id']);
				Phpfox::getService('user.auth')->logout();
			}
			else
			{
				if (Phpfox::getParam('core.auth_user_via_session'))
				{
					Phpfox::getLib('database')->delete(Phpfox::getT('session'), 'user_id = ' . (int) Phpfox::getUserId());
				}
				list ($bPass, $aReturn) = Phpfox::getService('user.auth')->login($aUser['email'], $aUser['password'], true, 'email', true);
				if ($bPass)			
				{
					Phpfox::getService('organization.process')->clearLogin($aUser['user_id']);
				}
			}			
		}
		
		$this->call('window.location.href = \'' . Phpfox::getLib('url')->makeUrl('') . '\';');
		// $this->setTitle('Login');
		// Phpfox::getBlock('organization.login-user');		
	}
	
	public function login()
	{
		Phpfox::isUser(true);
		$this->setTitle(Phpfox::getPhrase('organization.login_as_a_page'));
		Phpfox::getBlock('organization.login');
	}
	
	public function loginSearch()
	{
        // Parameters to be sent to the block
        $aParams = array(
            'page' => $this->get('page'),
        );
		
		// Call the block and send the parameters
		Phpfox::getBlock('organization.login', $aParams);
		
		// Display the block into the TB box
        $this->call('$(\'.js_box_content\').html(\'' . $this->getContent() . '\');');
 
	}
	
	public function processLogin()
	{
		if (Phpfox::getService('organization.process')->login($this->get('organization_id')))
		{
			$this->call('window.location.href = \'' . Phpfox::getLib('url')->makeUrl('') . '\';');
		}
	}
	
	public function pageModeration()
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('organization.can_moderate_organization', true);
		
		switch ($this->get('action'))
		{
			case 'approve':
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('organization.process')->approve($iId);
					$this->remove('#js_organization_' . $iId);					
				}								
				$sMessage = Phpfox::getPhrase('organization.organization_s_successfully_approved');
				break;			
			case 'delete':
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('organization.process')->delete($iId);
					$this->slideUp('#js_organization_' . $iId);
				}				
				$sMessage = Phpfox::getPhrase('organization.organization_s_successfully_deleted');
				break;
		}
		
		$this->updateCount();
		
		$this->alert($sMessage, Phpfox::getPhrase('organization.moderation'), 300, 150, true);
		$this->hide('.moderation_process');					
	}
	
	public function approve()
	{
		if (Phpfox::getService('organization.process')->approve($this->get('organization_id')))
		{
			$this->alert(Phpfox::getPhrase('organization.page_has_been_approved'), Phpfox::getPhrase('organization.page_approved'), 300, 100, true);
			$this->hide('#js_item_bar_approve_image');
			$this->hide('.js_moderation_off'); 
			$this->show('.js_moderation_on');
		}
	}	
	
	public function updateActivity()
	{
		if (Phpfox::getService('organization.process')->updateActivity($this->get('id'), $this->get('active'), $this->get('sub')))
		{

		}
	}	
	
	public function categoryOrdering()
	{
		$aVals = $this->get('val');
		Phpfox::getService('core.process')->updateOrdering(array(
				'table' => 'organization_type',
				'key' => 'type_id',
				'values' => $aVals['ordering']
			)
		);		
		
		Phpfox::getLib('cache')->remove('organization', 'substr');
	}	
	
	public function categorySubOrdering()
	{
		$aVals = $this->get('val');
		Phpfox::getService('core.process')->updateOrdering(array(
				'table' => 'organization_category',
				'key' => 'category_id',
				'values' => $aVals['ordering']
			)
		);		
		
		Phpfox::getLib('cache')->remove('organization', 'substr');
	}	

	public function approveClaim()
	{
		if (Phpfox::getService('organization.process')->approveClaim($this->get('claim_id')))
		{
			$this->hide('#claim_' . $this->get('claim_id'));
		}
		else
		{
			$this->alert('An error occured');
		}
	}
	
	public function denyClaim()
	{
		if (Phpfox::getService('organization.process')->denyClaim($this->get('claim_id')))
		{
			$this->hide('#claim_' . $this->get('claim_id'));
		}
		else
		{
			$this->alert('An error occured');
		}
	}
	
	public function setCoverPhoto()
	{
		$iPageId = $this->get('organization_id');
		$iPhotoId = $this->get('photo_id');
		
		if (Phpfox::getService('organization.process')->setCoverPhoto($iPageId , $iPhotoId))
		{
			$this->call('window.location.href = "' . Phpfox::permalink('organization', $this->get('organization_id'), '') . 'coverupdate_1";');
			
		}
		else
		{
			$aErr = Phpfox_Error::get();
			$sErr = implode($aErr);
		}
	}
	
	public function updateCoverPosition()
	{
		if (Phpfox::getService('organization.process')->updateCoverPosition($this->get('organization_id'), $this->get('position')))
		{
			$this->call('window.location.href = "' . Phpfox::permalink('organization', $this->get('organization_id'), '') . '";');
			//$this->call('location.reload();');
			Phpfox::addMessage(Phpfox::getPhrase('organization.position_set_correctly'));
		}
		else
		{
			$aErr = Phpfox_Error::get();
			$sErr = implode($aErr);
		}
	}
	
	public function removeCoverPhoto()
	{
		if (Phpfox::getService('organization.process')->removeCoverPhoto($this->get('organization_id')))
		{
			$this->call('window.location.href=window.location.href;');
		}
		else
		{
			$aErr = Phpfox_Error::get();
			$sErr = implode($aErr);
		}
	}
    //New Code
    public function formUploadAvatar()
    {
        $this->setTitle('Upload Profile Image');
        Phpfox::getComponent('organization.uploadAvatar',array('organization_id' => $this->get('organization_id'),'type'=>'type'),'block');
    }
    
    public function inviteFriend()
    { 
        if(Phpfox::getService('organization.process')->inviteFriend($this->get('user_id'),$this->get('page_id')))
        {
            $this->call('$("#friend_item_'.$this->get('user_id').'").find(".button").val("Invited");');
        }
        else
        {
            $this->call('alert("fail");');
        }
    }
    
}

?>
