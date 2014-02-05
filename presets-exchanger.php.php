<?php
/*
Plugin Name: Presets Exchanger
Plugin URI: http://wpmu.io
Description: allow users to exchange their redux export data
Version: 0.1
Author: Aristeides Stathopoulos
Author URI: http://aristeides.com
GitHub Plugin URI: https://github.com/shoestrap/presets-exchanger
*/


/*
 * Create the "Presets" custom post type
 */
add_action( 'init', 'create_presets_post_type' );
function create_presets_post_type() {
	register_post_type(
		'presets',
		array(
			'labels'            => array(
				'name'          => __( 'Presets' ),
				'singular_name' => __( 'Preset' )
			),
			'public'            => true,
			'has_archive'       => true,
			'supports'          => array(
				'title',
				'thumbnail',
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
		'id'         => 'presets_metabox',
		'title'      => 'Presets Fields',
		'pages'      => array( 'presets', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => false,
		'fields'     => array(
			array(
				'name' => __( 'Redux Export Data' ),
				'desc' => __( 'Export your theme settings from the Import/Export menu of the theme and paste it here. Please also include an image by clicking on the "Set Featured Image" link on the right.' ),
				'id'   => $prefix . 'export_data',
				'type' => 'textarea',
			),
		),
	);

	return $meta_boxes;
}

if ( !class_exists( 'cmb_Meta_Box' ) )
	require_once( plugin_dir_path(__FILE__) . 'includes/metabox-init.php' );