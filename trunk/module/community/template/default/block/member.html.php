<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div class="community_member">
    <h1 style="font-size: 18px !important;">{$aCommunity.total_member} users live in {$aCommunity.title} City {if !empty($aCommunity.country_child_name)}, {$aCommunity.country_child_name}{/if}, {$aCommunity.country_name}</h1>
    <div style="margin-top:10px;">
        {foreach from=$aUsers item=aUser}
        <div class="member_item">
            <div class="member_item_avatar">
                {img user=$aUser suffix='_120_square'}
            </div>
            <p><a style="font-size:12px;" href="{url link=$aUser.user_name}">{$aUser.full_name|ucwords}</a></p>
        </div>
        {/foreach}
        <div style="clear: both;"></div>
    </div>
</div>