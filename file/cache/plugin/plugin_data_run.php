<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (!PHPFOX_IS_AJAX && Phpfox::getUserBy(\'profile_page_id\') > 0)
		{
			$bSend = true;
			if (defined(\'PHPFOX_IS_PAGE_ADMIN\'))
			{
				$bSend = false;				
			}
			else
			{
				$aPage = Phpfox::getService(\'pages\')->getPage(Phpfox::getUserBy(\'profile_page_id\'));				
				$sReq1 = Phpfox::getLib(\'request\')->get(\'req1\');				
				if (empty($aPage[\'vanity_url\']))
				{
					if ($sReq1 == \'pages\')
					{
						// $bSend = false;
					}
				}
			}

			if ($bSend && !Phpfox::getService(\'pages\')->isInPage())
			{
				Phpfox::getLib(\'url\')->forward(Phpfox::getService(\'pages\')->getUrl($aPage[\'page_id\'], $aPage[\'title\'], $aPage[\'vanity_url\']));
			}
		} if(Phpfox::getLib(\'module\')->getFullControllerName() == \'core.index-visitor\')
	{
		Phpfox::getLib(\'template\')->setHeader(array(
			\'theme.js\' => \'module_user\',
			\'theme.css\' => \'module_user\',
		));
	} '; ?>