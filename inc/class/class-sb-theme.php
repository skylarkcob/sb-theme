<?php
class SB_Theme {
	public static function header() {
		include SB_TEMPLATE_PATH . "/template-theme-header.php";
	}
	
	public static function footer() {
		include SB_TEMPLATE_PATH . "/template-theme-footer.php";
	}
	
	public static function title() {
		if(is_home()) {
			echo get_bloginfo('name') . ' - ' . get_bloginfo('description');
		} elseif(is_post_type_archive('product')) {
			echo 'Danh sách sản phẩm';
		} elseif(is_tax()) {
			single_term_title();
		} else {
			wp_title('');
		}
	}
}
?>