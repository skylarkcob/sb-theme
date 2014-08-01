<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<?php
class SB_Widget {
	public $widgets = array(
		'SB_Post_Widget',
		'SB_Banner_Widget',
		'SB_Tab_Widget',
        'SB_Support_Widget',
        'SB_Link_Widget'
	);
	
	private $use_widgets = array();
	
	public function __construct() {
		
		global $sb_enable_widgets;
		$sb_enable_widgets = (array)$sb_enable_widgets;
		$this->use_widgets = $sb_enable_widgets;
		$this->load_all();
		foreach ( $sb_enable_widgets as $widget ) {		
			if( in_array( $widget, $this->widgets ) && class_exists( $widget ) ) {
				$this->register( $widget );
			}
		}
	}
	
	private function load_all() {
		foreach ( $this->use_widgets as $widget ) {
			$name = str_replace( '_', '-', strtolower( $widget ) );
			$this->load( $name );
		}
	}
	
	private function load($name) {
		$path = SB_CLASS_PATH . '/widgets/class-' . $name . '.php';
		if(file_exists($path)) {
			include $path;
		}
	}
	
	public function register($name) {		
		if(class_exists($name)) {
			register_widget($name);
		}
	}
}
?>