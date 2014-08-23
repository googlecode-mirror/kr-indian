<?php

if (App::req(1) == 'view')
{
	$oRow = App::api('video.get', array('id' => App::req(2)));
	if (isset($oRow->id))
	{		
		echo '$(\'#app_js_video_view_' . $oRow->id . '\').html(\'' . App::cleanJs($oRow->embed_code) . '\');';
	}
	exit;
}

App::setTitle('Videos');

?>