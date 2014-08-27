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
?>