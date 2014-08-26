<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: rsvp.html.php 4503 2012-07-11 14:41:02Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" action="{url link='current'}" onsubmit="$('#js_fevent_rsvp_button').find('input:first').attr('disabled', true); $('#js_fevent_rsvp_update').html($.ajaxProcess('{phrase var="fevent.updating"}')).show(); $(this).ajaxCall('fevent.addRsvp', '&id={$afevent.fevent_id}'); return false;">
{if isset($aCallback) && $aCallback !== false}
	<div><input type="hidden" name="module" value="{$aCallback.module}" /></div>
	<div><input type="hidden" name="item" value="{$aCallback.item}" /></div>
{/if}
	<div class="p_2">
		<label onclick="$('#js_fevent_rsvp_button').show(); $('.js_fevent_rsvp').attr('checked', false); $(this).find('.js_fevent_rsvp').attr('checked', true);"><input type="radio" name="rsvp" value="1" class="checkbox v_middle js_fevent_rsvp" {if $afevent.rsvp_id == 1}checked="checked" {/if}/> {phrase var='fevent.attending'}</label>
	</div>
	<div class="p_2">
		<label onclick="$('#js_fevent_rsvp_button').show(); $('.js_fevent_rsvp').attr('checked', false); $(this).find('.js_fevent_rsvp').attr('checked', true);"><input type="radio" name="rsvp" value="2" class="checkbox v_middle js_fevent_rsvp" {if $afevent.rsvp_id == 2}checked="checked" {/if}/> {phrase var='fevent.maybe_attending'}</label>
	</div>
	<div class="p_2">
		<label onclick="$('#js_fevent_rsvp_button').show(); $('.js_fevent_rsvp').attr('checked', false); $(this).find('.js_fevent_rsvp').attr('checked', true);"><input type="radio" name="rsvp" value="3" class="checkbox v_middle js_fevent_rsvp" {if $afevent.rsvp_id == 3}checked="checked" {/if}/> {phrase var='fevent.not_attending'}</label>
	</div>	
	<div id="js_fevent_rsvp_button" class="p_2" style="margin-top:10px;{if $afevent.rsvp_id} display:none;{/if}">
		<input type="submit" id="btn_rsvp_submit" value="{if $afevent.rsvp_id}{phrase var='fevent.update_your_rsvp'}{else}{phrase var='fevent.submit_your_rsvp'}{/if}" class="button" /> <span id="js_fevent_rsvp_update"></span>
	</div>
</form>