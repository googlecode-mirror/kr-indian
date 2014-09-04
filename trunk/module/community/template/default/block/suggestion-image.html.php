<?php
    defined('PHPFOX') or exit('NO DICE!');
?>

<form action="{url link='community.upload'}" target="upload_panel" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="val[community_id]" value="{$iCommunityId}">
    <input type="file" name="image">
    <input type="submit" value="Submit" class="button">
    <iframe name="upload_panel" id="upload_panel" style="width: 0;height: 0;opacity: 0;"></iframe>
</form>