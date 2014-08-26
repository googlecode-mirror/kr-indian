<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_fevent
 * @version 		$Id: index.html.php 5844 2013-05-09 08:00:59Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !count($afevents)}
<div class="extra_info">
	{phrase var='fevent.no_fevents_found'}
</div>
{else}

{foreach from=$afevents key=sDate item=aGroups}
<div class="block">
	<div class="title">{$sDate}</div>	
	<div class="border">
		<div class="content">
			{foreach from=$aGroups name=fevents item=afevent}
			{item name='fevent'}
				<div id="js_fevent_item_holder_{$afevent.fevent_id}" class="js_fevent_parent {if $afevent.is_sponsor && !defined('PHPFOX_IS_GROUP_VIEW')}row_sponsored {elseif $afevent.is_featured && $sView != 'featured'}row_featured {/if}{if is_int($phpfox.iteration.fevents)}row1{else}row2{/if}{if $phpfox.iteration.fevents == 1} row_first{/if}">
					{if !Phpfox::isMobile()}
					<div class="row_title_image_header">
						
						{if isset($sView) && $sView == 'featured'}
						{else}
						<div class="js_featured_fevent row_featured_link"{if !$afevent.is_featured} style="display:none;"{/if}>
							{phrase var='fevent.featured'}
						</div>					
						{/if}	
						<div id="js_sponsor_phrase_{$afevent.fevent_id}" class="js_sponsor_fevent row_sponsored_link"{if !$afevent.is_sponsor} style="display:none;"{/if}>
							{phrase var='fevent.sponsored'}
						</div>					
						
						<a href="{$afevent.url}">{img server_id=$afevent.server_id title=$afevent.title path='fevent.url_image' file=$afevent.image_path suffix='_120' max_width='120' max_height='120' itemprop='image'}</a>
					</div>				
					<div class="row_title_image_header_body">	
					{/if}
						<div class="row_title">	
	
							<div class="row_title_image">		
								<a href="{$afevent.url}">{img user=$afevent suffix='_50_square' max_width='50' max_height='50'}</a>
								{if ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')
									|| ($afevent.view_id == 0 && ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent'))
									|| ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_fevent')) || Phpfox::getUserParam('fevent.can_edit_other_fevent')
									|| ($afevent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_delete_own_fevent')) || Phpfox::getUserParam('fevent.can_delete_other_fevent')
									|| (defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getService('pages')->isAdmin('' . $aPage.page_id . ''))
								}
								<div class="row_edit_bar_parent">
									<div class="row_edit_bar_holder">
										<ul>
											{template file='fevent.block.menu'}
										</ul>			
									</div>
									<div class="row_edit_bar">				
											<a href="#" class="row_edit_bar_action"><span>{phrase var='fevent.actions'}</span></a>							
									</div>
								</div>		
								{/if}							
								{if Phpfox::getUserParam('fevent.can_approve_fevents') || Phpfox::getUserParam('fevent.can_delete_other_fevent')}<a href="#{$afevent.fevent_id}" class="moderate_link" rel="fevent">{phrase var='fevent.moderate'}</a>{/if}
							</div>
							<div class="row_title_info">		
								<header>
									<h1 itemprop="name"><a href="{$afevent.url}" class="link" itemprop="url">{$afevent.title|clean|split:25}</a></h1>
								</header>
								<div class="extra_info">
									<ul class="extra_info_middot">{if isset($afevent.start_time_micro)}<li itemprop="startDate" style="display:none;">{$afevent.start_time_micro}<li>{/if}{$afevent.start_time_phrase} {phrase var='fevent.at'} {$afevent.start_time_phrase_stamp}</li><li><span>&middot;</span></li><li>{$afevent|user}</li></ul>
								</div>
								
								{if Phpfox::isMobile()}
								<a href="{$afevent.url}">{img server_id=$afevent.server_id title=$afevent.title path='fevent.url_image' file=$afevent.image_path suffix='_120' max_width='120' max_height='120'}</a>
								{/if}
		
								{module name='feed.comment' aFeed=$afevent.aFeed}				
								
							</div>			
							
						</div>	
					{if !Phpfox::isMobile()}
					</div>
					{/if}
				</div>
			{/item}
			{/foreach}
		</div>
	</div>
</div>
{/foreach}

{if Phpfox::getUserParam('fevent.can_approve_fevents') || Phpfox::getUserParam('fevent.can_delete_other_fevent')}
{moderation}
{/if}

{pager}
{/if}