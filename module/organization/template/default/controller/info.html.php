<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div class="right_about left_block" style="padding-bottom: 10px;">
    <div class="left_block_title">Basic Info</div> 
    <div class="info_item">
        <label style="font-weight: bold;width: 125px; line-height: 25px;">Founder:</label> <br>  
        <label style="width: 100%;line-height: 20px;">{$aOrganization.founder}</label>
        <div style="clear: both;"></div>
    </div>
    <div class="info_item">
        <label style="font-weight: bold;;width: 125px;line-height: 25px;">Mission statement:</label><br>  
       <label style="width: 100%;line-height: 20px;">{$aOrganization.mission_statement}</label>
    </div>
    <div class="info_item">
        <label style="font-weight: bold;width: 125px;line-height: 25px;">Phone:</label><br>    
        <label style="width: 100%;line-height: 20px">{$aOrganization.phone}</label>
    </div>
</div>

