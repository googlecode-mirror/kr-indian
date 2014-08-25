
$(document).ready(function(e){
    var sHeaderInfo = '<div class="header_info"><div class="header_info_content"><div class="header_info_left">OUR VISION</div><div class="header_line"></div><div class="header_info_right"><p>Healthier Earth, the first global social networking site based around volunteering to move Earth<br>forward and begin the New Golden Age on Earth founded on Love, Peace and Harmony for all. </p></div></div></div>';
    if($('#nb_body').length > 0){
        $('#nb_body').prepend(sHeaderInfo); 
    }
    else{
        $('#main_core_body_holder_guest').prepend(sHeaderInfo);
    }

    $('.user_register_holder').append('<div class="swap_bg"><img class="bg_image" src="'+oParams.sJsHome+'/file/pic/background.jpg"/></div>')
    $('.user_register_intro h1').text('Advancing Future Communities');
    $('.user_register_title').text('Sign up for free today!');
    $('#js_signup_block').prepend('<ul class="register_tab"><li class="active_tab_register" rel="individual_tab">Individual</li><li rel="organization_tab">Organization</li></ul>');
	$('.user_register_intro').append('<div class="bg_video"><iframe src="//www.youtube.com/embed/UGuPtmxeCNQ?rel=0&controls=0&showinfo=0" frameborder="0" allowfullscreen></iframe></div>');
	$('.swap_bg').css('min-height',($(window).height() - 60) + 'px');
	$('#js_signup_block div').addClass('register_panel').addClass('individual_tab');
	$('#js_signup_block').append('<div class="organization_tab register_panel"></div>');
	$('#js_signup_block').on('click','.register_tab li',function(e){
		$('.register_panel').hide();
		$('.register_tab li').removeClass('active_tab_register');
		$(this).addClass('active_tab_register');
		$('.' + $(this).attr('rel')).show();
	});
});