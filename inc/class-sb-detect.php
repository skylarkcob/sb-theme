<?php
class SB_Detect {
    public static function is_mobile() {
        if(!class_exists('Mobile_Detect')) {
            require SB_THEME_LIB_PATH . '/mobile-detect/Mobile_Detect.php';
        }
        $detect = new Mobile_Detect();
        return $detect->isMobile();
    }

    public static function is_browser($name) {
        $result = false;
        switch($name) {
            case 'ie':
                $result = SB_Browser::is_ie();
                break;
        }
        return $result;
    }

    public static function get_bots() {
        $result = '';
        $bots_list = array(
            'Abacho' => 'AbachoBOT',
            'Acoon' => 'Acoon',
            'Aesop' => 'AESOP_com_SpiderMan',
            'Ah-ha' => 'ah-ha.com crawler',
            'Euroseek' => 'Arachnoidea',
            'AlltheWeb' => 'FAST-WebCrawler',
            'SearchHippo' => 'Fluffy the spider',
            'Google' => 'Googlebot',
            'Gigablast' => 'Gigabot',
            'Yuntis' => 'Gulper',
            'Alexa' => 'ia_archiver',
            'LookSmart' => 'MantraAgent',
            'Microsoft' => 'MSN',
            'AltaVista' => 'Scooter',
            'Scrub The Web' => 'Scrubby',
            'Teoma' => 'Teoma_agent1',
            'WhatUSeek' => 'Winona',
            'Wisenut' => 'ZyBorg',
            'IBM' => 'Almaden',
            'NameProtect.com' => 'NameProtect',
            'DMOZ' => 'Robozilla',
            'Tracerlock.com' => 'Tracerlock',
            'W3C' => 'W3C_Validator',
            'WDG' => 'WDG_Validator',
            'Looksmart' => 'Zealbot',
            'Yahoo' => 'Slurp',
            'Bing' => 'bingbot',
            'MSN' => 'msnbot',
            'Yahoo' => 'yahoobot',
            'Google' => 'googlebot'
        );
        $regexp = '/' . implode('|', $bots_list) . '/';
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match($regexp, $ua, $matches)) {
           $result = array_search($matches[0], $bots_list);
        }
        return $result;
    }

    public static function is_bots() {
        $bots = self::get_bots();
        if(!empty($bots)) {
            return true;
        }
        return false;
    }
}