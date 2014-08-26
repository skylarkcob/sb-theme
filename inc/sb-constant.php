<?php
if ( !defined( 'ABSPATH' ) ) exit;

if(SB_WP::bbp_installed()) {
	define("SB_BBP_AVATAR_SMALL", 20);
}

define("SB_USER_POST_POINT", 10);
define("SB_USER_COMMENT_POINT", 5);
define("SB_TIME_BETWEEN_POST", 2);
define("SB_POST_CHARACTER_LIMIT", 300);
define("SB_POST_IMAGE_LIMIT", 4);
define("SB_SPAM_URL_LEN", 50);
define("SB_SPAM_LINK_COUNT", 5);