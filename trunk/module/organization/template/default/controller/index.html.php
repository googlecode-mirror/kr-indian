<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: index.html.php 3990 2012-03-09 15:28:08Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if count($aOrganizations)}
{if $sView == 'my' && Phpfox::getUserBy('profile_organization_id')}
<div class="message">
	{phrase var='organization.note_that_organization_displayed_here_are_organization_created_by_the_page' global_full_name=$sGlobalUserFullName|clean profile_full_name=$aGlobalProfilePageLogin.full_name|clean}
</div>
{/if}
{foreach from=$aOrganizations name=organization item=aOrganization}
<div id="js_pages_{$aOrganization.organization_id}" class="js_pages_parent {if is_int($phpfox.iteration.organization/2)}row1{else}row2{/if}{if $phpfox.iteration.organization == 1 && !PHPFOX_IS_AJAX} row_first{/if}">		
		<div class="row_title">	
			<div class="row_title_image">
				<a href="{$aOrganization.link}">{img server_id=$aOrganization.profile_server_id title=$aOrganization.title path='core.url_user' file=$aOrganization.profile_user_image suffix='_50_square' max_width='50' max_height='50' is_page_image=true}</a>						
				
				{if Phpfox::getUserParam('organization.can_moderate_organization') || $aOrganization.user_id == Phpfox::getUserId()}
				<div class="row_edit_bar_parent">
					<div class="row_edit_bar_holder">
						<ul>
							{template file='organization.block.link'}
						</ul>			
					</div>
					<div class="row_edit_bar">				
							<a href="#" class="row_edit_bar_action"><span>{phrase var='organization.actions'}</span></a>							
					</div>
				</div>				
				{/if}
				
				{if Phpfox::getUserParam('organization.can_moderate_organization')}
				<a href="#{$aOrganization.organization_id}" class="moderate_link" rel="organization">{phrase var='organization.moderate'}</a>
				{/if}
			</div>
			<div class="row_title_info">
				<a href="{$aOrganization.link}" class="link">{$aOrganization.title|clean|shorten:55:'...'|split:40}</a>			
				<div class="extra_info">
					<ul class="extra_info_middot"><li>{$aOrganization.category_name|convert}</li>{if $aOrganization.organization_type == '1'}<li><span>&middot;</span></li><li>{if $aOrganization.total_like > 1}{phrase var='organization.total_members' total=$aOrganization.total_like|number_format}{elseif $aOrganization.total_like == 1}{phrase var='organization.1_member'}{else}{phrase var='organization.no_members'}{/if}</li>{/if}</ul>
				</div>
				{if $aOrganization.organization_type == '0'}
				{module name='feed.comment' aFeed=$aOrganization.aFeed}				
				{/if}
			</div>					
		</div>	
</div>
{/foreach}
{if Phpfox::getUserParam('organization.can_moderate_organization')}
{moderation}
{/if}

{pager}
{else}
<div class="extra_info">
	{phrase var='organization.no_organization_found'}
</div>
{/if}