<?php

function appBuildComment($oComment, $iCnt = 0)
{
	$sHtml = '';
	$sHtml .= '<div class="app_comment' . ($iCnt == 1 ? ' app_comment_no_border' : '') . '">';
	
	$sHtml .= '<div class="app_comment_image"><img src="' . $oComment->profile_image . '" width="32" height="32" /></div>';
	
	$sHtml .= '<div class="app_comment_body"><a href="#" class="app_comment_link">' . $oComment->profile_full_name . '</a> ' . $oComment->text . '<div class="app_comment_time">' . $oComment->post_convert_time . '</div></div>';
	
	$sHtml .= '</div>';

	return $sHtml;
}

if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'add')
{
	$aExtra = explode('|', $_REQUEST['extra']);
	$oComment = App::api('comment.add', array('text' => $_REQUEST['text'], 'module' => $aExtra[0], 'id' => $aExtra[1]));
	if (isset($oComment->profile_user_id))
	{
		echo 'var comments = parseInt($(\'#app_comment_count_' . $aExtra[0] . '' . $aExtra[1] . '\').html());';
		echo '$(\'#app_comment_count_' . $aExtra[0] . '' . $aExtra[1] . '\').html((comments + 1));';
		echo '$(\'#app_comment_body_' . $aExtra[0] . '' . $aExtra[1] . '\').append(\'<div class="app_cmt_tmp_' . $aExtra[0] . '' . $aExtra[1] . '">' . App::cleanJs(appBuildComment($oComment)) . '</div>\');';
	}
	exit;
}

$oComments = App::api('comment.get', array('module' => $_GET['module'], 'id' => $_GET['id']));

$iMax = 5;
$iCnt = 0;
$sHtml = '';
$bHasHiddenContent = false;

if (count($oComments) > $iMax)
{
	$sHtml .= '<a href="#" class="app_comment_view_all">View All ' . count($oComments) . ' Comments</a><div class="app_comment_hidden">';
}

foreach ($oComments as $oComment)
{
	$iCnt++;
	if ($iCnt === (count($oComments) - $iMax))
	{
		$sHtml .= '</div>';		
	}
	$sHtml .= appBuildComment($oComment, $iCnt);
}

echo '$(\'.app_cmt_tmp_' . $_GET['module'] . '' . $_GET['id'] . '\').remove();';
echo '$(\'#app_item_comment_body_' . $_GET['module'] . '' . $_GET['id'] . '\').prepend(\'<div class="app_cmt_tmp_' . $_GET['module'] . '' . $_GET['id'] . '">' . App::cleanJs($sHtml) . '</div<\').show();';
echo '$(\'#app_item_comment_body_' . $_GET['module'] . '' . $_GET['id'] . '\').find(\'.app_comment_text:first\').focus();';
echo '$App.behavior();';

exit;

?>