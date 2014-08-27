<?php
	if(Phpfox::getLib('module')->getFullControllerName() == 'core.index-visitor')
	{   return false;
		Phpfox::getLib('template')->setHeader(array(
			'theme.js' => 'module_user',
			'theme.css' => 'module_user',
		));
	}
?>