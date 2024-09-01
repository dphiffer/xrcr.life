<?php

$labels = array(
	'name'               => 'Contacts',
	'singular_name'      => 'Contact',
	'menu_name'          => 'Contacts',
	'name_admin_bar'     => 'Contacts',
	'add_new'            => 'Add New',
	'add_new_item'       => 'Add New Contact',
	'new_item'           => 'New Contact',
	'edit_item'          => 'Edit Contact',
	'view_item'          => 'View Contact',
	'all_items'          => 'All Contacts',
	'search_items'       => 'Search Contacts',
	'parent_item_colon'  => 'Parent Contacts:',
	'not_found'          => 'No Contacts found.',
	'not_found_in_trash' => 'No Contacts found in Trash.',
);
$args = array(
	'labels'             => $labels,
	'description'        => 'Contacts',
	'public'             => false,
	'publicly_queryable' => false,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => false,
	'capability_type'    => 'post',
	'has_archive'        => true,
	'hierarchical'       => true,
	'menu_position'      => 5,
	'menu_icon'          => 'dashicons-admin-users',
	'supports'           => array('revisions')
);
register_post_type('contact', $args);

$labels = array(
	'name'              => 'Attributes',
	'singular_name'     => 'Attribute',
	'search_items'      => 'Search Attributes',
	'all_items'         => 'All Attributes',
	'parent_item'       => 'Parent Attribute',
	'parent_item_colon' => 'Parent Attribute:',
	'edit_item'         => 'Edit Attribute',
	'update_item'       => 'Update Attribute',
	'add_new_item'      => 'Add New Attribute',
	'new_item_name'     => 'New Attribute Name',
	'menu_name'         => 'Attributes',
);
$args = array(
	'hierarchical'      => true,
	'labels'            => $labels,
	'show_ui'           => true,
	'show_admin_column' => true,
	'public'            => false,
	'query_var'         => false
);
register_taxonomy('attributes', array('contact'), $args);

$labels = array(
	'name'               => 'Calls',
	'singular_name'      => 'Call',
	'menu_name'          => 'Calls',
	'name_admin_bar'     => 'Calls',
	'add_new'            => 'Add New',
	'add_new_item'       => 'Add New Call',
	'new_item'           => 'New Call',
	'edit_item'          => 'Edit Call',
	'view_item'          => 'View Call',
	'all_items'          => 'All Calls',
	'search_items'       => 'Search Calls',
	'parent_item_colon'  => 'Parent Calls:',
	'not_found'          => 'No Calls found.',
	'not_found_in_trash' => 'No Calls found in Trash.',
);
$args = array(
	'labels'             => $labels,
	'description'        => 'Contacts',
	'public'             => false,
	'publicly_queryable' => false,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => false,
	'capability_type'    => 'post',
	'has_archive'        => true,
	'hierarchical'       => false,
	'menu_position'      => 5,
	'menu_icon'          => 'dashicons-phone',
	'supports'           => array('revisions')
);
register_post_type('call', $args);

$labels = array(
	'name'              => 'Call Type',
	'singular_name'     => 'Call Type',
	'search_items'      => 'Search Call Types',
	'all_items'         => 'All Call Types',
	'parent_item'       => 'Parent Call Types',
	'parent_item_colon' => 'Parent Call Types:',
	'edit_item'         => 'Edit Call Type',
	'update_item'       => 'Update Call Type',
	'add_new_item'      => 'Add New Call Type',
	'new_item_name'     => 'New Call Type Name',
	'menu_name'         => 'Call Types',
);
$args = array(
	'hierarchical'      => true,
	'labels'            => $labels,
	'show_ui'           => true,
	'show_admin_column' => true,
	'public'            => false,
	'query_var'         => false
);
register_taxonomy('call_type', array('call'), $args);

$labels = array(
	'name'               => 'Events',
	'singular_name'      => 'Event',
	'menu_name'          => 'Calendar',
	'name_admin_bar'     => 'Calendar',
	'add_new'            => 'Add New',
	'add_new_item'       => 'Add New Event',
	'new_item'           => 'New Calendar Event',
	'edit_item'          => 'Edit Calendar Event',
	'view_item'          => 'View Calendar Event',
	'all_items'          => 'All Calendar Events',
	'search_items'       => 'Search Calendar Events',
	'parent_item_colon'  => 'Parent Calendar Events:',
	'not_found'          => 'No Calendar Events found.',
	'not_found_in_trash' => 'No Calendar Events found in Trash.',
);
$args = array(
	'labels'             => $labels,
	'description'        => 'Calendar Events',
	'public'             => true,
	'publicly_queryable' => true,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => true,
	'rewrite'            => array('slug' => 'event'),
	'capability_type'    => 'post',
	'has_archive'        => false,
	'hierarchical'       => false,
	'menu_position'      => 5,
	'menu_icon'          => 'dashicons-calendar-alt',
	'supports'           => array('title', 'revisions')
);
register_post_type('event', $args);

$labels = array(
	'name'              => 'Event Types',
	'singular_name'     => 'Event Type',
	'search_items'      => 'Search Event Types',
	'all_items'         => 'All Event Types',
	'parent_item'       => 'Parent Event Type',
	'parent_item_colon' => 'Parent Event Type:',
	'edit_item'         => 'Edit Event Type',
	'update_item'       => 'Update Event Type',
	'add_new_item'      => 'Add Event Type',
	'new_item_name'     => 'New Event Type',
	'menu_name'         => 'Event Types',
);
$args = array(
	'hierarchical'      => true,
	'labels'            => $labels,
	'show_ui'           => true,
	'show_admin_column' => true,
	'public'            => false,
	'query_var'         => false
);
register_taxonomy('event_type', array('event'), $args);
