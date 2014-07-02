<?php
class SB_Customize {
	private $customize;

	public function __construct() {
	
	}
	
	private function add_section($id, $title, $priority, $description) {
		$this->customize->add_section($id, array(
			'title'			=> __($title, 'sbtheme'),
			'priority'		=> $priority,
			'capability'	=> 'edit_theme_options',
			'description'	=> __($description, 'sbtheme')
		));
	}
	
	public function init($wp_customize) {
		$this->customize = $wp_customize;
		$this->add_section('sb_general_section', 'General Settings', 10, 'Cài đặt chung cho giao diện');
		$this->add_section('sb_social_section', 'Social Settings', 11, 'Thiết lập thông tin mạng xã hội');
		
		$this->add_setting('sb_options[logo]', '', 'option');
		$this->add_setting('sb_options[favicon]', '', 'option');
		$this->add_setting('sb_options[facebook]', '', 'option');
		$this->add_setting('sb_options[twitter]', '', 'option');
		$this->add_setting('sb_options[gplus]', '', 'option');
		$this->add_setting('sb_options[linkedin]', '', 'option');
		$this->add_setting('sb_options[pinterest]', '', 'option');
		$this->add_setting('sb_options[rss]', '', 'option');
		$this->add_setting('sb_options[youtube]', '', 'option');
		//$this->add_setting('sb_options[test_select]', 'ga', 'option');
		
		$this->add_control('sb_logo_option', 'Đường dẫn logo', 'sb_general_section', 'sb_options[logo]', 10);
		$this->add_control('sb_favicon_option', 'Đường dẫn favicon', 'sb_general_section', 'sb_options[favicon]', 10);
		$this->add_control('sb_facebook_option', 'Đường dẫn Facebook', 'sb_social_section', 'sb_options[facebook]', 10);
		$this->add_control('sb_twitter_option', 'Đường dẫn Twitter', 'sb_social_section', 'sb_options[twitter]', 10);
		$this->add_control('sb_gplus_option', 'Đường dẫn Google Plus', 'sb_social_section', 'sb_options[gplus]', 10);
		$this->add_control('sb_linkedin_option', 'Đường dẫn Linkedin', 'sb_social_section', 'sb_options[linkedin]', 10);
		$this->add_control('sb_pinterest_option', 'Đường dẫn Pinterest', 'sb_social_section', 'sb_options[pinterest]', 10);
		$this->add_control('sb_rss_option', 'Đường dẫn RSS', 'sb_social_section', 'sb_options[rss]', 10);
		$this->add_control('sb_youtube_option', 'Đường dẫn YouTube', 'sb_social_section', 'sb_options[youtube]', 10);
		//$this->add_control_select('sb_test_select_option', 'Test Select', 'sb_general_section', 'sb_options[test_select]', 10, array('ga' => 'Ga', 'vit' => 'Vit'));
		
		$this->get_setting();
	}
	
	private function add_setting($id, $default, $type) {
		$this->customize->add_setting($id, array(
			'default'		=> $default,
			'type'			=> $type,
			'capability'	=> 'edit_theme_options'
		));
	}
	
	private function add_control($id, $label, $section_id, $setting_id, $priority) {
		$this->customize->add_control($id, array(
			'label'		=> __($label, 'sbtheme'),
			'section'	=> $section_id,
			'settings'	=> $setting_id,
			'priority'	=> $priority
		));
	}
	
	private function add_control_select($id, $label, $section_id, $setting_id, $priority, $choices) {
		$this->customize->add_control($id, array(
			'label'		=> __($label, SB_DOMAIN),
			'section'	=> $section_id,
			'settings'	=> $setting_id,
			'type'		=> 'select',
			'choices'	=> $choices,
			'priority'	=> $priority
		));
	}
	
	private function get_setting() {
		$this->customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$this->customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$this->customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	}
}


