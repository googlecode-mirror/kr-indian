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
    $Behavior.addNewfevent = function()
    {
        $('.minvite_frend').click(function()
        {
           var parent = $('.minvite_frend').closest('.friend_item');
           //parent.hide()
          var id = parent.attr("id");
           alert(id);
        });
    }

</script>
{/literal}
<div class="left_block">
    <div class="left_block_title">Invite</div>
    <div class="left_block_content">
        <input type="text" name="search" placeholder="Search" id="search_friend_bar" onkeyup="searchOnChange();">
        <div>
            {foreach from=$aFriends item=aFriend}
            <div class="friend_item" style="margin-bottom: 5px;" id="friend_item_{$aFriend.user_id}">
                <div style="float: left;">
                    <img src="{$aFriend.user_image}" alt="" style="width: 40px;">
                </div>
                <div>
                    <a href="{$aFriend.user_profile}" class='friend_full_name' style="margin-left: 5px; line-height: 40px; cursor: pointer;">{$aFriend.full_name}</a>
                    <div style="float: right; margin-top: 10px;">
                        <input id="{$aFriend.user_id}"  class="minvite_frend button" type="button" value="Invite">
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
