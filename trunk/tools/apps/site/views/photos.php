<?php 

if (!empty($_POST['status']))
{
	ob_clean();
	
	$sError = '';
	if (!empty($_FILES['photo']))
	{
		if (empty($_FILES['photo']['error']))
		{
			$aTypes = array('', 'gif', 'jpg', 'png');
			$aInfo = @getImageSize($_FILES['photo']['tmp_name']);
			if (isset($aTypes[$aInfo[2]]))
			{			
				$sName = uniqid() . '.' . $aTypes[$aInfo[2]];
							
				move_uploaded_file($_FILES['photo']['tmp_name'], APP_DIR . 'uploads/' . $sName);	
				
				$aRow = App::api('photo.add', array('url' => APP_SITE . 'uploads/' . $sName, 'description' => $_POST['status']));
				if (isset($aRow->id))
				{
					$sItem = App::item(array(
							'id' => $aRow->id,
							'module' => 'photo',
							'display_name' => $aRow->uploaded_by,
							'profile_image' => $aRow->uploaded_by_image,
							'content' => $aRow->description . '<div class="app_item_break"><a href="' . $aRow->photo_1024px . '" class="app_view_photo"><img src="' . $aRow->photo_500px . '" alt="" class="app_item_img" /></a></div>',
							'likes' => $aRow->likes,
							'is_liked' => $aRow->is_liked,
							'comments' => $aRow->comments,
							'time' => $aRow->convert_time_stamp
					), true);
					
					$iId = 'app_new_status_' . $aRow->id . '';
					
					echo '<script type="text/javascript">window.parent.$App.addItem(\'photo\', \'' . $iId . '\', \'' . App::cleanJs($sItem) . '\');</script>';								
				}
				else
				{
					$sError = $aRow->error_message;
				}				
				
				unlink(APP_DIR . 'uploads/' . $sName);
			}
			else
			{
				$sError = 'Not a valid image. We only accept .png, .gif or .jpg images.';
			}
		}
		else
		{
			$sError = 'Unable to upload the photo.';
		}
	}
		
	if (!empty($sError))
	{
		echo '<script type="text/javascript">window.parent.$App.error(\'photo\', \'' . $sError . '\');</script>';
	}
	
	exit;
}

?>
<?php if (!App::isAjax()): ?> 
<div class="app_form">
	<form method="post" action="<?php echo App::link('photos'); ?>" enctype="multipart/form-data" target="app_iframe_src">
		<div id="app_form_photo_upload_holder">
			<div class="app_form_input">
				Share a Photo
			</div>		
			<input type="file" name="photo" id="app_form_photo_upload" />
		</div>		
		<div class="app_form_hidden">
			<textarea cols="60" rows="10" name="status" id="app_form_status" placeholder="Say something about this photo..."></textarea>
			<div class="app_submit">
				<input type="submit" value="Share" class="app_button" onclick="$('.app_form_process').show();" />
				<div class="app_form_process"></div>
			</div>
		</div>
	</form>
	<iframe id="app_iframe_src" name="app_iframe_src" style="display:none;"></iframe>
</div>
<div id="app_new_status_updates"></div>
<?php endif; ?>
<?php 

$aPhotos = (array) App::api('photo.get');

foreach ($aPhotos as $oPhoto)
{
	App::item(array(
			'module' => 'photo',
			'id' => $oPhoto->id,
			'display_name' => $oPhoto->uploaded_by,
			'profile_image' => $oPhoto->uploaded_by_image,
			'content' => $oPhoto->description . '<div class="app_item_break"><a href="' . $oPhoto->photo_1024px . '" class="app_view_photo"><img src="' . $oPhoto->photo_500px . '" alt="" class="app_item_img" /></a></div>',
			'likes' => $oPhoto->likes,
			'is_liked' => $oPhoto->is_liked,
			'comments' => $oPhoto->comments,
			'time' => $oPhoto->convert_time_stamp
			));
}

if (empty($aPhotos))
{
	echo '<div class="app_item_done">No more photos to view at this time.</div>';	
}
else
{
	App::pager('photos');
}

?>