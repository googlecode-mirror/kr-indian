<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_fevent
 * @version 		$Id: phpfox.class.php 2518 2011-04-11 19:18:17Z Raymond_Benc $
 */
class Module_fevent
{
	public static $aTables = array(
		'fevent',
		'fevent_category',
		'fevent_category_data',
		'fevent_feed',
		'fevent_feed_comment',
		'fevent_invite',		
		'fevent_text'
	);
	
	public static $aInstallWritable = array(
		'file/pic/fevent/'
	);		
}

?>