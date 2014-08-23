<?php
	if(Phpfox::getLib('module')->getFullControllerName() == 'core.index-visitor')
	{
		Phpfox::getLib('template')->setHeader(array(
			'theme.js' => 'module_user',
			'theme.css' => 'module_user',
		));
	}
?>