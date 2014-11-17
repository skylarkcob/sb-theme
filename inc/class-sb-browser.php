<?php
class SB_Browser {
    public static function is_firefox() {
        global $is_gecko;
        return $is_gecko;
    }

    public static function is_safari_iphone() {
        global $is_iphone;
        return $is_iphone;
    }

    public static function is_chrome() {
        global $is_chrome;
        return $is_chrome;
    }

    public static function is_safari() {
        global $is_safari;
        return $is_safari;
    }

    public static function is_ie() {
        global $is_IE;
        return $is_IE;
    }

    public static function is_ie_windows() {
        global $is_winIE;
        return $is_winIE;
    }

    public static function is_ie_mac() {
        global $is_macIE;
        return $is_macIE;
    }

    public static function is_opera() {
        global $is_opera;
        return $is_opera;
    }

    public static function is_netscape_4() {
        global $is_NS4;
        return $is_NS4;
    }

    public static function is_lynx() {
        global $is_lynx;
        return $is_lynx;
    }

    public static function get_name() {
        $name = 'unknown';
        if(self::is_safari_iphone()) {
            $name = 'safari-iphone';
        } elseif(self::is_safari()) {
            $name = 'safari';
        } elseif(self::is_ie_mac()) {
            $name = 'ie-mac';
        } elseif(self::is_ie_windows()) {
            $name = 'ie-windows';
        } elseif(self::is_ie()) {
            $name = 'ie';
        } elseif(self::is_firefox()) {
            $name = 'firefox';
        } elseif(self::is_chrome()) {
            $name = 'crhome';
        } elseif(self::is_opera()) {
            $name = 'opera';
        } elseif(self::is_netscape_4()) {
            $name = 'netscape-4';
        } elseif(self::is_lynx()) {
            $name = 'lynx';
        }
        return $name;
    }
}