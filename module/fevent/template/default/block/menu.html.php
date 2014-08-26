<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: menu.html.php 3737 2011-12-09 07:50:12Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
	{if ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')}
		<li><a href="{url link='fevent.add' id=$afevent.fevent_id}">{phrase var='fevent.edit_fevent'}</a></li>	
	{/if}
	{if $afevent.view_id == 0 && ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')}
		<li><a href="{url link='fevent.add.invite' id=$afevent.fevent_id}">{phrase var='fevent.invite_people_to_come'}</a></li>	
		<li><a href="{url link='fevent.add.email' id=$afevent.fevent_id}">{phrase var='fevent.mass_email_guests'}</a></li>	
	{/if}		
	{if ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')}
		<li><a href="{url link='fevent.add.manage' id=$afevent.fevent_id}">{phrase var='fevent.manage_guest_list'}</a></li>	
	{/if}
	
	{if $afevent.view_id == 0 && Phpfox::getUserParam('fevent.can_feature_fevents')}
		<li id="js_feature_{$afevent.fevent_id}"{if $afevent.is_featured} style="display:none;"{/if}><a href="#" title="{phrase var='fevent.feature_this_fevent'}" onclick="$(this).parent().hide(); $('#js_unfeature_{$afevent.fevent_id}').show(); $(this).parents('.js_fevent_parent:first').addClass('row_featured').find('.js_featured_fevent').show(); $.ajaxCall('fevent.feature', 'fevent_id={$afevent.fevent_id}&amp;type=1'); return false;">{phrase var='fevent.feature'}</a></li>				
		<li id="js_unfeature_{$afevent.fevent_id}"{if !$afevent.is_featured} style="display:none;"{/if}><a href="#" title="{phrase var='fevent.un_feature_this_fevent'}" onclick="$(this).parent().hide(); $('#js_feature_{$afevent.fevent_id}').show(); $(this).parents('.js_fevent_parent:first').removeClass('row_featured').find('.js_featured_fevent').hide(); $.ajaxCall('fevent.feature', 'fevent_id={$afevent.fevent_id}&amp;type=0'); return false;">{phrase var='fevent.unfeature'}</a></li>				
	{/if}	
	
	{if Phpfox::getUserParam('fevent.can_sponsor_fevent')}
		<li id="js_fevent_sponsor_{$afevent.fevent_id}" {if $afevent.is_sponsor}style="display:none;"{/if}><a href="#" onclick="$.ajaxCall('fevent.sponsor', 'fevent_id={$afevent.fevent_id}&type=1', 'GET'); return false;">{phrase var='fevent.sponsor_this_fevent'}</a></li>
		<li id="js_fevent_unsponsor_{$afevent.fevent_id}" {if !$afevent.is_sponsor}style="display:none;"{/if}><a href="#" onclick="$.ajaxCall('fevent.sponsor', 'fevent_id={$afevent.fevent_id}&type=0', 'GET'); return false;">{phrase var='fevent.unsponsor_this_fevent'}</a></li>				
	{elseif Phpfox::getUserParam('fevent.can_purchase_sponsor') && !defined('PHPFOX_IS_GROUP_VIEW') 
		&& $afevent.user_id == Phpfox::getUserId()
		&& $afevent.is_sponsor != 1}
		<li> 
			<a href="{permalink module='ad.sponsor' id=$afevent.fevent_id title=$afevent.title section=fevent}"> 
				{phrase var='fevent.sponsor_this_fevent'}
			</a>
		</li>
	{/if}
	
	{if (($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_delete_own_fevent')) || Phpfox::getUserParam('fevent.can_delete_other_fevent'))
		|| (defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getService('pages')->isAdmin('' . $aPage.page_id . ''))
	}
		<li class="item_delete"><a href="{url link='fevent' delete=$afevent.fevent_id}" class="sJsConfirm">{phrase var='fevent.delete_fevent'}</a></li>
	{/if}	