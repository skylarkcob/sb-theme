<?php
defined('ABSPATH') or die('Please do not pip me!');

$GLOBALS['sb_theme'] = array(
    'version' => SB_THEME_VERSION
);

if(SB_Tool::use_ads_system()) {
    $GLOBALS['sb_theme_ads_items'] = array();
}