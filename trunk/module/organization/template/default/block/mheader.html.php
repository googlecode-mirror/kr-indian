<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>

<link rel="stylesheet" href="{param var='core.path'}module/organization/static/css/default/default/style.css" type="text/css">
<div id="m_header">
    <div id="cover_photo">  
    
        {if $bRefreshPhoto}
            <div class="cover_photo_link">
        {else}
            {if isset($aCoverPhoto.photo_id)}<a href="{permalink module='photo' id=$aCoverPhoto.photo_id title=$aCoverPhoto.title}userid_{$aCoverPhoto.user_id}/" class="thickbox photo_holder_image cover_photo_link" rel="{$aCoverPhoto.photo_id}">{/if}
        {/if}

        {if isset($aCoverPhoto.photo_id)}
            {if isset($bNoPrefix) && $bNoPrefix == true}
                {img id='js_photo_cover_position' server_id=$aCoverPhoto.server_id path='photo.url_photo' file=$aCoverPhoto.destination suffix='' width=980 title=$aCoverPhoto.title style='position:absolute; top:'$sLogoPosition'px; left:0px;' time_stamp=true}
            {else}
                {if $bRefreshPhoto || $bNewCoverPhoto}
                    {img id='js_photo_cover_position' server_id=$aCoverPhoto.server_id path='photo.url_photo' file=$aCoverPhoto.destination suffix='_1024' width=980 title=$aCoverPhoto.title style='position:absolute; top:'$sLogoPosition'px; left:0px;' time_stamp=true}
                {else}
                    {img id='js_photo_cover_position' server_id=$aCoverPhoto.server_id path='photo.url_photo' file=$aCoverPhoto.destination suffix='_1024' width=980 title=$aCoverPhoto.title style='position:absolute; top:'$sLogoPosition'px; left:0px;'}
                {/if}
            {/if}
        {/if}
        {if $bRefreshPhoto}
            </div>
        {else}
            </a>
        {/if}
        {if $bRefreshPhoto}
            {literal}
                <style type="text/css">
                    #js_photo_cover_position
                    {
                        cursor:move;
                    }
                </style>
                <script type="text/javascript">
                var sCoverPosition = '0';    
                $Behavior.positionCoverPhoto = function(){            
                    $('#js_photo_cover_position').draggable('destroy').draggable({
                        axis: 'y',
                        cursor: 'move',    
                        stop: function(event, ui){
                            var sStop = $(this).position();
                            sCoverPosition = sStop.top;            
                        }
                    });    
                }
                </script>
            {/literal}
        {/if}
        {if $bRefreshPhoto}
            <div class="cover_photo_change" style="background-color: rgba(0,0,0,0.4);position:absolute;left:0px; bottom:0px;width:100%;border:none;color:#fff">
                {phrase var='user.drag_to_reposition_cover'}
                <form method="post" action="#">
                    <ul class="table_clear_button">
                        <li id="js_cover_update_loader_upload" style="display:none;">{img theme='ajax/add.gif' class='v_middle'}</li>        
                        <li class="js_cover_update_li"><div><input type="button" class="button button_off" value="{phrase var='user.cancel_cover_photo'}" name="cancel" onclick="window.location.href='{if $bIsOrganization }{$sOrganizationLink}{elseif $bIsOrganization}{$sOrganizationLink}{else}{url link='profile'}{/if}';" /></div></li>
                        <li class="js_cover_update_li"><div><input type="button" class="button" value="{phrase var='user.save_changes'}" name="save" onclick="$('.js_cover_update_li').hide(); $('#js_cover_update_loader_upload').show(); $.ajaxCall('{$sAjaxModule}.updateCoverPosition', 'position=' + sCoverPosition{if $sAjaxModule == 'pages'} + '&organization_id={$aOrganization.organization_id}'{elseif $sAjaxModule == 'organization'} + '&organization_id={$aOrganization.organization_id}'{/if}); return false;" /></div></li>
                    </ul>
                    <div class="clear"></div>
                </form>
            </div>
        {/if}
        
        <div class="image_avatar" onclick="$Core.box('organization.formUploadAvatar',405,'organization_id={$aOrganization.organization_id}&type=organization');" >
        {if $aOrganization.is_app}
        {img server_id=$aOrganization.image_server_id path='app.url_image' file=$aOrganization.aApp.image_path suffix='_160' max_width='175' max_height='300' title=$aOrganization.aApp.app_title}
        {else}
            {if Phpfox::getParam('core.keep_non_square_images')}
                {img server_id=$aOrganization.image_server_id title=$aOrganization.title path='core.url_user' file=$aOrganization.image_path suffix='_160_square' max_width='175' max_height='300'}
            {else}
                {img server_id=$aOrganization.image_server_id title=$aOrganization.title path='core.url_user' file=$aOrganization.image_path suffix='_160_square' max_width='175' max_height='300'}
            {/if}
        {/if}
            <div id="ic_loading">
                <img src="{param var='core.path'}module/organization/static/image/ic_loading.gif">
            </div>
            <div id="update_tooltip">
                <img src="{param var='core.path'}module/organization/static/image/ic_picture.png" alt="">
                <span>Update image profile</span>
            </div>
        </div>

         {if !$bRefreshPhoto}
            <input type="button" class="button" style="position:absolute;right:15px;bottom:15px" value="Add a cover" onclick="$Core.box('profile.logo',500,'organization_id={$aOrganization.organization_id}&type=organization');">
            <div class="m_header_infor" >
            <a href="{$aOrganization.link}">{$aOrganization.title}</a><br>
               <span style="font-size: 18px; text-shadow: 2px 2px 2px #272729;">
                    {if isset($aUser.category_name)}{$aUser.category_name|convert}{/if}
                </span>
            </div>
        {/if}
    </div>

    {literal}
        <script type="text/javascript">
           $Behavior.initHeader = function(){

           }
        </script>
    {/literal}
    <div class="menu_bar">
        <ul >
            <li class="active"><a href="#">Timeline</a></li>
            <li><span></span></li>
            <li><a href="#">Introducton</a></li>
            <li><span></span></li>
            <li><a href="#">Members</a></li>
            <li><span></span></li>
            <li><a href="#">Album</a></li>
        </ul>
    </div>
</div>