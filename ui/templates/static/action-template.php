<?php
/**
*	General action
*
*/
?>
<div class="routedactions-field-group" id="setup_action">
	<label>Action</label>
	<input type="text" id="route_action" value="{{route_action}}" name="route_action">
</div>
<div class="routedactions-field-group" id="setup_action">
	<label>Argument</label>
	<select id="route_action_args" value="{{route_args}}" name="route_args">
	<option value="get" {{#is route_args value="get"}}selected="selected"{{/is}}>$_GET</option>
	<option value="post" {{#is route_args value="post"}}selected="selected"{{/is}} >$_POST</option>
	<option value="request" {{#is route_args value="request"}}selected="selected"{{/is}} >$_REQUEST</option>
	</select>
</div>