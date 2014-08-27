<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo SB_WP::get_current_language(); ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php SB_Theme::title(); ?></title>
<?php if(SB_WP::is_mobile()) : ?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php endif; ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php $favicon = SB_WP::get_favicon_url(); ?>
<?php if(!empty($favicon)) : ?>
<link type="images/x-icon" href="<?php echo $favicon; ?>" rel="icon">
<?php endif; ?>
    <?php
    if(is_single() || is_page()) {
        printf('<meta content="%s" property="article:published_time">', get_the_date('c'));
        printf('<meta content="%s" property="article:modified_time">', get_the_modified_time('c'));
    }
    ?>
<?php wp_head(); ?>
</head>
<body <?php body_class('sbtheme'); ?>>
<!-- // Theme is created by SB Team - laidinhcuongvn@gmail.com - ladiuvn@gmail.com - http://sauhi.com - http://hocwp.net -->
<div class="site-container sb-site">
    <?php SB_Theme::mobile_sidebar(); ?>