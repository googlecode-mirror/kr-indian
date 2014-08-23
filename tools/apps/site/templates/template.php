<?php 
defined('APP_') or exit('NO DICE!');
/* @version $Id: template.php 5147 2013-01-16 09:43:00Z Raymond_Benc $ */ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
	<head>
		<title><?php echo App::getTitle(); ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo APP_SITE; ?>static/css/clear.css?v=<?php echo App::getVersion(); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo APP_SITE; ?>static/css/layout.css?v=<?php echo App::getVersion(); ?>" />
		<?php if (App::isMobile()): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo APP_SITE; ?>static/css/mobile.css?v=<?php echo App::getVersion(); ?>" />
		<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<?php endif; ?>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js?v=<?php echo App::getVersion(); ?>"></script>
		<script type="text/javascript" src="<?php echo APP_SITE; ?>static/js/main.js"></script>
	</head>
	<body>
		
		<div id="app_header">			
			<div class="app_holder">
				<div id="app_header_inner">
					<a href="<?php echo App::link(''); ?>" class="app_logo app_js" rel="index"><?php echo APP_SITE_NAME; ?></a>
				</div>
				<?php if (!App::isMobile()): ?>
				<a href="<?php echo App::link('profile/' . App::user()->user_name); ?>" class="app_header_user"><div style="background-image:url('<?php echo App::user()->photo_50px_square; ?>');"></div><?php echo App::user()->full_name; ?></a>
				<?php endif; ?>
			</div>
		</div>
		
		<div class="app_holder">
			<div id="app_body">
			
				<?php if (App::getToken()): ?>
				<div id="app_body_left">
					<div class="app_menu">
						<ul>
							<?php echo App::getMenu(); ?>
						</ul>
					</div>			
				</div>
				<div id="app_body_content">
				<?php endif; ?>
					<div id="app_body_padding">
						<div id="app_error_msg"></div>
						<?php echo App::getView(); ?>
					</div>	
				<?php if (App::getToken()): ?>					
				</div>
				<?php endif; ?>
			</div>
			<div id="app_footer">				
				<div class="app_footer_menu">
					<ul>
						<?php if (App::getTotalApiCalls()): ?>
						<li><a href="#" class="app_live_call_show"><?php echo App::getTotalApiCalls(); ?> Live API Call(s)</a></li>
						<li>&middot;</li>
						<?php endif; ?>
						<li><a href="<?php echo App::link('aboutus'); ?>" class="app_js" rel="aboutus">About Us</a></li>
					</ul>
				</div>
				<?php echo APP_SITE_NAME; ?> &copy; <?php echo date('Y', App::getTime()); ?>
			</div>
		</div>
		
		<div class="app_dev_holder">
			<div class="app_holder">
				<?php foreach (App::getApiCalls() as $aApiCall): ?>
				<div class="app_dev">
					<div class="app_dev_method"><?php echo $aApiCall['method']; ?></div>
					<div class="app_dev_sep">POST</div>
					<div class="app_dev_post"><pre><?php print_r($aApiCall['post']); ?></pre></div>
					<div class="app_dev_sep">RETURN</div>
					<div class="app_dev_return"><pre><?php print_r($aApiCall['return']); ?></pre></div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		
		<script type="text/javascript">
			$App.init({
				home: '<?php echo APP_SITE; ?>',
				mobile: <?php echo (App::isMobile() ? 'true' : 'false'); ?>
			});
		</script>
	</body>
</html>