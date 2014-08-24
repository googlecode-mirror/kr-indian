<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Module
 * @version 		$Id: phpfox.class.php 5022 2012-11-13 08:05:06Z Raymond_Benc $
 */
class Module_organization
{
	public static $aDevelopers = array(
		array(
			'name' => 'Raymond_Benc',
			'website' => 'www.phpfox.com'
		)
	);
	
	public static $aTables = array(
		'organization',
		'organization_admin',
		'organization_category',
		'organization_claim',
		'organization_design_order',
		'organization_feed',
		'organization_feed_comment',
		'organization_invite',
		'organization_login',
		'organization_perm',
		'organization_shoutbox',
		'organization_signup',
		'organization_text',
		'organization_type',
		'organization_url',
		'organization_widget',
		'organization_widget_text'
	);
	
	public static $aInstallWritable = array(
			'file/pic/organization/'
	);	
}

?>