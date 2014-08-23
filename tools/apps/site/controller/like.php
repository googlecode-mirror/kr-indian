<?php

$oLike = App::api('like.process', array('module' => $_GET['module'], 'id' => $_GET['id'], 'type' => $_GET['do']));
if ($_GET['do'] == 'add')
{
	if (is_bool($oLike) && $oLike)
	{
		echo 'var totallikes = parseInt($(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.app_item_likes:first\').html()); $(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.app_item_likes:first\').html((totallikes + 1));';
		echo '$(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.app_item_likeit\').hide();';
		echo '$(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.is_unlike:first\').show();';
	}
}
else
{
	if (is_bool($oLike) && $oLike)
	{
		echo 'var totallikes = parseInt($(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.app_item_likes:first\').html()); $(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.app_item_likes:first\').html((totallikes - 1));';
		echo '$(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.app_item_likeit\').hide();';
		echo '$(\'#app_item_like_' . $_GET['module'] . $_GET['id'] . '\').find(\'.is_like:first\').show();';
	}	
}

exit;

?>