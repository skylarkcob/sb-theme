<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SB_Widget {
	private $widgets = null;
	private $use_widgets = null;
	
	public function __construct() {
        $this->init();
		$this->load_all();
	}

    private function init_widget_class_name() {
        $this->widgets = array();
        $this->add_new_widget_class_name("SB_Post_Widget");
        $this->add_new_widget_class_name("SB_Banner_Widget");
        $this->add_new_widget_class_name(("SB_Tab_Widget"));
        $this->add_new_widget_class_name("SB_Support_Widget");
        $this->add_new_widget_class_name("SB_Link_Widget");
    }

    private function add_new_widget_class_name($widget_class_name) {
        if(is_array($this->widgets) && !in_array($widget_class_name, $this->widgets)) {
            array_push($this->widgets, $widget_class_name);
        }
    }

    private function add_to_list($widget_class_name) {
        if(is_array($this->use_widgets) && !in_array($widget_class_name, $this->use_widgets)) {
            array_push($this->use_widgets, $widget_class_name);
        }
    }

    private function init() {
        $this->use_widgets = array();
        $post_widget_enabled = apply_filters("sb_post_widget_enabled", SB_WP::is_sb_post_widget_enabled());
        $banner_widget_enabled = apply_filters("sb_banner_widget_enabled", SB_WP::is_sb_banner_widget_enabled());
        $tab_widget_enabled = apply_filters("sb_tab_widget_enabled", SB_WP::is_sb_tab_widget_enabled());
        $support_widget_enabled = apply_filters("sb_support_widget_enabled", SB_WP::is_sb_support_widget_enabled());
        $link_widget_enabled = apply_filters("sb_link_widget_enabled", SB_WP::is_sb_link_widget_enabled());
        if($post_widget_enabled) {
            $this->add_to_list("SB_Post_Widget");
        }
        if($banner_widget_enabled) {
            $this->add_to_list("SB_Banner_Widget");
        }
        if($tab_widget_enabled) {
            $this->add_to_list("SB_Tab_Widget");
        }
        if($support_widget_enabled) {
            $this->add_to_list("SB_Support_Widget");
        }
        if($link_widget_enabled) {
            $this->add_to_list("SB_Link_Widget");
        }
    }
	
	private function load_all() {
		foreach ( $this->use_widgets as $widget ) {
			$name = str_replace( '_', '-', strtolower( $widget ) );
			$this->load( $name );
            $this->register($widget);
		}
	}
	
	private function load($name) {
		$path = SB_CLASS_PATH . '/widgets/class-' . $name . '.php';
		if(file_exists($path)) {
			include $path;
		}
	}
	
	public function register($widget_name_class) {
		if(class_exists($widget_name_class)) {
			register_widget($widget_name_class);
		}
	}
}