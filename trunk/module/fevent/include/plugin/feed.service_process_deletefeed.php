<?php

if (Phpfox::getLib('request')->get('module') == 'fevent')
{
	$afevent = Phpfox::getService('fevent')->getForEdit($aFeed['parent_user_id'], true);
	if (isset($afevent['fevent_id']) && $afevent['user_id'] == Phpfox::getUserId())
	{
		define('PHPFOX_FEED_CAN_DELETE', true);
	}
}

?>
