<?php
defined('ABSPATH') or die('Please do not pip me!');

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
            $name = 'chrome';
        } elseif(self::is_opera()) {
            $name = 'opera';
        } elseif(self::is_netscape_4()) {
            $name = 'netscape-4';
        } elseif(self::is_lynx()) {
            $name = 'lynx';
        }
        if('unknown' == $name) {
            $name = SB_PHP::get_browser();
            $name = strtolower($name);
        }
        return $name;
    }

    public static function get_browser_data($browser = false) {
        $data = array(
            'chrome' => (object) array(
                'name' => 'Google Chrome',
                'long_name' => 'Google Chrome',
                'wikipedia' => 'Google_Chrome',
                'normalized' => 1, // just first number
                'facebook' => 'googlechrome',
                'url' => 'http://www.google.com/chrome',
                'info' => __( '&#8220;A fast new browser from Google. Try&nbsp;it&nbsp;now!&#8221;', 'browsehappy' ),
            ),
            'firefox' => (object) array(
                'name' => 'Mozilla Firefox',
                'long_name' => 'Mozilla Firefox',
                'wikipedia' => 'Firefox',
                'normalized' => 1.5, // include second number if non-zero
                'facebook' => 'Firefox',
                'url' => 'http://www.firefox.com/',
                'info' => __( "&#8220;Your online security is Firefox's top priority. Firefox is free, and made to help you get the most out of the&nbsp;web.&#8221;", 'browsehappy' ),
            ),
            'safari' => (object) array(
                'name' => 'Safari',
                'long_name' => 'Apple Safari',
                'wikipedia' => 'Safari',
                'normalized' => 1.5, // include second number if non-zero
                'facebook' => false,
                'url' => 'http://www.apple.com/safari/',
                'info' => __( '&#8220;Safari for Mac and Windows from Apple, the world’s most innovative&nbsp;browser.&#8221;', 'browsehappy' ),
            ),
            'opera' => (object) array(
                'name' => 'Opera',
                'long_name' => 'Opera',
                'wikipedia' => 'Opera',
                'normalized' => 2, // include second number
                'facebook' => 'Opera',
                'url' => 'http://www.opera.com/',
                'info' => __( '&#8220;The fastest browser on Earth—secure, powerful and easy to use, with excellent privacy protection. And&nbsp;it&nbsp;is&nbsp;free.&#8221;', 'browsehappy' ),
            ),
            'ie' => (object) array(
                'name' => 'Internet Explorer',
                'long_name' => 'Microsoft Internet Explorer',
                'wikipedia' => 'Internet_Explorer',
                'normalized' => 1, // just first number
                'facebook' => 'internetexplorer',
                'url' => 'http://www.microsoft.com/windows/internet-explorer/',
                'info' => __( '&#8220;Designed to help you take control of your privacy and browse with confidence. Free from&nbsp;Microsoft.&#8221;', 'browsehappy' ),
            ),
        );
        if(false === $browser) {
            return $data;
        }
        if(!isset($data[$browser])) {
            return false;
        }
        return $data[$browser];
    }

    public static function normalize_version($browser, $version) {
        $normalize = self::get_browser_data($browser)->normalized;
        $version = explode('.', $version);
        if(1.5 == $normalize) {
            $return = $version[0];
            if('0' !== $version[1]) {
                $return .= '.' . $version[1];
            }
            return $return;
        }
        $return = array();
        for($i = 0; $i < $normalize; $i++) {
            $return[] = $version[$i];
        }
        return implode('.', $return);
    }

    public static function clear_version_cache() {
        $browsers = array_keys(self::get_browser_data());
        foreach($browsers as $browser) {
            $transient_name = 'sb_theme_browser_' . $browser . '_version';
            delete_transient($transient_name);
        }
    }

    public static function get_latest_version($browser_name, $normalize = true) {
        return self::fetch_version($browser_name, $normalize);
    }

    public static function fetch_version($browser, $normalize = true) {
        $transient_name = 'sb_theme_browser_' . $browser . '_version';
        $fragment = self::get_browser_data($browser)->wikipedia;
        if(!$fragment) {
            return false;
        }
        $stored_version = get_transient($transient_name);
        if(false !== $stored_version) {
            if($normalize) {
                return self::normalize_version($browser, $stored_version);
            }
            return $stored_version;
        }
        $url = 'http://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=php&titles=Template:Latest_stable_software_release/';
        $url .= $fragment;
        $response = wp_remote_get($url);
        if(is_wp_error($response)) {
            return false;
        }
        if(!$content = wp_remote_retrieve_body($response)) {
            return false;
        }
        if(!is_serialized($content)) {
            return false;
        }
        $content = maybe_unserialize($content);
        $page = array_pop($content['query']['pages']);
        $raw_data = explode("\n", $page['revisions'][0]['*']);
        $version = false;
        foreach($raw_data as $data) {
            $data = trim($data, '| ');
            if(false !== strpos($data, 'Android') || false !== strpos($data, 'iOS')) {
                continue;
            }
            if((false !== $pos = strpos($data, 'latest_release_version')) || (false !== $pos = strpos($data, 'latest release version'))) {
                if($pos) {
                    $data = substr($data, $pos);
                }
                $version = trim(str_replace(array('latest_release_version', 'latest release version', '='), '', $data), '| ') . " ";
                $version = substr($version, 0, strpos($version, ' '));
                break;
            }
        }
        if(false === $version) {
            return false;
        }
        $version = preg_replace('/[^0-9\.]/', '', $version);
        set_transient($transient_name, $version, 2 * DAY_IN_SECONDS);
        if($normalize) {
            return self::normalize_version($browser, $version);
        }
        return $version;
    }
}