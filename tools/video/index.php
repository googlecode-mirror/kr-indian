<?php

define('PHPFOX_DIR', dirname(__FILE__) . '/');

error_reporting(E_ALL);
set_time_limit(0);
ini_set('memory_limit', '200M');

date_default_timezone_set('GMT');

if (!file_exists(PHPFOX_DIR . 'settings.php'))
{
	exit('Can\'t find "settings.php".');
}

if (PHP_SAPI == 'cli')
{
	$_SERVER['REMOTE_ADDR'] = '';
	$_SERVER['HTTP_HOST'] = '';
}

class PHPFox
{
	private static $_mReturn = null;
	private static $_aSettings = array();
	private static $_bPassed = true;
	private static $_sErrorMsg = '';	
	private static $_bIsJson = false;
	private static $_bIsFlashUpload = false;
	private static $_bIsInline = false;
		
	public static function run()
	{	
		$_SETTING = array();
		require_once(PHPFOX_DIR . 'settings.php');
		
		self::$_aSettings = $_SETTING;
		
		$sAction = '_upload';
		if (isset($_REQUEST['action']) && method_exists('PHPFox', '_' . $_REQUEST['action']))
		{
			$sAction = '_' . $_REQUEST['action'];
		}
		
		if (PHP_SAPI == 'cli' && !empty($_SERVER['argv']) && isset($_SERVER['argv'][1]) && method_exists('PHPFox', '_' . $_SERVER['argv'][1]))
		{
			$sAction = '_' . $_SERVER['argv'][1];
		}
		
		$hConnection = mysql_connect(self::param('_V_DB_HOST'), self::param('_V_DB_USER'), self::param('_V_DB_PASS'));
		if (!$hConnection)
		{
			self::fail(mysql_error(), true);
		}
		else
		{
			if (!mysql_select_db(self::param('_V_DB_NAME'), $hConnection))
			{
				self::fail(mysql_error(), true);
			}
		}
		
		self::$sAction();
		if (!self::$_bPassed)
		{
			if (self::$_bIsJson)
			{
				echo json_encode(array('error' => true, 'output' => self::$_sErrorMsg));	
			}
			else
			{
				$sParent = 'window.parent.';
				if (self::$_bIsFlashUpload)
				{
					$sParent = '';
				}
				$sReturn = $sParent . '$(\'#js_upload_inner_form\').show();';
				$sReturn .= $sParent . '$(\'#js_video_process\').hide();';
				if (!self::$_bIsFlashUpload)
				{
					echo '<script type="text/javascript">';
				}
				
				if (self::$_bIsInline)
				{
					echo 'window.parent.$Core.resetActivityFeedError(\'' . addslashes(self::$_sErrorMsg) . '\');';
				}
				else 
				{
					echo '' . $sReturn . ' ' . $sParent . 'alert(\'' . addslashes(self::$_sErrorMsg) . '\');';
				}
				
				if (!self::$_bIsFlashUpload)
				{
					echo '</script>';
				}
			}	
		}	
		else
		{
			if (self::$_bIsJson)
			{
				echo json_encode(array('error' => false));
			}
		}	
	}
	
	public static function param($sParam)
	{
		return self::$_aSettings[$sParam];
	}
	
	public static function send($sAction, $aExtras = array())
	{
		$oCurl = curl_init();

		$aPosts = array(
				'_v_secret' => self::param('_V_SECRET_KEY'),
				'_v_action' => $sAction
			);
		
		if (!empty($aExtras))
		{
			$aPosts = array_merge($aExtras, $aPosts);
		}
		
		$sPost = '';
		foreach ($aPosts as $sKey => $sValue)
		{
			$sPost .= '&' . $sKey . '=' . $sValue . '';
		}
		
		curl_setopt($oCurl, CURLOPT_URL, self::param('_V_MODULE_URL'));
		curl_setopt($oCurl, CURLOPT_HEADER, 0);	
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($oCurl, CURLOPT_POST, 1);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sPost);
		curl_setopt($oCurl, CURLOPT_USERAGENT, 'PHPFox Video Conversion');
		self::$_mReturn = json_decode(curl_exec($oCurl));
		curl_close($oCurl);

		return (self::$_mReturn->fail ? false : true);
	}
	
	public static function fail($sMsg)
	{
		self::$_bPassed = false;
		self::$_sErrorMsg = $sMsg;
		
		return false;
	}
	
	public static function exec($sCmd)
	{
		echo shell_exec($sCmd);
	}
	
	private static function _convert()
	{
		ignore_user_abort(true);
		
		$sLockFile = PHPFOX_DIR . self::param('_V_UPLOAD_FOLDER') . 'cron.lock';
		if (file_exists($sLockFile))
		{
			return;
		}
		
		touch($sLockFile);
		
		self::send('getSettings');		
		
		$hQuery = mysql_query('SELECT * FROM v_upload WHERE in_process = 0');
		$aRow = mysql_fetch_array($hQuery, MYSQL_ASSOC);
		if (isset($aRow['upload_id']))
		{
			mysql_query('UPDATE v_upload SET in_process = 1 WHERE upload_id = ' . (int) $aRow['upload_id']);

			$sSource = PHPFOX_DIR . self::param('_V_UPLOAD_FOLDER') . $aRow['file_name'];
			$sDestination = PHPFOX_DIR . self::param('_V_CONVERT_FOLDER') . $aRow['file_name'];
			$sImageLocation = PHPFOX_DIR . self::param('_V_CONVERT_FOLDER') . $aRow['file_name'] . '.jpg';
			$aSetting = (array) self::$_mReturn->output;
			
			foreach ($aSetting['video.covert_mp4_exec'] as $sExecCode)
			{
				if (!file_exists(str_replace('.flv', '.mp4', $sDestination)))
				{
					self::exec(str_replace(array('{SOURCE}', '{DESTINATION}'), array($sSource, $sDestination . '.mp4'), $sExecCode));
				}
			}
			
			foreach ($aSetting['video.covert_webm_exec'] as $sExecCode)
			{
				if (!file_exists(str_replace('.flv', '.webm', $sDestination)))
				{
					self::exec(str_replace(array('{SOURCE}', '{DESTINATION}'), array($sSource, $sDestination . '.webm'), $sExecCode));
				}
			}
			
			foreach ($aSetting['video.covert_ogg_exec'] as $sExecCode)
			{
				if (!file_exists(str_replace('.flv', '.ogg', $sDestination)))
				{
					self::exec(str_replace(array('{SOURCE}', '{DESTINATION}'), array($sSource, $sDestination . '.ogg'), $sExecCode));
				}
			}
			
			foreach ($aSetting['video.covert_mp4_image'] as $sExecCode)
			{
				if (!file_exists(sprintf($sImageLocation, '')))
				{
					self::exec(str_replace(array('{SOURCE}', '{DESTINATION}'), array($sDestination . '.mp4', $sImageLocation), $sExecCode));
				}
			}			
			
			$aPing = array(
						'id' => $aRow['upload_id'],
						'name' => $aRow['file_name'],
						'url' => md5(self::param('_V_THIS_URL'))			
					);
			
			self::send('completed', $aPing);
			
			print_r(self::$_mReturn);
			
			mysql_query('DELETE FROM v_upload WHERE upload_id = ' . (int) $aRow['upload_id']);
			
		 	unlink($sSource);
		}
		
		unlink($sLockFile);
	}
	
	private static function _check()
	{
		self::$_bIsJson = true;
		if (empty($_POST['_v_secret']) || empty($_POST['_v_id']))
		{
			return self::fail('SECRET and/or ID is empty.', true);	
		}
		
		if ($_POST['_v_secret'] != self::param('_V_SECRET_KEY'))
		{
			return self::fail('SECRET is not valid.', true);	
		}
		
		$hQuery = mysql_query('SELECT * FROM v_upload WHERE upload_id = ' . (int) $_POST['_v_id']);
		$aUpload = mysql_fetch_array($hQuery, MYSQL_ASSOC);
		if (!isset($aUpload['upload_id']))
		{
			return self::fail('Unable to find the video you wish to upload.', true);
		}
	}
	
	private static function _upload()
	{
		if (isset($_FILES['Filedata']) && !isset($_FILES['video']))
		{
			$_FILES['video'] = $_FILES['Filedata'];
			self::$_bIsFlashUpload = true;
		}
		
		if (isset($_POST['val']['video_inline']))
		{
			self::$_bIsInline = true;
		}
		
		if (empty($_FILES['video']))
		{
			return self::fail('Upload failed. VIDEO array is empty.');
		}
		
		if (empty($_POST['_v_hash']))
		{
			return self::fail('Upload failed. Missing hash.');
		}
		
		if (!self::send('canUpload', array('hash_id' => $_POST['_v_hash'])))
		{
			return self::fail('You are unable to upload a video.');
		}				
		
		$sFilename = strtolower($_FILES['video']['name']);
		$aExts = preg_split("/[\/\\.]/", $sFilename);
		$iCnt = count($aExts)-1;		
		$sExt = strtolower($aExts[$iCnt]);
		
		if (!in_array($sExt, self::param('_V_EXTS')))
		{
			return self::fail('Not a valid file extension. We accept: ' . implode(',', self::param('_V_EXTS')));
		}
		
		$sFileName = md5($_FILES['video']['name'] . $_FILES['video']['tmp_name'] . uniqid()) . '.' . $sExt;
		if (move_uploaded_file($_FILES['video']['tmp_name'], PHPFOX_DIR . self::param('_V_UPLOAD_FOLDER') . $sFileName))
		{
			$sPosts = '';
			foreach ($_POST as $sKey => $mValue)
			{
				if (is_array($mValue))
				{
					foreach ($mValue as $sV => $mV)
					{
						if (is_array($mV))
						{
							foreach ($mV as $mV1 => $mV2)
							{
								$sPosts .= '&' . $sKey . '[' . $sV . '][' . $mV1 . ']=' . addslashes($mV2);
							}	
							continue;
						}
						$sPosts .= '&' . $sKey . '[' . $sV . ']=' . addslashes($mV);
					}
					continue;
				}
				$sPosts .= '&' . $sKey . '=' . addslashes($mValue);
			}
			
			foreach ($_FILES['video'] as $sKey => $mValue)
			{
				$sPosts .= '&video[' . $sKey . ']=' . $mValue;
			}
			$sPosts .= '&video[ext]=' . $sExt;
			$sPosts .= '&_v[url]=' . md5(self::param('_V_THIS_URL'));
			
			mysql_query('INSERT INTO v_upload SET file_name = \'' . $sFileName . '\', time_stamp = \'' . time() . '\'');
			
			$sPosts .= '&_v[id]=' . mysql_insert_id() . '';
			
			$sCall = '$.ajaxCall(\'video.newUploadComplete\', \'' . $sPosts . '\');';
			if (self::$_bIsFlashUpload)
			{
				echo $sCall;
			}
			else
			{
				echo '<script type="text/javascript">document.domain = "' . self::param('_V_JS_URL') . '"; window.parent.' . $sCall . '</script>';
			}
		}
		else
		{
			return self::fail('Unable to upload this file.');
		}
	}
}

PHPFox::run();

?>