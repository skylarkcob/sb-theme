<?php
class SB_Hook {
	private $styles = array();
	private $scripts = array();
	private $sidebars = array();
	public function __construct() {
		$this->style_init();
		$this->move_jquery_to_footer();
		$this->script_init();
		$this->sidebar_init();
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
        array_push($this->scripts, array('sbtheme-script', SB_THEME_URI . '/js/sbtheme-script.js', array('sbtheme')));
	}
	
	public function allow_upload_stl_file($mimes) {
		return array_merge($mimes, array('stl' => '3d/stl'));
	}
	
	private function sidebar_init() {
		$this->sidebars[] = $this->register_sidebar('primary', 'Primary Sidebar', 'Main sidebar that appears on the left or right.');
	}
	
	private function move_jquery_to_footer() {
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
			if("thingiview" == $value[0]) {
				wp_localize_script($value[0], 'sbStl', array('url' => SB_LIB_URI . "/thingiview/js"));
			}
		}
	}
	
	public function sbtheme_customize_init($wp_customize) {
		$customize = new SB_Customize();
		$customize->init($wp_customize);
	}
	
	private function run() {
		global $pagenow, $sb_enable_shop, $sb_enable_3dfile;
		
		add_action('wp_enqueue_scripts', array($this, 'script_and_style'));
		add_action('after_setup_theme', array($this, 'sbtheme_setup'));
		add_action( 'customize_preview_init', array($this, 'sbtheme_customize_script') );
		add_action( 'customize_register', array($this, 'sbtheme_customize_init' ));
		add_action('init', array($this, 'session_init'), 1);
		
		if ( $sb_enable_shop && is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
			add_action( 'init', array($this, 'sb_woocommerce_image_size'), 1 );
		}
		
		add_filter('widget_title', array($this, 'default_widget_title'), 10, 3);
		add_filter('intermediate_image_sizes_advanced', array($this, 'remove_default_image_sizes'));
		add_filter('excerpt_more', array($this, 'change_excerpt_more'));

		if($sb_enable_3dfile) {
			add_filter('upload_mimes', array($this, 'allow_upload_stl_file'));
		}
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
		register_sidebar(array(
			'name'          => __( $name, 'sbtheme' ),
			'id'            => $id,
			'description'   => __( $description, 'sbtheme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
			'after_widget'  => '</div></div></section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4><div class="widget-content">'
		));
	}


	
	public function default_widget_title($title) {
		if(empty($title)) {
			$title = '<span class="no-title"></span>';
		}
		return $title;
	}
	
	public function sbtheme_setup() {
		global $sb_enable_shop;
		
		load_theme_textdomain( 'sbtheme', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 300, 300 );
		add_image_size('thumbnail', 300, 300, false);
		add_image_size('thumbnail_crop', 300, 300, true);
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		));
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
		));
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
	
	public function sbtheme_customize_script() {
		wp_register_script( 'sb-customize', SB_JS_URI . '/customize.js', array( 'customize-preview' ), false, true );
		wp_enqueue_script('sb-customize');
	}
	
	public function session_init() {
		if(!session_id()) {
			session_start();
		}
	}
}
?>