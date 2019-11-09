<?php

add_role('caller', 'Caller', array(
	'read' => true,
	'edit_posts' => true,
	'edit_published_posts' => true
));

$caller_role = get_role('caller');
$caller_role->add_cap('call_contacts', true);

$editor_role = get_role('editor');
$editor_role->add_cap('call_contacts', true);

$admin_role = get_role('administrator');
$admin_role->add_cap('call_contacts', true);
