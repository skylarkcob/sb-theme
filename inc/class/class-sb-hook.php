<?php
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
		$this->sidebars[] = $this->register_sidebar('primary', 'Main Sidebar', SB_Theme::phrase("main_sidebar_description"));
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
            if("sbtheme-script" == $value[0]) {
                $file = SB_THEME_PATH . "/js/sbtheme-script.js";
                if(!file_exists($file)) continue;
            }
			wp_register_script($value[0], $value[1], $value[2], false, true);
			wp_enqueue_script($value[0]);
			if("sbtheme" == $value[0]) {
				wp_localize_script( $value[0], 'sbAjax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
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
		global $sb_enable_3dfile, $sb_enable_links, $sb_enable_scroll_to_top;
		add_filter('intermediate_image_sizes_advanced', array($this, 'remove_default_image_sizes'));
		$this->sbtheme_sidebar_widget_hook();
		add_action('wp_enqueue_scripts', array($this, 'script_and_style'));
		add_action('after_setup_theme', array($this, 'sbtheme_setup'));
		add_action( 'customize_preview_init', array($this, 'sbtheme_customize_script') );
		add_filter('excerpt_more', array($this, 'change_excerpt_more'));
		add_filter( 'wp_mail_content_type', array($this, 'sbtheme_set_html_mail' ));
		if($sb_enable_3dfile) {
			add_filter('upload_mimes', array($this, 'allow_upload_stl_file'));
		}
		
		if($sb_enable_links) {
			add_filter( 'pre_option_link_manager_enabled', '__return_true' );
		}
		
		add_action('wp_footer', array($this, 'sbtheme_footer_element'));
		
		$this->sbtheme_admin_hook();
		add_action('init', array($this, 'sbtheme_init'));
	}
	
	public function sbtheme_footer_element() {
		$options = SB_WP::option();
		if(isset($options['enable_scroll_top']) && (bool)$options['enable_scroll_top']) {
			$this->scroll_to_top();
		}
		$this->float_ads_init();
	}
	
	public function sbtheme_admin_hook() {
		if(is_admin()) {
			global $sb_media_upload;
			$sb_media_upload = true;
			add_action('admin_enqueue_scripts', array($this, 'sbtheme_admin_script_and_style'));
			if((isset($_GET['page']) && 'sbtheme-option' == $_GET['page']) || $sb_media_upload) {
				$this->rich_editor_init();
				$this->media_upload_init();
			}
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
		SB_Theme::float_ads();
	}

	public function sbtheme_widget_param($params) {
		$widget_id = $params[0]['widget_id'];
		$widget_class = SB_WP::build_widget_class($widget_id);
        $params[0]['before_widget'] = '<section id="'.$widget_id.'" class="widget '.$widget_class.'"><div class="widget-wrap">';
        $params[0]['after_widget'] = '</div></section>';
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
		global $sb_enable_shop, $sb_language;
		
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
		if($sb_enable_shop) {
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
			SB_WP::update_permalink("/%postname%");
		}
	}
	
	public function sb_woocommerce_image_size() {
		global $sb_enable_shop;
		if($sb_enable_shop) {
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
		
	}
	
	public function sbtheme_init() {
		$options = SB_Theme::option();
		if(is_admin() && isset($options['enable_tivi']) && (bool) $options['enable_tivi']) {
			global $wp_post_types;
			if(post_type_exists("television")) {
				$television = $wp_post_types['television'];
				if($television) {
					$wp_post_types['television']->menu_icon = SB_Icon::post_type_icon("tivi");
				}
			}
		}
		$this->session_init();
		global $sb_enable_shop, $pagenow;
		if ( $sb_enable_shop && is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
			$this->sb_woocommerce_image_size();
		}
	}
}
?>