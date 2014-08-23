<?php 

function buildVideoContent($aRow)
{
	$sRowContent = $aRow->title;
	$sRowContent .= '<div class="app_item_break" id="app_js_video_view_' . $aRow->id . '">';
	$sRowContent .= '<a href="#" class="app_ajax_call" rel="videos/view/' . $aRow->id . '"><div class="app_item_play"></div><img src="' . $aRow->photo . '" alt="" class="app_item_img" /></a>';
	$sRowContent .= '</div>';
	
	return $sRowContent;
}

if (!empty($_POST['status']))
{
	ob_clean();
	$aRow = App::api('video.add', array('url' => $_POST['status']));
	if (isset($aRow->id))
	{
		$sItem = App::item(array(
			'id' => $aRow->id,
			'module' => 'video',
			'display_name' => $aRow->uploaded_by,
			'profile_image' => $aRow->uploaded_by_image,
			'content' => buildVideoContent($aRow),
			'likes' => $aRow->likes,
			'is_liked' => $aRow->is_liked,
			'comments' => $aRow->comments,
			'time' => $aRow->convert_time_stamp
			), true);
	
		$iId = 'app_new_status_' . $aRow->id . '';
		
		echo '$App.addItem(\'status\', \'' . $iId . '\', \'' . App::cleanJs($sItem) . '\');';
	}	
	
	exit;
}

?>
<?php if (!App::isAjax()): ?>
<div class="app_form">
	<form method="post" action="#">
		<div><input type="hidden" name="do" value="videos" id="app_form_action" /></div>
		<div class="app_form_input">
			Share a Video
		</div>		
		<input type="text" name="status" value="" id="app_form_status" placeholder="http://" />
		<div class="app_submit">
			<input type="submit" value="Post" class="app_button" id="app_form_button" />
			<div class="app_form_process"></div>
		</div>
	</form>
</div>
<div id="app_new_status_updates"></div>
<?php endif; ?>
<?php 

$aRows = (array) App::api('video.get');
foreach ($aRows as $aRow)
{
	App::item(array(
			'id' => $aRow->id,
			'module' => 'video',
			'display_name' => $aRow->uploaded_by,
			'profile_image' => $aRow->uploaded_by_image,
			'content' => buildVideoContent($aRow),
			'likes' => $aRow->likes,
			'is_liked' => $aRow->is_liked,
			'comments' => $aRow->comments,
			'time' => $aRow->convert_time_stamp
			));		
}

if (empty($aRows))
{
	echo '<div class="app_item_done">No more videos to view at this time.</div>';	
}
else
{
	App::pager('videos');
}

?>