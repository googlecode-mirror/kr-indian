<?php

class App
{
	private static $_sToken = '';
	private static $_sUrl = '';
	
	public static function init($sUrl)
	{		
		if (session_id() == '') 
		{
			session_start();
		}
		
		self::$_sUrl = $sUrl;
		
		if (!empty($_SESSION['token']))
		{
			self::$_sToken = $_SESSION['token'];
		}
		else
		{
			$oToken = json_decode(file_get_contents($sUrl . 'token.php?key=' . $_GET['key']));
		
			if (!isset($oToken->token))
			{
				exit('Not able to create a token.');
			}
			else
			{
				self::$_sToken = $oToken->token;
				$_SESSION['token'] = $oToken->token;
			}
		}		
	}
	
	public static function printHeader()
	{
		echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>';
		echo '<link rel="stylesheet" type="text/css" href="' . self::$_sUrl . 'static/style.php" />';
		echo '<script type="text/javascript">';
		echo '$(document).ready(function(){';		
		echo '$(\'body\').append(\'<iframe id="crossdomain_frame" src="' . self::$_sUrl . 'static/crossdomain.php?height=\' + document.body.scrollHeight + \'&nocache=\' + Math.random() + \'" height="0" width="0" frameborder="0"></iframe>\');';
		echo '});';
		echo '</script>';	
	}

	/**
	 * Run all requests to the API server
	 * @param string $sMethod Method name
	 * @param array $aParams Array of anything you need to pass to the API server.
	 * @return object
	 */
	public static function cmd($sMethod, $aPost = array())
	{
		// Build the request string we are going to POST to the API server. We include some of the required params.
		$sPost = 'token=' . self::$_sToken . '&method=' . $sMethod;
		foreach ($aPost as $sKey => $sValue)
		{
			$sPost .= '&' . $sKey . '=' . $sValue;
		}

		// Start curl request.
		$hCurl = curl_init();
			
		curl_setopt($hCurl, CURLOPT_URL, self::$_sUrl . 'api.php');
		curl_setopt($hCurl, CURLOPT_HEADER, false);
		curl_setopt($hCurl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($hCurl, CURLOPT_SSL_VERIFYPEER, false);
			
		curl_setopt($hCurl, CURLOPT_POST, true);
		curl_setopt($hCurl, CURLOPT_POSTFIELDS, $sPost);

		// Run the exec
		$sData = curl_exec($hCurl);
			
		// Close the curl connection
		curl_close($hCurl);

		// Return the curl request and since we use JSON we decode it.
		return json_decode(trim($sData));
	}

	/**
	 * Gets users information.
	 * @staticvar array $aUser
	 * @param int $iUserId User ID#
	 * @return object
	 */
	public static function getUser($iUserId = 0)
	{
		static $aUser = array();

		if (!isset($aUser[$iUserId]))
		{
			$aUser[$iUserId] = self::cmd('user.getUser', ($iUserId ? array('user_id' => $iUserId) : array()));
		}

		return $aUser[$iUserId]->output;
	}

	public static function postStatus($sStatus, $iPrivacy = 0)
	{
		return (bool) self::cmd('user.updateStatus', array('user_status' => $sStatus, 'privacy' => $iPrivacy))->output;
	}
	
	public static function getFriends($iUserId = 0, $iPage = 0)
	{
		static $aUser = array();

		if (!isset($aUser[$iUserId]))
		{
			$aUser[$iUserId] = self::cmd('friend.getFriends', ($iUserId ? array('user_id' => $iUserId, 'page' => $iPage) : array('page' => $iPage)));
		}

		return $aUser[$iUserId]->output;
	}
}

?>