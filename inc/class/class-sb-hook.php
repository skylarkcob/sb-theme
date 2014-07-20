<?php
if ( !defined( 'ABSPATH' ) ) exit;

class SB_Hook {
	private $styles = array();
	private $scripts = array();
	private $sidebars = array();
	public function __construct() {
		$this->style_init();
		$this->move_jquery_to_footer();
		$this->script_init();
		$this->run();
	}
	
	private function style_init() {
		$this->styles['bootstrap-style'] = SB_LIB_URI . '/bootstrap/css/bootstrap.min.css';
		$this->styles['font-awesome-style'] = SB_LIB_URI . '/font-awesome/css/font-awesome.min.css';
		$this->styles['superfish-style'] = SB_LIB_URI . '/superfish/css/superfish.min.css';
		$this->styles['sb-style'] = SB_CSS_URI . '/sb-style.css';
		$main_style = SB_THEME_PATH . "/main-style.css";
		if(file_exists($main_style)) {
			$this->styles['sbtheme-style'] = SB_THEME_URI . "/main-style.css";
		}
	}
	
	private function script_init() {
		global $sb_enable_3dfile;
		array_push($this->scripts, array('bootstrap', SB_LIB_URI . '/bootstrap/js/bootstrap.min.js', array()));
		array_push($this->scripts, array('superfish', SB_LIB_URI . '/superfish/js/superfish.min.js', array('jquery', 'hoverIntent')));
		array_push($this->scripts, array('sbtheme', SB_JS_URI . '/sb-script.js', array('jquery')));
		array_push($this->scripts, array('addthis', SB_JS_URI . '/addthis_widget.js#pubid=ra-4e8109ea4780ac8d', array()));
		$main_script = SB_THEME_PATH . "/js/sbtheme-script.js";
		if(file_exists($main_script)) {
			array_push($this->scripts, array('sbtheme-script', SB_THEME_URI . '/js/sbtheme-script.js', array('sbtheme')));
		}
	}
	
	public function allow_upload_stl_file($mimes) {
		return array_merge($mimes, array('stl' => '3d/stl'));
	}
	
	private function sidebar_init() {
		$options = SB_WP::option();
		$widget = new SB_Widget();
		$this->sidebars[] = $this->register_sidebar('primary', 'Main Sidebar', SB_PHP::add_dotted(SB_Theme::phrase("main_sidebar_description")));
		do_action("sbtheme_widget_area");
		if(isset($options['enable_leaderboard_ads']) && (bool)$options['enable_leaderboard_ads']) {
			SB_WP::register_sidebar( 'leaderboard-ads', "Leaderboard Banner", SB_PHP::add_dotted(SB_Theme::phrase("leaderboard_banner_description")));
		}
		if(isset($options['enable_float_ads']) && (bool)$options['enable_float_ads']) {
			SB_WP::register_sidebar( 'float-ads-left', "Float Ads Left", SB_PHP::add_dotted(SB_Theme::phrase("float_ads_left_description")));
			SB_WP::register_sidebar( 'float-ads-right', "Float Ads Right", SB_PHP::add_dotted(SB_Theme::phrase("float_ads_right_description")));
		}
		
	}
	
	public function sbtheme_widget_init() {
		$this->sidebar_init();
	}
	
	public function sbtheme_sidebar_widget_hook() {
		add_action("widgets_init", array($this, 'sbtheme_widget_init'));
		//add_filter('dynamic_sidebar_params', array($this, 'sbtheme_widget_param'));
		//add_filter('widget_title', array($this, 'default_widget_title'), 10, 3);
	}
	
	private function move_jquery_to_footer() {
		if(!is_admin()) {
			$jquery = ABSPATH . WPINC . "/js/jquery/jquery.js";
			$jquery_migrate = ABSPATH . WPINC . "/js/jquery/jquery-migrate.min.js";
			if(file_exists($jquery) && file_exists($jquery_migrate)) {
				wp_dequeue_script('jquery');
				wp_deregister_script('jquery');
				wp_dequeue_script('jquery-migrate');
				wp_deregister_script('jquery-migrate');
				wp_register_script('jquery-migrate', includes_url("js/jquery/jquery-migrate.min.js"), array(), false, true);
				wp_register_script('jquery', includes_url("js/jquery/jquery.js"), array('jquery-migrate'), false, true);
				wp_enqueue_script('jquery');
			}
		}
	}
	
	public function script_and_style() {
		// Enqueue style
		foreach($this->styles as $key => $url) {
			wp_register_style($key, $url);
			wp_enqueue_style($key);
		}
		
		// Enqueue script
		foreach($this->scripts as $value) {
			$options = SB_WP::option();
			$handle = $value[0];
            if("sbtheme-script" == $handle) {
                $file = SB_THEME_PATH . "/js/sbtheme-script.js";
                if(!file_exists($file)) continue;
            }
			if("addthis" == $handle) {
				if(!SB_WP::utility_enabled('enable_addthis')) continue;
			}
			wp_register_script($handle, $value[1], $value[2], false, true);
			wp_enqueue_script($handle);
			if("sbtheme" == $handle) {
				wp_localize_script( $handle, 'sbAjax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
			}
		}
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
	}
	
	public static function sbtheme_set_html_mail(){
		return "text/html";
	}
	
	public function sbtheme_customize_init($wp_customize) {
		$customize = new SB_Customize();
		$customize->init($wp_customize);
	}
	
	private function run() {
		
		add_filter('intermediate_image_sizes_advanced', array($this, 'remove_default_image_sizes'));
		$this->sbtheme_sidebar_widget_hook();
		add_action('wp_enqueue_scripts', array($this, 'script_and_style'));
		add_action('after_setup_theme', array($this, 'sbtheme_setup'));
		add_action( 'customize_preview_init', array($this, 'sbtheme_customize_script') );
		add_filter('excerpt_more', array($this, 'change_excerpt_more'));
		add_filter( 'wp_mail_content_type', array($this, 'sbtheme_set_html_mail' ));
		if(SB_WP::utility_enabled('enable_3dfile')) {
			add_filter('upload_mimes', array($this, 'allow_upload_stl_file'));
		}
		
		if(SB_WP::utility_enabled('enable_links_manager')) {
			add_filter( 'pre_option_link_manager_enabled', '__return_true' );
		}
		
		add_action('wp_footer', array($this, 'sbtheme_footer_element'));
		
		$this->sbtheme_admin_hook();
		add_action('init', array($this, 'sbtheme_init'));
		$this->sbtheme_custom_login_page();
	}
	
	public static function sbtheme_custom_login_page() {
		if(SB_WP::is_login_page()) {
			add_action( 'login_enqueue_scripts', array('SB_Hook', 'sbtheme_login_style'));
			add_filter( 'login_headerurl', array('SB_Hook', 'sbtheme_login_form_logo_url'));
			add_filter('login_headertitle', array('SB_Hook', 'sbtheme_login_form_logo_description'));
			add_filter('gettext', array('SB_Hook', 'sbtheme_login_form_text'), 20, 3);
			add_filter( 'login_errors', array('SB_Hook', 'sbtheme_login_error_message'));
			add_filter('login_message', array('SB_Hook', 'sbtheme_login_message'));
		}
	}
	
	public function sbtheme_login_style() {
		echo '<link media="all" type="text/css" href="'.SB_CSS_URI.'/sb-login-style.css" id="sb-login-style-css" rel="stylesheet">';
	}
	
	public function sbtheme_login_form_logo_url() {
		return home_url('/');
	}
	
	public function sbtheme_login_form_logo_description() {
		return get_bloginfo('description');
	}
	
	public function sbtheme_login_message($message) {
		$action = $_REQUEST['action'];
		if($action == 'register') {
			$message = '<p class="message register">'.SB_PHP::add_dotted(SB_WP::phrase('register_for_this_site')).'</p>';
		} elseif($action == 'lostpassword') {
			$message = '<p class="message">'.SB_PHP::add_dotted(SB_WP::phrase('enter_your_email_to_receive_new_password')).'</p>';
		}
		return $message;
	}
	
	public function sbtheme_login_error_message($error) {
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'lostpassword') {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('error')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('please_enter_your_email_correctly'));
		} elseif(isset($_REQUEST['registration']) && $_REQUEST['registration'] == 'disabled') {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('error')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('you_do_not_have_permission_to_register'));
		} elseif(isset($_REQUEST['action']) && $_REQUEST['action'] == 'register') {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('error')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('please_check_your_information'));
		} else {
			$error = '<strong>'.SB_PHP::add_colon(SB_WP::phrase('login_failed')).'</strong> '.SB_PHP::add_dotted(SB_WP::phrase('please_enter_your_account_correctly'));
		}
		return $error;
	}
	
	public function sbtheme_login_form_text( $translated_text, $text, $domain ) {
		$str_text = SB_PHP::lowercase($translated_text);
		$str_text = trim(trim(trim($str_text, '?'), '.'), ':');
		switch ( $str_text ) {
            case 'log in':
                $translated_text = __( SB_WP::phrase('log_in'), SB_DOMAIN );
                break;
            case 'username':
                $translated_text = __( SB_WP::phrase('username'), SB_DOMAIN );
                break;
			case 'password':
                $translated_text = __( SB_WP::phrase('password'), SB_DOMAIN );
                break;
			case 'remember me':
                $translated_text = __( SB_WP::phrase('remember_me'), SB_DOMAIN );
                break;
			case 'lost your password':
                $translated_text = __( SB_WP::phrase('lost_your_password'), SB_DOMAIN );
                break;
			case '&larr; back to %s':
                $translated_text = __( SB_WP::phrase('back_to_home_page'), SB_DOMAIN );
                break;
			case 'register':
                $translated_text = __( SB_WP::phrase('register'), SB_DOMAIN );
                break;
			case 'e-mail':
                $translated_text = __( SB_WP::phrase('email'), SB_DOMAIN );
                break;
			case 'a password will be e-mailed to you':
                $translated_text = __( SB_PHP::add_dotted(SB_WP::phrase('a_password_will_be_email_to_you')), SB_DOMAIN );
                break;
			case 'username or e-mail':
                $translated_text = __( SB_WP::phrase('username_or_email'), SB_DOMAIN );
                break;
			case 'get new password':
                $translated_text = __( SB_WP::phrase('get_new_password'), SB_DOMAIN );
                break;
			case 'you are now logged out':
                $translated_text = __( SB_WP::phrase('you_are_now_logged_out'), SB_DOMAIN );
                break;
        }
		return $translated_text;
	}
	
	public function sbtheme_footer_element() {
		if(SB_WP::utility_enabled('enable_scroll_top')) {
			$this->scroll_to_top();
		}
		if(SB_WP::utility_enabled('enable_float_ads')) {
			$this->float_ads_init();
		}
	}
	
	public function sbtheme_admin_hook() {
		if(is_admin()) {
			add_action('admin_enqueue_scripts', array($this, 'sbtheme_admin_script_and_style'));
			$this->rich_editor_init();
			$this->media_upload_init();
			add_action('admin_menu', array($this, 'sbtheme_custom_menu_page'), 102);
			//add_action( 'customize_register', array($this, 'sbtheme_customize_init' ));
			add_action('admin_init', array($this, 'sbtheme_admin_init'), 99);
		}
	}
	
	public function sbtheme_custom_menu_page() {
		remove_submenu_page( 'themes.php', 'customize.php' );
		remove_submenu_page( 'themes.php', 'theme-editor.php' );
	}
	
	public function change_excerpt_more($more) {
		return '...';
	}
	
	function remove_default_image_sizes( $sizes ) {
		if(isset($sizes['thumbnail'])) unset( $sizes['thumbnail'] );
		if(isset($sizes['medium'])) unset( $sizes['medium'] );
		if(isset($sizes['large'])) unset( $sizes['large'] );     
		return $sizes;
	}
	
	public static function register_sidebar($id, $name, $description) {
		SB_WP::register_sidebar( $id, $name, $description);
	}
	
	public function media_upload_init() {
		add_thickbox();
		wp_enqueue_script( 'media-upload' );
	}
	
	public function rich_editor_init() {
		wp_enqueue_script( 'word-count' );
		wp_enqueue_script('post');
		if ( user_can_richedit() ) {
			wp_enqueue_script('editor');
		}
	}
	
	public function float_ads_init() {
		SB_Theme::float_ads_sidebar();
	}

	public function sbtheme_widget_param($params) {
		$widget_id = $params[0]['widget_id'];
		$widget_class = SB_WP::build_widget_class($widget_id);
        $params[0]['before_widget'] = '<div id="'.$widget_id.'" class="widget '.$widget_class.'"><div class="widget-wrap">';
        $params[0]['after_widget'] = '</div></div>';
        $params[0]['before_title'] = '<h4 class="widget-title">';
        $params[0]['after_title'] = '</h4>';
        return $params;
    }
	
	public function default_widget_title($title) {
		if(empty($title)) {
			$title = '<span class="no-title"></span>';
		}
		return $title;
	}
	
	public function sbtheme_setup() {
		global $sb_language;
		load_theme_textdomain( SB_DOMAIN, get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 300, 300 );
		add_image_size('thumbnail', 300, 300, false);
		add_image_size('thumbnail_crop', 300, 300, true);
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ));
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery' ));
		register_nav_menus( array(
			'top'		=> __( 'Top menu', 'sbtheme' ),
			'footer'	=> __( 'Footer menu', 'sbtheme' ),
			'primary'	=> __('Primary menu', 'sbtheme')
		));
		if(SB_WP::utility_enabled('enable_shop')) {
			add_theme_support( 'woocommerce' );
			add_image_size('product_large', 600, 600, false);
			add_image_size('product_large_crop', 600, 600, true);
			add_image_size('product_medium', 400, 400, false);
			add_image_size('product_medium_crop', 400, 400, true);
			add_image_size('product_small', 200, 200, false);
			add_image_size('product_small_crop', 200, 200, true);			
		}
		if("vi" == $sb_language->get()) {
			update_option("timezone_string", "Asia/Ho_Chi_Minh");
			update_option("date_format", "d/m/Y");
		}
		$permalink_struct = SB_WP::get_permalink_struct();
		if(empty($permalink_struct)) {
			SB_WP::update_permalink("/%postname%");
		}
		if(SB_WP::bbp_installed() && is_user_logged_in()) {
			add_filter( 'bbp_after_get_the_content_parse_args', array($this, 'bbp_enable_visual_editor'));
			add_filter( 'bbp_get_single_forum_description', array($this, 'bbp_empty_description') );
			add_filter( 'bbp_get_single_topic_description', array($this, 'bbp_empty_description') );
		}
	}
	
	public function bbp_enable_visual_editor( $args = array() ) {
		$args['tinymce'] = true;
		$args['media_buttons'] = true;
		$args['dfw'] = true;
		$args['teeny'] = false;
		return $args;
	}
	
	public function bbp_empty_description() {
		return '';
	}
	
	public function sb_woocommerce_image_size() {
		if(SB_WP::utility_enabled('enable_shop')) {
			$catalog = array(
				'width'		=> '400',
				'height'	=> '400',
				'crop'		=> 1
			);				 
			$single = array(
				'width'		=> '600',
				'height'	=> '600',
				'crop'		=> 1
			);				 
			$thumbnail = array(
				'width'		=> '200',
				'height'	=> '200',
				'crop'		=> 1
			);				
			update_option( 'shop_catalog_image_size', $catalog );
			update_option( 'shop_single_image_size', $single );
			update_option( 'shop_thumbnail_image_size', $thumbnail );
		}
	}
	
	public function sbtheme_admin_script_and_style() {
		wp_register_script('sbtheme-admin', SB_JS_URI . '/sb-admin-script.js', array('jquery'), false, true);
		wp_localize_script( 'sbtheme-admin', 'sbAdminAjax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script('sbtheme-admin');
		
		
		wp_register_style('sbtheme-admin-style', SB_CSS_URI . '/sb-admin-style.css');
		wp_enqueue_style('sbtheme-admin-style');
		
	}
	
	public function sbtheme_customize_script() {
		wp_register_script( 'sb-customize', SB_JS_URI . '/customize.js', array( 'customize-preview' ), false, true );
		wp_enqueue_script('sb-customize');
	}
	
	public function session_init() {
		if(!session_id()) {
			session_start();
		}
	}
	
	public function scroll_to_top() {
		echo '<a id="scroll-to-top" href="#" class="go-top-button"><i class="fa fa-chevron-up"></i></a>';
	}
	
	public function sbtheme_admin_init() {
		add_filter('user_contactmethods', array($this, 'sbtheme_add_user_contact_method'));
	}
	
	public function sbtheme_add_user_contact_method($profile_fields) {
		if(!isset($profile_fields['twitter'])) {
			$profile_fields['twitter'] = 'Twitter URL';
		}
		if(!isset($profile_fields['facebook'])) {
			$profile_fields['facebook'] = 'Facebook URL';
		}
		if(!isset($profile_fields['gplus'])) {
			$profile_fields['gplus'] = 'Google+ URL';
		}
		if(isset($profile_fields['aim'])) {
			unset($profile_fields['aim']);
		}
		return $profile_fields;
	}
	
	public function sbtheme_init() {
		if(is_admin() && SB_WP::utility_enabled('enable_tivi')) {
			global $wp_post_types;
			if(post_type_exists("television")) {
				$television = $wp_post_types['television'];
				if($television) {
					$wp_post_types['television']->menu_icon = SB_Icon::post_type_icon("tivi");
				}
			}
		}
		$this->session_init();
		global $pagenow;
		if ( SB_WP::utility_enabled('enable_shop') && is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
			$this->sb_woocommerce_image_size();
		}
	}
}
?>