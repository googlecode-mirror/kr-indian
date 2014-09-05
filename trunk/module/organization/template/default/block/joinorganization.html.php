<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Profile
 * @version 		$Id: pic.html.php 4710 2012-09-21 08:59:25Z Raymond_Benc $
 * @description		This template is used to display the Like/Join link in Pages with Timeline.
 */
 
defined('PHPFOX') or exit('NO DICE!'); 


?> 

{if !Phpfox::getUserBy('profile_organization_id') && Phpfox::isUser()}
	{if isset($aOrganization) && $aOrganization.reg_method == '2' && !isset($aOrganization.is_invited) && $aOrganization.page_type == '1'}
	{else}
		{if isset($aOrganization) && isset($aOrganization.is_reg) && $aOrganization.is_reg}
		{else}
			{if isset($aOrganization) && isset($aOrganization.is_liked) && $aOrganization.is_liked != true}
				{if !isset($aUser) || !isset($aUser.use_timeline)}<span id="pages_like_join_position"{if $aOrganization.is_liked} style="display:none;"{/if}> {/if}
					<a href="#" id="pages_like_join" {if isset($aUser) && isset($aUser.use_timeline) && $aUser.use_timeline}style=""{/if}onclick="$(this).parent().hide(); $('#js_add_pages_unlike').show(); {if $aOrganization.organization_type == '1' && $aOrganization.reg_method == '1'} $.ajaxCall('organization.signup', 'organization_id={$aOrganization.organization_id}'); {else}$.ajaxCall('like.add', 'type_id=organization&amp;item_id={$aOrganization.organization_id}');{/if} return false;">
						{if $aOrganization.organization_type == '1' }
							{phrase var='pages.join'}
						{else}
							{phrase var='pages.like'}
						{/if}
					</a>
				{if !isset($aUser) || !isset($aUser.use_timeline)}</span>{/if}
			{/if}
		{/if}
	{/if}
{/if}

