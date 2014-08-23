<?php 

if (!empty($_POST['status']))
{
	ob_clean();
	$aRow = App::api('user.updateStatus', array('user_status' => $_REQUEST['status']));
	$sItem = App::item(array(
			'id' => $aRow->id,
			'module' => 'user_status',
			'display_name' => $aRow->profile_full_name,
			'profile_image' => $aRow->profile_image,
			'content' => $aRow->update,
			'likes' => $aRow->likes,
			'is_liked' => $aRow->is_liked,
			'comments' => $aRow->comments,
			'time' => $aRow->convert_time_stamp
	), true);
	
	$iId = 'app_new_status_' . $aRow->id . '';
	
	echo '$App.addItem(\'status\', \'' . $iId . '\', \'' . App::cleanJs($sItem) . '\');';
	exit;
}

?>
<?php if (!App::isAjax()): ?>
<div class="app_form">
	<form method="post" action="#">
		<div><input type="hidden" name="do" value="index" id="app_form_action" /></div>
		<textarea cols="60" rows="10" name="status" id="app_form_status" placeholder="What's going on?"></textarea>
		<div class="app_submit">
			<input type="submit" value="Post" class="app_button" id="app_form_button" />
			<div class="app_form_process"></div>
		</div>
	</form>
</div>
<div id="app_new_status_updates"></div>
<?php endif; ?>
<?php
$aRows = (array) App::api('user.status');
foreach ($aRows as $aRow)
{
	App::item(array(
		'id' => $aRow->id,
		'module' => 'user_status',
		'display_name' => $aRow->profile_full_name,
		'profile_image' => $aRow->profile_image,
		'content' => $aRow->update,
		'likes' => $aRow->likes,
		'is_liked' => $aRow->is_liked,
		'comments' => $aRow->comments,
		'time' => $aRow->convert_time_stamp
	));
}

if (empty($aRows))
{
	if (App::isAjax())
	{
		echo '<div class="app_item_done">No more updates to view at this time.</div>';
	}
	else
	{
		
	}
}
else
{
	App::pager('');
}

?>