var current_screen_object = {page : "admin", state : ""}, route_animation_speed = 100,confirm_save_notice;

function route_create_new_route(){
	return '{"type" : true}';
}
function route_confirm_delete(){
	return confirm('Are you sure you want to remove this route?');
}
function route_reload_routes(){
	jQuery('#routedactions-canvas .wp-filter-link.routedactions-trigger.current').trigger('click');
}
function route_clear_canvas(){

	jQuery('.routedactions-route-tool').each(function(k,v){
		setTimeout(function(){
			jQuery(v).animate({opacity: 0, marginLeft: -10}, route_animation_speed);
		}, ( route_animation_speed / 2 ) *jQuery(v).index());
	});

	jQuery('#routedactions-canvas').fadeOut(route_animation_speed, function(){
		jQuery(this).empty().show();
	})

}
function route_toggle_activation(obj){
	obj.params.trigger.removeClass('disabled')
	if(obj.data.message === 'Activate'){
		obj.params.trigger.removeClass('button-primary').text(obj.data.message);
	}else{
		obj.params.trigger.addClass('button-primary').text(obj.data.message);
	}
}
function route_reset_screen_state(){
	current_screen_object.state = '';
}
function route_build_new_route(el){
	var clicked 	= jQuery(el),
		name 		= jQuery('#new_route_name'),
		description	= jQuery('#new_route_description'),
		path		= jQuery('#new_route_path'),
		slug		= jQuery('#new_route_slug');

	if(!name.val().length){
		name.focus();
		return false;
	}
	if(!slug.val().length){
		slug.focus();
		return false;
	}

	clicked.data('route_name', name.val() );
	clicked.data('route_description', description.val() );
	clicked.data('route_path', path.val() );
	clicked.data('route_slug', slug.val() );

	route_clear_canvas();
	
	return true;

}

jQuery(function($){
	var baldrickPending = [];
	// bind slugs
	$(document).on('keyup change', '[data-format="slug"]', function(){		
		this.value = this.value.replace(/[^a-z0-9]/gi, '_').toLowerCase();
	});
	
	// bind label update
	$(document).on('keyup change', '[data-sync]', function(){
		var input = $(this),
			sync = $('.' + input.data('sync'));
		sync.text(input.val());
	});

	// initialise baldrick triggers
	$('.routedactions-trigger').baldrick({
		request			:	ajaxurl,
		method			:	'POST',
		activeClass		:	'none',
		loadQuery		:	'#loading-indicator',
		helper			:	{
			event		:	function(el, obj, ev){
				baldrickPending.push( obj.request );
			},
			refresh		:	function(obj){
				baldrickPending.shift();
				//setup canvas state
				if(!baldrickPending.length){				
					if(!current_screen_object.page){
						current_screen_object.state = $('#routedactions-canvas').serialize();
					}
				}
			}
		}
	});

	// initialize baldrick core form
	$('#routedactions-canvas').baldrick({
		request			:	ajaxurl,		
		method			:	'POST',
		activeClass		:	'none',
		loadQuery		:	'#loading-indicator'
	});

});
