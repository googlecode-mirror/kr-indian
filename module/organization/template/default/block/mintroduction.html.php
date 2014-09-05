<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          Raymond Benc
 * @package          Module_Profile
 * @version         $Id: logo.html.php 4914 2012-10-22 07:52:17Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<link rel="stylesheet" href="{param var='core.path'}module/organization/static/css/default/default/style.css" type="text/css">
<div class="left_block">
    <div class="left_block_title">Introduction</div>
    <div class="left_block_content">{$aOrganization.text}</div>
    <div class="left_block_content"><a>{$aOrganization.website}</a></div>
</div>
