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
}