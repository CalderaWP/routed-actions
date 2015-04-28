<?php
/**
*	Editor Tools Template
*
*/
?>
<script type="text/html" id="routedactions-editor-tools-tmpl">
	{{#if error}}
		<li class="routedactions-route-tool routedactions-route-tool-error" style="opacity:0;margin-left: -10px;"><span class="wp-filter-link">{{error}}</span> </li>
	{{/if}}
	{{#if confirm}}
		<li class="routedactions-route-tool routedactions-route-tool-error" style="opacity:0;margin-left: -10px;"><span class="wp-filter-link">{{confirm}}</span> </li>
	{{/if}}
	
	{{#each tools}}
		<li class="routedactions-route-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link routedactions-editor-tab routedactions-trigger" data-panel="{{slug}}" data-active-class="current" data-group="editor-tabs" {{#if default}}data-autoload="true"{{/if}} data-request="route_toggle_editor_tab" data-callback="route_switch_editor_tab" href="#{{slug}}">{{label}}</a> </li>
	{{/each}}
	{{#if panels}}
		<li class="routedactions-route-tool routedactions-route-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
		{{#each panels}}
			<li class="routedactions-route-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link routedactions-editor-tab routedactions-trigger" data-panel="{{slug}}" data-active-class="current" data-group="editor-tabs" data-request="route_toggle_editor_tab" data-callback="route_switch_editor_tab" href="#{{slug}}">{{label}}</a> </li>
		{{/each}}		
	{{/if}}
	{{#if editors}}
	<li class="routedactions-route-tool routedactions-route-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
	{{/if}}
	{{#each editors}}
		<li class="routedactions-route-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link routedactions-editor-tab routedactions-trigger" data-panel="{{slug}}" data-active-class="current" data-group="editor-tabs" data-request="route_toggle_editor_tab" data-callback="route_switch_editor_tab" href="#{{slug}}">{{label}}</a> </li>
	{{/each}}	
	{{#if meta_tools}}
		<li class="routedactions-route-tool routedactions-route-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
		{{#each meta_tools}}
			<li class="routedactions-route-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link routedactions-editor-tab routedactions-trigger" data-panel="{{slug}}" {{#each attributes}}{{@key}}="{{this}}" {{/each}}href="#{{slug}}">{{label}}</a> </li>
		{{/each}}
	{{/if}}
	<li class="routedactions-route-tool routedactions-route-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
	{{#if confirm}}
	<li class="routedactions-route-tool routedactions-close-route-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link routedactions-trigger"
		id="routedactions-close-editor"
		href="#close-route"
		data-request="route_close_editor"
		data-target="#routedactions-toolbar"
		data-template="#routedactions-admin-menus-tmpl"
		data-before="route_clear_canvas"
		data-callback="route_reveal_tools"
		{{#if error}}
			data-autoload="true"
			data-delay="2000"
		{{/if}}
	>Close, without saving</a></li>
	{{/if}}	
	{{#unless confirm}}
	<li class="routedactions-route-tool routedactions-close-route-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link routedactions-trigger"
		id="routedactions-close-editor"
		href="#close-route"
		data-action="route_close_editor"
		data-target="#routedactions-toolbar"
		data-template="#routedactions-editor-tools-tmpl"
		data-before="route_check_state"
		data-callback="route_reveal_tools"
		data-route="{{id}}"
		{{#if error}}
			data-autoload="true"
			data-delay="2000"
		{{/if}}
	>Close</a></li>
	<li style="display:none;">
	<?php 
	// LOAD GENERAL SETTINGS
	?>
	<span class="routedactions-trigger" data-event="none" data-request="route_get_screen_canvas_data" data-target="#routedactions-canvas" data-target-insert="append" data-template="#routedactions-general-settings-tmpl" {{#unless menuonly}}data-autoload="true"{{/unless}}></span>

	{{#if panels}}
	<?php
	// INIT PANELS	
	?>
	{{#each panels}}
	<span class="routedactions-trigger" data-event="none" data-request="route_get_screen_canvas_data" data-target="#routedactions-canvas" data-target-insert="append" data-template="#routedactions-panel-{{slug}}-tmpl" {{#unless ../menuonly}}data-autoload="true"{{/unless}}></span>
	{{/each}}
	{{/if}}


	</li>
	{{/unless}}
	<?php do_action( "routedactions_editor_menu" ); ?>

</script>

<?php
/**
*	General Settings Template
*
*/
?>
<script type="text/html" id="routedactions-general-settings-tmpl">
<div class="routedactions-editor-panel" id="general_settings">
	<h2 class="routedactions-panel-title">General Settings <small class="routedactions-panel-caption">general route details</small></h2>
	<input name="id" type="hidden" value="{{id}}" id="route_id">
	<input name="type" type="hidden" value="{{type}}" id="route_id">
	<div id="setup_name" class="routedactions-field-group">
		<label>Route Name</label>
		<input name="name" type="text" value="{{name}}" id="route_name">
	</div>
	<div id="setup_description" class="routedactions-field-group">
		<label>Description</label>
		<input name="description" type="text" value="{{description}}" id="route_description">
	</div>
	<div id="setup_path" class="routedactions-field-group">
		<label>Path</label>
		<input name="path" type="text" value="{{path}}" id="route_path">
	</div>
	<div id="setup_slug" class="routedactions-field-group">
		<label>Slug</label>
		<input name="slug" type="text" value="{{slug}}" data-format="slug" id="route_slug">
	</div>
</div>
</script>
<?php
/**
 * Pull in Panel & fieldtype templates
 */
do_action( "routedactions_editor_templates" );
