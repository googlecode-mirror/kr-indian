<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: index.html.php 3332 2011-10-20 12:50:29Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="table_header">
	{phrase var='organization.categories'}
</div>
<table id="js_drag_drop" cellpadding="0" cellspacing="0">
	<tr>
		<th></th>
		<th style="width:20px;"></th>
		<th>{phrase var='organization.name'}</th>
		<th class="t_center" style="width:60px;">{phrase var='organization.active'}</th>	
	</tr>
	{foreach from=$aCategories key=iKey item=aCategory}
	<tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
		<td class="drag_handle"><input type="hidden" name="val[ordering][{if $bSubCategory}{$aCategory.category_id}{else}{$aCategory.type_id}{/if}]" value="{$aCategory.ordering}" /></td>
		<td class="t_center">
			<a href="#" class="js_drop_down_link" title="Manage">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
			<div class="link_menu">
				<ul>
					<li><a href="{if $bSubCategory}{url link='admincp.organization.add' sub=$aCategory.category_id}{else}{url link='admincp.organization.add' id=$aCategory.type_id}{/if}">{phrase var='organization.edit'}</a></li>		
					{if isset($aCategory.categories) && ($iTotalSub = count($aCategory.categories))}
					<li><a href="{url link='admincp.organization' sub={$aCategory.type_id}">{phrase var='organization.manage_sub_categories_total' total=$iTotalSub}</a></li>		
					{/if}
					<li><a href="{if $bSubCategory}{url link='admincp.organization' sub=$aCategory.type_id delete=$aCategory.category_id}{else}{url link='admincp.organization' delete=$aCategory.type_id}{/if}" onclick="return confirm('{phrase var='organization.are_you_sure'}');">{phrase var='organization.delete'}</a></li>		
				</ul>
			</div>		
		</td>	
		<td>{$aCategory.name|convert}</td>
		<td class="t_center">
			<div class="js_item_is_active"{if !$aCategory.is_active} style="display:none;"{/if}>
				<a href="#?call=organization.updateActivity&amp;id={if $bSubCategory}{$aCategory.category_id}{else}{$aCategory.type_id}{/if}&amp;active=0&amp;sub={if $bSubCategory}1{else}0{/if}" class="js_item_active_link" title="{phrase var='organization.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
			</div>
			<div class="js_item_is_not_active"{if $aCategory.is_active} style="display:none;"{/if}>
				<a href="#?call=organization.updateActivity&amp;id={if $bSubCategory}{$aCategory.category_id}{else}{$aCategory.type_id}{/if}&amp;active=1&amp;sub={if $bSubCategory}1{else}0{/if}" class="js_item_active_link" title="{phrase var='organization.activate'}">{img theme='misc/bullet_red.png' alt=''}</a>
			</div>		
		</td>		
	</tr>
	{/foreach}
</table>