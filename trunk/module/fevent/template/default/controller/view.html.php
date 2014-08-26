<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: view.html.php 3342 2011-10-21 12:59:32Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if $afevent.view_id == '1'}
<div class="message js_moderation_off">
	{phrase var='fevent.fevent_is_pending_approval'}
</div>
{/if}

{if ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')
	|| ($afevent.view_id == 0 && ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent'))
	|| ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')
	|| ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_delete_own_fevent')) || Phpfox::getUserParam('fevent.can_delete_other_fevent')
}
<div class="item_bar">
	<div class="item_bar_action_holder">
	{if (Phpfox::getUserParam('fevent.can_approve_fevents') && $afevent.view_id == '1')}
		<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>			
		<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('fevent.approve', 'inline=true&amp;fevent_id={$afevent.fevent_id}'); return false;">{phrase var='fevent.approve'}</a>
	{/if}
		<a href="#" class="item_bar_action"><span>{phrase var='fevent.actions'}</span></a>	
		<ul>
			{template file='fevent.block.menu'}
		</ul>			
	</div>
</div>
{/if}
{plugin call='fevent.template_default_controller_view_extra_info'}