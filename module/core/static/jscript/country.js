
$Behavior.countryIsoChange = function()
{
	$('#country_iso').change(function()
	{
		var sChildValue = $('#js_country_child_id_value').val();
		var sExtra = '';
		$('#js_country_child_id').html('');
		$('#country_iso').after('<span id="js_cache_country_iso">' + $.ajaxProcess('no_message') + '</span>');
		if ($('#js_country_child_is_search').length > 0)
		{
			sExtra += '&country_child_filter=true';
		}		
		$.ajaxCall('core.getChildren', 'country_iso=' + this.value + '&country_child_id=' + sChildValue + sExtra, 'GET');
        
	});	
    
    $(document).on('change','#js_country_child_id_value',function(){
        var country_iso = $('#country_iso').val();
        var country_child_id = 0;
        if($('#js_country_child_id_value').length > 0){
            country_child_id = $('#js_country_child_id_value').val();
        }
        $('.loading_city').show();
        $.ajaxCall('community.getCityLocations','country_iso=' + country_iso + '&country_child_id=' + country_child_id);
    });
    
    $(document).on('change','#cb_city_location',function(){
        $('#new_city_location').val(''); 
        $('#new_city_location').hide(); 
    });
}