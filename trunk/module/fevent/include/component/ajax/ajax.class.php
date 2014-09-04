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
 * @version 		$Id: ajax.class.php 5538 2013-03-25 13:20:22Z Miguel_Espinoza $
 */
class fevent_Component_Ajax_Ajax extends Phpfox_Ajax
{
	public function loadMiniForm()
	{
		Phpfox::getBlock('fevent.mini');
		
		$sContent = $this->getContent(false);
		$sContent = str_replace(array("\n", "\t"), '', $sContent);
		
		$this->html('.block_fevent_sub_holder', $sContent);
		$this->call('$Core.loadInit();');
	}
	
	public function deleteImage()
	{
		Phpfox::isUser(true);
		
		if (Phpfox::getService('fevent.process')->deleteImage($this->get('id')))
		{
			
		}
	}
	
	public function addRsvp()
	{
		Phpfox::isUser(true);
		
		if (Phpfox::getService('fevent.process')->addRsvp($this->get('id'), $this->get('rsvp'), Phpfox::getUserId()))
		{
			if ($this->get('rsvp') == 3)
			{
				$sRsvpMessage = Phpfox::getPhrase('fevent.not_attending');
			}
			elseif ($this->get('rsvp') == 2)
			{
				$sRsvpMessage = Phpfox::getPhrase('fevent.maybe_attending');
			}
			elseif ($this->get('rsvp') == 1)
			{
				$sRsvpMessage = Phpfox::getPhrase('fevent.attending');
			}
			
			if ($this->get('inline'))
			{
				$this->html('#js_fevent_rsvp_' . $this->get('id'), $sRsvpMessage);
				$this->hide('#js_fevent_rsvp_invite_image_' . $this->get('id'));
			}
			else 
			{
				$this->html('#js_fevent_rsvp_update', Phpfox::getPhrase('fevent.done'), '.fadeOut(5000);')
					->html('#js_fevent_rsvp_' . $this->get('id'), $sRsvpMessage)
					->call('$(\'#js_fevent_rsvp_button\').find(\'input:first\').attr(\'disabled\', false);')
					->call('tb_remove();');
				
				$this->call('$.ajaxCall(\'fevent.listGuests\', \'&rsvp=' . $this->get('rsvp') . '&id=' . $this->get('id') . '' . ($this->get('module') ? '&module=' . $this->get('module') . '&item=' . $this->get('item') . '' : '') . '\');')
					->call('$Behavior.fevent_ajax_1 = function(){ $(\'#js_block_border_fevent_list .menu:first ul li\').removeClass(\'active\'); $(\'#js_block_border_fevent_list .menu:first ul li a\').each(function() { var aParts = explode(\'rsvp=\', this.href); var aParts2 = explode(\'&\', aParts[1]); if (aParts2[0] == ' . $this->get('rsvp') . ') {  $(this).parent().addClass(\'active\'); } }); };');
			}
		}
	}
	
	public function listGuests()
	{
		Phpfox::getBlock('fevent.list');
		
		$this->html('#js_fevent_item_holder', $this->getContent(false));
	}
	
	public function browseList()
	{	
		Phpfox::getBlock('fevent.browse');
		
		if ((int) $this->get('page') > 0)
		{
			$this->html('#js_fevent_browse_guest_list', $this->getContent(false));
		}
		else 
		{
			$this->setTitle(Phpfox::getPhrase('fevent.guest_list'));	
		}
	}
	
	public function deleteGuest()
	{
		if (Phpfox::getService('fevent.process')->deleteGuest($this->get('id')))
		{
			
		}
	}
	
	public function delete()
	{
		if (Phpfox::getService('fevent.process')->delete($this->get('id')))
		{
			$this->call('$(\'#js_fevent_item_holder_' . $this->get('id') . '\').html(\'<div class="message" style="margin:0px;">' . Phpfox::getPhrase('fevent.successfully_deleted_fevent') . '</div>\').fadeOut(5000);');			
		}
	}
	
	public function rsvp()
	{
		Phpfox::getBlock('fevent.rsvp');
	}
	
	public function feature()
	{
		if (Phpfox::getService('fevent.process')->feature($this->get('fevent_id'), $this->get('type')))
		{
			
		}
	}	

	public function sponsor()
	{
	    if (Phpfox::getService('fevent.process')->sponsor($this->get('fevent_id'), $this->get('type')))
	    {
		if ($this->get('type') == '1')
		{
		    Phpfox::getService('ad.process')->addSponsor(array('module' => 'fevent', 'item_id' => $this->get('fevent_id')));
		    $this->call('$("#js_fevent_unsponsor_'.$this->get('fevent_id').'").show();');
		    $this->call('$("#js_fevent_sponsor_'.$this->get('fevent_id').'").hide();');
		    $this->addClass('#js_fevent_item_holder_'.$this->get('fevent_id'), 'row_sponsored');
			$this->show('#js_sponsor_phrase_' . $this->get('fevent_id'));
		    $this->alert(Phpfox::getPhrase('fevent.fevent_successfully_sponsored'));
		}
		else
		{
		    Phpfox::getService('ad.process')->deleteAdminSponsor('fevent', $this->get('fevent_id'));
		    $this->call('$("#js_fevent_unsponsor_'.$this->get('fevent_id').'").hide();');
		    $this->call('$("#js_fevent_sponsor_'.$this->get('fevent_id').'").show();');
		    $this->removeClass('#js_fevent_item_holder_'.$this->get('fevent_id'), 'row_sponsored');
			$this->hide('#js_sponsor_phrase_' . $this->get('fevent_id'));
		    $this->alert(Phpfox::getPhrase('fevent.fevent_successfully_un_sponsored'));
		}
	    }
	}
	
	public function approve()
	{
		if (Phpfox::getService('fevent.process')->approve($this->get('fevent_id')))
		{
			$this->alert(Phpfox::getPhrase('fevent.fevent_has_been_approved'), Phpfox::getPhrase('fevent.fevent_approved'), 300, 100, true);
			$this->hide('#js_item_bar_approve_image');
			$this->hide('.js_moderation_off'); 
			$this->show('.js_moderation_on');				
		}
	}
	
	public function moderation()
	{
		Phpfox::isUser(true);	
		
		switch ($this->get('action'))
		{
			case 'approve':
				Phpfox::getUserParam('fevent.can_approve_fevents', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('fevent.process')->approve($iId);
					$this->remove('#js_fevent_item_holder_' . $iId);					
				}				
				$this->updateCount();
				$sMessage = Phpfox::getPhrase('fevent.fevent_s_successfully_approved');
				break;			
			case 'delete':
				Phpfox::getUserParam('fevent.can_delete_other_fevent', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('fevent.process')->delete($iId);
					$this->slideUp('#js_fevent_item_holder_' . $iId);
				}				
				$sMessage = Phpfox::getPhrase('fevent.fevent_s_successfully_deleted');
				break;
		}
		
		$this->alert($sMessage, 'Moderation', 300, 150, true);
		$this->hide('.moderation_process');			
	}	

	public function massEmail()
	{
		$iPage = $this->get('page', 1);
		$sSubject = $this->get('subject');
		$sText = $this->get('text');
		
		if ($iPage == 1 && !Phpfox::getService('fevent')->canSendEmails($this->get('id')))
		{
			$this->hide('#js_fevent_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.you_are_unable_to_send_out_any_mass_emails_at_the_moment'));
			
			return;
		}
		
		if (empty($sSubject) || empty($sText))
		{
			$this->hide('#js_fevent_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.fill_in_both_a_subject_and_text_for_your_mass_email'));
			
			return;
		}
		
		$iCnt = Phpfox::getService('fevent.process')->massEmail($this->get('id'), $iPage, $this->get('subject'), $this->get('text'));
		
		if ($iCnt === false)
		{
			$this->hide('#js_fevent_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.you_are_unable_to_send_a_mass_email_for_this_fevent'));
			
			return;
		}		
	
		Phpfox::getLib('pager')->set(array('ajax' => 'fevent.massEmail', 'page' => $iPage, 'size' => 20, 'count' => $iCnt));		
		
		if ($iPage < Phpfox::getLib('pager')->getLastPage())
		{
			$this->call('$.ajaxCall(\'fevent.massEmail\', \'id=' . $this->get('id') . '&page=' . ($iPage + 1) . '&subject=' . $this->get('subject') . '&text=' . $this->get('text') . '\');');
			
			$this->html('#js_fevent_mass_mail_send', Phpfox::getPhrase('fevent.email_progress_page_total', array('page' => $iPage, 'total' => Phpfox::getLib('pager')->getLastPage())));
		}
		else 
		{
			if (!Phpfox::getService('fevent')->canSendEmails($this->get('id'), true))
			{
				$this->hide('#js_send_email')
					->show('#js_send_email_fail')
					->html('#js_time_left', Phpfox::getTime(Phpfox::getParam('mail.mail_time_stamp'), Phpfox::getService('fevent')->getTimeLeft($this->get('id'))));
			}
			
			$this->hide('#js_fevent_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.done'));
		}
	}	
	
	public function removeInvite()
	{
		Phpfox::getService('fevent.process')->removeInvite($this->get('id'));
	}
	
	public function addFeedComment()
	{
		Phpfox::isUser(true);
		
		$aVals = (array) $this->get('val');	
		
		if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
		{
			$this->alert(Phpfox::getPhrase('user.add_some_text_to_share'));
			$this->call('$Core.activityFeedProcess(false);');
			return;			
		}		
		
		$afevent = Phpfox::getService('fevent')->getForEdit($aVals['callback_item_id'], true);
		
		if (!isset($afevent['fevent_id']))
		{
			$this->alert(Phpfox::getPhrase('fevent.unable_to_find_the_fevent_you_are_trying_to_comment_on'));
			$this->call('$Core.activityFeedProcess(false);');
			return;
		}
		
		$sLink = Phpfox::permalink('fevent', $afevent['fevent_id'], $afevent['title']);
		$aCallback = array(
			'module' => 'fevent',
			'table_prefix' => 'fevent_',
			'link' => $sLink,
			'email_user_id' => $afevent['user_id'],
			'subject' => Phpfox::getPhrase('fevent.full_name_wrote_a_comment_on_your_fevent_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $afevent['title'])),
			'message' => Phpfox::getPhrase('fevent.full_name_wrote_a_comment_on_your_fevent_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $afevent['title'])),
			'notification' => 'fevent_comment',
			'feed_id' => 'fevent_comment',
			'item_id' => $afevent['fevent_id']
		);
		
		$aVals['parent_user_id'] = $aVals['callback_item_id'];
		
		if (isset($aVals['user_status']) && ($iId = Phpfox::getService('feed.process')->callback($aCallback)->addComment($aVals)))
		{
			Phpfox::getLib('database')->updateCounter('fevent', 'total_comment', 'fevent_id', $afevent['fevent_id']);		
			
			Phpfox::getService('feed')->callback($aCallback)->processAjax($iId);
		}
		else 
		{
			$this->call('$Core.activityFeedProcess(false);');
		}		
	}
    public function updateCoverPosition()
    {
        if (Phpfox::getService('fevent.process')->updateCoverPosition($this->get('fevent_id'), $this->get('position')))
        {
            $this->call('window.location.href = "' . Phpfox::permalink('fevent', $this->get('fevent_id'), '') . '";');
            Phpfox::addMessage(Phpfox::getPhrase('fevent.position_set_correctly'));
        }
        else
        {
            $aErr = Phpfox_Error::get();
            $sErr = implode($aErr);
        }
    }
     public function inviteFriend()
     {
        $idFevent = $this->get('id');
        $iFrendId = $this->get('userId');
        $sFullName = $this->get('name');
        if (Phpfox::getService('fevent')->sendInviteEvent($idFevent,$iFrendId))
        {  
            $this->call("$('#friend_item_$iFrendId').hide();");
            $this->call("$('.public_message').html('$sFullName has been invited');");
            $this->call("$('.public_message').show();");
        }
        else
        {
            $this->call("$('.public_message').html('Error, Please again !');");
            $this->call("$('.public_message').show();");
        }
     }	
}

?>