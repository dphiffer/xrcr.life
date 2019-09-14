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
	'supports'           => array('title', 'revisions')
);
register_post_type('contact', $args);

$labels = array(
	'name'              => 'Attended',
	'singular_name'     => 'Attended',
	'search_items'      => 'Search Attended',
	'all_items'         => 'All Events Attended',
	'parent_item'       => 'Parent Event',
	'parent_item_colon' => 'Parent Event:',
	'edit_item'         => 'Edit Event Attended',
	'update_item'       => 'Update Event Attended',
	'add_new_item'      => 'Add New Event Attended',
	'new_item_name'     => 'New Event Attended Name',
	'menu_name'         => 'Attended',
);
$args = array(
	'hierarchical'      => true,
	'labels'            => $labels,
	'show_ui'           => true,
	'show_admin_column' => true,
	'public'            => false,
	'query_var'         => false
);
register_taxonomy('attended', array('contact'), $args);

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
