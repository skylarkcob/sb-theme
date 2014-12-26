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

    public static function get_visitor_ip() {
        return SB_Core::get_visitor_ip();
    }

    public static function get_visitor_operating_system() {
        return SB_PHP::get_operating_system();
    }

    public static function get_visitor_operating_sytem_code() {
        $os = self::get_visitor_operating_system();
        $os = SB_PHP::lowercase($os);
        $result = '';
        if(self::is_mobile()) {
            if(!class_exists('Mobile_Detect')) {
                require SB_THEME_LIB_PATH . '/mobile-detect/Mobile_Detect.php';
            }
            $detect = new Mobile_Detect();
            if($detect->is('AndroidOS')) {
                $result = 'android';
            } elseif($detect->is('BlackBerryOS')) {
                $result = 'blackberry';
            } elseif($detect->is('PalmOS')) {
                $result = 'palm';
            } elseif($detect->is('SymbianOS')) {
                $result = 'symbian';
            } elseif($detect->is('WindowsMobileOS')) {
                $result = 'windowsmobile';
            } elseif($detect->is('WindowsPhoneOS')) {
                $result = 'windowsphone';
            } elseif($detect->is('iOS')) {
                $result = 'ios';
            } elseif($detect->is('MeeGoOS')) {
                $result = 'meego';
            } elseif($detect->is('MaemoOS')) {
                $result = 'maemo';
            } elseif($detect->is('JavaOS')) {
                $result = 'java';
            } elseif($detect->is('webOS')) {
                $result = 'web';
            } elseif($detect->is('badaOS')) {
                $result = 'bada';
            } elseif($detect->is('BREWOS')) {
                $result = 'brew';
            }
        } else {
            if(SB_PHP::is_string_contain($os, 'windows')) {
                $result = 'windows';
            } elseif(SB_PHP::is_string_contain($os, 'linux')) {
                $result = 'linux';
            } elseif(SB_PHP::is_string_contain($os, 'mac')) {
                $result = 'mac';
            }
        }
        return $result;
    }

    public static function get_visitor_country_code_by_ip($ip_address) {
        return SB_PHP::get_country_code_by_ip($ip_address);
    }

    public static function get_visitor_country_by_ip($ip_address) {
        return SB_PHP::ip_info_geoplugin($ip_address, 'country');
    }

    public static function get_referer() {
        return wp_get_referer();
    }

    public static function get_visitor_country_code() {
        $ip_address = self::get_visitor_ip();
        return self::get_visitor_country_code_by_ip($ip_address);
    }

    public static function get_visitor_country() {
        $ip_address = self::get_visitor_ip();
        return self::get_visitor_country_by_ip($ip_address);
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