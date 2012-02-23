<?php $CI = & get_instance();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo (isset($meta_title) && !empty($meta_title)) ? $meta_title.' - '.$CI->config->config['site_setting']['basic']['site_name']: $CI->config->config['site_setting']['basic']['site_name']; ?></title>
	<link href="<?php echo css_base_url(); ?>css.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo css_base_url(); ?>jquery/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
	<?php if(isset($css_file) && $css_file):
		if(is_array($css_file)): 
			foreach($css_file as $css)
				echo '<link href="'.css_base_url().$css.'" rel="stylesheet" type="text/css" />';
		else: ?>
			<link href="<?php echo css_base_url().$css_file ?>" rel="stylesheet" type="text/css" />
		<?php endif; ?>
	<?php endif;?>
	<script type="text/javascript" src="<?php echo js_base_url(); ?>jquery-1.7.1.js"></script>
	<script type="text/javascript" src="<?php echo js_base_url(); ?>jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo js_base_url(); ?>common.js"></script>
	<?php if(isset($js_file_header) && !empty($js_file_header)):?>
		<?php 
		if(is_array($js_file_header)): 
			foreach($js_file_header as $js)
				echo '<script type="text/javascript" src="'.js_base_url().$js.'"></script>';
		else: ?>
		<script type="text/javascript" src="<?php echo js_base_url().$js_file_header ?>"></script>
		<?php endif; ?>
	<?php endif;?>
	<script>
	<!--
	site_url = '<?php echo site_url();?>/';
	base_url = '<?php echo base_url();?>';
	thisURL = '<?php echo $_SERVER['REQUEST_URI'];?>';
	-->
	</script>
</head>
<body>