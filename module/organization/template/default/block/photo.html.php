<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: controller.html.php 64 2009-01-19 15:05:54Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="profile_image">
    <div class="profile_image_holder">
		{if $aOrganization.is_app}
		{img server_id=$aOrganization.image_server_id path='app.url_image' file=$aOrganization.aApp.image_path suffix='_120' max_width='175' max_height='300' title=$aOrganization.aApp.app_title}
		{else}
			{if Phpfox::getParam('core.keep_non_square_images')}
				{img thickbox=true server_id=$aOrganization.image_server_id title=$aOrganization.title path='core.url_user' file=$aOrganization.image_path suffix='_120' max_width='175' max_height='300'}
			{else}
				{img thickbox=true server_id=$aOrganization.image_server_id title=$aOrganization.title path='core.url_user' file=$aOrganization.image_path suffix='_120_square' max_width='175' max_height='300'}
			{/if}
		{/if}
	</div>
	<div class="profile_no_timeline">

		{if isset($aOrganization.title)}
		{template file='organization.block.joinorganization'}
		{/if}

	</div>
</div>
{if $bCanViewOrganization}
<div class="sub_section_menu">
	<ul>		
		{foreach from=$aOrganizationLinks item=aOrganizationLink}
			<li class="{if isset($aOrganizationLink.is_selected)} active{/if}">
				<a href="{$aOrganizationLink.url}" class="ajax_link"{if isset($aOrganizationLink.icon)} style="background-image:url('{if isset($aOrganizationLink.icon_pass) && $aOrganizationLink.icon_pass}{img thickbox=true server_id=$aOrganizationLink.icon_server path='pages.url_image' file=$aOrganizationLink.icon suffix='_16' return_url=true}{else}{img theme=$aOrganizationLink.icon' return_url=true}{/if}');"{/if}>{$aOrganizationLink.phrase}{if isset($aOrganizationLink.total)}<span>({$aOrganizationLink.total|number_format})</span>{/if}</a>				
				{if isset($aOrganizationLink.sub_menu) && is_array($aOrganizationLink.sub_menu) && count($aOrganizationLink.sub_menu)}
				<ul>
				{foreach from=$aOrganizationLink.sub_menu item=aProfileLinkSub}
					<li class="{if isset($aProfileLinkSub.is_selected)} active{/if}"><a href="{url link=$aOrganizationLink.url}{$aProfileLinkSub.url}">{$aProfileLinkSub.phrase}{if isset($aProfileLinkSub.total) && $aProfileLinkSub.total > 0}<span class="pending">{$aProfileLinkSub.total|number_format}</span>{/if}</a></li>
				{/foreach}
				</ul>
				{/if}				
			</li>
		{/foreach}
	</ul>
    <div class="clear"></div>
</div>
{/if}
