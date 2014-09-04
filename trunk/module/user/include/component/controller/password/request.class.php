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
 * @package  		Module_User
 * @version 		$Id: request.class.php 3113 2011-09-16 07:49:14Z Raymond_Benc $
 */
class User_Component_Controller_Password_Request extends Phpfox_Component 
{
	/**
	 * Process the controller
	 *
	 */
	public function process()
	{
		if (Phpfox::isUser())
		{
			$this->url()->send('');
		}			
		
		if ($sEmail = $this->request()->get('email'))
		{			
			if (Phpfox::getService('user.password')->requestPassword($sEmail))
			{
                if(PHPFOX_IS_AJAX)
                {
                    echo '$("#" + tb_get_active() + " .js_box_content").html("'.Phpfox::getPhrase('user.password_request_successfully_sent_check_your_email_to_verify_your_request').'");setTimeout("tb_remove();",3000);';
                    die();
                }
				$this->url()->send('user.password.request', null, Phpfox::getPhrase('user.password_request_successfully_sent_check_your_email_to_verify_your_request'));
			}
            if(PHPFOX_IS_AJAX)
            {
                $aErrors = Phpfox_Error::get();
                Phpfox::getLib('ajax')->alert(implode(' ',$aErrors));
                die();
            }
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('user.password_request'))->setBreadcrumb(Phpfox::getPhrase('user.password_request'));
	}
}

?>