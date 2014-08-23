
var $App = {
	params: {},
		
	init: function(params){
		this.params = params;
		
		this.behavior();
	},	
	
	behavior: function(){	
		$('*').unbind();

		$('#app_pager').click(function(){
			
			var page = $(this).attr('href').replace('#page', '');
			var action = $(this).attr('rel');
			
			$App.pager(action, page);
			
			return false;
		});		
		
		$('.app_ajax_call').click(function(){
			
			var rel = $(this).attr('rel');
			var href = $(this).attr('href');

			href = href.replace('#', '').replace('&amp;', '&');
			if (href != ''){
				href = '&' + href;
			}
		
			$.ajax({
				type: 'GET',
				url: $App.params.home + 'index.php',
				dataType: 'script',	
				data: 'cmdajax=1&action=' + rel + href
			});			
						
			return false;
		});
		
		$('.app_comment_text').keyup(function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			 if(code == 13){
				var value = $(this).val();
				$(this).val('');
				$.ajax({
					type: 'POST',
					url: $App.params.home + 'index.php',
					dataType: 'script',	
					data: 'cmdajax=1&action=comment&do=add&text=' + value + '&extra=' + $(this).parents().find('.app_comment_form:first').val()
				});
				 
			   return false;
			 }			
		});
		
		$('.app_comment_view_all').click(function(){
			$(this).parent().find('.app_comment_hidden:first').show();
			return false;
		});
		
		$('.app_live_call_show').click(function(){
			$('.app_dev_holder').toggle();
			return false;
		});
		
		$('.app_view_photo').click(function(){
			var image = $(this).attr('href');
			var html = '';
			
			$('.app_image').remove();
			
			
			
			
			html += '<div class="app_image" onclick="$App.removeImage();">';
			
			html += '</div>';
			
			html += '<div class="app_image_holder" onclick="$App.removeImage();"><a href="#" onclick="$App.removeImage(); return false;"><img src="' + image + '" alt="" /></a></div>';
						
			$('body').prepend(html);
						
			$('.app_image').css({'z-index': '1000', 'position': 'fixed', 'top': '0px', 'left': '0px', height: getPageHeight() + 'px'});
			$('.app_image_holder').css({'top': getPageScroll()[1] + 'px'});
									
			return false;
		});
		
		if (!this.params.mobile){			
			$('.app_menu').css({
				'position': 'fixed',
				'width': $('#app_body_left').width()
			});
		}
		
		$('.app_form textarea').focus(function(){
			$(this).height('60px');
		});
		
		$('#app_form_button').click(function(){
			$('.app_form_process').show();
			$(this).attr('disabled', true);
			var value = $('#app_form_status').val();
			$.ajax({
				type: 'POST',
				url: $App.params.home + 'index.php',
				dataType: 'script',	
				data: 'cmdajax=1&action=' + $('#app_form_action').val() + '&status=' + value
			});			
			return false;
		});
		
		$('#app_form_photo_upload').change(function(){			
			$('#app_form_photo_upload_holder').hide();
			$('.app_form_hidden').show();
			$('.app_form_hidden textarea').focus();
		});
	},	
	
	removeImage: function()
	{
		$('.app_image').remove();
		$('.app_image_holder').remove();
	},
	
	pager: function(action, page){		
		$('#app_pager').remove();
		$.ajax({
				type: 'GET',
				url: $App.params.home + 'index.php',
				dataType: 'json',	
				data: 'cmdajax=true&action=' + action + '&page=' + page,
				success: function(data){
					$('#app_body_padding').append(data.html);
					
					$App.behavior();
				}
			});			
	},
	
	error: function(type, msg){
		
		switch (type){
			case 'photo':
				$('#app_form_photo_upload_holder').show().addClass('app_form_padding');
				break;
		}
		$('.app_form_process').hide();
		$('#app_error_msg').html('');
		$('#app_error_msg').html('<div class="app_error_msg">' + msg + '</div>');
	},
	
	addItem: function(type, id, html){

		$('.app_form_process').hide();
		$('#app_new_status_updates').prepend('<div id="' + id + '" class="app_item_new">' + html + '</div>');
		$('#app_form_button').attr('disabled', false);
		$('#app_form_status').val('');
		$('.app_form textarea').height('15px');
		$('#' + id + '').fadeIn('slow');
		
		switch (type){
			case 'photo':
				$('.app_form_hidden').hide();
				$('#app_form_photo_upload_holder').show().removeClass('app_form_padding');
				$('#app_form_photo_upload').val('');
				break;
		}
		
		$App.behavior();		
	}
};

function p(sMessage)
{
	if (typeof console === 'undefined' || typeof console.log == 'undefined')
	{			
		return false;
	}
	console.log(sMessage);
}

// getPageScroll() by quirksmode.com
function getPageScroll() {
  var xScroll, yScroll;
  if (self.pageYOffset) {
    yScroll = self.pageYOffset;
    xScroll = self.pageXOffset;
  } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
    yScroll = document.documentElement.scrollTop;
    xScroll = document.documentElement.scrollLeft;
  } else if (document.body) {// all other Explorers
    yScroll = document.body.scrollTop;
    xScroll = document.body.scrollLeft;
  }
  return new Array(xScroll,yScroll);
}

// Adapted from getPageSize() by quirksmode.com
function getPageHeight() {
  var windowHeight
  if (self.innerHeight) {	// all except Explorer
    windowHeight = self.innerHeight;
  } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
    windowHeight = document.documentElement.clientHeight;
  } else if (document.body) { // other Explorers
    windowHeight = document.body.clientHeight;
  }
  return windowHeight;
}