<?php
	if(Phpfox::getLib('module')->getFullControllerName() == 'core.index-visitor')
	{ 
		Phpfox::getLib('template')->setHeader(array(
			'theme.js' => 'module_user',
			'theme.css' => 'module_user',
		));
	}
    Phpfox::getLib('template')->setHeader(array(
        'font-awesome-4.2.0/css/font-awesome.min.css' => 'module_community'
    ));
    if(Phpfox::getLib('module')->getFullControllerName() == 'profile.index')
    {
        $aCurrentUser = Phpfox::getService('user')->get(Phpfox::getUserId());
        if($aCurrentUser['profile_organization_id'] != 0)
        {
            Phpfox::getLib('url')->send('organization'.'.'.$aCurrentUser['profile_organization_id']);
        }
    }
    if(Phpfox::getLib('module')->getFullControllerName() == 'user.register' && !PHPFOX_IS_AJAX)
    {
        Phpfox::getLib('url')->send('');
    }
?>