<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_Spam {
    public static $texts = array(
        'Canvas Art Cheap',
        'gucci outlet',
        'Louis Vuitton',
        'smgtv.co.uk',
        'dancebrazil.org',
        'massive demand for this product',
        'дневной заработок',
        'lettersvsnumbers',
        'sivictxixxgn',
        'ealwibidnwfc',
        'air force mid',
        'sanimpex',
        'optime',
        'nike free herren',
        'egve',
        'air max nike',
        'snowsportsacademy',
        'jak rozpoznac',
        'loralaplante',
        'coach outlet',
        'rolka',
        'zqcgcmmmjrut',
        'maillot',
        'Louis',
        'ray ban',
        'wayfarers loose',
        'new balance',
        'deia Louis',
        'studdedic behavior',
        'cross body bag',
        'urge to merge',
        'temperate and cool regions'
    );

    public static $ips = array(
        '27.150.209.24',
        '117.26.252.135',
        '117.26.252.135',
        '27.159.254.106',
        '27.153.231.54',
        '94.153.11.24'
    );

    public static function check($content) {
        $content = SB_PHP::lowercase($content);
        foreach(self::$texts as $text) {
            $text = SB_PHP::lowercase($text);
            if(SB_PHP::is_string_contain($content, $text)) {
                return true;
            }
        }
        return false;
    }
}