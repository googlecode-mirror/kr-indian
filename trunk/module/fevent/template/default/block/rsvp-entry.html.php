<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: rsvp-entry.html.php 3342 2011-10-21 12:59:32Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
					{if isset($afevent.rsvp_id)}
					<div class="feed_comment_extra">
						<a href="#" onclick="tb_show('{phrase var='fevent.rsvp' phpfox_squote=true}', $.ajaxBox('fevent.rsvp', 'height=130&amp;width=300&amp;id={$afevent.fevent_id}{if $aCallback !== false}&amp;module={$aCallback.module}&amp;item={$aCallback.item}{/if}')); return false;" id="js_fevent_rsvp_{$afevent.fevent_id}">
						{if $afevent.rsvp_id == 3}
							{phrase var='fevent.not_attending'}
						{elseif $afevent.rsvp_id == 2}
							{phrase var='fevent.maybe_attending'}
						{elseif $afevent.rsvp_id == 1}
							{phrase var='fevent.attending'}
						{else}
							{phrase var='fevent.respond'}
						{/if}						
						</a>
					</div>
					{/if}