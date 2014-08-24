<li><a href="{url link='organization.add' id=$aOrganization.organization_id}">{phrase var='organization.manage'}</a></li>
{if Phpfox::getUserParam('organization.can_design_organization') && isset($aOrganization.is_admin) && $aOrganization.is_admin}
	<li>
		<a href="{$aOrganization.link}designer/" class="no_ajax_link">
			{phrase var='organization.customize_design'}
		</a>
	</li>
{/if}
{if Phpfox::getUserParam('organization.can_moderate_organization') || $aOrganization.user_id == Phpfox::getUserId()}
	<li class="item_delete">
		<a href="{url link='organization' delete=$aOrganization.organization_id}" onclick="return confirm('{phrase var='organization.are_you_sure'}');" class="no_ajax_link">
			{phrase var='organization.delete'}
		</a>
	</li>
{/if}
{if Phpfox::getUserParam('organization.can_add_cover_photo_organization')}
<li>
	<a href="#" onclick="$(this).parent().find('.cover_section_menu_drop:first').toggle(); event.cancelBubble = true; if (event.stopPropagation) event.stopPropagation();return false;">
		{if empty($aOrganization.cover_photo_id)}
			{phrase var='user.add_a_cover'}
		{else}
			{phrase var='user.change_cover'}
		{/if}
	</a>
	<div class="cover_section_menu_drop" style="display: none;">
		<ul style="display:block">
			<li>
				<a href="{url link='organization.'$aOrganization.organization_id}photo">
					{phrase var='user.choose_from_photos'}
				</a>
			</li>
			<li>
				<a href="#" onclick="$(this).parent().find('.cover_section_menu_drop:first').hide(); $Core.box('profile.logo', 500, 'organization_id={$aOrganization.organization_id}&type=organization'); return false;">
					{phrase var='user.upload_photo'}
				</a>
			</li>
			{if !empty($aOrganization.cover_photo_id)}
				<li>
					<a href="{$aOrganization.link}coverupdate_1">
						{phrase var='user.reposition'}
					</a>
				</li>
				<li>
					<a href="#" onclick="$(this).parent().find('.cover_section_menu_drop:first').hide(); $.ajaxCall('organization.removeLogo', 'organization_id={$aOrganization.organization_id}'); return false;">
						{phrase var='user.remove'}
					</a>
				</li>
			{/if}
		</ul>
	</div>
</li>
{/if}