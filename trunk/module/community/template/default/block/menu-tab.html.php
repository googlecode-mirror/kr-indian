<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

{literal}
<style type="text/css">
    .block_menu_tab li{
        line-height: 24px;
        padding-left:5px;
        color: #666;
    }
    .block_menu_tab li a{
        text-decoration: none;
        color: inherit;
    }
    .block_menu_tab li:hover{
        background-color:#3b5998;
        color: #FFF !important;
    }
    .block_menu_tab li i{
        font-size:12px;
    }
</style>
{/literal}

<div class="block_menu_tab">
    <ul>
        <li><a href=""><i class="fa fa-user"></i> Friend</a></li>
        <li><a href="{url link='community.view' id=$aCommunity.community_id}"><i class="fa fa-share-alt"></i> City Community</a></li>
        <li><a href=""><i class="fa fa-puzzle-piece"></i> Neighborhood</a></li>
        <li><a href="{url link='organization'}"><i class="fa fa-cubes"></i> Organization</a></li>
    </ul>
</div>