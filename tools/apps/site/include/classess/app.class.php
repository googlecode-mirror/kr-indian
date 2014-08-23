<?php
/* @version $Id: app.class.php 5147 2013-01-16 09:43:00Z Raymond_Benc $ */

class App
{
	private static $_sAction = 'index';
	private static $_bIsAjax = false;
	private static $_iPage = 0;	
	private static $_sTitle = '';
	private static $_aParams = array();
	private static $_oUser = null;
	private static $_iTotalCalls = 0;
	private static $_aCallHistory = array();
	
	public static function run()
	{	
		// phpFox site has returned a key, which we will use to auth the user.
		if (isset($_GET['auth']) && !empty($_GET['key']))
		{
			// Get the token
			$oToken = json_decode(file_get_contents(APP_BASE . 'token.php?key=' . $_GET['key']));
				
			if (!empty($oToken->token))
			{
				$_SESSION['auth_token'] = $oToken->token;
		
				self::send('installed');
			}
		}
		
		if (!empty($_REQUEST['action']))
		{
			$_REQUEST['action'] = ltrim($_REQUEST['action'], '/');
			$aActionParts = explode('/', $_REQUEST['action']);
			foreach ($aActionParts as $iKey => $sActionPart)
			{
				if ($iKey == 0)
				{
					continue;
				}
				
				self::$_aParams['req' . $iKey] = $sActionPart;
			}
			
			$sAction = $aActionParts[0];
			
			self::$_sAction = preg_replace('/[^\da-z]/i', '\\1', $sAction);
		}		
		
		if (!empty($_GET['cmdajax']))
		{
			self::$_bIsAjax = true;
		}
		
		if (isset($_GET['page']))
		{
			self::$_iPage = (int) $_GET['page'];
		}		
		
		self::$_oUser = self::api('user.get', array(), true);
		
		if (self::getToken())
		{
			$sActionFile = APP_DIR . 'controller/' . self::$_sAction . '.php';
			if (file_exists($sActionFile))
			{
				require_once ($sActionFile);
			}
			else
			{
				self::$_sAction = 'index';
			}
		}
		else
		{
			self::$_sAction = 'login';
			require_once(APP_DIR . 'controller/login.php');
		}		
				
		if (self::$_bIsAjax)
		{
			self::getView();	
			
			$sContent = ob_get_contents();
			
			ob_clean();
			
			echo json_encode(array('title' => self::getTitle(), 'html' => $sContent));
		}
		else
		{			
			require_once(APP_DIR . 'templates/template.php');
		}
	
		ob_end_flush();
	}
	
	public static function isAjax()
	{
		return self::$_bIsAjax;
	}
	
	public static function isMobile()
	{
		$bIsMobile = false;
		$sAgent = $_SERVER['HTTP_USER_AGENT'];
		
		if (preg_match('/android/i', $sAgent))
		{
			$bIsMobile = true;
			$sAgent = 'Android';
		}
		elseif (preg_match('/opera mini/i', $sAgent))
		{
			$bIsMobile = true;
			$sAgent = 'Opera Mini';
		}
		elseif (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|fennec|plucker|xiino|blazer|elaine)/i', $sAgent))
		{
			$bIsMobile = true;
			$sAgent = 'Palm';
		}
		elseif (preg_match('/blackberry/i', $sAgent))
		{
			$bIsMobile = true;
			$sAgent = 'Blackberry';
		}
		elseif (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|windows phone)/i', $sAgent))
		{
			$bIsMobile = true;
			$sAgent = 'Windows Smartphone';
		}
		elseif (preg_match('/Version\/(.*) Safari\/(.*)/i', $sAgent, $aMatches) && isset($aMatches[1]))
		{
			if (preg_match("/iPhone/i", $sAgent) || preg_match("/ipod/i", $sAgent))
			{
				$aParts = explode(' ', trim($aMatches[1]));
				$sAgent = 'Safari iPhone ' . $aParts[0];
				$bIsMobile = true;
			}
			else
			{
				$sAgent = 'Safari ' . $aMatches[1];
			}
		}
		elseif (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i', $sAgent))
		{
			$bIsMobile = true;
		}		
		
		return $bIsMobile;
	}
	
	public static function user()
	{
		return self::$_oUser;
	}
	
	public static function req($iNum)
	{
		return (isset(self::$_aParams['req' . $iNum]) ? self::$_aParams['req' . $iNum] : '');
	}
	
	public static function getView()
	{	
		$sActionFile = APP_DIR . 'views/' . self::$_sAction . '.php';
		if (file_exists($sActionFile))
		{
			require_once ($sActionFile);
		}
	}
	
	public static function getTime()
	{
		return APP_TIME;
	}
	
	public static function link($sAction)
	{
		$sUrl = APP_SITE;
		if (!empty($sAction))
		{
			$sUrl .= (APP_REWRITE ? '' : '?action=') . $sAction;
		}
		
		return $sUrl;
	}
	
	public static function send($sAction)
	{
		ob_clean();
		
		header('Location: ' . APP_SITE . (APP_REWRITE ? '' : '?action=') . $sAction);
		exit;
	}
	
	public static function getToken()
	{
		if (empty($_SESSION['auth_token']))
		{
			return false;
		}	
		
		return $_SESSION['auth_token'];
	}
	
	public static function getVersion()
	{
		return self::getTime();
	}
	
	public static function getMenu()
	{
		$aMenus = array(
				'Activity Feed',
				'' => 'Status Updates',
				'photos' => 'Photos',
				'videos' => 'Videos'
				);
		
		$sLinks = '';
		foreach ($aMenus as $sLink => $sPhrase)
		{
			if (is_numeric($sLink))
			{
				$sLinks .= '<li class="app_menu_header">' . $sPhrase . '</li>';
				continue;
			}
			$sLinks .= '<li><a href="' . self::link($sLink) . '" class="app_js' . (((self::$_sAction == $sLink) || (empty($sLink) && self::$_sAction == 'index')) ? ' active' : '') . '" rel="' . $sLink . '">' . $sPhrase . '</a></li>';
		}
		
		return $sLinks;
	}
	
	public static function item($aParams = array(), $bReturn = false)
	{
		$sHtml = '<div class="app_item">';
		
		$sHtml .= '<div class="app_item_time">' . $aParams['time'] . '</div>';
		
		$sHtml .= '<div class="app_item_left"><a href="#"><img src="' . $aParams['profile_image'] . '" /></a></div>';		
		$sHtml .= '<div class="app_item_content">';
		
		$sHtml .= '<a href="#" class="app_item_title">' . $aParams['display_name'] . '</a>';
		
		$sHtml .= '<div class="app_item_data">' . $aParams['content'] . '</div>';
		
		$sHtml .= '<div class="app_item_comments">';
		
		$sHtml .= '<div class="app_item_cmt_total" id="app_comment_count_' . $aParams['module'] . '' . $aParams['id'] . '">' . $aParams['comments'] . '</div>';
		$sHtml .= '<a href="#do=get&amp;module=' . $aParams['module'] . '&amp;id=' . $aParams['id'] . '" class="app_item_comments_link app_ajax_call" rel="comment">Comment</a>';
		
		
		$sHtml .= '<div id="app_item_like_' . $aParams['module'] . $aParams['id'] . '">';
		$sHtml .= '<div class="app_item_likes">' . $aParams['likes'] . '</div>';
		if (isset($aParams['is_liked']))
		{
			$sHtml .= '<a href="#module=' . $aParams['module'] . '&amp;id=' . $aParams['id'] . '&amp;do=add" rel="like" class="is_like app_ajax_call app_item_likeit"' . (empty($aParams['is_liked']) ? '' : ' style="display:none;"') . '>Like</a>';
			$sHtml .= '<a href="#module=' . $aParams['module'] . '&amp;id=' . $aParams['id'] . '&amp;do=remove" rel="like" class="is_unlike app_ajax_call app_item_likeit"' . (!empty($aParams['is_liked']) ? '' : ' style="display:none;"') . '>Unlike</a>';
		}
		$sHtml .= '</div>';	
		
		$sHtml .= '</div>';
				
		$sHtml .= '<div class="app_item_comment_body" id="app_item_comment_body_' . $aParams['module'] . '' . $aParams['id'] . '">';
		$sHtml .= '<div id="app_comment_body_' . $aParams['module'] . '' . $aParams['id'] . '"></div>';
		$sHtml .= '<div class="app_comment"><div class="app_comment_image"><img src="' . App::user()->photo_50px_square . '" alt="" width="32" height="32" /></div>';		
		$sHtml .= '<div class="app_comment_body">';		
		$sHtml .= '<div><input type="hidden" class="app_comment_form" name="app_comment_form" value="' . $aParams['module'] . '|' . $aParams['id'] . '" /></div>';
		$sHtml .= '<input type="text" name="post[text]" placeholder="Write a comment..." class="app_comment_text" />';
		$sHtml .= '</div></div></div>';
		
		$sHtml .= '</div>';
		$sHtml .= '</div>';
		
		if ($bReturn)
		{
			return $sHtml;
		}
		
		echo $sHtml;
	}
	
	public static function pager($sAction)
	{
		$sHtml = '<a href="#page' . (self::$_iPage + 1) . '" id="app_pager" rel="' . $sAction . '">View More</a>';
		
		echo $sHtml;
	}
	
	public static function getTitle()
	{
		$sTitle = (empty(self::$_sTitle) ? APP_SITE_NAME : self::$_sTitle);
		
		return $sTitle;
	}
	
	public static function setTitle($sTitle)
	{
		self::$_sTitle = $sTitle;
	}
	
	public static function cleanJs($sContent)
	{
		$sContent = str_replace(array("\n", "\t"), '', $sContent);
		$sContent = str_replace('\\', '\\\\', $sContent);
		$sContent = str_replace('"', '\"', $sContent);
		
		return $sContent;
	}
	
	public static function getTotalApiCalls()
	{
		return self::$_iTotalCalls;	
	}	
	
	public static function getApiCalls()
	{
		return self::$_aCallHistory;
	}
	
	public static function api($sMethod, $aPost = array(), $bCache = false)
	{		
		// Build the request string we are going to POST to the API server. We include some of the required params.
		$sPost = 'token=' . self::getToken() . '&method=' . $sMethod . '&page=' . self::$_iPage;		
		foreach ($aPost as $sKey => $sValue)
		{
			$sPost .= '&' . $sKey . '=' . $sValue;
		}
	
		if ($bCache && isset($_SESSION['appcache_' . md5($sPost)]))
		{
			return $_SESSION['appcache_' . md5($sPost)];
		}
		
		self::$_iTotalCalls++;
		
		// p('PAGE: ' . self::$_iPage);
	
		// Start curl request.
		$hCurl = curl_init();
	
		// echo '<a href="' . APP_BASE . 'api.php?' . $sPost . '" target="_blank">View Source</a>';
	
		curl_setopt($hCurl, CURLOPT_URL, APP_BASE . 'api.php');
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
		$oJson = json_decode(trim($sData));
		
		/*
		if (isset($oJson->output->error_id))
		{
			header('Location: ' . APP_BASE . 'auth.php?key=' . APP_ID);
			exit;
		}
		*/
		if (isset($oJson->api))
		{
			self::$_iPage = (int) $oJson->api->current_page;					
		}
		
		if ($bCache)
		{
			$_SESSION['appcache_' . md5($sPost)] = $oJson->output;
		}
		
		self::$_aCallHistory[] = array(
				'method' => $sMethod,
				'post' => $aPost,
				'return' => $oJson
				);
		
		return $oJson->output;
	}	
}

?>