<?php
class SB_Hook {
	private $styles = array();
	private $scripts = array();
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
		array_push($this->scripts, array('bootstrap', SB_LIB_URI . '/bootstrap/js/bootstrap.min.js', array()));
		array_push($this->scripts, array('superfish', SB_LIB_URI . '/superfish/js/superfish.min.js', array('jquery', 'hoverIntent')));
		array_push($this->scripts, array('sbtheme', SB_JS_URI . '/sb-script.js', array('jquery')));
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
		foreach($this->styles as $key => $url) {
			wp_register_style($key, $url);
			wp_enqueue_style($key);
		}
		foreach($this->scripts as $value) {
			wp_register_script($value[0], $value[1], $value[2], false, true);
			wp_enqueue_script($value[0]);
		}
	}
	
	private function run() {
		add_action('wp_enqueue_scripts', array($this, 'script_and_style'));
		
		add_filter('widget_title', array($this, 'default_widget_title'), 10, 3);
		add_filter('intermediate_image_sizes_advanced', array($this, 'remove_default_image_sizes'));
		add_filter('excerpt_more', array($this, 'change_excerpt_more'));
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
}
?>