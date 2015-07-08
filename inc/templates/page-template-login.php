<?php
defined('ABSPATH') or die('Please do not pip me!');

SB_Theme::the_login_page_header();
sb_theme_get_content('content-page-login');
get_footer();