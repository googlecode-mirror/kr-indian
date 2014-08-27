<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div class="community_header">
    <div class="community_header_left">
        <img src="{param var='core.path'}module/community/static/image/default_community.jpeg">
    </div>
    <div class="community_header_content">
        <h1 style="margin-top:0px;padding-top:0px;font-size: 20px;"><span style="color: #666;">Introduce about </span>  <strong style="color: #333;"> {$aCommunity.title} City {if !empty($aCommunity.country_child_name)}, {$aCommunity.country_child_name}{/if}, {$aCommunity.country_name}</strong></h1>
    </div>
</div>