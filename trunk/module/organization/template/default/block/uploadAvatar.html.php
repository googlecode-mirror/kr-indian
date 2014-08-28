<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script type="text/javascript">
    function uploadAvatarSuccess(url){
        $('#ic_loading').removeClass('ic_loading_active');
         $('.image_avatar>img').attr('src',url);
         tb_remove();
    }
    
    function uploadAvatarStar(){
        $('#ic_loading').addClass('ic_loading_active');
    }
</script>
{/literal}
<form action="{url link='organization.uploadavatar'}" method="POST" target="upload_avatar" enctype="multipart/form-data" onsubmit="uploadAvatarStar();">
    <label>Choose file</label>
    <input type="hidden" value="{$iOrganization}" name="organization_id">
    <input type="hidden" value="{$type}" name="type">
    <input type="file" name="image">
    <input type="submit" value="Submit" class="button" style="margin-left: 10px;">
    <iframe name="upload_avatar" id="upload_avatar" style="width: 0px;height:0px;opacity: 0;"></iframe>
</form>