<?php
/*
  Plugin Name: Routed Actions
  Plugin URI: http://digilab.co.za
  Description: Create a url to route to a defined action.
  Author: David Cramer
  Version: 1.0.0
  Author URI: http://digilab.co.za
 */

//initilize plugin

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('RACTIONS_PATH', plugin_dir_path(__FILE__));
define('RACTIONS_URL', plugin_dir_url(__FILE__));
define('RACTIONS_ICON', 'dashicons-arrow-right');
define('RACTIONS_VER', '1.0.0');


include_once RACTIONS_PATH . 'includes/routedactions.php';
// load types
include_once RACTIONS_PATH . 'includes/functions-types.php';
// load panels
include_once RACTIONS_PATH . 'includes/functions-panels.php';

if(is_admin()){

	// load admin functions
	include_once RACTIONS_PATH . 'includes/functions-admin.php';
	include_once RACTIONS_PATH . 'includes/functions-editor.php';

}else{

	// add handler actions
	add_action( 'init', 'routedactions_define_handler_rewrites' );

	// add route hook
	add_action( 'template_redirect', 'routedactions_define_handle_route', 1000 );

}
