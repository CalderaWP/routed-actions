<?php

global $route_routes_types;

?><div class="wrap">
	<h2 class="routedactions-h2"><span class="dashicons <?php echo RACTIONS_ICON; ?>"></span><span id="routedactions-page-title">Routed Actions</span> <small id="routedactions-page-caption" data-version="<?php echo RACTIONS_VER; ?>" style="font-size: 11px; line-height: 10px; color: rgb(159, 159, 159);"><?php echo RACTIONS_VER; ?></small> <span id="loading-indicator"><span class="spinner"></span></span></h2>
	<div class="wp-filter">
		<ul class="wp-filter-links" id="routedactions-toolbar">
		<?php include RACTIONS_PATH . 'ui/admin-menus.php'; ?>
		</ul>
	</div>
	<form id="routedactions-canvas" data-action="route_route_handler"></form>
</div>

<?php
// pull in templates

// admin specific
include_once RACTIONS_PATH . 'ui/templates/admin/admin-templates.php';

// editor specific
include_once RACTIONS_PATH . 'ui/templates/editor/editor-templates.php';

?>
<script type="text/javascript">
	confirm_save_notice = '<?php _e("The changes you made will be lost if you navigate away from this page."); ?>';
	/*Init*/
	route_reveal_tools();

</script>