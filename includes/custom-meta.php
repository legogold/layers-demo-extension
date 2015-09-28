<?php 
/**
* Layers Demo Custom Meta
* http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/
**/

/*
* Add Meta Box
* http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/#custom-fields-in-the-editor
*/

add_action( 'add_meta_boxes', 'layers_demo_add_meta_box' );
function layers_demo_add_meta_box() {

  $screens = array('layers-demo-posts');
  foreach ( $screens as $screen ) {

	  add_meta_box(
		'layers_demo_meta_sectionid',
		__( 'Layers Extension Demo Custom Meta', 'layerswp' ),
		'layers_demo_meta_box_callback',
		$screen,
			'normal',
			'high'
	   );
  	}
}



/*
* Create Meta Callback - Prints the box content.
* @param WP_Post $post The object for the current post/page.
*/
function layers_demo_meta_box_callback( $post ) {

// Add an nonce field so we can check for it later.
wp_nonce_field( 'layers_demo_meta_box', 'layers_demo_meta_box_nonce' );

/*
* Use get_post_meta() to retrieve an existing value
* from the database and use the value for the form.
*/
$credit_name = get_post_meta( $post->ID, 'my_photo_credit', true );
$credit_url = get_post_meta( $post->ID, 'my_credit_url', true );

// form elements go here
if( class_exists( 'Layers_Form_Elements' ) ) {
$form_elements = new Layers_Form_Elements();

	//Photo Source
	echo '<p class="layers-form-item">';
	echo '<label>'.__('Photo Source', 'layerswp').'</label> ';
	echo $form_elements->input( 
		array(
			'type' => 'text',
			'name' => 'my_photo_credit',
			'id' => 'my_photo_credit',
			'placeholder' => __( 'Photo Source', 'layerswp' ),
			'value' => ( isset( $credit_name ) ? $credit_name : '' ),
			'class' => 'layers-text'
		)
	);
	echo '</p>';
	// Photo Source URL
	echo '<p class="layers-form-item">';
	echo '<label>'.__('Photo Source URL', 'layerswp').'</label> ';
	echo $form_elements->input( 
		array(
			'type' => 'text',
			'name' => 'my_credit_url',
			'id' => 'my_credit_url',
			'placeholder' => __( 'Photo Credit URL', 'layerswp' ),
			'value' => ( isset( $credit_url ) ? $credit_url : '' ),
			'class' => 'layers-text'
		)
	);
	echo '</p>';
	
	$photo_description = get_post_meta( $post->ID, 'my_photo_desc', true );
	
	/* Add WP Editor as replacement of textarea */
	echo '<p class="layers-form-item">';
	echo '<label>'.__('Photo Description', 'layerswp').'</label> ';
	wp_editor( $photo_description, 'my_photo_desc', array(
		'wpautop'       => true,
		'media_buttons' => false,
		'textarea_name' => 'my_photo_desc',
		'textarea_rows' => 10,
		'teeny'         => true
	) );
	echo '</p>';
  } // If Class exists
} // End callback

/*
* Save the Meta
* http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/#saving-meta-data
*/

function layers_demo_save_meta_box_data( $post_id ) {
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'layers_demo_meta_box_nonce' ] ) && wp_verify_nonce( $_POST[ 'layers_demo_meta_box' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	
	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
	return;
	}
	
	// Checks for input and sanitizes/saves if needed
	if( isset( $_POST[ 'my_photo_credit' ] ) ) {
	update_post_meta( $post_id, 'my_photo_credit', sanitize_text_field( $_POST[ 'my_photo_credit' ] ) );
	}
	if( isset( $_POST[ 'my_credit_url' ] ) ) {
	update_post_meta( $post_id, 'my_credit_url', sanitize_text_field( $_POST[ 'my_credit_url' ] ) );
	}
	if( isset( $_POST[ 'my_photo_desc' ] ) ) {
	update_post_meta( $post_id, 'my_photo_desc', sanitize_text_field( $_POST[ 'my_photo_desc' ] ) );
	}
}

add_action( 'save_post', 'layers_demo_save_meta_box_data' );
