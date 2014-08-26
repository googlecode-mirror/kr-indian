<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: invite.html.php 3533 2011-11-21 14:07:21Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<ul class="block_listing">
{foreach from=$afeventInvites item=afeventInvite}
	<li>
		<div class="block_listing_image">
			{img server_id=$afeventInvite.server_id title=$afeventInvite.title path='fevent.url_image' file=$afeventInvite.image_path suffix='_50' max_width='32' max_height='32'}
		</div>
		<div class="block_listing_title" style="padding-left:36px;">
			<a href="{permalink module='fevent' id=$afeventInvite.fevent_id title=$afeventInvite.title}">{$afeventInvite.title|clean}</a>
			<div class="extra_info">
				{$afeventInvite.start_time_phrase} at {$afeventInvite.start_time_phrase_stamp}	
				<div class="fevent_rsvp_invite_image" id="js_fevent_rsvp_invite_image_{$afeventInvite.fevent_id}">
					{img theme='ajax/add.gif'}
				</div>
				<ul class="fevent_rsvp_invite"><li>{phrase var='fevent.rsvp'}:</li><li><a href="#" onclick="$(this).parent().parent().hide(); $('#js_fevent_rsvp_invite_image_{$afeventInvite.fevent_id}').show(); $.ajaxCall('fevent.addRsvp', 'id={$afeventInvite.fevent_id}&amp;rsvp=1&amp;inline=1'); return false;">{phrase var='fevent.yes'}</a></li><li><span>&middot;</span></li><li><a href="#" onclick="$(this).parent().parent().hide(); $('#js_fevent_rsvp_invite_image_{$afeventInvite.fevent_id}').show(); $.ajaxCall('fevent.addRsvp', 'id={$afeventInvite.fevent_id}&amp;rsvp=3&amp;inline=1'); return false;">{phrase var='fevent.no'}</a></li><li><span>&middot;</span></li><li><a href="#" onclick="$(this).parent().parent().hide(); $('#js_fevent_rsvp_invite_image_{$afeventInvite.fevent_id}').show(); $.ajaxCall('fevent.addRsvp', 'id={$afeventInvite.fevent_id}&amp;rsvp=2&amp;inline=1'); return false;">{phrase var='fevent.maybe'}</a></li></ul>
			</div>
		</div>		
		<div class="clear"></div>
	</li>
{/foreach}
</ul>