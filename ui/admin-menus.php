<?php
if ( !get_option('permalink_structure') ) {
?>
<li style="opacity: 1; margin-left: 0px;" class="routedactions-route-tool routedactions-route-tool-error"><span class="wp-filter-link" style="cursor:default;">Routed Actions requires that <a href="options-permalink.php">permalink</a> structures are enabled and not set to "Default".</span> </li>
<?php
}else{ ?>
	<li class="routedactions-route-tool routedactions-routes" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link routedactions-trigger" href="#routes" data-action="route_load_projects" data-active-class="current" data-group="filter-nav" data-callback="route_reset_screen_state" data-template="#routes-list-tmpl" data-target="#routedactions-canvas" data-autoload="true">Route Actions</a> </li>
	<li class="routedactions-route-tool routedactions-route-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
	<li class="routedactions-route-tool routedactions-new" style="opacity:0;margin-left: -10px;"><span class="wp-filter-link" >New Route Action</span>
	<ul><?php
	$route_routes_types = apply_filters( "routedactions_get_route_types", array() );			
	foreach( $route_routes_types as $type_slug=>$route_type){
		$create_button = __('Create '.$route_type['name']).'|{"data-route" : "'.$type_slug.'", "data-action" : "route_create_route", "data-active-class": "disabled", "data-load-class": "disabled", "data-callback": "route_reveal_tools", "data-before" : "route_build_new_route", "data-target" : "#routedactions-toolbar", "data-template" : "#routedactions-editor-tools-tmpl", "data-modal-autoclose" : "new_route" }';
	?>
		<li><a class="wp-filter-link routedactions-trigger" href="#new-routes" data-request="route_create_new_route" data-modal="new_route" data-modal-title="New <?php echo $route_type['name']; ?>" data-modal-buttons='<?php echo $create_button; ?>' data-modal-height="265px" data-modal-width="530px" data-active-class="current" data-group="filter-nav" data-routetype="<?php echo $type_slug; ?>" data-template="#create-new-route-tmpl"><?php echo $route_type['name']; ?></a></li>
	<?php }
	do_action( 'routedactions_admin_menu' );
	?>
	</ul>
	</li>
<?php } ?>