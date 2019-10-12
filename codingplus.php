<?php
/*
 * Plugin Name: Coding Plus
 * Description: Base plugin extension Coding Ninjas
 * Version: 1.0
 * Author: Aleksandr Baranets
 * License: GPLv2 or later
 */

function codingplus_admin_notices() {
	echo '<div class="error"><p>Important! Install or activate the base plugin "Coding Ninjas Tasks"</p></div>'; //Message
}

function codingplus_activation() {
    if (!is_plugin_active('codingninjasdev/coding-ninjas.php') ) { //Check if the base plugin is activated
        deactivate_plugins('codingplus/codingplus.php'); //If the base plugin is not active, then deactivate this plugin
    }
}
add_action( 'admin_init', 'codingplus_activation' );

function codingplus_admin_notices_msg() {
if (!is_plugin_active('codingninjasdev/coding-ninjas.php') ) {
	add_action( 'admin_notices', 'codingplus_admin_notices' ); //Check when you need to show an error message
	}
}
add_action( 'admin_init', 'codingplus_admin_notices_msg' );

/*--If the logic is not broken, then run all the functions of the plugin--*/
require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'settings.php';

/*----------Add scrpt-----------*/
function include_codingplusscript() {
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
	wp_enqueue_script( 'codingplus', plugins_url('inc/js/codingplus.js', __FILE__), array(), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'include_codingplusscript' );
?>