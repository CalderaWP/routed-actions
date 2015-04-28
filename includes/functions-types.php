<?php




add_filter( "routedactions_get_route_types", "routedactions_load_route_types", 1);
function routedactions_load_route_types(){
	// need to make this a filter.
	$routes_types = array(
		'static_action'		=>	array(
			'name'			=>	"Static Action",
			'panels'		=>	array(
				'static'
			)
		)
	);

	return $routes_types;
}