<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Miguel Espinoza
 * @package 		Phpfox
 * @version 		$Id: sponsored.html.php 3214 2011-09-30 12:05:14Z Raymond_Benc $
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{if isset($aSponsorfevents.image_path) && $aSponsorfevents.image_path != ''}
<div class="t_center">
    <a href="{url link='ad.sponsor' view=$aSponsorfevents.sponsor_id}">
	{img title=$aSponsorfevents.title path='fevent.url_image' file=$aSponsorfevents.image_path suffix='_200' max_width='200' max_height='200'}
    </a>
</div>
{/if}
<div class="t_center info sponsored_title">
    <a href="{url link='ad.sponsor' view=$aSponsorfevents.sponsor_id}">
	{$aSponsorfevents.title}
    </a>
</div>

{if isset($aSponsorfevents.host)}
<div class="info">
	<div class="info_left">
		{phrase var='fevent.host'}:
	</div>
	<div class="info_right">
		{$aSponsorfevents.host|clean}
	</div>
</div>
{/if}
<div class="info">
	<div class="info_left">
		{phrase var='fevent.date'}:
	</div>
	<div class="info_right">
		{$aSponsorfevents.fevent_date}
	</div>
</div>
{if !empty($aSponsorfevents.country_iso)}
<div class="info">
    <div class="info_left">
		{phrase var='fevent.location'}:
    </div>
    <div class="info_right">
		{$aSponsorfevents.country_iso|location}
		{if !empty($aSponsorfevents.country_child_id)}
	<div class="p_2">&raquo; {$aSponsorfevents.country_child_id|location_child}</div>
		{/if}
		{if !empty($aSponsorfevents.city)}
	<div class="p_2">&raquo; {$aSponsorfevents.city|clean} </div>
		{/if}
    </div>
</div>
{/if}