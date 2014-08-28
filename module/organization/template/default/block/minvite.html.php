<?php 
defined('PHPFOX') or exit('NO DICE!'); 
?>
{literal}
<script type="text/javascript">
    function searchOnChange(){
        var text = $('#search_friend_bar').val();
        $('.friend_item').each(function(index){
            if($(this).find('.friend_full_name').text().toLowerCase().indexOf(text.toLowerCase()) != -1){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    }
</script>
{/literal}
<div class="left_block">
    <div class="left_block_title">Invite</div>
    <div class="left_block_content">
        <input type="text" name="search" placeholder="Search" id="search_friend_bar" onkeyup="searchOnChange();">
         <ul>
        {foreach from=$aFriends item=aFriend}
            <li class="friend_item" id="friend_item_{$aFriend.user_id}">
                <div class="left">
                    <img src="{$aFriend.user_image}" alt="" style="width: 40px;">
                </div>
                <div class="right">
                    <a class='friend_full_name' >{$aFriend.full_name}</a>
                     <div style="float: right;">
                    <input type="button" value="Invite" class="button" onclick="$.ajaxCall('organization.inviteFriend','user_id={$aFriend.user_id}&organization_id={$aOrganization.organization_id}');" style="position:relative; top:10px">
                    </div>
                </div>
            </li>
        {/foreach}
        </ul>
    </div>
</div>
