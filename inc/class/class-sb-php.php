<?php
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
	
	public static function is_valid_url($url) {
		if(filter_var($url, FILTER_VALIDATE_URL)) {
			return true;
		}
		return false;
	}
}
?>