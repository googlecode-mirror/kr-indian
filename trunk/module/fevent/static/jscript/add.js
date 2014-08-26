
$Behavior.addNewfevent = function()
{
	$('.js_fevent_change_group').click(function()
	{
		if ($(this).parent().hasClass('locked'))
		{
			return false;
		}
		
		aParts = explode('#', this.href);
		
		$('.js_fevent_block').hide();
		$('#js_fevent_block_' + aParts[1]).show();
		$(this).parents('.header_bar_menu:first').find('li').removeClass('active');
		$(this).parent().addClass('active');
		$('#js_fevent_add_action').val(aParts[1]);
	});
	
	$('.js_mp_category_list').change(function()
	{
		var iParentId = parseInt(this.id.replace('js_mp_id_', ''));
		
		$('.js_mp_category_list').each(function()
		{
			if (parseInt(this.id.replace('js_mp_id_', '')) > iParentId)
			{
				$('#js_mp_holder_' + this.id.replace('js_mp_id_', '')).hide();				
				
				this.value = '';
			}
		});
		
		$('#js_mp_holder_' + $(this).val()).show();
	});	
}