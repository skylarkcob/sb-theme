<?php
/*
Plugin Name: Restore Link Title Field
Plugin URI: http://ottopress.com/wordpress-plugins/restore-link-title-field
Description: Adds back the missing title field to the TinyMCE link editor
Version: 1.3
Author: Otto
Author URI: http://ottopress.com
License: GPLv2
License URI: http://www.opensource.org/licenses/GPL-2.0
*/

add_action( 'wp_enqueue_editor', 'restore_link_title_field', 20 );
function restore_link_title_field() {
	wp_enqueue_script( 'wplinkTitle', plugins_url( 'restore-link-title-field.js', __FILE__ ), array( 'wplink' ), '1.3', true );
	wp_localize_script( 'wplinkTitle', 'wpLinkTitleL10n', array(
		'titleLabel' => __( 'Title' ),
	) );
}


