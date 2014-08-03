<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_PHP {
	public static function url_exists($url) {
		if (!$fp = curl_init($url)) return false;
		return true;
	}
	
	public static function substr($str, $len, $more = '...', $charset = 'UTF-8'){
		$str = html_entity_decode($str, ENT_QUOTES, $charset);
		if(mb_strlen($str, $charset) > $len) {
			$arr = explode(' ', $str);
			$str = mb_substr($str, 0, $len, $charset);
			$arrRes = explode(' ', $str);
			$last = $arr[count($arrRes)-1];
			unset($arr);
			if(strcasecmp($arrRes[count($arrRes)-1], $last)) {
				unset($arrRes[count($arrRes)-1]);
			}
			return implode(' ', $arrRes).$more;
		}
		return $str;
	}
	
	public static function timezone_hcm() {
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		
	}
	
	public static function remove_punctuation($str) {
        $last_char = self::get_last_char($str);
        if(')' == $last_char) {
            return $str;
        }
		return preg_replace('/^\PL+|\PL\z/', '', $str);
	}
	
	public static function is_punctuation($char) {
		$punctuations = array('.', '!', ':', ',', ';', '?');
		if(in_array($char, $punctuations)) {
			return true;
		}
		return false;
	}
	
	public static function get_last_char($str) {
		return mb_substr($str, -1);
	}
	
	public static function get_punctuation($str) {
		$punctuation = SB_PHP::get_last_char($str);
		if(!SB_PHP::is_punctuation($punctuation)) {
			$punctuation = '';
		}
		return $punctuation;
	}
	
	public static function is_number($number) {
		return is_numeric($number);
	}
	
	public static function lowercase($str, $charset = 'UTF-8') {
		return mb_strtolower($str, $charset);
	}
	
	public static function uppercase($str, $charset = 'UTF-8') {
		return mb_strtoupper($str, $charset);
	}
	
	public static function get_current_date($format = 'd-m-Y') {
		self::timezone_hcm();
		return date($format);
	}
	
	public static function get_current_date_time($format = 'd-m-Y H:i:s') {
		self::timezone_hcm();
		return date($format);
	}
	
	public static function generate_token() {
		return md5(uniqid(mt_rand(), true));
	}
	
	public static function percentage($val1, $val2, $precision) {
		$res = 100 - round( ($val1 / $val2) * 100, $precision );		
		return $res;
	}
	
	public static function str_unique($str, $split = ' ') {
		$str = explode($split, $str);
		$str = array_unique($str);
		$str = implode($split, $str);
		return $str;
	}
	
	public static function is_image_url($url) {
		$img_formats = array("png", "jpg", "jpeg", "gif", "tiff", "bmp");
		$path_info = pathinfo($url);
		if (in_array(strtolower($path_info['extension']), $img_formats)) {
			return true;
		}
		return false;
	}

    public static function paragraph_to_array($list_paragraph) {
        $list_paragraph = str_replace("</p>", "", $list_paragraph);
        $list_paragraph = explode("<p>", $list_paragraph);
        return array_filter($list_paragraph);
    }
	
	public static function is_favicon_url($url) {
		$favicon_formats = array("png", "ico");
		$path_info = pathinfo($url);
		if (in_array(strtolower($path_info['extension']), $favicon_formats)) {
			return true;
		}
		return false;
	}
	
	public static function implode_all($arr, $split = '~') {
		if(!is_array($arr)) return $arr;
		$result = "";
		foreach($arr as $value) {
			if(empty($value)) continue;
			if(is_array($value)) {
				$result .= self::implode_all($value, $split).$split;
			}
			else {
				$result .= $value.$split;
			}
		}
		$result = trim($result, $split);
		return $result;
	}
	
	public static function add_exclamation_mark($text) {
        if(empty($text)) {
            return $text;
        }
		return self::add_punctuation($text, '!');
	}
	
	public static function add_commas($text) {
        if(empty($text)) {
            return $text;
        }
		return self::add_punctuation($text, ',');
	}
	
	public static function get_path($file) {
		return dirname($file);
	}
	
	public static function base64_to_jpeg($base64_string, $output_file) {
		$folder = self::get_path($output_file);
		if(!file_exists($folder)) {
			mkdir($folder, 0777, true);
		}
		$ifp = fopen($output_file, "wb"); 
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1])); 
		fclose($ifp);
		return $output_file; 
	}
	
	public static function add_punctuation_mark($text, $punc) {
        if(empty($text)) {
            return $text;
        }
		$char = substr($text, -1);
		if($punc != $char) {
			$text .= $punc;
		}
		return $text;
	}
	
	public static function add_dotted($text) {
		$char = substr($text, -1);
		if('.' != $char) {
			$text .= '.';
		}
		return $text;
	}
	
	public static function add_punctuation($text, $punc) {
        if(empty($text)) {
            return $text;
        }
		$char = substr($text, -1);
		if($punc != $char) {
			$text .= $punc;
		}
		return $text;
	}
	
	public static function add_colon($text) {
		$char = substr($text, -1);
		if(':' != $char) {
			$text .= ':';
		}
		return $text;
	}
	
	function current_weekday($format = 'd/m/Y H:i:s') {
		self::timezone_hcm();
		$weekday = date("l");
		$weekday = strtolower($weekday);
		switch($weekday) {
			case 'monday':
				$weekday = 'Thứ hai';
				break;
			case 'tuesday':
				$weekday = 'Thứ ba';
				break;
			case 'wednesday':
				$weekday = 'Thứ tư';
				break;
			case 'thursday':
				$weekday = 'Thứ năm';
				break;
			case 'friday':
				$weekday = 'Thứ sáu';
				break;
			case 'saturday':
				$weekday = 'Thứ bảy';
				break;
			default:
				$weekday = 'Chủ nhật';
				break;
		}
		return $weekday.', '.date($format);
	}
	
	public static function remove_http($url) {
		$disallowed = array('http://', 'https://');
		foreach($disallowed as $d) {
			if(strpos($url, $d) !== false) {
				return str_replace($d, '', $url);
			}
		}
		return $url;
	}
	
	public static function get_session($key) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
	}
	
	public static function set_session($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public static function set_session_array($key, $value) {
		$old = (array)self::get_session($key);
		if(!in_array($value, $old)) {
			array_push($old, $value);
		}
		self::set_session($key, $old);
	}
	
	public static function is_valid_url($url) {
		if(filter_var($url, FILTER_VALIDATE_URL)) {
			return true;
		}
		return false;
	}
	
	public static function is_url($url) {
		return self::is_valid_url($url);
	}
	
	public static function is_url_valid($url) {
		return self::is_valid_url($url);
	}
	
	public static function get_checkbox_value($value) {
		if(isset($value) && (bool)$value) {
			return 1;
		}
		return 0;
	}
	
	public static function get_single_line_value($value) {
		return strip_tags(stripslashes($value));
	}
	
	public static function is_string_contain($string, $key) {
		if (strpos($string, $key) !== false) {
			return true;
		}
		return false;
	}

    public static function str_contains($string, $key) {
        return self::is_string_contain($string, $key);
    }

    public  static function add_string_unique($old_string, $text) {
        if(!self::is_string_contain($old_string, $text)) {
            $old_string .= $text;
        }
        $old_string = trim($old_string);
        return $old_string;
    }
	
}