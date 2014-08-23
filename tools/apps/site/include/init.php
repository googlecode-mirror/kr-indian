<?php
/* @version $Id: init.php 5112 2013-01-11 06:56:25Z Raymond_Benc $ */

// Enable error reporting to help us debug issues
error_reporting(E_ALL);

// Start PHP sessions
session_start();

define('APP_', true);

if (!file_exists(APP_DIR . 'include/config.php'))
{
	exit('Config file is missing: include/config.php');
}

// Default time to GMT
if (function_exists('date_default_timezone_set'))
{
	date_default_timezone_set('GMT');

	define('APP_TIME', time());
}
else
{
	define('APP_TIME', strtotime(gmdate("M d Y H:i:s", time())));
}

header('Content-type: text/html; charset=utf-8');

require_once(APP_DIR . 'include/config.php');
require_once(APP_DIR . 'include/classess/app.class.php');

/**
 * PHP print_r Data so its readable
 *
 * @see print_r()
 * @param mixed $mInfo Can be any sort of type that will be outputed by print_r()
 */
function d($mInfo, $bVarDump = false)
{
	$bCliOrAjax = (PHP_SAPI == 'cli');
	(!$bCliOrAjax ? print '<pre style="text-align:left; padding-left:15px;">' : false);
	($bVarDump ? var_dump($mInfo) : print_r($mInfo));
	(!$bCliOrAjax ? print '</pre>' : false);
}

/**
 * Print DATA
 */
function p()
{
	$aArgs = func_get_args();
	$bCliOrAjax = (PHP_SAPI == 'cli');
	foreach($aArgs as $sStr)
	{
		print ($bCliOrAjax ? '' : '<pre>') . "{$sStr}" . ($bCliOrAjax ? "\n" : '</pre><br />');
	}
}

?>