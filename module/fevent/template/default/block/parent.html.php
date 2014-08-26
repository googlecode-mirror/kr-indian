<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: parent.html.php 1121 2009-10-01 12:59:13Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !count($afevents)}
<div class="extra_info">
	{phrase var='fevent.no_upcoming_fevents'}
	<ul class="action">
		<li><a href="{url link='fevent.add' module=$afeventParent.module item=$afeventParent.item}">{phrase var='fevent.add_an_fevent'}</a></li>
	</ul>
</div>
{else}
{foreach from=$afevents name=fevents item=afevent}
<div class="{if is_int($phpfox.iteration.fevents/2)}row1{else}row2{/if}{if $phpfox.iteration.fevents == 1} row_first{/if}">
	<div style="width:55px; position:absolute; text-align:center;">
		<a href="{$afevent.url}">{img server_id=$afevent.server_id title=$afevent.title path='fevent.url_image' file=$afevent.image_path suffix='_50' max_width='50' max_height='50'}</a>
	</div>
	<div style="margin-left:60px; min-height:55px; height:auto !important; height:55px;">	
		<a href="{$afevent.url}">{$afevent.title|clean|shorten:25:'...'|split:20}</a>
		{if !empty($afevent.tag_line)}
		<div class="extra_info">
			{$afevent.tag_line|clean|shorten:30:'...'|split:20}
		</div>
		{/if}	
		<div class="extra_info">
			{phrase var='fevent.time_stamp_at_location' time_stamp=$afevent.start_time_stamp location=$afevent.location_clean}{if !empty($afevent.city)}, {$afevent.city|clean}{/if}, {$afevent.country_iso|location}
		</div>		
	</div>
</div>
{/foreach}
{/if}