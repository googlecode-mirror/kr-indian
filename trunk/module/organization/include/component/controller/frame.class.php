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
 * @package 		Phpfox_Component
 * @version 		$Id: frame.class.php 4594 2012-08-14 06:34:45Z Raymond_Benc $
 */
class organization_Component_Controller_Frame extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if (($aVals = $this->request()->getArray('val')))
		{
			if (($this->request()->get('widget_id') ? Phpfox::getService('organization.process')->updateWidget($this->request()->get('widget_id'), $this->request()->get('val')) : Phpfox::getService('organization.process')->addWidget($this->request()->get('val'))))
			{
				$aVals = $this->request()->get('val');
				echo '<script type="text/javascript">window.parent.location.href = \'' . Phpfox::getLib('url')->makeUrl('organization.add.widget', array('id' => $aVals['organization_id'])) . '\';</script>';
			}
			else
			{
				echo '<script type="text/javascript">';
				echo 'window.parent.$(\'#js_organization_widget_error\').html(\'<div class="error_message">' . implode('', Phpfox_Error::get()) . '</div>\');';
				echo '</script>';
			}
		}
		exit;
	}
}
?>