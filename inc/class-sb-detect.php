<?php
class SB_Detect {
    public static function is_mobile() {
        if(!class_exists('Mobile_Detect')) {
            require SB_THEME_LIB_PATH . '/Mobile-Detect/Mobile_Detect.php';
        }
        $detect = new Mobile_Detect();
        return $detect->isMobile();
    }
}