$Behavior.initViewfevent = function()
{
	var bDisable = true;
	if ($('.js_fevent_rsvp:checked').length < 1)
	{
		$('#btn_rsvp_submit').attr('disabled', 'disabled');
		$('.js_fevent_rsvp').click(function(){
			$('#btn_rsvp_submit').removeAttr('disabled','');
		});
	}	
}