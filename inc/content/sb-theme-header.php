<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php SB_Theme::the_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php SB_Theme::the_favicon_html(); ?>
    <?php SB_Theme::the_date_meta_html(); ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!-- // Theme is created by SB Team - laidinhcuongvn@gmail.com - ladiuvn@gmail.com - http://sauhi.com - http://hocwp.net -->
<div class="site-container sb-site">