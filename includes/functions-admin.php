<?php

// include panel definitions

// include types




add_action( "wp_ajax_route_load_projects", "routedactions_load_projects" );
function routedactions_load_projects(){
	

	$routes_types = apply_filters( "routedactions_get_route_types", array() );

	$routes = get_option( 'CRA_ROUTES' );


	if( empty( $routes ) ){
		wp_send_json( array('message' => 'No Routes Defined' ) );
	}

	$projects = array();
	$out = array(
		'project' => array(),
		'count' => 0,
		"list_type"		=>	str_replace('#', '', $_POST['href'])
	);
	if(empty($_POST['filter'])){
		$out['current'] = true;
	}
	foreach( $routes as $route_id=>$route ){
		if(!isset($routes_types[$route['type']])){
			continue;
		}
		
		if(!empty($route['project'])){
			$project = $route['project'];
		}else{
			$project = 'Ungrouped';
		}

		if($_POST['href'] == '#routes'){
			$filter = $route['type'];
			$filter_name = $routes_types[$filter]['name'];
		}
		if($_POST['href'] == '#projects'){
			$filter = $project;
			$filter_name = ucfirst( str_replace('_', ' ', $project ) );
		}

		if(!isset($out['filter'][$filter])){
			$out['filter'][$filter] = array(
				'type'	=>	$filter_name,
				'count' =>	0
			);
			if(!empty($_POST['filter']) && $_POST['filter'] === $filter){
				$out['filter'][$filter]['current'] = true;
			}
			if(!empty($_POST['project']) && $_POST['project'] === $project){
				$out['project'][$filter]['current'] = true;
			}
		}

		$out['filter'][$filter]['count'] += 1;
		$out['count']	+= 1;
		if(!empty($_POST['filter']) && $_POST['filter'] !== $filter){
			continue;
		}
		if(!empty($_POST['project']) && $_POST['project'] !== $project){
			continue;
		}
		$route_item = array(
			"id"			=>	$route_id,
			"name" 			=> 	$route['name'],
			"description" 	=> 	$route['description'],
			"type"			=>	$routes_types[$route['type']]['name'],
			"route"		=>	$route
		);
		if(!empty($route['state'])){
			$route_item['state'] = true;
		}
		$projects[] = $route_item;


	};
	$out['project'] = $projects;
	$out[$out['list_type']] = true;

	wp_send_json( $out );
}

add_action( "wp_ajax_route_delete_route", "routedactions_delete_route" );
function routedactions_delete_route(){

	$routes = get_option( 'CRA_ROUTES' );
	if(isset($routes[$_POST['route']])){
		unset($routes[$_POST['route']]);
		delete_option( $routes[$_POST['route']] );
	}


	update_option( 'CRA_ROUTES', $routes );

	// rebuild rewrite tags
	routedactions_define_handler_rewrites();
	flush_rewrite_rules();

	exit;
}

// create new route
add_action( "wp_ajax_route_create_route", "routedactions_create_route" );
function routedactions_create_route(){

	// build new route
	// path check
	$path = explode('/', trim($_POST['route_path'],'/'));
	foreach($path as $key=>&$part){
		if(strlen($part) === 0){
			unset($path[$key]);
		}else{
			$part = sanitize_title( $part );
		}
	}			

	$new_route = array(
		'id'			=>	strtoupper(uniqid('CRA')),
		'name'			=>	$_POST['route_name'],
		'description'	=>	$_POST['route_description'],
		'slug'			=>	$_POST['route_slug'],
		'path'			=>	implode('/', $path),
		'type'			=>	$_POST['route']
	);
	$new_route = apply_filters( 'routedactions_create_route', $new_route);
	$new_route = apply_filters( 'routedactions_create_route-'.$_POST['route'], $new_route);
	// register new route
	$routes = get_option('CRA_ROUTES');
	if(empty($routes)){
		$routes = array();
	}
	$routes[$new_route['id']] = $new_route;
	update_option( 'CRA_ROUTES', $routes );

	// save new route
	update_option( $new_route['id'], $new_route);

	// load new route and send to editor
	routedactions_load_route( $new_route['id'] );

}

// activate an route
add_action( "wp_ajax_route_activate_route", "routedactions_activate_route" );
function routedactions_activate_route(){
	$routes = get_option( 'CRA_ROUTES' );
	if(empty($routes[$_POST['route']])){
		wp_send_json( array('message'=>'error, invalid route') );
	}
	$route = $routes[$_POST['route']];
	
	if(isset($routes[$_POST['route']]['state'])){
		unset($routes[$_POST['route']]['state']);
		$message = 'Activate';
		do_action( 'routedactions_activate_route', $route);
		do_action( 'routedactions_activate_route-'.$route['type'], $route);
	}else{
		$routes[$_POST['route']]['state'] = 'active';
		$message = 'Deactivate';
		do_action( 'routedactions_deactivate_route', $route);
		do_action( 'routedactions_deactivate_route-'.$route['type'], $route);
	}

	update_option( 'CRA_ROUTES', $routes );

	// rebuild rewrite tags
	routedactions_define_handler_rewrites();
	flush_rewrite_rules();

	wp_send_json( array('message' => $message) );
}

// create new route
add_action( "wp_ajax_route_route_handler", "routedactions_route_handler" );
function routedactions_route_handler(){

	$routes = get_option( 'CRA_ROUTES' );
	if(empty($routes[$_POST['id']])){
		wp_send_json( array('message'=>'error, invalid route') );
	}
	$data = stripslashes_deep( $_POST );
	foreach( $routes[$data['id']] as $field=>&$value){
		if(isset($data[$field])){
			if($field === 'path'){
				$path = explode('/', trim($data[$field],'/'));
				foreach($path as $key=>&$part){
					if(strlen($part) === 0){
						unset($path[$key]);
					}else{
						$part = sanitize_title( $part );
					}
				}			
				$data[$field] = implode('/', $path);
			}
			$value = $data[$field];
		}
	}

	do_action( 'routedactions_update_route', $data);
	do_action( 'routedactions_update_route-'.$routes[$data['id']]['type'], $data);

	// update regestry
	update_option( 'CRA_ROUTES', $routes );
		
	update_option( $data['id'], $data );

	wp_send_json( array('message' => 'Route Action updated' ) );
}










