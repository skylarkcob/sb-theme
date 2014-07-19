<?php global $sb_language; ?>
<!DOCTYPE html>
<html lang="<?php echo $sb_language->get(); ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php SB_Theme::title(); ?></title>
<meta content="width=device-width,minimum-scale=1,maximum-scale=1" name="viewport">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php $options = SB_WP::option(); ?>
<?php $favicon = isset($options['favicon']) ? $options['favicon'] : ''; ?>
<?php if(!empty($favicon)) : ?>
<link type="images/x-icon" href="<?php echo $favicon; ?>" rel="icon">
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class('sbtheme'); ?>>
<!-- // Theme is created by SB Team - laidinhcuongvn@gmail.com - ladiuvn@gmail.com - http://sauhi.com - http://hocwp.net -->
<div class="site-container sb-site">