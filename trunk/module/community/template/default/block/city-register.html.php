<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<div class="table_left">
    <label for="city_location">{required}{phrase var='user.city'}:</label>
</div>
<div class="table_right">
    {if count($aCitys)}
    <select name="val[city_location]">
        <option value="0">Choose a City</option>
        {foreach from=$aCitys item=aCity}
        <option value="{$aCity.city_id}">{$aCity.name}</option>
        {/foreach}
    </select>
    <a href="" onclick="$('#new_city_location').show();return false;">Enter your city</a>
    <input style="margin-top:10px;display: none;" type="text" name="val[new_city_location]" id="new_city_location" value="{value type='input' id='city_location'}" size="30"/>
    {else}
    <input type="text" name="val[new_city_location]" id="new_city_location" value="{value type='input' id='city_location'}" size="30" />
    {/if}
</div>    