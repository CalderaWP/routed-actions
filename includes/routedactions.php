<?php


// Add Admin menu page
add_action( 'admin_menu', 'routedactions_register_admin_page' );

// Add admin scritps and styles
add_action( 'admin_enqueue_scripts', 'routedactions_enqueue_admin_stylescripts' );

	
function routedactions_define_handler_rewrites(){
	$routes = routedactions_get_active_routes();
	
	add_rewrite_tag('%callback_route_action%', '(callback_route_action)');
	foreach($routes as $route){
		$path = '';
		if(!empty($route['path'])){
			$path = trim($route['path'],'/').'/';
		}
		add_rewrite_rule('^'.$path.$route['slug'].'/?','index.php?callback_route_action='.$route['id'],'top');
	}
}

function routedactions_define_handle_route(){
	global $wp_query;

	if( !empty( $wp_query->query['callback_route_action'] ) ){
		$route = get_option( $wp_query->query['callback_route_action'] );
		if(!empty($route['route_action'])){

			if(!empty($route['route_args'])){
				switch($route['route_args']){
					case 'request':
						$args = $_REQUEST;
						break;
					case 'post':
						$args = $_POST;
						break;
					case 'get':
					default:
						$args = $_GET;
						break;

				}
			}

			do_action( $route['route_action'], $args );
		}else{
			do_action( 'routedactions_do_action-'.$route['type'], $route );
		}
		exit;
	}
}

function routedactions_register_admin_page(){
	global $routedactions_pages;

	$routedactions_pages[] = add_menu_page( 'Routed Actions', 'Routed Actions', 'manage_options', 'routedactions', 'routedactions_build_admin_page', RACTIONS_ICON );
	add_submenu_page( 'routedactions', 'Routed Actions', 'Routed Actions', 'manage_options', 'routedactions', 'routedactions_build_admin_page' );
	
	//foreach($pagesets as $setid=>$pageset){
	//	$this->screen_prefix[] 	 = add_submenu_page( 'routedactions', 'Routed Actions - ' . $form['name'], '- '.$form['name'], 'manage_options', 'routedactions-pin-' . $form_id, 'routedactions_build_admin_page' );
	//}

}

function routedactions_enqueue_admin_stylescripts(){
	global $routedactions_pages, $field_types;

	$screen = get_current_screen();

	if( in_array( $screen->base, array('post') ) ){
		$routes = get_option( 'CRA_ROUTES' );
		foreach($routes as $route){
			if(empty($route['state'])){
				continue;
			}
			// modals
			wp_enqueue_style(  'routedactions-modal-styles'			, RACTIONS_URL . 'assets/css/modals.css'				, array()							, RACTIONS_VER );
			// scripts
			wp_enqueue_script( 'routedactions-handlebars'			, RACTIONS_URL . 'assets/js/handlebars.js'			, array()							, RACTIONS_VER );
			wp_enqueue_script( 'routedactions-baldrick-handlebars'	, RACTIONS_URL . 'assets/js/handlebars.baldrick.js'	, array('routedactions-baldrick')	, RACTIONS_VER );
			wp_enqueue_script( 'routedactions-baldrick-modals'		, RACTIONS_URL . 'assets/js/modals.baldrick.js'		, array('routedactions-baldrick')	, RACTIONS_VER );
			wp_enqueue_script( 'routedactions-baldrick'				, RACTIONS_URL . 'assets/js/jquery.baldrick.js'		, array('jquery')					, RACTIONS_VER );
			wp_enqueue_script( 'routedactions-app'					, RACTIONS_URL . 'assets/js/admin-app.js'			, array('jquery')					, RACTIONS_VER );

			break;
		}
		return;
	}

	if( !in_array( $screen->base, $routedactions_pages ) ){
		return;
	}
	// include media scripts
	wp_enqueue_media();

	// load styles
	wp_enqueue_style( 'routedactions-admin-styles'			, RACTIONS_URL . 'assets/css/admin.css'				, array()							, RACTIONS_VER );
	wp_enqueue_style( 'routedactions-editor-styles'			, RACTIONS_URL . 'assets/css/editor.css'			, array()							, RACTIONS_VER );
	wp_enqueue_style( 'routedactions-modal-styles'			, RACTIONS_URL . 'assets/css/modals.css'			, array()							, RACTIONS_VER );
	
	// load scripts
	wp_enqueue_script( 'routedactions-handlebars'			, RACTIONS_URL . 'assets/js/handlebars.js'			, array()							, RACTIONS_VER );
	wp_enqueue_script( 'routedactions-baldrick-handlebars'	, RACTIONS_URL . 'assets/js/handlebars.baldrick.js'	, array('routedactions-baldrick')	, RACTIONS_VER );
	wp_enqueue_script( 'routedactions-baldrick-modals'		, RACTIONS_URL . 'assets/js/modals.baldrick.js'		, array('routedactions-baldrick')	, RACTIONS_VER );
	wp_enqueue_script( 'routedactions-baldrick'				, RACTIONS_URL . 'assets/js/jquery.baldrick.js'		, array('jquery')					, RACTIONS_VER );
	wp_enqueue_script( 'routedactions-app'					, RACTIONS_URL . 'assets/js/admin-app.js'			, array('jquery')					, RACTIONS_VER );
	wp_enqueue_script( 'routedactions-editor-app'			, RACTIONS_URL . 'assets/js/editor-app.js'			, array('jquery')					, RACTIONS_VER );	
	
	// panel support scripts ( jquery-ui etc.. )
	$panels = apply_filters( 'routedactions_get_route_panels', array(), 1);
	if( !empty( $panels ) ){
		foreach($panels as $panel_id=>$panel){
			if(isset($panel['styles'])){
				// panel has styles
				foreach($panel['styles'] as $style){
					wp_enqueue_style( 'routedactions-panel-'. sanitize_key( basename( $style ) ) .'-styles'				, $style		, array()							, RACTIONS_VER );
				}
			}
			if(isset($panel['scripts'])){
				// panel has scripts
				foreach($panel['scripts'] as $script){
					if( false !== strpos($script, '/')){
						// url
						wp_enqueue_script( 'routedactions-panel-'. sanitize_key( basename( $script ) )				, $script, array('jquery')					, RACTIONS_VER );
					}else{
						// slug
						wp_enqueue_script( $script );
					}
				}
			}
		}
	}
	
	$field_types = apply_filters( 'routedactions_get_field_types', array() );
	if( !empty( $field_types ) ){
		foreach($field_types as $field_type_id=>$field_type){
			if(isset($field_type['setup']['styles'])){
				// field_type has styles
				foreach($field_type['setup']['styles'] as $style_key=>$style){
					wp_enqueue_style( 'routedactions-fieldtype-'. $field_type_id . sanitize_key( basename( $style ) ) .'-styles'				, $style		, array()							, RACTIONS_VER );
				}
			}
			if(isset($field_type['setup']['scripts'])){
				// field_type has scripts
				foreach($field_type['setup']['scripts'] as $script){
					if( false !== strpos($script, '/')){
						// url						
						wp_enqueue_script( 'routedactions-fieldtype-'. $field_type_id . sanitize_key( basename( $script ) )				, $script, array('jquery')					, RACTIONS_VER );
					}else{
						// slug
						wp_enqueue_script( $script );
					}
				}
			}
		}
	}
}

function routedactions_build_admin_page(){
	include RACTIONS_PATH . 'ui/admin.php';
}


// helper function to get active routes of a specific type
function routedactions_get_active_routes($type = null){

	$routes = get_option( 'CRA_ROUTES' );
	$returns = array();
	foreach( (array) $routes as $route_id=>$route_def){

		if( !empty($route_def['state']) ){
			if(!empty($type)){
				if($route_def['type'] !== $type){
					continue;
				}
			}
			$returns[$route_id] = get_option( $route_id );
		}
	}

	return $returns;
}











