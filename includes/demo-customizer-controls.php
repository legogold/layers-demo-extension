<?php 
/**
* Layers Demo Customizer Options
* http://docs.layerswp.com/create-an-extension-adding-customizer-controls/
**/

	
/* Create Custom Customizer Panel
* http://docs.layerswp.com/reference/layers_customizer_panels/
* @since  1.1
* @return $panels
*/

function demo_customizer_panels( $panels ){
	$panels['layers-extension-demo'] = array(
			'title' => __( 'Demo Extension', LAYERS_DEMO_EXTENSION_SLUG ),
			'priority' => 130
	);
	return $panels;
}

/* Create Custom Customizer Section
* http://docs.layerswp.com/reference/layers_customizer_sections/
* @since  1.1
* @return $sections
*/

function demo_customizer_sections( $sections ){

	$sections[ 'layers-demo-options' ] = array(
			'options' => array(
					'title' =>__( 'Demo Options' , LAYERS_DEMO_EXTENSION_SLUG ),
					'panel' => 'layers-extension-demo'
			)
	);

	return $sections;
}


/* Create Custom Customizer Controls
* http://docs.layerswp.com/reference/layers_customizer_controls/
* @since  1.1
* @return $controls
*/

function demo_customizer_controls( $controls ){
    $controls[ 'section-name' ] = array(
      'option-one' => array(
         'type' => 'layers-text',
         'label' => __( 'Option One' , LAYERS_DEMO_EXTENSION_SLUG )
      ),
      'option-two' => array(
         'type' => 'layers-text',
         'label' => __( 'Option Two' , LAYERS_DEMO_EXTENSION_SLUG )
       )      
    );
    return $controls;
}