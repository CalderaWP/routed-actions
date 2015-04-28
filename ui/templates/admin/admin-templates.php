<script type="text/html" id="routes-list-tmpl">
{{#if filter}}
	<div class="wp-filter wp-filter-small wp-filter-{{list_type}}">
		<ul class="wp-filter-links">
		<li><a class="wp-filter-link routedactions-trigger {{#if current}}current{{/if}}" href="#{{list_type}}" data-action="route_load_projects" data-active-class="current" data-group="filter-sub-nav" data-template="#routes-list-tmpl" data-target="#routedactions-canvas">All <span class="count">{{count}}</span></a></li>
		{{#if routes}}
			<li class="routedactions-route-tool routedactions-route-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
		{{/if}}
	{{#each filter}}
		<li><a class="wp-filter-link routedactions-trigger {{#if current}}current{{/if}}" href="#{{../list_type}}" data-filter="{{@key}}" data-action="route_load_projects" data-active-class="current" data-group="filter-sub-nav" data-template="#routes-list-tmpl" data-target="#routedactions-canvas">{{{type}}} <span class="count">{{count}}</span></a></li>
	{{/each}}
		</ul>
	</div>
{{/if}}
<div class="callback-route-list-{{list_type}}">
	{{#if message}}
	<p class="description" style="margin-left: 30px;">{{message}}</p>
	{{/if}}
	{{#each project}}
	<div id="route-{{id}}" style="margin: 0 10px 10px 0; width: 430px; float: left; height: 143px; overflow: hidden; border: 1px solid #e5e5e5; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04); background:#fff; color:#333;position: relative;">
		<h2 style="height: 28px; margin: 0px; font-size: 16px; padding: 6px 12px; text-shadow:0 0 2px #fff;">{{name}} <small style="color: rgb(159, 159, 159); font-style: italic;"> {{type}}</small></h2>
		<div style="margin: 0px 0px 6px; overflow: auto; height: 20px; padding: 0px 12px;">
			<span class="description" style="color: rgb(159, 159, 159); font-style: italic;">{{description}}</span>
		</div>
		<div style="margin: 0px; padding: 0 12px;">
			<input type="text" readonly="readonly" value="<?php echo get_site_url() .'/{{#if route/path}}{{route/path}}/{{/if}}'; ?>{{route/slug}}" style="width:100%;" onclick="this.select()">
		</div>
		<div style="position: absolute; bottom: 0px; padding: 6px; background: none repeat scroll 0 0 rgba(0, 0, 0, 0.03); left: 0px; right: 0px; border-top: 1px solid #e5e5e5;">
			<a id="activate-toggle-{{id}}" class="button button-small routedactions-trigger {{#if state}}button-primary{{/if}}" href="#activate_route"
				data-action="route_activate_route"
				data-active-class="disabled"
				data-group="{{id}}"
				data-route="{{id}}"
				data-target="#route_{{id}}"
				data-target-insert="replace"
				data-callback="route_toggle_activation"
			>{{#if state}}Deactivate{{else}}Activate{{/if}}</a>
			<a class="button button-small routedactions-trigger"
				href="#edit-{{id}}"
				data-action="route_load_route"
				data-route="{{id}}"
				data-target="#routedactions-toolbar"
				data-template="#routedactions-editor-tools-tmpl"
				data-before="route_clear_canvas"
				data-callback="route_reveal_tools"
			>Edit</a>
			<a class="button button-small routedactions-trigger right"
				href="#delete-{{id}}"
				data-action="route_delete_route"
				data-route="{{id}}"
				data-before="route_confirm_delete"
				data-callback="route_reload_routes"
			>Delete</a>
		</div>
	</div>
	{{/each}}
</div>
</script>
<script type="text/html" id="routedactions-admin-menus-tmpl">
<?php include RACTIONS_PATH . 'ui/admin-menus.php'; ?>
</script>
<script type="text/html" id="create-new-route-tmpl">
<div id="setup_name" class="routedactions-field-group">
	<label>Route Name</label>
	<input type="text" value="" id="new_route_name">
</div>
<div id="setup_description" class="routedactions-field-group">
	<label>Description</label>
	<input type="text" value="" id="new_route_description">
</div>
<div id="setup_path" class="routedactions-field-group">
	<label>Path</label>
	<input type="text" value="" id="new_route_path">
</div>
<div id="setup_slug" class="routedactions-field-group">
	<label>Slug</label>
	<input type="text" value="" id="new_route_slug" data-format="slug">
</div>
</script>
<?php
do_action( 'routedactions_admin_templates' );
?>