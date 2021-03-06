<?php
defined('ABSPATH') or die('Please do not pip me!');

if(class_exists('SB_Captcha')) {
    return;
}

class SB_Captcha {
    public static function get($args = array()) {
        global $sb_captcha;
        if(empty($sb_captcha) && self::use_captcha()) {
            if(!is_array($args)) {
                $len = $args;
                $args = (array) $args;
                $args['len'] = $len;
            }
            $sb_captcha = new ReallySimpleCaptcha();
            $len = isset($args['len']) ? $args['len'] : 4;
            $bg = isset($args['bg']) ? (array) $args['bg'] : array();
            if(count($bg) > 0) {
                $sb_captcha->bg = $bg;
            }
            $sb_captcha->char_length = $len;
            $sb_captcha->cleanup(5);
        }
        return $sb_captcha;
    }

    public static function use_captcha() {
        return SB_Tool::use_captcha();
    }

    public static function required_plugins_installed() {
        $result = true;
        if(!class_exists('ReallySimpleCaptcha')) {
            $result = false;
        }
        return $result;
    }

    public static function need_check() {
        if(self::use_captcha() && self::required_plugins_installed()) {
            return true;
        }
        return false;
    }

    public static function need_check_login_captcha() {
        if(SB_Tool::use_login_captcha() && self::need_check()) {
            return true;
        }
        return false;
    }

    public static function check_login_captcha_post_empty() {
        if(self::need_check_login_captcha()) {
            if(!isset($_POST['captcha_code']) || empty($_POST['captcha_code'])) {
                return true;
            }
        }
        return false;
    }

    public static function check_login_captcha_post_valid() {
        if(SB_Captcha::need_check_login_captcha()) {
            if(isset($_POST['captcha_code']) && !SB_Captcha::check($_POST['captcha_code'])) {
                return false;
            }
        }
        return true;
    }

    public static function build_key($args = array()) {
        $key = isset($args['key']) ? $args['key'] : 'default';
        $key = SB_Cache::build_captcha_transient_name($key);
        return $key;
    }

    public static function cleanup($minute = 5) {
        $sb_captcha = self::get($args);
        $sb_captcha->cleanup($minute);
    }

    public static function delete_expired_transient() {
        SB_Cache::delete_captcha_expired_cache();
    }

    public static function delete_all_transient() {
        SB_Cache::delete_all_captcha_cache();
    }

    public static function remove($file_name) {
        if(empty($file_name)) {
            return;
        }
        $sb_captcha = self::get();
        SB_Cache::delete_captcha_transient($file_name);
        $sb_captcha->remove($file_name);
    }

    public static function get_captcha_session() {
        $file_name = SB_PHP::get_session('sb_theme_captcha_image');
        return $file_name;
    }

    public static function set_captcha_session($file_name) {
        SB_PHP::set_session('sb_theme_captcha_image', $file_name);
    }

    public static function build_transient_name($file_name) {
        return SB_Cache::build_captcha_transient_name($file_name);
    }

    public static function set_transient($name, $value) {
        set_transient($name, $value, 30 * MINUTE_IN_SECONDS);
    }

    public static function generate_image($args = array()) {
        if(!self::use_captcha() || !self::required_plugins_installed()) {
            return;
        }
        $sb_captcha = self::get($args);
        $file_name = self::get_captcha_session();
        $transient_keys = self::build_transient_name($file_name);
        $force = isset($args['force']) ? (bool) $args['force'] : false;
        if($force || empty($file_name) || false === get_transient($transient_keys['prefix']) || !file_exists(trailingslashit($sb_captcha->tmp_dir) . $file_name)) {
            $old_file_name = $file_name;
            $word = $sb_captcha->generate_random_word();
            $prefix = mt_rand();
            $file_name = $sb_captcha->generate_image( $prefix, $word );
            $transient_keys = self::build_transient_name($file_name);
            self::set_captcha_session($file_name);
            self::set_transient($transient_keys['prefix'], $prefix);
            self::set_transient($transient_keys['code'], SB_Core::hash_password($word));
            self::remove($old_file_name);
        }
        if(file_exists(plugins_url() . 'really-simple-captcha')) {
            $file_name = trailingslashit(plugins_url()) . 'really-simple-captcha/tmp/' . $file_name;
        } else {
            $upload = SB_Core::get_upload_folder_detail();
            $file_name = $upload['url'] . '/sb-theme-captcha/' . $file_name;
        }
        return $file_name;
    }

    public static function check($code) {
        if(!self::use_captcha() || !self::required_plugins_installed()) {
            return true;
        }
        $result = false;
        $file_name = SB_Captcha::get_captcha_session();
        if(!empty($file_name)) {
            $transient_keys = SB_Cache::build_captcha_transient_name($file_name);
            $transient_key = $transient_keys['code'];
            if(false !== ($captcha_code = get_transient($transient_key))) {
                if(SB_Core::password_compare($code, $captcha_code)) {
                    $result = true;
                }
            }
        }
        self::remove($file_name);
        return $result;
    }

    public static function the_image($args = array()) {
        $url = self::generate_image($args);
        $file_name = SB_PHP::get_file_name_without_extension($url);
        $class = isset($args['class']) ? $args['class'] : '';
        $class = SB_PHP::add_string_with_space_before($class, 'sb-captcha-image captcha-code captcha-code-image sb-theme-captcha-image');
        if(!empty($url)) {
            $len = isset($args['len']) ? $args['len'] : 4;
            echo '<img alt="" class="' . $class . '" data-file="' . $file_name . '" src="' . $url . '" data-len="' . $len . '">';
        }
    }
}