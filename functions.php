<?php 
/*
* Plugin Name: Layers - Demo Extension
* Version: 1.0
* Plugin URI: http://www.yourpluginpage.com
* Description: What does your Extension do for Layers?
* Author: You
* Author URI: http://www.yoursite.com/
*
* Requires at least: 4.0
* Tested up to: 4.3
*
* Layers Plugin: True
* Layers Required Version: 1.2.4
*
* Text Domain: layers-demo-extension
* Domain Path: /lang/
*
* @package WordPress
* @author Obox
* @since 1.0.0
*/

// Secure it
// http://docs.layerswp.com/create-extension-setup-your-plugin/#make-it-secure
if ( ! defined( 'ABSPATH' ) ) exit;

// define constants 
// http://docs.layerswp.com/create-extension-setup-your-plugin/#constants
define( 'LAYERS_DEMO_EXTENSION_SLUG' , 'layers-demo-extension' );
define( 'LAYERS_DEMO_EXTENSION_VER' , '1.0' );
define( 'LAYERS_DEMO_EXTENSION_DIR' , trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'LAYERS_DEMO_EXTENSION_URI' , trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'LAYERS_DEMO_EXTENSION_FILE' , trailingslashit( __FILE__ ) );
define( 'LAYERS_REQUIRED_VERSION' , '1.2.4' );
// Load plugin class files
// http://docs.layerswp.com/create-an-extension-setup-your-plugin-class/#including-files-adding-functionality
require_once( 'includes/class-demo-extension.php' );

// Load custom meta
// http://docs.layerswp.com/create-an-extension-including-scripts-styles-helper-files/

require_once( 'includes/custom-meta.php' );



// Instantiate Plugin
// http://docs.layerswp.com/create-an-extension-setup-your-plugin-class/#instantiating
function layers_demo_extension_init() {

	global $layers_demo_extension;

	$layers_demo_extension = Layers_Demo_Extension::get_instance();
}

add_action( 'plugins_loaded', 'layers_demo_extension_init' );


// Register Post Types
// http://docs.layerswp.com/create-an-extension-adding-custom-post-types-page-templates/
add_action('init', 'register_layers_demo_post_type');

if ( !post_type_exists('layers-demo-posts') ) {
	function register_layers_demo_post_type() {
	
	$label_singular = __('Demo Post', LAYERS_DEMO_EXTENSION_SLUG);
	$label_plural = __('Demo Posts', LAYERS_DEMO_EXTENSION_SLUG);

	register_post_type(
		'layers-demo-posts',
			array(
				'label' => $label_plural,
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'query_var' => true,
				'has_archive' => true,
				'rewrite' => array(
					'slug' => __('story', LAYERS_DEMO_EXTENSION_SLUG),
					'with_front' => false,
				),
				'supports' => array(
					'title',
					'editor',
					'revisions',
					'thumbnail',
					'custom-fields',
				),
				'labels' => array (
					'name' => $label_plural,
					'singular_name' => $label_singular,
					'menu_name' => $label_plural,
					'add_new' => __('Add New', LAYERS_DEMO_EXTENSION_SLUG),
					'add_new_item' => __('Add New ' . $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
					'edit' => __('Edit', LAYERS_DEMO_EXTENSION_SLUG),
					'edit_item' => __('Edit ' . $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
					'new_item' => __('New ' . $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
					'view' => __('View ' . $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
					'view_item' => __('View ' . $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
					'search_items' => __('Search ' . $label_plural, LAYERS_DEMO_EXTENSION_SLUG),
					'not_found' => __('No ' . $label_plural . ' Found', LAYERS_DEMO_EXTENSION_SLUG),
					'not_found_in_trash' =>__('No ' . $label_plural . ' Found in Trash', LAYERS_DEMO_EXTENSION_SLUG),
					'parent' => __('Parent ' . $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
				)
			)
		);
	}
} // END if

// Create Custom Taxonomy for Demo Post type
// hook into the init action and call create_layers_demo_taxonomies when it fires
add_action( 'init', 'create_layers_demo_taxonomies');

// http://docs.layerswp.com/create-an-extension-adding-custom-post-types-page-templates/#custom-taxonomies
function create_layers_demo_taxonomies() {

	$label_singular = 'Topic';
	$label_plural = 'Topics';
	
	$labels = array(
		'name' => $label_plural,
		'singular_name' => $label_singular,
		'menu_name' => $label_plural,
		'search_items' => __( 'Search '. $label_plural, LAYERS_DEMO_EXTENSION_SLUG),
		'all_items' => __( 'All '. $label_plural, LAYERS_DEMO_EXTENSION_SLUG),
		'parent_item' => __( 'Parent '. $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
		'parent_item_colon' => __( 'Parent '. $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
		'edit_item' => __( 'Edit '. $label_plural, LAYERS_DEMO_EXTENSION_SLUG),
		'update_item' => __( 'Update '. $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
		'add_new_item' => __( 'Add New '. $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
		'new_item_name' => __( 'New '. $label_singular, LAYERS_DEMO_EXTENSION_SLUG),
	);
	
	$args = array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => __('topic', LAYERS_DEMO_EXTENSION_SLUG) ),
	);
	
	register_taxonomy( 'layers-demo-category', array( 'layers-demo-posts' ), $args );

}

// Setup Single and Archive templates
// http://docs.layerswp.com/create-an-extension-adding-custom-post-types-page-templates/#custom-page-templates
function get_custom_post_type_template($single_template) {
    global $post;

    if ($post->post_type == 'layers-demo-posts') {
        $single_template = LAYERS_DEMO_EXTENSION_DIR . 'templates/single-layers-demo-posts.php';
    }
    return $single_template;
}


add_filter( 'single_template', 'get_custom_post_type_template' );

function get_custom_post_type_archive($archive_template) {
    global $post;

    if (is_tax('layers-demo-category')) {
        $archive_template = LAYERS_DEMO_EXTENSION_DIR . 'templates/archive-layers-demo-category.php';
    }
    return $archive_template;
}
add_filter( 'archive_template', 'get_custom_post_type_archive' );