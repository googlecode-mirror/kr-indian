<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: menu.html.php 4871 2012-10-10 05:51:05Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="pages_view_sub_menu" id="pages_menu">
	<ul>
		{if $aOrganization.is_admin}
			<li><a href="{url link='pages.add' id=$aOrganization.organization_id}">{phrase var='pages.edit_page'}</a></li>		
		{/if}
		{module name='share.link' type='pages' url=$aOrganization.link title=$aOrganization.title display='menu' sharefeedid=$aOrganization.organization_id sharemodule='pages'}
		{if !Phpfox::getUserBy('profile_organization_id')}
			<li id="js_add_pages_unlike" {if !$aOrganization.is_liked} style="display:none;"{/if}><a href="#" onclick="$(this).parent().hide(); $('#pages_like_join_position').show(); $.ajaxCall('like.delete', 'type_id=pages&amp;item_id={$aOrganization.organization_id}'); return false;">{if $aOrganization.organization_type == '1'}{phrase var='pages.remove_membership'}{else}{phrase var='pages.unlike'}{/if}</a></li>
		{/if}		
		{if !$aOrganization.is_admin && Phpfox::getUserParam('pages.can_claim_page') && empty($aOrganization.claim_id)}
			<li>
				<a href="#?call=contact.showQuickContact&amp;height=600&amp;width=600&amp;organization_id={$aOrganization.organization_id}" class="inlinePopup js_claim_page" title="{phrase var='pages.claim_page'}">
					{phrase var='pages.claim_page'}
				</a>
			</li>
		{/if}
	</ul>
</div>