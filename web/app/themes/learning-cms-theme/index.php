<?php
global $wp_query;

$obj = $wp_query->queried_object;
$res = array();
if (!$obj || !isset($obj->post_type)) {
	return wp_send_json($res);
}
$res['ID'] = $obj->ID;
$res['post_type'] = $obj->post_type;
$acl_enabled = get_field('acl_enabled', $obj->ID);
$res['acl_enabled'] = $acl_enabled ? true : false;
$acl_roles = array_filter(get_field('acl_roles', $obj->ID));
$res['acl_roles'] = $acl_roles ? $acl_roles : array();

return wp_send_json($res);
?>
