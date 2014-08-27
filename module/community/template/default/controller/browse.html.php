<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div>
    {foreach from=$aCommunitys item=aCommunity}
    <div class="item_item">
        <div class="item_item_inner">
            <div class="item_item_avatar">
                <img src="{param var='core.path'}module/community/static/image/image1.jpg">
            </div>
            <div class="item_item_info">
                <p class="item_item_title"><a href="{url link='community.view' id=$aCommunity.community_id}">{$aCommunity.title|ucwords}</a></p>
                <p class="item_item_location">Ho Chi Minh, Viet Nam</p>
            </div>
        </div>
    </div>
    {/foreach}
    <div style="clear: both;"></div>
</div>
{pager}