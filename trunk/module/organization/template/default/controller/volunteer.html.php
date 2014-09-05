<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
<div class="right_about left_block" style="margin-right: 0px;">
    <div class="left_block_title">Volunteer</div> 
    <div style="min-height: 397px;">
         <ul>
         {foreach from=$aMembers.1 item=aMember}
            <li class="member_item">
               <a href="{url link=$aMember.user_name}">{img user=$aMember suffix='_100_square'}</a>
               <a href="{url link=$aMember.user_name}" style="font-weight: bold;margin-top: 5px;">{$aMember.full_name}</a>
            </li>
         {/foreach}
         </ul>
    </div> 
</div>