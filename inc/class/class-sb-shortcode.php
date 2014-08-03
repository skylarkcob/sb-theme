<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SB_Shortcode {
	public function __construct() {
		global $sb_enable_3dfile;
		if($sb_enable_3dfile) {
			add_shortcode('stl', array($this, 'stl_func'));
		}
	}
	
	public function stl_func($atts = array(), $content = null) {
		extract(shortcode_atts(array('src' => '', 'provider' => '123dapp'), $atts));
		$kq = '';
		if(empty($src) && !empty($content)) {
			$src = $content;
		}
		if(!empty($src)) {
			$blob = 'blob/';
			if(false !== strpos($src, 'github')) {
				$src = str_replace($blob, '', $src);
				$provider = 'github';
			}
			if('github' == strtolower($provider)) {
				$src = SB_PHP::remove_http($src);
				$src = str_replace('github.com', 'https://raw.githubusercontent.com', $src);
				$src = 'https://render.githubusercontent.com/view/3d?url='.$src;
			} else {
				$src = 'http://apps.123dapp.com/embeddedview/?url='.$src.'&amp;showthumbnail=true';
			}
			$kq = '<div onmousewheel="return false;" class="file-embed">';
			$kq .= '<iframe src="'.$src.'" frameborder="0"></iframe>';
			$kq .= '</div>';
		}
		return $kq;
	}
}