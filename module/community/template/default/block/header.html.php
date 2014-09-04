<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div class="community_header">
    <div class="community_header_left">
        <img src="{param var='core.path'}module/community/static/image/default_community.jpeg">
        <div class="preview_image_panel">{foreach from=$aPreviews item=aPreview}<span>{img path='community.url_image' file=$aPreview.image_path server_id=$aPreview.server_id suffix=$aPreview.suffix}{/foreach}</span></div>
    </div>
    <div class="community_header_content">
        <h1 style="margin-top:0px;padding-top:0px;font-size: 20px;"><span style="color: #666;">Introduce about </span>  <strong style="color: #333;"> {$aCommunity.title} City {if !empty($aCommunity.country_child_name)}, {$aCommunity.country_child_name}{/if}, {$aCommunity.country_name}</strong></h1>
    </div>
    <input onclick="$Core.box('community.uploadImageForm',500,'community_id={$aCommunity.community_id}');" class="bt_suggestion_image button" type="button" class="button" value="Suggestion Image">
</div>