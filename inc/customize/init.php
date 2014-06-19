<?php
function sbwp_customize_register( $wp_customize ) {

	/* ================================================
	 * Theme Settings
	 * ================================================ */

	$wp_customize->add_section( 'sbwp_general_section', array(
		'title' => __( 'General Settings', 'sbwp' ),
		'priority' => 13,
		'capability' => 'edit_theme_options',
		'description' => __('General theme settings.', 'sbwp')
	));
	
	$wp_customize->add_setting( 'sbwp_options[logo_uri]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_logo_uri', array(
		'label'		=> __( 'Logo url', 'sbwp' ),
		'section'	=> 'sbwp_general_section',
		'settings'	=> 'sbwp_options[logo_uri]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[favicon_uri]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_favicon_uri', array(
		'label'		=> __( 'Favicon url', 'sbwp' ),
		'section'	=> 'sbwp_general_section',
		'settings'	=> 'sbwp_options[favicon_uri]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[feedburner_name]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_feedburner_name', array(
		'label'		=> __( 'FeedBurner name', 'sbwp' ),
		'section'	=> 'sbwp_general_section',
		'settings'	=> 'sbwp_options[feedburner_name]',
		'priority'	=> 10
	));
	
	/* ================================================
	 * Social Settings
	 * ================================================ */

	$wp_customize->add_section( 'sbwp_social_section', array(
		'title' => __( 'Social Settings', 'sbwp' ),
		'priority' => 14,
		'capability' => 'edit_theme_options',
		'description' => __('Your social network information.', 'sbwp')
	));
	
	$wp_customize->add_setting( 'sbwp_options[facebook]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_facebook', array(
		'label'		=> __( 'Facebook url', 'sbwp' ),
		'section'	=> 'sbwp_social_section',
		'settings'	=> 'sbwp_options[facebook]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[twitter]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_social_twitter', array(
		'label'		=> __( 'Twitter url', 'sbwp' ),
		'section'	=> 'sbwp_social_section',
		'settings'	=> 'sbwp_options[twitter]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[google_plus]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_social_google_plus', array(
		'label'		=> __( 'Google+ url', 'sbwp' ),
		'section'	=> 'sbwp_social_section',
		'settings'	=> 'sbwp_options[google_plus]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[linkedin]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_social_linkedin', array(
		'label'		=> __( 'Linkedin url', 'sbwp' ),
		'section'	=> 'sbwp_social_section',
		'settings'	=> 'sbwp_options[linkedin]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[pinterest]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_social_pinterest', array(
		'label'		=> __( 'Pinterest url', 'sbwp' ),
		'section'	=> 'sbwp_social_section',
		'settings'	=> 'sbwp_options[pinterest]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[rss]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_social_rss', array(
		'label'		=> __( 'RSS url', 'sbwp' ),
		'section'	=> 'sbwp_social_section',
		'settings'	=> 'sbwp_options[rss]',
		'priority'	=> 10
	));
	
	$wp_customize->add_setting( 'sbwp_options[youtube]', array(
		'default' => '',
		'type' => 'option',
		'capability' => 'edit_theme_options'
	));
	
	$wp_customize->add_control( 'sbwp_social_youtube', array(
		'label'		=> __( 'YouTube url', 'sbwp' ),
		'section'	=> 'sbwp_social_section',
		'settings'	=> 'sbwp_options[youtube]',
		'priority'	=> 10
	));

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	
}
add_action( 'customize_register', 'sbwp_customize_register' );

function sbwp_customize_preview_js() {
	SB_Script::register( 'sbwp-customizer', SBWP_INC_URI . '/customize/customize.js', array( 'customize-preview' ) );
	SB_Script::enqueue('sbwp-customizer');
}
add_action( 'customize_preview_init', 'sbwp_customize_preview_js' );
