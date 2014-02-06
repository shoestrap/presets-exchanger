<?php
/*
Plugin Name: Presets Exchanger
Plugin URI: http://wpmu.io
Description: allow users to exchange their redux export data
Version: 0.2
Author: Aristeides Stathopoulos
Author URI: http://aristeides.com
GitHub Plugin URI: https://github.com/shoestrap/presets-exchanger
*/

if ( !defined( 'SHOESTRAP_PRESETS_URL' ) )
	define( 'SHOESTRAP_PRESETS_URL', plugin_dir_url( __FILE__ ) );

/*
 * Create the "Presets" custom post type
 */
add_action( 'init', 'create_presets_post_type' );
function create_presets_post_type() {
	register_post_type(
		'presets',
		array(
			'labels'			=> array(
				'name'			=> __( 'Presets' ),
				'singular_name'	=> __( 'Preset' )
			),
			'public'			=> true,
			'has_archive'		=> true,
			'supports'			=> array(
				'title',
				'thumbnail',
			),
			'capability_type'	=> array( 'preset', 'presets' ),
			'capabilities'		=> array(
				'publish_posts' 		=> 'publish_presets',
				'edit_posts'			=> 'edit_presets',
				'edit_others_posts'		=> 'edit_others_presets',
				'delete_posts'			=> 'delete_presets',
				'delete_others_posts'	=> 'delete_others_presets',
				'read_private_posts'	=> 'read_private_presets',
				'edit_post'				=> 'edit_preset',
				'delete_post'			=> 'delete_preset',
				'read_post'				=> 'read_preset',
			),
		)
	);
}


add_filter( 'cmb_meta_boxes', 'presets_metabox' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function presets_metabox( array $meta_boxes ) {
	$prefix = '_wpmuio_';

	// Start with an underscore to hide fields from custom fields list

	$meta_boxes[] = array(
		'id'         => 'presets_export_metabox',
		'title'      => 'Redux Export data',
		'pages'      => array( 'presets', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => false,
		'fields'     => array(
			array(
				'name' => __( 'Redux Export Data' ),
				'desc' => __( 'Export your theme settings from the Import/Export menu of the theme and paste it here. Please also include an image by clicking on the "Set Featured Image" link on the right.' ),
				'id'   => $prefix . 'export_data',
				'type' => 'text',
			),
		),
	);

	$meta_boxes[] = array(
		'id'         => 'presets_info_metabox',
		'title'      => 'Redux Export Description',
		'pages'      => array( 'presets', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => false,
		'fields'     => array(
			array(
				'name' => __( 'Description' ),
				'desc' => __( 'A small description for your export data' ),
				'id'   => $prefix . 'export_description',
				'type' => 'textarea',
			),
		),
	);

	return $meta_boxes;
}

if ( !class_exists( 'cmb_Meta_Box' ) )
	require_once( plugin_dir_path(__FILE__) . 'includes/metabox-init.php' );


/*
 * Add our custom capabilities
 */
function add_subscriber_presets_caps() {
    $subscriber 	= get_role( 'subscriber' );
    $administrator 	= get_role( 'administrator' );

    $subscriber->add_cap( 'publish_presets' );
    $subscriber->add_cap( 'edit_presets' );
    $subscriber->add_cap( 'edit_preset' );
    $subscriber->add_cap( 'delete_preset' );
    $subscriber->add_cap( 'read_preset' );


    $administrator->add_cap( 'edit_others_posts' );
    $administrator->add_cap( 'publish_presets' );
    $administrator->add_cap( 'edit_presets' );
    $administrator->add_cap( 'edit_others_presets' );
    $administrator->add_cap( 'delete_presets' );
    $administrator->add_cap( 'delete_others_presets' );
    $administrator->add_cap( 'read_private_presets' );
    $administrator->add_cap( 'edit_preset' );
    $administrator->add_cap( 'delete_preset' );
    $administrator->add_cap( 'read_preset' );

}
add_action( 'admin_init', 'add_subscriber_presets_caps');

require_once( plugin_dir_path(__FILE__) . 'includes/template-functions.php' );
