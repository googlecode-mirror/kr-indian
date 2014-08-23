<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			natio
 * @package 		PhpFox
 * @version 		$Id: phpfox-cdn.php 5614 2013-04-08 11:38:14Z Miguel_Espinoza $
 */

set_time_limit(0);

if (!file_exists('./phpfox-cdn-setting.php'))
{
	exit('Missing config file.');
}

require_once('./phpfox-cdn-setting.php');

final class PHPFOX_CDN
{
	static $_bPass = true;
	static $_aMsg = array();
	static $_sDebug = '';
	
	public static function error($iErrorCode, $sMsg)
	{
		self::$_bPass = false;		
		self::$_aMsg['error_code'] = $iErrorCode;
		self::$_aMsg['error'] = $sMsg;
	}
	
	public static function debug($sDebug)
	{
		self::$_sDebug = $sDebug;
	}
	
	public static function isPassed()
	{
		return self::$_bPass;
	}
	
	public static function output()
	{
		echo json_encode(array('pass' => self::$_bPass, 'output' => self::$_aMsg));
		/*
		$hFile = fopen('./upload.log', 'a+');
		fwrite($hFile, self::$_sDebug . "\n");
		fclose($hFile);
		* 
		*/
	}
}

if (empty($_POST['action']))
{
	PHPFOX_CDN::error('MISSING_ACTION', 'Missing action.');
}
elseif (empty($_POST['cdn_key']))
{
	PHPFOX_CDN::error('MISSING_KEY', 'Missing CDN key.');
}
elseif ($_POST['cdn_key'] != CDN_KEY)
{
	PHPFOX_CDN::error('KEY_NOT_MATCH', 'Key does not match.');	
}
elseif (is_writable(CDN_FOLDER) != true)
{
    PHPFOX_CDN::error('NOT_WRITEABLE', 'The target folder is not writeable.');	
}
else
{
	switch ($_POST['action'])
	{
		case 'upload':
			if (empty($_FILES['upload']))
			{
				PHPFOX_CDN::error('NOTHING_WAS_UPLOADED', 'Nothing was uploaded.');
			}
			elseif (empty($_POST['file_name']))
			{
				PHPFOX_CDN::error('MISSING_FILE_NAME', 'Missing the filename of the file uploaded.');
			}
			else 
			{
				$sName = $_POST['file_name'];
				$sName = str_replace("\\", '/', $sName);
				$aParts = explode('.', $sName);

				move_uploaded_file($_FILES['upload']['tmp_name'], '' . CDN_FOLDER . '/' . md5($aParts[0]) . '.' . $aParts[1]);				

				// PHPFOX_CDN::debug('Moved ' . $_FILES['upload']['tmp_name'] . ' to ../' . preg_replace('/\/server\/([0-9]+)\//i', '\\1', $_SERVER['REQUEST_URI']) . '/' . md5($aParts[0]) . '.' . $aParts[1] . ' (' . $sName . ')');
			}		
			break;
	}
}

PHPFOX_CDN::output();

?>