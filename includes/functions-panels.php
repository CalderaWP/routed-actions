<?php




// filter to register panels
add_filter( "routedactions_get_route_panels", "routedactions_load_route_panels", 1);
function routedactions_load_route_panels($panels){

	$panels = array(
		'static'		=>	array(
			'label'		=>	'Action',
			'title'		=>	'Callback Action',
			'caption'	=>	'define an action to do',
			'template'	=>	RACTIONS_PATH . 'ui/templates/static/action-template.php'
		)
	);

	return $panels;
}
