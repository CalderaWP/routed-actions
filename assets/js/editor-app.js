var field_type_templates = {};

function route_reveal_tools(obj){
	
	var title 	= jQuery('#routedactions-page-title'),
	caption = jQuery('#routedactions-page-caption');

	jQuery('.routedactions-route-tool').each(function(k,v){
		setTimeout(function(){
			jQuery(v).animate({opacity: 1, marginLeft: 0}, route_animation_speed);
		}, (route_animation_speed / 2 ) *jQuery(v).index());
	});
	
	if(obj){

		if(!obj.rawData.confirm){
			obj.state = current_screen_object.state;
			current_screen_object = obj;
		}
		caption.animate({opacity: 0}, ( route_animation_speed / 2 ));
		title.fadeOut(route_animation_speed, function(){
			var newtitle = jQuery(this);
			
			newtitle.empty().show();
			setTimeout(function(){
				caption.text(obj.rawData.type).animate({opacity: 1}, ( route_animation_speed / 2 ));
			}, ( route_animation_speed / 15 )*obj.rawData.name.length );

			for(var c = 0; c < obj.rawData.name.length; c++){
				setTimeout(function(c){
					newtitle.append( obj.rawData.name.substr(c,1) );
				}, ( route_animation_speed / 15 )*c, c);
			}

		})
	}
}

function route_update_save_state(){
	current_screen_object.state = jQuery('#routedactions-canvas').serialize();
}

function route_check_state(el, e){


	// check state 
	var current_state = jQuery('#routedactions-canvas').serialize();
	if(current_state === current_screen_object.state){
		jQuery(el).data('request', 'route_close_editor');		
		jQuery(el).data('template', '#routedactions-admin-menus-tmpl');
		current_screen_object.state = '';
	}
	return true;
}

function route_close_editor(el, e){

	if(el.params){
		jQuery('#routedactions-close-editor').trigger('click');
		return;
	}

	route_clear_canvas();

	var rawData = {page: "admin", name : "Routed Actions", type : jQuery('#routedactions-page-caption').data('version'), state: "" };

	jQuery('.routedactions-editor-panel:visible').hide();

	current_screen_editors = false;
	current_screen_object = {page : "admin"}

	jQuery(document).trigger('close_editor');
	return rawData;
}

function route_get_screen_canvas_data(obj){
	
	if( current_screen_object.rawData ){
		var return_object = current_screen_object.rawData;
		if(obj.trigger.data('menuonly')){
			return_object.menuonly = true
		}
		return return_object;
	}
	return current_screen_object;
}

function route_toggle_editor_tab(obj, e){

	var panel = {
		'panel' : obj.trigger.data('panel'),
		'group' : obj.trigger.data('group'),
		'title'	: obj.trigger.data('title')
	}
	return panel;
}
function route_switch_editor_tab(obj){

	jQuery('.routedactions-route-tool .wp-filter-link').removeClass('current');
	obj.params.trigger.addClass('current');
	
	jQuery('.routedactions-editor-panel:visible').fadeOut(route_animation_speed, function(){
		var panel = jQuery('#' + obj.params.trigger.data('panel') );
		panel.fadeIn(route_animation_speed);

		if(panel.data('callback') && typeof window[panel.data('callback')] === 'function'){
			window[panel.data('callback')](obj.params.trigger);
		}
		jQuery(document).trigger('switch_panel');
	})
}


// make an id
function route_generate_id(){
	var new_id = "ID-" + Math.random().toString(36).substr(2, 9).toUpperCase();
	if( jQuery('.' + new_id).length ){
		return route_generate_id();
	}
	return new_id;
}

// setup fieldtype config
function route_setup_fieldtype(obj){
	var type = obj.trigger.val(),
		conf = {id: obj.trigger.data('field')};
	if(field_type_templates[type]){
		return field_type_templates[type](conf);
	}else{
		return field_type_templates._no_config_(conf);
	}
}

// do callbuc for script inits
function route_init_fieldtype_switch(obj){
	//color_picker_init
	console.log(window[obj.params.trigger.val() + '_init']);
	if( typeof window[obj.params.trigger.val() + '_init'] === 'function'){
		window[obj.params.trigger.val() + '_init']();
	}
}


// Constant Inits & Binds //
jQuery(function($){

	// route title bind
	$(document).on('keyup change', '#route_name', function(){

		var title = $('#routedactions-page-title'),
		field = $(this);

		title.text( field.val() );
	})

	// precompile fieldtype templates
	var templates = $('.routedactions-field-template');
	for(var t=0; t<templates.length; t++){
		var template = $(templates[t]),
			html 	 = template.html(),
			type 	 = template.data('type');
		
		field_type_templates[type] = Handlebars.compile(html);

	}

});


function route_save_route(el){
	jQuery(document).trigger('editor_save');
	jQuery('#routedactions-canvas').submit();
}

// bind save check
window.onbeforeunload = function() {
	if(current_screen_object.state){
		var current_state = jQuery('#routedactions-canvas').serialize();
		if(current_state !== current_screen_object.state){
	  		return confirm_save_notice;
	  	}
	}
}

