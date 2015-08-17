<?php
defined('ABSPATH') or die('Please do not pip me!');

class SB_PHP {
    public static function url_exists( $url ) {
        $file_headers = @get_headers( $url );
        $result = true;
        if ( $file_headers[0] == 'HTTP/1.1 404 Not Found' ) {
            $result = false;
        }
        return $result;
    }

    public static function currency_format_vietnamese( $number, $suffix = ' ₫' ) {
        return number_format( $number, 0, '.', ',' ) . $suffix;
    }

    public static function format_number_vietnamese($price, $decimals = 0, $decimal_separator = ',', $thousand_separator = '.') {
        if(!is_numeric($price)) {
            return $price;
        }
        return number_format($price, $decimals, $decimal_separator, $thousand_separator);
    }
    
    public static function get_max_number($numbers = array()) {
        $max = array_shift($numbers);
        foreach($numbers as $number) {
            if($number > $max) {
                $max = $number;
            }
        }
        return $max;
    }

    public static function upload($args = array()) {
        $name = isset($args['name']) ? $args['name'] : '';
        $path = isset($args['path']) ? $args['path'] : '';
        $size = isset($args['size']) ? $args['size'] : 0;
        $max_size = isset($args['max_size']) ? $args['max_size'] : -1;
        $is_image = isset($args['is_image']) ? $args['is_image'] : false;
        $extensions = isset($args['extensions']) ? $args['extensions'] : array();
        $tmp_name = isset($args['tmp_name']) ? $args['tmp_name'] : '';
        $duplicate_exists = isset($args['duplicate_exists']) ? $args['duplicate_exists'] : true;
        $result = array(
            'success' => false
        );
        $result['image_base64'] = self::image_base64($tmp_name);
        $name = strtolower($name);
        $file_path = $path . '/' . basename($name);
        $file_type = pathinfo($file_path, PATHINFO_EXTENSION);
        if($is_image) {
            $check = getimagesize($tmp_name);
            if($check === false) {
                $result['message'][] = 'Tập tin ' . $name . ' không phải là hình ảnh.';
                return $result;
            }
        }
        if(file_exists($file_path)) {
            if($duplicate_exists) {
                $path_info = pathinfo($file_path);
                $name = $path_info['filename'] . '-' . self::random_string() . '.' . $file_type;
                $name = strtolower($name);
                $file_path = $path . '/' . basename($name);
            } else {
                $result['message'][] = 'Tập tin ' . $name . ' đã tồn tại.';
                return $result;
            }
        }
        if($max_size > 0 && $size > $max_size) {
            $result['message'][] = 'Dung lượng tập tin không được quá ' . $max_size . 'KB.';
            return $result;
        }
        if(count($extensions) > 0 && !in_array($file_type, $extensions)) {
            $result['message'][] = 'Bạn không được phép upload tập tin với định dạng ' . $file_type . '.';
            return $result;
        }
        $file_path = strtolower($file_path);
        if(move_uploaded_file($tmp_name, $file_path)) {
            $result['success'] = true;
        } else {
            $result['message'][] = 'Đã có lỗi xảy ra, tập tin của bạn chưa được upload.';
        }
        $result['name'] = $name;
        $result['path'] = $file_path;
        return $result;
    }

    public static function is_phone($number) {
        $regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
        $result = (preg_match($regex, $number)) ? true : false;
        if($result) {
            $len = strlen($number);
            if($len < 7 || $len > 20) {
                $result = false;
            }
        }
        return true;
    }

    public static function get_min_number($numbers = array()) {
        $min = array_shift($numbers);
        foreach($numbers as $number) {
            if($number < $min) {
                $min = $number;
            }
        }
        return $min;
    }

    public static function encrypt($key, $string) {
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encrypted;
    }

    public static function decrypt($key, $encrypted) {
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), '\0');
        return $decrypted;
    }

    public static function get_html_tag_content($tag, $html) {
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        $result = $dom->getElementsByTagName($tag)->item(0)->nodeValue;
        return $result;
    }

    public static function object_to_array($object) {
        return json_decode(json_encode($object), true);
    }

    public static function get_operating_system() {
        $result = 'Unknown OS';
        $os = array(
            '/windows nt 10.0/i'    => 'Windows 10',
            '/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/windows nt 6.0/i'     => 'Windows Vista',
            '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     => 'Windows XP',
            '/windows xp/i'         => 'Windows XP',
            '/windows nt 5.0/i'     => 'Windows 2000',
            '/windows me/i'         => 'Windows ME',
            '/win98/i'              => 'Windows 98',
            '/win95/i'              => 'Windows 95',
            '/win16/i'              => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipod/i'               => 'iPod',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile'
        );
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        foreach ( $os as $regex => $value ) {
            if ( preg_match( $regex, $user_agent ) ) {
                $result = $value;
                break;
            }
        }
        return $result;
    }

    public static function get_current_day_of_week($full = true) {
        $format = 'l';
        if(!$full) {
            $format = 'D';
        }
        return date($format);
    }

    public static function is_array_has_value( $arr ) {
        if( is_array( $arr ) && count( $arr ) > 0 ) {
            return true;
        }
        return false;
    }

    public static function trim_all_array_item($source_array) {
        return array_map('trim', $source_array);
    }

    public static function array_to_string($delimiter, $text) {
        $datas = explode($delimiter, $text);
        $datas = array_filter($datas);
        return $datas;
    }

    public static function to_data_image($file) {
        $image_data = @file_get_contents($file);
        return 'data:image/png;base64,' . base64_encode($image_data);
    }

    public static function image_base64($file) {
        return self::to_data_image($file);
    }

    public static function get_user_agent() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        return $user_agent;
    }

    public static function get_browser() {
        $result = 'Unknown Browser';
        $browsers = array(
            '/msie/i'       => 'Internet Explorer',
            '/firefox/i'    => 'Firefox',
            '/safari/i'     => 'Safari',
            '/chrome/i'     => 'Chrome',
            '/opera/i'      => 'Opera',
            '/netscape/i'   => 'Netscape',
            '/maxthon/i'    => 'Maxthon',
            '/konqueror/i'  => 'Konqueror',
            '/mobile/i'     => 'Handheld Browser'
        );
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        foreach ($browsers as $regex => $value ) {
            if ( preg_match( $regex, $user_agent ) ) {
                $result = $value;
                break;
            }
        }
        return $result;
    }

    public static function replace_image_source( $img_tag, $new_source ) {
        $doc = new DOMDocument();
        $doc->loadHTML( $img_tag );
        $tags = $doc->getElementsByTagName( 'img' );
        foreach ( $tags as $tag ) {
            $tag->setAttribute( 'src', $new_source );
        }
        return $doc->saveHTML();
    }

    public static function clean_url( $url ) {
        $url = self::lowercase( $url );
        $url = str_replace( ' ', '-', $url );
        $url = self::remove_vietnamese( $url );
        $url = preg_replace( '/[^A-Za-z0-9\-]/', '', $url );
        return preg_replace( '/-+/', '-', $url );
    }

    public static function mysql_time_format() {
        return SB_DATE_TIME_FORMAT;
    }

    public static function format_date( $date, $format ) {
        return date( $format, strtotime( $date ) );
    }

    public static function move_item_to_beginning_by_key( $key, $arr ) {
        $tmp = isset( $arr[ $key ] ) ? $arr[ $key ] : '';
        if ( $tmp ) {
            unset( $arr[ $key ] );
            array_unshift( $arr, $tmp );
        }
        return $arr;
    }

    public static function delete_file( $file_path ) {
        $file_path = realpath( $file_path );
        if ( is_readable( $file_path ) ) {
            unlink( $file_path );
        }
    }

    public static function convert_datetime_format_to_jquery($php_format) {
        $matched_symbols = array(
            // Day
            'd' => 'dd',
            'D' => 'D',
            'j' => 'd',
            'l' => 'DD',
            'N' => '',
            'S' => '',
            'w' => '',
            'z' => 'o',
            // Week
            'W' => '',
            // Month
            'F' => 'MM',
            'm' => 'mm',
            'M' => 'M',
            'n' => 'm',
            't' => '',
            // Year
            'L' => '',
            'o' => '',
            'Y' => 'yy',
            'y' => 'y',
            // Time
            'a' => '',
            'A' => '',
            'B' => '',
            'g' => '',
            'G' => '',
            'h' => '',
            'H' => '',
            'i' => '',
            's' => '',
            'u' => ''
        );
        $result = '';
        $escaping = false;
        for($i = 0; $i < strlen($php_format); $i++) {
            $char = $php_format[$i];
            if(isset($matched_symbols[$char])) {
                $result .= $matched_symbols[$char];
            } else {
                $result .= $char;
            }
        }
        return esc_attr($result);
    }

    public static function date_plus_minute( $date, $minute ) {
        $kq = new DateTime( $date );
        $time_modify = '+' . $minute;
        if ( $minute > 1 ) {
            $time_modify .= ' minutes';
        } else {
            $time_modify .= ' minute';
        }
        $kq->modify( $time_modify );
        return $kq->format( self::mysql_time_format() );
    }

    public static function date_minus_minute( $date1, $date2 ) {
        $date1 = new DateTime( $date1 );
        $date2 = new DateTime( $date2 );
        $diff = $date1->diff( $date2 );
        return round( date_create( '@0' )->add( $diff )->getTimestamp() / MINUTE_IN_SECONDS, 0 );
    }

    public static function create_folder($file_path, $chmod = 0777) {
        if ( ! file_exists( $file_path ) ) {
            mkdir($file_path, $chmod);
        }
    }

    public static function copy( $source, $destination ) {
        if ( @fclose( @fopen( $source, 'r' ) ) ) {
            copy( $source, $destination );
            return true;
        }
        return false;
    }

    public static function add_string_with_space_before( $old_string, $new_string ) {
        $old_string .= ' ';
        $old_string = self::add_string_unique( $old_string, $new_string );
        return $old_string;
    }

    public static function get_countries() {
        $countries = array(
            'AF' => array('name' => 'Afghanistan', 'nativetongue' => '‫افغانستان'),
            'AX' => array('name' => 'Åland Islands', 'nativetongue' => 'Åland'),
            'AL' => array('name' => 'Albania', 'nativetongue' => 'Shqipëri'),
            'DZ' => array('name' => 'Algeria', 'nativetongue' => '‫الجزائر'),
            'AS' => array('name' => 'American Samoa', 'nativetongue' => ''),
            'AD' => array('name' => 'Andorra', 'nativetongue' => ''),
            'AO' => array('name' => 'Angola', 'nativetongue' => ''),
            'AI' => array('name' => 'Anguilla', 'nativetongue' => ''),
            'AQ' => array('name' => 'Antarctica', 'nativetongue' => ''),
            'AG' => array('name' => 'Antigua and Barbuda', 'nativetongue' => ''),
            'AR' => array('name' => 'Argentina', 'nativetongue' => ''),
            'AM' => array('name' => 'Armenia', 'nativetongue' => 'Հայաստան'),
            'AW' => array('name' => 'Aruba', 'nativetongue' => ''),
            'AC' => array('name' => 'Ascension Island', 'nativetongue' => ''),
            'AU' => array('name' => 'Australia', 'nativetongue' => ''),
            'AT' => array('name' => 'Austria', 'nativetongue' => 'Österreich'),
            'AZ' => array('name' => 'Azerbaijan', 'nativetongue' => 'Azərbaycan'),
            'BS' => array('name' => 'Bahamas', 'nativetongue' => ''),
            'BH' => array('name' => 'Bahrain', 'nativetongue' => '‫البحرين'),
            'BD' => array('name' => 'Bangladesh', 'nativetongue' => 'বাংলাদেশ'),
            'BB' => array('name' => 'Barbados', 'nativetongue' => ''),
            'BY' => array('name' => 'Belarus', 'nativetongue' => 'Беларусь'),
            'BE' => array('name' => 'Belgium', 'nativetongue' => 'België'),
            'BZ' => array('name' => 'Belize', 'nativetongue' => ''),
            'BJ' => array('name' => 'Benin', 'nativetongue' => 'Bénin'),
            'BM' => array('name' => 'Bermuda', 'nativetongue' => ''),
            'BT' => array('name' => 'Bhutan', 'nativetongue' => 'འབྲུག'),
            'BO' => array('name' => 'Bolivia', 'nativetongue' => ''),
            'BA' => array('name' => 'Bosnia and Herzegovina', 'nativetongue' => 'Босна и Херцеговина'),
            'BW' => array('name' => 'Botswana', 'nativetongue' => ''),
            'BV' => array('name' => 'Bouvet Island', 'nativetongue' => ''),
            'BR' => array('name' => 'Brazil', 'nativetongue' => 'Brasil'),
            'IO' => array('name' => 'British Indian Ocean Territory','nativetongue' => ''),
            'VG' => array('name' => 'British Virgin Islands', 'nativetongue' => ''),
            'BN' => array('name' => 'Brunei', 'nativetongue' => ''),
            'BG' => array('name' => 'Bulgaria', 'nativetongue' => 'България'),
            'BF' => array('name' => 'Burkina Faso', 'nativetongue' => ''),
            'BI' => array('name' => 'Burundi', 'nativetongue' => 'Uburundi'),
            'KH' => array('name' => 'Cambodia', 'nativetongue' => 'កម្ពុជា'),
            'CM' => array('name' => 'Cameroon', 'nativetongue' => 'Cameroun'),
            'CA' => array('name' => 'Canada', 'nativetongue' => ''),
            'IC' => array('name' => 'Canary Islands', 'nativetongue' => 'islas Canarias'),
            'CV' => array('name' => 'Cape Verde', 'nativetongue' => 'Kabu Verdi'),
            'BQ' => array('name' => 'Caribbean Netherlands', 'nativetongue' => ''),
            'KY' => array('name' => 'Cayman Islands', 'nativetongue' => ''),
            'CF' => array('name' => 'Central African Republic','nativetongue' => 'République centrafricaine'),
            'EA' => array('name' => 'Ceuta and Melilla', 'nativetongue' => 'Ceuta y Melilla'),
            'TD' => array('name' => 'Chad', 'nativetongue' => 'Tchad'),
            'CL' => array('name' => 'Chile', 'nativetongue' => ''),
            'CN' => array('name' => 'China', 'nativetongue' => '中国'),
            'CX' => array('name' => 'Christmas Island', 'nativetongue' => ''),
            'CP' => array('name' => 'Clipperton Island', 'nativetongue' => ''),
            'CC' => array('name' => 'Cocos (Keeling) Islands', 'nativetongue' => 'Kepulauan Cocos (Keeling)'),
            'CO' => array('name' => 'Colombia', 'nativetongue' => ''),
            'KM' => array('name' => 'Comoros', 'nativetongue' => '‫جزر القمر'),
            'CD' => array('name' => 'Congo (DRC)', 'nativetongue' => 'Jamhuri ya Kidemokrasia ya Kongo'),
            'CG' => array('name' => 'Congo (Republic)', 'nativetongue' => 'Congo-Brazzaville'),
            'CK' => array('name' => 'Cook Islands', 'nativetongue' => ''),
            'CR' => array('name' => 'Costa Rica', 'nativetongue' => ''),
            'CI' => array('name' => 'Côte d’Ivoire', 'nativetongue' => ''),
            'HR' => array('name' => 'Croatia', 'nativetongue' => 'Hrvatska'),
            'CU' => array('name' => 'Cuba', 'nativetongue' => ''),
            'CW' => array('name' => 'Curaçao', 'nativetongue' => ''),
            'CY' => array('name' => 'Cyprus', 'nativetongue' => 'Κύπρος'),
            'CZ' => array('name' => 'Czech Republic', 'nativetongue' => 'Česká republika'),
            'DK' => array('name' => 'Denmark', 'nativetongue' => 'Danmark'),
            'DG' => array('name' => 'Diego Garcia', 'nativetongue' => ''),
            'DJ' => array('name' => 'Djibouti', 'nativetongue' => ''),
            'DM' => array('name' => 'Dominica', 'nativetongue' => ''),
            'DO' => array('name' => 'Dominican Republic', 'nativetongue' => 'República Dominicana'),
            'EC' => array('name' => 'Ecuador', 'nativetongue' => ''),
            'EG' => array('name' => 'Egypt', 'nativetongue' => '‫مصر'),
            'SV' => array('name' => 'El Salvador', 'nativetongue' => ''),
            'GQ' => array('name' => 'Equatorial Guinea','nativetongue' => 'Guinea Ecuatorial'),
            'ER' => array('name' => 'Eritrea', 'nativetongue' => ''),
            'EE' => array('name' => 'Estonia', 'nativetongue' => 'Eesti'),
            'ET' => array('name' => 'Ethiopia', 'nativetongue' => ''),
            'FK' => array('name' => 'Falkland Islands', 'nativetongue' => 'Islas Malvinas'),
            'FO' => array('name' => 'Faroe Islands', 'nativetongue' => 'Føroyar'),
            'FJ' => array('name' => 'Fiji', 'nativetongue' => ''),
            'FI' => array('name' => 'Finland', 'nativetongue' => 'Suomi'),
            'FR' => array('name' => 'France', 'nativetongue' => ''),
            'GF' => array('name' => 'French Guiana', 'nativetongue' => 'Guyane française'),
            'PF' => array('name' => 'French Polynesia', 'nativetongue' => 'Polynésie française'),
            'TF' => array('name' => 'French Southern Territories', 'nativetongue' => 'Terres australes françaises'),
            'GA' => array('name' => 'Gabon', 'nativetongue' => ''),
            'GM' => array('name' => 'Gambia', 'nativetongue' => ''),
            'GE' => array('name' => 'Georgia', 'nativetongue' => 'საქართველო'),
            'DE' => array('name' => 'Germany', 'nativetongue' => 'Deutschland'),
            'GH' => array('name' => 'Ghana', 'nativetongue' => 'Gaana'),
            'GI' => array('name' => 'Gibraltar', 'nativetongue' => ''),
            'GR' => array('name' => 'Greece', 'nativetongue' => 'Ελλάδα'),
            'GL' => array('name' => 'Greenland', 'nativetongue' => 'Kalaallit Nunaat'),
            'GD' => array('name' => 'Grenada', 'nativetongue' => ''),
            'GP' => array('name' => 'Guadeloupe', 'nativetongue' => ''),
            'GU' => array('name' => 'Guam', 'nativetongue' => ''),
            'GT' => array('name' => 'Guatemala', 'nativetongue' => ''),
            'GG' => array('name' => 'Guernsey', 'nativetongue' => ''),
            'GN' => array('name' => 'Guinea', 'nativetongue' => 'Guinée'),
            'GW' => array('name' => 'Guinea-Bissau', 'nativetongue' => 'Guiné Bissau'),
            'GY' => array('name' => 'Guyana', 'nativetongue' => ''),
            'HT' => array('name' => 'Haiti', 'nativetongue' => ''),
            'HM' => array('name' => 'Heard & McDonald Islands', 'nativetongue' => ''),
            'HN' => array('name' => 'Honduras', 'nativetongue' => ''),
            'HK' => array('name' => 'Hong Kong', 'nativetongue' => '香港'),
            'HU' => array('name' => 'Hungary', 'nativetongue' => 'Magyarország'),
            'IS' => array('name' => 'Iceland', 'nativetongue' => 'Ísland'),
            'IN' => array('name' => 'India', 'nativetongue' => 'भारत'),
            'ID' => array('name' => 'Indonesia', 'nativetongue' => ''),
            'IR' => array('name' => 'Iran', 'nativetongue' => '‫ایران'),
            'IQ' => array('name' => 'Iraq', 'nativetongue' => '‫العراق'),
            'IE' => array('name' => 'Ireland', 'nativetongue' => ''),
            'IM' => array('name' => 'Isle of Man', 'nativetongue' => ''),
            'IL' => array('name' => 'Israel', 'nativetongue' => '‫ישראל'),
            'IT' => array('name' => 'Italy', 'nativetongue' => 'Italia'),
            'JM' => array('name' => 'Jamaica', 'nativetongue' => ''),
            'JP' => array('name' => 'Japan', 'nativetongue' => '日本'),
            'JE' => array('name' => 'Jersey', 'nativetongue' => ''),
            'JO' => array('name' => 'Jordan', 'nativetongue' => '‫الأردن'),
            'KZ' => array('name' => 'Kazakhstan', 'nativetongue' => 'Казахстан'),
            'KE' => array('name' => 'Kenya', 'nativetongue' => ''),
            'KI' => array('name' => 'Kiribati', 'nativetongue' => ''),
            'XK' => array('name' => 'Kosovo', 'nativetongue' => 'Kosovë'),
            'KW' => array('name' => 'Kuwait', 'nativetongue' => '‫الكويت'),
            'KG' => array('name' => 'Kyrgyzstan', 'nativetongue' => 'Кыргызстан'),
            'LA' => array('name' => 'Laos', 'nativetongue' => 'ລາວ'),
            'LV' => array('name' => 'Latvia', 'nativetongue' => 'Latvija'),
            'LB' => array('name' => 'Lebanon', 'nativetongue' => '‫لبنان'),
            'LS' => array('name' => 'Lesotho', 'nativetongue' => ''),
            'LR' => array('name' => 'Liberia', 'nativetongue' => ''),
            'LY' => array('name' => 'Libya', 'nativetongue' => '‫ليبيا'),
            'LI' => array('name' => 'Liechtenstein', 'nativetongue' => ''),
            'LT' => array('name' => 'Lithuania', 'nativetongue' => 'Lietuva'),
            'LU' => array('name' => 'Luxembourg', 'nativetongue' => ''),
            'MO' => array('name' => 'Macau', 'nativetongue' => '澳門'),
            'MK' => array('name' => 'Macedonia (FYROM)','nativetongue' => 'Македонија'),
            'MG' => array('name' => 'Madagascar', 'nativetongue' => 'Madagasikara'),
            'MW' => array('name' => 'Malawi', 'nativetongue' => ''),
            'MY' => array('name' => 'Malaysia', 'nativetongue' => ''),
            'MV' => array('name' => 'Maldives', 'nativetongue' => ''),
            'ML' => array('name' => 'Mali', 'nativetongue' => ''),
            'MT' => array('name' => 'Malta', 'nativetongue' => ''),
            'MH' => array('name' => 'Marshall Islands', 'nativetongue' => ''),
            'MQ' => array('name' => 'Martinique', 'nativetongue' => ''),
            'MR' => array('name' => 'Mauritania', 'nativetongue' => '‫موريتانيا'),
            'MU' => array('name' => 'Mauritius', 'nativetongue' => 'Moris'),
            'YT' => array('name' => 'Mayotte', 'nativetongue' => ''),
            'MX' => array('name' => 'Mexico', 'nativetongue' => ''),
            'FM' => array('name' => 'Micronesia', 'nativetongue' => ''),
            'MD' => array('name' => 'Moldova', 'nativetongue' => 'Republica Moldova'),
            'MC' => array('name' => 'Monaco', 'nativetongue' => ''),
            'MN' => array('name' => 'Mongolia', 'nativetongue' => 'Монгол'),
            'ME' => array('name' => 'Montenegro', 'nativetongue' => 'Crna Gora'),
            'MS' => array('name' => 'Montserrat', 'nativetongue' => ''),
            'MA' => array('name' => 'Morocco', 'nativetongue' => '‫المغرب'),
            'MZ' => array('name' => 'Mozambique', 'nativetongue' => 'Moçambique'),
            'MM' => array('name' => 'Myanmar (Burma)', 'nativetongue' => 'မြန်မာ'),
            'NA' => array('name' => 'Namibia', 'nativetongue' => 'Namibië'),
            'NR' => array('name' => 'Nauru', 'nativetongue' => ''),
            'NP' => array('name' => 'Nepal', 'nativetongue' => 'नेपाल'),
            'NL' => array('name' => 'Netherlands', 'nativetongue' => 'Nederland'),
            'NC' => array('name' => 'New Caledonia', 'nativetongue' => 'Nouvelle-Calédonie'),
            'NZ' => array('name' => 'New Zealand', 'nativetongue' => ''),
            'NI' => array('name' => 'Nicaragua', 'nativetongue' => ''),
            'NE' => array('name' => 'Niger', 'nativetongue' => 'Nijar'),
            'NG' => array('name' => 'Nigeria', 'nativetongue' => ''),
            'NU' => array('name' => 'Niue', 'nativetongue' => ''),
            'NF' => array('name' => 'Norfolk Island', 'nativetongue' => ''),
            'MP' => array('name' => 'Northern Mariana Islands', 'nativetongue' => ''),
            'KP' => array('name' => 'North Korea', 'nativetongue' => '조선 민주주의 인민 공화국'),
            'NO' => array('name' => 'Norway', 'nativetongue' => 'Norge'),
            'OM' => array('name' => 'Oman', 'nativetongue' => '‫عُمان'),
            'PK' => array('name' => 'Pakistan', 'nativetongue' => '‫پاکستان'),
            'PW' => array('name' => 'Palau', 'nativetongue' => ''),
            'PS' => array('name' => 'Palestine', 'nativetongue' => '‫فلسطين'),
            'PA' => array('name' => 'Panama', 'nativetongue' => ''),
            'PG' => array('name' => 'Papua New Guinea', 'nativetongue' => ''),
            'PY' => array('name' => 'Paraguay', 'nativetongue' => ''),
            'PE' => array('name' => 'Peru', 'nativetongue' => 'Perú'),
            'PH' => array('name' => 'Philippines', 'nativetongue' => ''),
            'PN' => array('name' => 'Pitcairn Islands', 'nativetongue' => ''),
            'PL' => array('name' => 'Poland', 'nativetongue' => 'Polska'),
            'PT' => array('name' => 'Portugal', 'nativetongue' => ''),
            'PR' => array('name' => 'Puerto Rico', 'nativetongue' => ''),
            'QA' => array('name' => 'Qatar', 'nativetongue' => '‫قطر'),
            'RE' => array('name' => 'Réunion', 'nativetongue' => 'La Réunion'),
            'RO' => array('name' => 'Romania', 'nativetongue' => 'România'),
            'RU' => array('name' => 'Russia', 'nativetongue' => 'Россия'),
            'RW' => array('name' => 'Rwanda', 'nativetongue' => ''),
            'BL' => array('name' => 'Saint Barthélemy', 'nativetongue' => 'Saint-Barthélemy'),
            'SH' => array('name' => 'Saint Helena', 'nativetongue' => ''),
            'KN' => array('name' => 'Saint Kitts and Nevis', 'nativetongue' => ''),
            'LC' => array('name' => 'Saint Lucia', 'nativetongue' => ''),
            'MF' => array('name' => 'Saint Martin', 'nativetongue' => ''),
            'PM' => array('name' => 'Saint Pierre and Miquelon', 'nativetongue' => 'Saint-Pierre-et-Miquelon'),
            'WS' => array('name' => 'Samoa', 'nativetongue' => ''),
            'SM' => array('name' => 'San Marino', 'nativetongue' => ''),
            'ST' => array('name' => 'São Tomé and Príncipe', 'nativetongue' => 'São Tomé e Príncipe'),
            'SA' => array('name' => 'Saudi Arabia', 'nativetongue' => '‫المملكة العربية السعودية'),
            'SN' => array('name' => 'Senegal', 'nativetongue' => 'Sénégal'),
            'RS' => array('name' => 'Serbia', 'nativetongue' => 'Србија'),
            'SC' => array('name' => 'Seychelles', 'nativetongue' => ''),
            'SL' => array('name' => 'Sierra Leone', 'nativetongue' => ''),
            'SG' => array('name' => 'Singapore', 'nativetongue' => ''),
            'SX' => array('name' => 'Sint Maarten', 'nativetongue' => ''),
            'SK' => array('name' => 'Slovakia', 'nativetongue' => 'Slovensko'),
            'SI' => array('name' => 'Slovenia', 'nativetongue' => 'Slovenija'),
            'SB' => array('name' => 'Solomon Islands', 'nativetongue' => ''),
            'SO' => array('name' => 'Somalia', 'nativetongue' => 'Soomaaliya'),
            'ZA' => array('name' => 'South Africa', 'nativetongue' => ''),
            'GS' => array('name' => 'South Georgia & South Sandwich Islands', 'nativetongue' => ''),
            'KR' => array('name' => 'South Korea', 'nativetongue' => '대한민국'),
            'SS' => array('name' => 'South Sudan', 'nativetongue' => '‫جنوب السودان'),
            'ES' => array('name' => 'Spain', 'nativetongue' => 'España'),
            'LK' => array('name' => 'Sri Lanka', 'nativetongue' => 'ශ්‍රී ලංකාව'),
            'VC' => array('name' => 'St. Vincent & Grenadines', 'nativetongue' => ''),
            'SD' => array('name' => 'Sudan', 'nativetongue' => '‫السودان'),
            'SR' => array('name' => 'Suriname', 'nativetongue' => ''),
            'SJ' => array('name' => 'Svalbard and Jan Mayen', 'nativetongue' => 'Svalbard og Jan Mayen'),
            'SZ' => array('name' => 'Swaziland', 'nativetongue' => ''),
            'SE' => array('name' => 'Sweden', 'nativetongue' => 'Sverige'),
            'CH' => array('name' => 'Switzerland', 'nativetongue' => 'Schweiz'),
            'SY' => array('name' => 'Syria', 'nativetongue' => '‫سوريا'),
            'TW' => array('name' => 'Taiwan', 'nativetongue' => '台灣'),
            'TJ' => array('name' => 'Tajikistan', 'nativetongue' => ''),
            'TZ' => array('name' => 'Tanzania', 'nativetongue' => ''),
            'TH' => array('name' => 'Thailand', 'nativetongue' => 'ไทย'),
            'TL' => array('name' => 'Timor-Leste', 'nativetongue' => ''),
            'TG' => array('name' => 'Togo', 'nativetongue' => ''),
            'TK' => array('name' => 'Tokelau', 'nativetongue' => ''),
            'TO' => array('name' => 'Tonga', 'nativetongue' => ''),
            'TT' => array('name' => 'Trinidad and Tobago', 'nativetongue' => ''),
            'TA' => array('name' => 'Tristan da Cunha', 'nativetongue' => ''),
            'TN' => array('name' => 'Tunisia', 'nativetongue' => '‫تونس'),
            'TR' => array('name' => 'Turkey', 'nativetongue' => 'Türkiye'),
            'TM' => array('name' => 'Turkmenistan', 'nativetongue' => ''),
            'TC' => array('name' => 'Turks and Caicos Islands', 'nativetongue' => ''),
            'TV' => array('name' => 'Tuvalu', 'nativetongue' => ''),
            'UM' => array('name' => 'U.S. Outlying Islands', 'nativetongue' => ''),
            'VI' => array('name' => 'U.S. Virgin Islands', 'nativetongue' => ''),
            'UG' => array('name' => 'Uganda', 'nativetongue' => ''),
            'UA' => array('name' => 'Ukraine', 'nativetongue' => 'Україна'),
            'AE' => array('name' => 'United Arab Emirates', 'nativetongue' => '‫الإمارات العربية المتحدة'),
            'GB' => array('name' => 'United Kingdom', 'nativetongue' => ''),
            'US' => array('name' => 'United States', 'nativetongue' => ''),
            'UY' => array('name' => 'Uruguay', 'nativetongue' => ''),
            'UZ' => array('name' => 'Uzbekistan', 'nativetongue' => 'Oʻzbekiston'),
            'VU' => array('name' => 'Vanuatu', 'nativetongue' => ''),
            'VA' => array('name' => 'Vatican City', 'nativetongue' => 'Città del Vaticano'),
            'VE' => array('name' => 'Venezuela', 'nativetongue' => ''),
            'VN' => array('name' => 'Vietnam', 'nativetongue' => 'Việt Nam'),
            'WF' => array('name' => 'Wallis and Futuna', 'nativetongue' => ''),
            'EH' => array('name' => 'Western Sahara', 'nativetongue' => '‫الصحراء الغربية'),
            'YE' => array('name' => 'Yemen', 'nativetongue' => '‫اليمن'),
            'ZM' => array('name' => 'Zambia', 'nativetongue' => ''),
            'ZW' => array('name' => 'Zimbabwe', 'nativetongue' => '')
        );
        return $countries;
    }
    
    public static function substr( $str, $len, $more = '...', $charset = 'UTF-8' ) {
        $more = esc_html($more);
        $str = html_entity_decode( $str, ENT_QUOTES, $charset );
        if ( mb_strlen( $str, $charset ) > $len ) {
            $arr = explode( ' ', $str );
            $str = mb_substr( $str, 0, $len, $charset );
            $arr_words = explode( ' ', $str );
            $index = count( $arr_words ) - 1;
            $last = $arr[ $index ];
            unset( $arr );
            if ( strcasecmp( $arr_words[ $index ], $last ) ) {
                unset( $arr_words[ $index ] );
            }
            return implode( ' ', $arr_words ) . $more;
        }
        return $str;
    }

    public static function get_substring($args = array()) {
        $text = isset($args['text']) ? $args['text'] : '';
        if(empty($text)) {
            $text = isset($args['string']) ? $args['string'] : '';
        }
        $more = isset($args['more']) ? $args['more'] : esc_html(SB_THEME_THREE_DOT);
        $read_more = isset($args['read_more']) ? $args['read_more'] : '';
        $len = isset($args['len']) ? absint($args['len']) : 0;
        if($len == 0) {
            $len = isset($args['length']) ? absint($args['length']) : 0;
        }
        $old_length = mb_strlen($text, 'utf-8');
        $sub_str = self::substr($text, $len, $more);
        $new_length = mb_strlen($sub_str, 'utf-8') - strlen($more);
        if(!empty($text) && !empty($read_more) && $new_length < $old_length) {
            $sub_str .= ' ' . $read_more;
        }
        return $sub_str;
    }

    public static function remove_array_item_by_value($arr, $arr_remove) {
        $arr = array_diff($arr, $arr_remove);
        return $arr;
    }

    public static function esc_id($id) {
        $id = strtolower($id);
        $chars = array(
            '-',
            ' ',
            '[',
            ']',
            '@',
            '.'
        );
        $id = str_replace($chars, '_', $id);
        return $id;
    }

    public static function array_shift( &$array, $number = 1 ) {
        $result = array();
        $number = absint( $number );
        if ( ! is_array( $array ) || ! is_numeric( $number ) ) {
            return $result;
        }
        if ( 1 == $number ) {
            return array_shift( $array );
        }
        if ( $number >= count( $array ) ) {
            $result = $array;
            $array = array();
            return $result;
        }
        for ( $i = 0; $i < $number; $i++ ) {
            $item = array_shift( $array );
            array_push( $result, $item );
        }
        return $result;
    }

    public static function timezone_hcm() {
        date_default_timezone_set( 'Asia/Ho_Chi_Minh' );
    }

    public static function set_vietnam_timezone() {
        self::timezone_hcm();
    }

    public static function set_default_timezone( $timezone_string ) {
        date_default_timezone_set( $timezone_string );
    }

    public static function get_input_number( $value ) {
        return intval($value);
    }

    public static function add_punctuation( $text, $punc ) {
        if ( empty( $text ) ) {
            return $text;
        }
        $char = self::get_last_char( $text );
        if ( $punc != $char ) {
            $text .= $punc;
        }
        return $text;
    }

	public static function replace_html_mark($search, $replace, $html_tag) {
		$html_tag = str_replace($search, $replace, $html_tag);
	}

    public static function add_punctuation_mark( $text, $punc ) {
        return self::add_punctuation( $text, $punc );
    }

    public static function add_dotted( $text ) {
        return self::add_punctuation( $text, '.' );
    }

    public static function add_colon( $text ) {
        return self::add_punctuation( $text, ':' );
    }

    public static function add_exclamation_mark( $text ) {
        return self::add_punctuation( $text, '!' );
    }

    public static function add_commas( $text ) {
        return self::add_punctuation( $text, ',' );
    }

    public static function remove_punctuation( $str ) {
        $last_char = self::get_last_char( $str );
        if( ')' == $last_char ) {
            return $str;
        }
        return preg_replace( '/^\PL+|\PL\z/', '', $str );
    }

    public static function is_punctuation( $char ) {
        $punctuations = array( '.', '!', ':', ',', ';', '?' );
        if ( in_array( $char, $punctuations ) ) {
            return true;
        }
        return false;
    }

    public static function get_last_char( $str ) {
        return mb_substr( $str, -1 );
    }

    public static function get_punctuation( $str ) {
        $punctuation = self::get_last_char( $str );
        if ( ! self::is_punctuation( $punctuation ) ) {
            $punctuation = '';
        }
        return $punctuation;
    }

    public static function is_number( $number ) {
        return is_numeric( $number );
    }

    public static function lowercase( $str, $charset = 'UTF-8' ) {
        return mb_strtolower( $str, $charset );
    }

    public static function get_first_char( $string, $encoding = 'utf-8' ) {
        $result = '';
        if ( ! empty( $string ) ) {
            $result = mb_substr( $string, 0, 1, $encoding );
        }
        return $result;
    }

    public static function uppercase_first_char( $string, $encoding = 'utf-8' ) {
        $first_char = self::get_first_char( $string, $encoding );
        $len = mb_strlen( $string, $encoding );
        $then = mb_substr( $string, 1, $len - 1, $encoding );
        $first_char = mb_strtoupper( $first_char, $encoding );
        return $first_char . $then;
    }

    public static function uppercase_only_first_char( $string, $encoding = 'utf-8' ) {
        $string = self::strtolower( $string, $encoding );
        $string = self::uppercase_first_char( $string, $encoding );
        return $string;
    }

    public static function strtolower( $string, $encoding = 'utf-8' ) {
        return self::lowercase( $string, $encoding );
    }

    public static function strtoupper( $string, $encoding = 'utf-8' ) {
        return self::uppercase( $string, $encoding );
    }

    public static function uppercase( $str, $charset = 'UTF-8' ) {
        return mb_strtoupper( $str, $charset );
    }

    public static function get_current_date( $format = 'd-m-Y' ) {
        self::timezone_hcm();
        return date( $format );
    }

    public static function get_last_date_of_month() {
        return date('Y-m-t');
    }

    public static function get_lastday_of_month_timestamp() {
        $lastday = self::get_last_date_of_month();
        $lastday_timestamp = strtotime($lastday);
        return $lastday_timestamp;
    }

    public static function get_current_url() {
        return $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public static function get_current_date_time( $format = 'd-m-Y H:i:s', $timezone_string = '' ) {
        if ( empty( $timezone_string ) ) {
            self::timezone_hcm();
        } else {
            self::set_default_timezone( $timezone_string );
        }
        return date( $format );
    }

    public static function get_week_of_date( $date ) {
        return date( 'W', strtotime( $date ) );
    }

    public static function get_month_of_date( $date ) {
        return date( 'n', strtotime( $date ) );
    }

    public static function generate_token() {
        return md5( uniqid( mt_rand(), true ) );
    }

    public static function percentage( $val1, $val2, $precision ) {
        if($val2 == 0) {
            return 0;
        }
        $res = 100 - round( ($val1 / $val2) * 100, $precision );
        return $res;
    }

    public static function str_unique( $str, $split = ' ' ) {
        $str = explode( $split, $str );
        $str = array_unique( $str );
        $str = implode( $split, $str );
        return $str;
    }

    public static function is_image_url( $url ) {
        $img_formats = array( 'png', 'jpg', 'jpeg', 'gif', 'tiff', 'bmp' );
        $path_info = pathinfo( $url );
        $extension = isset( $path_info['extension']) ? $path_info['extension'] : '';
        if ( in_array( strtolower( $extension ), $img_formats ) ) {
            return true;
        }
        return false;
    }

    public static function is_image_valid( $image_url ) {
        return self::is_image_url( $image_url );
    }

    public static function is_valid_image( $img_url ) {
        return self::is_image_url( $img_url );
    }

    public static function strip_shortcode( $string ) {
        $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
        $replace = '';
        return preg_replace( $pattern, $replace, $string );
    }

    public static function get_value_by_key( $array_value, $key ) {
        return isset( $array_value[ $key ] ) ? $array_value[ $key ] : '';
    }

    public static function strlen( $string, $encoding = 'utf-8' ) {
        return mb_strlen( $string, $encoding );
    }

    public static function get_first_image( $content ) {
        $doc = new DOMDocument();
        @$doc->loadHTML( $content );
        $xpath = new DOMXPath( $doc );
        $src = $xpath->evaluate( 'string(//img/@src)' );
        return $src;
    }

    public static function get_image_source($image) {
        return self::get_first_image($image);
    }

    public static function get_all_image_from_string( $data ) {
        preg_match_all( '/<img[^>]+>/i', $data, $matches );
        $matches = isset($matches[0]) ? $matches[0] : array();
        return $matches;
    }

    public static function get_all_image_html_from_string( $data ) {
        $matches = self::get_all_image_from_string( $data );
        $result = array();
        foreach ( $matches as $image ) {
            if ( is_array( $image ) ) {
                foreach ( $image as $new_image ) {
                    array_push( $result, $new_image );
                }
            } else {
                array_push( $result, $image );
            }
        }
        return $result;
    }

    public static function remove_all_image_from_string( $data ) {
        $data = preg_replace( '/<img[^>]+\>/i', '', $data );
        return $data;
    }

    public static function count_image( $content ) {
        return self::count_html_tag( $content, 'img' );
    }

    public static function count_html_tag( $content, $tag_name ) {
        $doc = new DOMDocument();
        @$doc->loadHTML( $content );
        $tags = $doc->getElementsByTagName( $tag_name );
        return $tags->length;
    }

    public static function remove_vietnamese( $string ) {
        $characters = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ( $characters as $key => $value ) {
            $string = preg_replace( "/($value)/i", $key, $string );
        }
        return $string;
    }

    public static function strip_bbcode( $string ) {
        return self::strip_shortcode( $string );
    }

    public static function strip_tag_from_string($string, $tags = '') {
        $tags = (array)$tags;
        $args = func_get_args();
        $text = array_shift($args);
        $tags = func_num_args() > 2 ? array_diff($args, array($text))  : (array)$tags;
        foreach ($tags as $tag){
            if(preg_match_all('/<'.$tag.'[^>]*>(.*)<\/'.$tag.'>/iU', $text, $found)){
                $text = str_replace($found[0],$found[1],$text);
            }
        }
        return $text;
    }

    public static function remove_all_link($string) {
        $result = self::strip_tag_from_string($string, 'a');
        return $result;
    }

    public static function paragraph_to_array( $list_paragraph ) {
        $list_paragraph = str_replace( '</p>', '', $list_paragraph );
        $list_paragraph = explode( '<p>', $list_paragraph );
        return array_filter( $list_paragraph );
    }

    public static function is_favicon_url( $url ) {
        $favicon_formats = array( 'png', 'ico' );
        $path_info = pathinfo( $url );
        $extension = isset( $path_info['extension']) ? $path_info['extension'] : '';
        if ( in_array( strtolower( $extension), $favicon_formats ) ) {
            return true;
        }
        return false;
    }

    public static function implode_all( $arr, $split = '~' ) {
        if ( ! is_array( $arr )) {
            return $arr;
        }
        $result = '';
        foreach ( $arr as $value ) {
            if ( empty( $value ) ) {
                continue;
            }
            if ( is_array( $value ) ) {
                $result .= self::implode_all( $value, $split ) . $split;
            } else {
                $result .= $value . $split;
            }
        }
        $result = trim( $result, $split );
        return $result;
    }

    public static function count_character( $string ) {
        $new_string = strip_tags( $string );
        return str_word_count( $new_string );
    }

    public static function ip_details( $ip ) {
        if ( ! self::is_ip_valid( $ip ) ) {
            return array();
        }
        $json = file_get_contents( "http://ipinfo.io/{$ip}" );
        $details = json_decode( $json );
        $details = (array) $details;
        return $details;
    }

    public static function get_ip_detail( $ip ) {
        return self::ip_details( $ip );
    }

    public static function ip_info_geoplugin( $ip = null, $purpose = 'location', $deep_detect = true ) {
        $output = null;
        if ( ! self::is_ip_valid( $ip ) ) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if ( $deep_detect ) {
                if ( filter_var( @$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP ) ) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                if ( filter_var( @$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP ) ) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
            }
        }
        $purpose = str_replace( array( 'name', '\n', '\t', ' ', '-', '_' ), null, strtolower( trim( $purpose ) ) );
        $support = array( 'country', 'countrycode', 'state', 'region', 'city', 'location', 'address' );
        $continents = array(
            'AF' => 'Africa',
            'AN' => 'Antarctica',
            'AS' => 'Asia',
            'EU' => 'Europe',
            'OC' => 'Australia (Oceania)',
            'NA' => 'North America',
            'SA' => 'South America'
        );
        if ( self::is_ip_valid( $ip ) && in_array( $purpose, $support ) ) {
            $ipdat = @json_decode( file_get_contents( 'http://www.geoplugin.net/json.gp?ip=' . $ip ) );
            if ( 2 == @strlen( trim( $ipdat->geoplugin_countryCode) ) ) {
                switch ( $purpose ) {
                    case 'location':
                        $output = array(
                            'city'              => @$ipdat->geoplugin_city,
                            'state'             => @$ipdat->geoplugin_regionName,
                            'country'           => @$ipdat->geoplugin_countryName,
                            'country_code'      => @$ipdat->geoplugin_countryCode,
                            'continent'         => @$continents[ strtoupper( $ipdat->geoplugin_continentCode ) ],
                            'continent_code'    => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case 'address':
                        $address = array( $ipdat->geoplugin_countryName );
                        if ( 1 <= @strlen( $ipdat->geoplugin_regionName ) ) {
                            $address[] = $ipdat->geoplugin_regionName;
                        }
                        if ( 1 <= @strlen($ipdat->geoplugin_city) ) {
                            $address[] = $ipdat->geoplugin_city;
                        }
                        $output = implode( ', ', array_reverse( $address ) );
                        break;
                    case 'city':
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case 'state':
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case 'region':
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case 'country':
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case 'countrycode':
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

    public static function get_geo_info( $ip_address ) {
        $result = '';
        if ( self::is_ip_valid( $ip_address ) ) {
            $host = 'http://www.geoplugin.net/php.gp?ip=' . $ip_address;
            if ( function_exists( 'curl_init' ) ) {
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $host );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch, CURLOPT_USERAGENT, 'SB Geo' );
                $result = curl_exec( $ch );
                curl_close ( $ch );
            } elseif ( ini_get( 'allow_url_fopen' ) ) {
                $result = file_get_contents( $host, 'r' );
            }
        }
        $result = unserialize($result);
        return $result;
    }

    public static function is_geoplugin_valid( $geo ) {
        if ( is_array( $geo ) && isset( $geo['geoplugin_status'] ) && 200 == $geo['geoplugin_status'] ) {
            return true;
        }
        return false;
    }

    public static function is_today( $date ) {
        if ( date( 'Ymd' ) == date( 'Ymd' , strtotime( $date ) ) ) {
            return true;
        }
        return false;
    }

    public static function get_country_code_by_ip( $ip_address ) {
        $result = '';
        $info = self::get_ip_detail( $ip_address );
        if ( isset( $info['country'] ) ) {
            $result = $info['country'];
        }
        if ( empty( $result ) ) {
            $result = self::ip_info_geoplugin( $ip_address, 'countrycode' );
        }
        return $result;
    }

    public static function get_domain_name( $url ) {
        $parse = parse_url( $url );
        $result = isset( $parse['host'] ) ? $parse['host'] : '';
        return $result;
    }

    public static function get_domain_name_full($url) {
        $domain = self::get_domain_name($url);
        $domain = str_replace('www.', '', $domain);
        return $domain;
    }

    public static function get_root_domain($url) {
        $domain_name = self::get_domain_name($url);
        $data = explode('.', $domain_name);
        while(count($data) > 2) {
            array_shift($data);
        }
        $domain_name = implode('.', $data);
        $last = $data[count($data) - 1];
        if('localhost' == $last || strlen($last) > 6) {
            $domain_name = $last;
        }
        return $domain_name;
    }

    public static function callback_exists($callback) {
        if(empty($callback) || (!is_array($callback) && !function_exists($callback)) || (is_array($callback) && count($callback) != 2) || (is_array($callback) && !method_exists($callback[0], $callback[1]))) {
            return false;
        }
        return true;
    }

    public static function currency_vnd_suffix() {
        return '₫';
    }

    public static function get_domain_name_only($url) {
        $root_domain = self::get_root_domain($url);
        $data = explode('.', $root_domain);
        return array_shift($data);
    }

    public static function get_domain_name_with_http( $url ) {
        $url = self::strtolower( $url );
        $domain_name = self::get_domain_name( $url );
        return self::add_http_to_url( $domain_name );
    }

    public static function add_http_to_url( $url ) {
        $url = self::strtolower( $url );
        if ( ! empty( $url ) && ! preg_match( '~^(?:f|ht)tps?://~i', $url ) ) {
            $url = 'http://' . $url;
        }
        return $url;
    }

    public static function ping_domain( $domain_name ) {
        $start_time = microtime( true );
        $file = @fsockopen( $domain_name, 80, $errno, $errstr, 10 );
        $stop_time = microtime( true );
        $status = 0;
        if ( ! $file ) {
            $status = -1;
        } else {
            fclose( $file );
            $status = ( $stop_time - $start_time ) * 1000;
            $status = floor( $status );
        }
        return $status;
    }

    public static function is_domain_alive( $domain_name ) {
        $status = self::ping_domain( $domain_name );
        if( -1 != $status ) {
            return true;
        }
        return false;
    }

    public static function is_url_alive( $url ) {
        $domain = self::get_domain_name( $url );
        return self::is_domain_alive( $domain );
    }

    public static function get_one_in_many_if_empty( $current_value, $array_value ) {
        if( empty( $current_value ) ) {
            $current_value = self::get_one_in_many( $array_value );
        }
        return $current_value;
    }

    public static function get_file_extension( $file_name ) {
        return pathinfo( $file_name, PATHINFO_EXTENSION );
    }

    public static function remove_file_extension( $file_name ) {
        return self::get_file_name_without_extension( $file_name );
    }

    public static function remove_min_css_extension( $file_name ) {
        return self::remove_file_extension( $file_name );
    }

    public static function add_file_extension( $file_name, $extension ) {
        return $file_name . '.' . $extension;
    }

    public static function change_file_extension( $file_name, $extension ) {
        $file_name = self::remove_file_extension( $file_name );
        return $file_name . '.' . $extension;
    }

    public static function get_file_name_without_extension( $file_name ) {
        $extension = self::get_file_extension( $file_name );
        $file_name = basename( $file_name );
        $file_name = basename( $file_name, '.' . $extension);
        return $file_name;
    }

    public static function get_one_in_many( $array_value ) {
        $result = '';
        if ( is_array( $array_value ) ) {
            foreach ( $array_value as $value ) {
                $result = $value;
                if ( ! empty( $result ) ) {
                    break;
                }
            }
        }
        return $result;
    }

    public static function is_ip_valid( $ip ) {
        if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
            return true;
        }
        return false;
    }

    public static function bool_to_int( $bool_value ) {
        $bool_value = (bool) $bool_value;
        if ( $bool_value ) {
            return 1;
        }
        return 0;
    }

    public static function bool_to_string($bool_value) {
        if($bool_value) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public static function int_to_bool( $int_value ) {
        $result = (bool) $int_value;
        return $result;
    }

    public static function get_part_of( $part, $total ) {
        return round( $part * $total );
    }

    public static function is_ip_vietnam( $ip ) {
        $details = self::ip_details( $ip );
        if ( isset( $details['country'] ) ) {
            $country = $details['country'];
            if ( 'VN' == $country ) {
                return true;
            }
        }
        return false;
    }

    public static function is_email_valid( $email ) {
        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            return true;
        }
        return false;
    }

    public static function get_domain_from_email( $email ) {
        $result = '';
        if ( self::is_email_valid( $email ) ) {
            $parts = explode( '@', $email );
            $result = array_pop( $parts );
        }
        return $result;
    }

    public static function array_sort( $array, $on, $order = SORT_ASC ) {
        $new_array = array();
        $sortable_array = array();
        if ( count( $array ) > 0) {
            foreach ( $array as $k => $v ) {
                if ( is_array( $v ) ) {
                    foreach ( $v as $k2 => $v2 ) {
                        if ( $k2 == $on ) {
                            $sortable_array[ $k ] = $v2;
                        }
                    }
                } else {
                    $sortable_array[ $k ] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort( $sortable_array );
                    break;
                case SORT_DESC:
                    arsort( $sortable_array );
                    break;
            }

            foreach ( $sortable_array as $k => $v ) {
                $new_array[ $k ] = $array[ $k ];
            }
        } else {
            $new_array = $array;
        }

        return $new_array;
    }

    public static function get_path( $file ) {
        return dirname( $file );
    }

    public static function base64_to_jpeg( $base64_string, $output_file ) {
        $folder = self::get_path( $output_file );
        if ( ! file_exists( $folder ) ) {
            mkdir( $folder, 0777, true );
        }
        $ifp = fopen( $output_file, 'wb' );
        $data = explode( ',', $base64_string );
        fwrite( $ifp, base64_decode( $data[1] ) );
        fclose( $ifp );
        return $output_file;
    }

    public static function convert_month_name_to_vietnamese($month_full_name) {
        switch($month_full_name) {
            case 'January':
                $month_full_name = 'Tháng một';
                break;
            case 'February':
                $month_full_name = 'Tháng hai';
                break;
            case 'March':
                $month_full_name = 'Tháng ba';
                break;
            case 'April':
                $month_full_name = 'Tháng tư';
                break;
            case 'May':
                $month_full_name = 'Tháng năm';
                break;
            case 'June':
                $month_full_name = 'Tháng sáu';
                break;
            case 'July':
                $month_full_name = 'Tháng bảy';
                break;
            case 'August':
                $month_full_name = 'Tháng tám';
                break;
            case 'September':
                $month_full_name = 'Tháng chín';
                break;
            case 'October':
                $month_full_name = 'Tháng mười';
                break;
            case 'November':
                $month_full_name = 'Tháng mười một';
                break;
            case 'December':
                $month_full_name = 'Tháng mười hai';
                break;
        }
        return $month_full_name;
    }

    public static function get_current_month_of_year($full = true) {
        $format = 'F';
        if(!$full) {
            $format = 'M';
        }
        return date($format);
    }

    public static function get_current_weekday_and_date_time( $format = 'd/m/Y H:i:s', $args = array() ) {
        return self::current_weekday( $format, $args );
    }

    public static function convert_day_name_to_vietnamese($day_name) {
        $weekday = $day_name;
        switch ( $weekday ) {
            case 'Monday':
                $weekday = 'Thứ hai';
                break;
            case 'Tuesday':
                $weekday = 'Thứ ba';
                break;
            case 'Wednesday':
                $weekday = 'Thứ tư';
                break;
            case 'Thursday':
                $weekday = 'Thứ năm';
                break;
            case 'Friday':
                $weekday = 'Thứ sáu';
                break;
            case 'Saturday':
                $weekday = 'Thứ bảy';
                break;
            case 'Sunday':
                $weekday = 'Chủ nhật';
                break;
        }
        return $weekday;
    }

    public static function current_weekday( $format = 'd/m/Y H:i:s', $args = array() ) {
        self::timezone_hcm();
        $weekday = date( 'l' );
        $labels = isset( $args['labels'] ) ? $args['labels'] : array();
        $separator = isset( $args['separator'] ) ? $args['separator'] : ', ';
        $weekday = self::convert_day_name_to_vietnamese($weekday);
        return $weekday . $separator . date( $format );
    }

    public static function remove_http($url) {
        $disallowed = array( 'http://', 'https://' );
        foreach ( $disallowed as $d ) {
            if ( false !== strpos( $url, $d ) ) {
                return str_replace( $d, '', $url );
            }
        }
        return $url;
    }

    public static function get_session( $key ) {
        return isset ( $_SESSION[ $key ] ) ? $_SESSION[ $key ] : '';
    }

    public static function set_session( $key, $value ) {
        $_SESSION[ $key ] = $value;
    }

    public static function color_hex_to_rgb($color, $opacity = false) {
        $default = 'rgb(0,0,0)';
        if(empty($color)) {
            return $default;
        }
        if($color[0] == '#') {
            $color = substr($color, 1);
        }
        if(strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif(strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }
        $rgb = array_map('hexdec', $hex);
        if($opacity) {
            if(abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(',', $rgb) . ')';
        }
        return $output;
    }

    public static function set_session_array( $key, $value, $args = array() ) {
        $json = isset( $args['json'] ) ? (bool) $args['json'] : false;
        $update = isset( $args['update'] ) ? (bool) $args['update'] : false;
        $old = (array) self::get_session( $key );
        $old = array_filter( $old );
        if ( $update && ! in_array( $value, $old ) ) {
            array_push( $old, $value );
        } else {
            $old = $value;
        }
        if ( $json ) {
            $old = json_encode( $old );
        }
        self::set_session( $key, $old );
    }

    public static function set_cookie( $key, $value, $expire, $domain = '' ) {
        setcookie( $key, $value, $expire, '/', $domain );
    }

    public static function delete_cookie( $key, $expire, $domain = '' ) {
        unset( $_COOKIE[ $key ] );
        self::set_cookie( $key, '', $expire, $domain );
    }

    public static function cookie_enabled() {
        setcookie( 'sb_check_cookie_enabled', 'sb_test_cookie', time() + 3600, '/' );
        $result = false;
        if ( count( $_COOKIE ) > 0 ) {
            $result = true;
        }
        return $result;
    }

    public static function get_cookie_array( $key ) {
        $value = isset( $_COOKIE[ $key ] ) ? $_COOKIE[ $key ] : '';
        $value = trim( $value );
        $value = str_replace( '\\', '', $value );
        $value = unserialize( $value );
        $value = (array) $value;
        $value = array_filter( $value );
        return $value;
    }

    public static function is_monday( $date_string = '' ) {
        if ( empty( $date_string ) ) {
            $date_string = date( 'D' );
        } else {
            $date_string = date( 'D', strtotime( self::string_to_datetime( $date_string ) ) );
        }
        if( $date_string === 'Mon' ) {
            return true;
        }
        return false;
    }

    public static function is_first_day_of_month() {
        $date = date( 'd' );
        if ( '01' == $date ) {
            return true;
        }
        return false;
    }

    public static function string_to_datetime( $string, $format = SB_DATE_TIME_FORMAT ) {
        $string = str_replace( '/', '-', $string );
        $string = trim( $string );
        return date( $format, strtotime( $string ) );
    }

    public static function json_string_to_array( $value ) {
        $value = str_replace( '\\', '', $value );
        if ( ! is_array( $value ) ) {
            $value = json_decode( $value, true );
        }
        return (array) $value;
    }

    public static function string_to_array($delimiter, $text) {
        if(is_array($text)) {
            return $text;
        }
        if(empty($text)) {
            return array();
        }
        $result = explode($delimiter, $text);
        $result = array_filter($result);
        return $result;
    }

    public static function to_array($needle, $filter_and_unique = true) {
        $result = $needle;
        if(!is_array($result)) {
            $result = (array)$result;
        }
        if($filter_and_unique) {
            $result = array_filter($result);
            $result = array_unique($result);
        }
        return $result;
    }

    public static function get_cookie( $key ) {
        return isset( $_COOKIE[ $key ] ) ? $_COOKIE[ $key ] : '';
    }

    public static function set_cookie_minute( $key, $value, $minute, $domain = '' ) {
        self::set_cookie( $key, $value, time() + ( MINUTE_IN_SECONDS * $minute ), $domain );
    }

    public static function set_cookie_hour( $key, $value, $hour, $domain = '' ) {
        $hour *= MINUTE_IN_SECONDS;
        self::set_cookie_minute( $key, $value, $hour, $domain );
    }

    public static function set_cookie_day( $key, $value, $day, $domain = '' ) {
        $day *= 24;
        self::set_cookie_hour( $key, $value, $day, $domain );
    }

    public static function set_cookie_week( $key, $value, $week, $domain = '' ) {
        $week *= 7;
        self::set_cookie_day( $key, $value, $week, $domain );
    }

    public static function set_cookie_month( $key, $value, $month, $domain = '' ) {
        $month *= 30;
        self::set_cookie_day( $key, $value, $month, $domain );
    }

    public static function sort_array_by_key_array( $array = array(), $order = array() ) {
        $ordered = array();
        foreach ( $order as $key ) {
            if ( is_array( $key ) ) {
                continue;
            }
            if ( array_key_exists( $key, $array ) ) {
                $ordered[ $key ] = $array[ $key ];
                unset( $array[ $key ] );
            }
        }
        return $ordered + $array;
    }

    public static function is_valid_url( $url ) {
        return self::is_url_valid( $url );
    }

    public static function is_url( $url ) {
        return self::is_valid_url( $url );
    }

    public static function is_url_valid( $url ) {
        if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
            return true;
        }
        return false;
    }

    public static function is_image_url_exists( $image_url ) {
        if( ! @file_get_contents( $image_url ) ) {
            return false;
        }
        return true;
    }

    public static function get_checkbox_value( $value ) {
        if( isset( $value ) && (bool) $value ) {
            return 1;
        }
        return 0;
    }

    public static function get_single_line_value( $value ) {
        return strip_tags( stripslashes( $value ) );
    }

    public static function is_string_contain( $string, $key ) {
        if(empty($key) && !empty($string)) {
            return false;
        }
        if ( false !== strpos( $string, $key ) ) {
            return true;
        }
        return false;
    }

    public static function str_contains( $string, $key ) {
        return self::is_string_contain( $string, $key );
    }

    public static function get_all_safe_char( $special_char = false ) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ( $special_char ) {
            $characters .= '{}#,!_@^';
            $characters .= '():.|`$';
            $characters .= '[];?=+-*~%';
        }
        return $characters;
    }

    public static function get_safe_characters($special_char = false) {
        return self::get_all_safe_char($special_char);
    }

    public static function subtract_array($array_value, $substract_array) {
        foreach($array_value as $key => $value) {
            foreach($substract_array as $compare) {
                if($compare == $value) {
                    unset($array_value[$key]);
                }
            }
        }
        return $array_value;
    }

    public static function random_string_number( $length = 6 ) {
        $characters = '0123456789';
        $len = strlen( $characters );
        $result = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $random_char = $characters[ rand( 0, $len - 1 ) ];
            $result .= $random_char;
        }
        return $result;
    }

    public static function random_string( $length = 10, $special_char = false ) {
        $characters = self::get_all_safe_char( $special_char );
        $len = strlen( $characters );
        $result = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $random_char = $characters[ rand(0, $len - 1 ) ];
            $result .= $random_char;
        }
        return $result;
    }

    public static function add_string_unique( $old_string, $text ) {
        if ( !self::is_string_contain( $old_string, $text ) ) {
            $old_string .= $text;
        }
        $old_string = trim( $old_string );
        return $old_string;
    }

    public static function get_version() {
        return phpversion();
    }

    public static function compare_version($compare_version, $compare) {
        return version_compare(self::get_version(), $compare_version, $compare);
    }

    public static function is_session_started() {
        $session_started = true;
        if(self::compare_version('5.4', '>=')) {
            if(PHP_SESSION_NONE == session_status()) {
                $session_started = false;
            }
        } else {
            if('' == session_id()) {
                $session_started = false;
            }
        }
        return $session_started;
    }

    public static function session_start() {
        $session_started = self::is_session_started();
        if(!$session_started) {
            session_start();
        }
    }

    public static function get_pc_ip() {
        $result = '';
        if ( function_exists( 'getHostByName' ) ) {
            if (self::compare_version('5.3.0', '<') && function_exists( 'php_uname' ) ) {
                $result = getHostByName( php_uname( 'n' ) );
            } elseif( function_exists( 'getHostName' ) ) {
                $result = getHostByName( getHostName() );
            }
        }
        return $result;
    }

    public static function get_pc_name() {
        $result = '';
        if ( function_exists( 'gethostname' ) ) {
            $result = gethostname();
        } else {
            $result = php_uname( 'n' );
        }
        return $result;
    }

    public static function count_next_day( $from, $to ) {
        $sec_from = strtotime ( date( SB_DATE_TIME_FORMAT, strtotime( $from ) ) );
        $sec_to = strtotime ( date( SB_DATE_TIME_FORMAT, strtotime( $to ) ) );
        $seconds =  $sec_to - $sec_from;
        $days = $seconds / DAY_IN_SECONDS;
        $days = ceil( $days );
        return abs( $days );
    }

    public static function is_this_week_day( $date ) {
        if ( date( 'Ymd' ) == date( 'Ymd', strtotime( $date ) ) ) {
            return true;
        }
        return false;
    }

    public static function is_yesterday( $date ) {
        if ( date( 'Ymd', strtotime( $date ) ) == date( 'Ymd', strtotime( self::get_current_date_time() ) - DAY_IN_SECONDS ) ) {
            return true;
        }
        return false;
    }

    public static function get_next_time_diff( $args = array() ) {
        $from = '';
        $to = '';
        $text_before = '';
        extract( $args, EXTR_OVERWRITE );
        $days = self::count_next_day( $from, $to );
        $result = '';
        if ( 1 == $days && self::is_today( $to ) ) {
            return 'Today';
        } elseif( 7 > $days ) {
            $result = self::_n( $days, '1 day', '%d days');
        } elseif( 7 <= $days && 30 > $days ) {
            $week = round( $days / 7, 0 );
            $result = self::_n( $week, '1 week', '%d weeks' );
        } elseif( 30 <= $days && 365 > $days ) {
            $value = round( $days / 30, 0 );
            $result = self::_n( $value, '1 month', '%d months' );
        } else {
            $value = round( $days / 365, 0 );
            $result = self::_n( $value, '1 year', '%d years' );
        }
        $result = $text_before . ' ' . $result;
        $result = trim( $result );
        return $result;
    }

    public static function _n( $number, $text_one, $text_many ) {
        if ( $number < 2 ) {
            return sprintf( __( $text_one, 'sb-theme' ), $number );
        }
        return sprintf( __( $text_many, 'sb-theme'), $number );
    }

}