<?php  /**
 * Layers Stories Widget
 *
 * This file is used to register and display the Layers widget.
 * http://docs.layerswp.com/development-tutorials-layers-builder-widgets/
 * 
 * @package Layers
 * @since Layers 1.0.0
 */
if( !class_exists( 'Layers_Demo_Widget' ) && class_exists( 'Layers_Widget' ) ) {

// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#widget-class
class Layers_Demo_Widget extends Layers_Widget {
 
        /**
        *  1 - Widget construction
		* http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#1-widget-construction
        */
        function Layers_Demo_Widget(){
			$this->widget_title = __( 'Stories' , 'layerswp' );
			$this->widget_id = 'stories';
			$this->post_type = 'layers-demo-posts';
			$this->taxonomy = 'layers-demo-category';
			$this->checkboxes = array(
				'show_media',
				'show_titles',
				'show_excerpts',
				'show_dates',
				'show_author',
				'show_credit',
				'show_topics',
				'show_call_to_action'
			);
			
			/* Widget settings. */ 

			$widget_ops = array( 
				  'classname' => 'obox-layers-' . $this->widget_id .'-widget', 
				  'description' => __( 'This widget is used to display your Posts', 'layerswp')
			);
			
			/* Widget control settings. */ 

			$control_ops = array( 
				  'width' => '660', 
				  'height' => NULL, 
				  'id_base' => 'layers-widget-' . $this->widget_id 
			);
			
			/* Create the widget. */ 
			
			parent::__construct( 'layers' . '-widget-' . $this->widget_id , $this->widget_title, $widget_ops, $control_ops );
			
			/* Setup Widget Defaults */
			$this->defaults = array (
				'title' => __( 'Latest Stories', 'layerswp' ),
				'excerpt' => __( 'Stay up to date with all our latest news and launches. Only the best quality makes it onto our blog!', 'layerswp' ),
				'text_style' => 'overlay',
				'category' => 0,
				'show_media' => 'on',
				'show_titles' => 'on',
				'show_excerpts' => 'off',
				'show_dates' => 'on',
				'show_author' => 'on',
				'show_credit' => 'on',
				'show_topics' => 'on',
				'excerpt_length' => 200,
				'show_call_to_action' => 'on',
				'call_to_action' => __( 'Read Story' , 'layerswp' ),
				'posts_per_page' => 6,
				'design' => array(
					'layout' => 'layout-boxed',
					'imageratios' => 'image-square',
					'textalign' => 'text-left',
					'liststyle' => 'list-grid',
					'columns' => '3',
					'gutter' => 'on',
					'background' => array(
						'position' => 'center',
						'repeat' => 'no-repeat'
					),
					'fonts' => array(
						'align' => 'text-left',
						'size' => 'medium',
						'color' => NULL,
						'shadow' => NULL
					)
				)
			);
			
		} // END main function
		
 		/**
        *  2 - Widget form
        * http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#2-widget-form
        * We use regulage HTML here, it makes reading the widget much easier 
        * than if we used just php to echo all the HTML out.
        * 
        */
        function form( $instance ){
			
		// $instance Defaults
			$instance_defaults = $this->defaults;
		
			// If we have information in this widget, then ignore the defaults
			if( !empty( $instance ) ) $instance_defaults = array();
		
			// Parse $instance
			$instance = wp_parse_args( $instance, $instance_defaults );
		
			extract( $instance, EXTR_SKIP );
			
			// Design Bar Components
			$design_bar_components = apply_filters(
				  'layers_' . $this->widget_id . '_widget_design_bar_components' ,
					  array(
						'layout',
						'fonts',
						'custom',
						'columns',
						'liststyle',
						'imageratios',
						'background',
						'advanced'
					  )
			);
			
			// Design Bar Components
			// We reference the Post Widget here, but remove show_tags and replace it with
			// show_credit and show_topics to work with our custom post type taxonomy and meta
			// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#widget-form-design-bar
			$design_bar_custom_components = apply_filters(
			   'layers_' . $this->widget_id . '_widget_design_bar_custom_components' ,
				   array(
					'display' => array(
					   'icon-css' => 'icon-display',
					   'label' => __( 'Display', 'layerswp' ),
					   'elements' => array(
						  'text_style' => array(
							'type' => 'select',
							'name' => $this->get_field_name( 'text_style' ) ,
							'id' => $this->get_field_id( 'text_style' ) ,
							'value' => ( isset( $text_style ) ) ? $text_style : NULL,
							'label' => __( 'Title & Excerpt Position' , 'layerswp' ),
							   'options' => array(
								  'regular' => __( 'Regular' , 'layerswp' ),
								  'overlay' => __( 'Overlay' , 'layerswp' )
							   )
						   ),
						'show_media' => array(
						   'type' => 'checkbox',
						   'name' => $this->get_field_name( 'show_media' ) ,
						   'id' => $this->get_field_id( 'show_media' ) ,
						   'value' => ( isset( $show_media ) ) ? $show_media : NULL,
						   'label' => __( 'Show Featured Images' , 'layerswp' )
						),
						'show_titles' => array(
						   'type' => 'checkbox',
						   'name' => $this->get_field_name( 'show_titles' ) ,
						   'id' => $this->get_field_id( 'show_titles' ) ,
						   'value' => ( isset( $show_titles ) ) ? $show_titles : NULL,
						   'label' => __( 'Show  Post Titles' , 'layerswp' )
						),
						'show_excerpts' => array(
							'type' => 'checkbox',
							'name' => $this->get_field_name( 'show_excerpts' ) ,
							'id' => $this->get_field_id( 'show_excerpts' ) ,
							'value' => ( isset( $show_excerpts ) ) ? $show_excerpts : NULL,
							'label' => __( 'Show Post Excerpts' , 'layerswp' )
						 ),
						'excerpt_length' => array(
							 'type' => 'number',
							 'name' => $this->get_field_name( 'excerpt_length' ) ,
							 'id' => $this->get_field_id( 'excerpt_length' ) ,
							 'min' => 0,
							 'max' => 10000,
							 'value' => ( isset( $excerpt_length ) ) ? $excerpt_length : NULL,
							 'label' => __( 'Excerpts Length' , 'layerswp' )
						 ),
						 'show_dates' => array(
							  'type' => 'checkbox',
							  'name' => $this->get_field_name( 'show_dates' ) ,
							  'id' => $this->get_field_id( 'show_dates' ) ,
							  'value' => ( isset( $show_dates ) ) ? $show_dates : NULL,
							  'label' => __( 'Show Post Dates' , 'layerswp' )
						 ),
						 'show_author' => array(
							   'type' => 'checkbox',
							   'name' => $this->get_field_name( 'show_author' ) ,
							   'id' => $this->get_field_id( 'show_author' ) ,
							   'value' => ( isset( $show_author ) ) ? $show_author : NULL,
							   'label' => __( 'Show Post Author' , 'layerswp' )
						 ),
						'show_credit' => array(
							   'type' => 'checkbox',
							   'name' => $this->get_field_name( 'show_credit' ) ,
							   'id' => $this->get_field_id( 'show_credit' ) ,
							   'value' => ( isset( $show_credit ) ) ? $show_credit : NULL,
							   'label' => __( 'Show Photo Credit' , 'layerswp' )
						 ),
						'show_topics' => array(
							   'type' => 'checkbox',
							   'name' => $this->get_field_name( 'show_topics' ) ,
							   'id' => $this->get_field_id( 'show_topics' ) ,
							   'value' => ( isset( $show_topics ) ) ? $show_topics : NULL,
							   'label' => __( 'Show Topics' , 'layerswp' )
						),
					   'show_call_to_action' => array(
							   'type' => 'checkbox',
							   'name' => $this->get_field_name( 'show_call_to_action' ) ,
							   'id' => $this->get_field_id( 'show_call_to_action' ) ,
							   'value' => ( isset( $show_call_to_action ) ) ? $show_call_to_action : NULL,
							   'label' => __( 'Show "Read More" Buttons' , 'layerswp' )
						 ),
					   'call_to_action' => array(
							   'type' => 'text',
							   'name' => $this->get_field_name( 'call_to_action' ) ,
							   'id' => $this->get_field_id( 'call_to_action' ) ,
							   'value' => ( isset( $call_to_action ) ) ? $call_to_action : NULL,
							   'label' => __( '"Read More" Text' , 'layerswp' )
						 ),
						'show_pagination' => array(
							   'type' => 'checkbox',
							   'name' => $this->get_field_name( 'show_pagination' ) ,
							   'id' => $this->get_field_id( 'show_pagination' ) ,
							   'value' => ( isset( $show_pagination ) ) ? $show_pagination : NULL,
							   'label' => __( 'Show Pagination' , 'layerswp' )
						 ),
					   )
				   )
			   )
			); // End Custom Design Bar Components
			
			// Instantiate the Deisgn Bar
			$this->design_bar(
				'side', // CSS Class Name
				  array(
					 'name' => $this->get_field_name( 'design' ),
					 'id' => $this->get_field_id( 'design' ),
				  ), // Widget Object
				 $instance, // Widget Values
				 $design_bar_components, // Standard Components
				 $design_bar_custom_components // Add-on Components
			);
			
			// Build Content Form 
			// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#content-options-form
			?>
			
			<div class="layers-container-large">
				<?php
					$this->form_elements()->header( 
						   array(
							'title' =>  __( 'Story' , 'layerswp' ),
							'icon_class' => 'post'
						   ) 
					);
				?>
				<section class="layers-accordion-section layers-content">
					<div class="layers-row layers-push-bottom">
		
					<p class="layers-form-item">
						  <?php echo $this->form_elements()->input(
							  array(
								  'type' => 'text',
								  'name' => $this->get_field_name( 'title' ) ,
								  'id' => $this->get_field_id( 'title' ) ,
								  'placeholder' => __( 'Enter title here' , 'layerswp' ),
								  'value' => ( isset( $title ) ) ? $title : NULL ,
								  'class' => 'layers-text layers-large'
							  )
						  ); ?>
					</p>
					
					<p class="layers-form-item">
						  <?php echo $this->form_elements()->input(
							  array(
								  'type' => 'textarea',
								  'name' => $this->get_field_name( 'excerpt' ) ,
								  'id' => $this->get_field_id( 'excerpt' ) ,
								  'placeholder' => __( 'Short Excerpt' , 'layerswp' ),
								  'value' => ( isset( $excerpt ) ) ? $excerpt : NULL ,
								  'class' => 'layers-textarea layers-large'
							  )
						  ); ?>
					</p>
					
					<?php // Select a Topic
					
					$terms = get_terms( $this->taxonomy );
					if( !is_wp_error( $terms ) ) { ?>
						<p class="layers-form-item">
							<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php echo __( 'Choose a Topic' , 'layerswp' ); ?></label>
							<?php $category_options[ 0 ] = __( 'All' , 'layerswp' );
							foreach ( $terms as $t ) $category_options[ $t->term_id ] = $t->name;
							echo $this->form_elements()->input(
								array(
									'type' => 'select',
									'name' => $this->get_field_name( 'category' ) ,
									'id' => $this->get_field_id( 'category' ) ,
									'placeholder' => __( 'Select a Category' , 'layerswp' ),
									'value' => ( isset( $category ) ) ? $category : NULL ,
									'options' => $category_options
								)
							); ?>
						</p>
					<?php } // if !is_wp_error 
					
					// Select the Number if Stories and how to sort them ?>
					
					 <p class="layers-form-item">
						<?php 
							echo __( 'Number of items to show' , 'layerswp' ); 
							echo $this->form_elements()->input(
								array(
									'type' => 'number',
									'name' => $this->get_field_name( 'posts_per_page' ) ,
									'id' => $this->get_field_id( 'posts_per_page' ) ,
									'value' => ( isset( $posts_per_page ) ) ? $posts_per_page : NULL ,
									'min' => '-1',
									'max' => '100'
								)
						); ?>
					</p>
					
                    <p class="layers-form-item">
						<?php echo __( 'Sort By' , 'layerswp' ); ?>
                        <?php echo $this->form_elements()->input(
							array(
								'type' => 'select',
								'name' => $this->get_field_name( 'orderby' ) ,
								'id' => $this->get_field_id( 'orderby' ) ,
								'value' => ( isset( $order ) ) ? $order : NULL ,
								'options' => $this->form_elements()->get_sort_options()
							)
                        ); ?>
                    </p>
                    
                    <p class="layers-form-item">
						<?php echo __( 'Sort Order' , 'layerswp' ); ?>
                        <?php echo $this->form_elements()->input(
							array(
								'type' => 'select',
								'name' => $this->get_field_name( 'sort_order' ) ,
								'id' => $this->get_field_id( 'sort_order' ) ,
								'value' => ( isset( $order ) ) ? $order : NULL ,
								'options' => $this->form_elements()->get_sort_options()
							)
                    	); ?>
                    </p>
					
					</div>
				</section>
			</div>
		
		<?php 
		} // Form
		
        /**
        *  3 - Update Options
		*  http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#3-update-controls 
        */    
 
        function update($new_instance, $old_instance) {
		  if ( isset( $this->checkboxes ) ) {
			foreach( $this->checkboxes as $cb ) {
			  if( isset( $old_instance[ $cb ] ) ) {
				$old_instance[ $cb ] = strip_tags( $new_instance[ $cb ] );
			  }
			} // foreach checkboxes
		  } // if checkboxes
		
		  return $new_instance;
		} 
         
		
		/**
		*  4 - Widget front end display
		*  http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#4-widget-front-end
		*/
		function widget( $args, $instance ) {
			
			// Turn $args array into variables.
			extract( $args );
			
			// $instance Defaults
			$instance_defaults = $this->defaults;
			
			// If we have information in this widget, then ignore the defaults
			if( !empty( $instance ) ) $instance_defaults = array();
			
			// Parse $instance
			$widget = wp_parse_args( $instance, $instance_defaults );
			
			// Enqueue Masonry if need be
			if( 'list-masonry' == $this->check_and_return( $widget , 'design', 'liststyle' ) ) $this->enqueue_masonry();
			
			// Set the span class for each column
			// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#defining-the-grid
			if( 'list-list' == $widget['design'][ 'liststyle' ] ) {
				$col_count = 1;
				$span_class = 'span-12';
			} else if( isset( $widget['design'][ 'columns']  ) ) {
				$col_count = str_ireplace('columns-', '', $widget['design'][ 'columns']  );
				$span_class = 'span-' . ( 12/ $col_count );
			} else {
				$col_count = 3;
				$span_class = 'span-4';
			}
			// Apply Styling
			// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#colors-and-font-settings
			layers_inline_styles( '#' . $widget_id, 'background', array( 'background' => $widget['design'][ 'background' ] ) );
			layers_inline_styles( '#' . $widget_id, 'color', array( 'selectors' => array( '.section-title h3.heading' , '.section-title div.excerpt' ) , 'color' => $widget['design']['fonts'][ 'color' ] ) );
			layers_inline_styles( '#' . $widget_id, 'background', array( 'selectors' => array( '.thumbnail:not(.with-overlay) .thumbnail-body' ) , 'background' => array( 'color' => $this->check_and_return( $widget, 'design', 'column-background-color' ) ) ) );
			layers_inline_button_styles( '#' . $widget_id, 'button', array( 'selectors' => array( '.thumbnail-body a.button' ) ,'button' => $this->check_and_return( $widget, 'design', 'buttons' ) ) );
			// Apply the advanced widget styling
			$this->apply_widget_advanced_styling( $widget_id, $widget );
			// Set Image Sizes
			if( isset( $widget['design'][ 'imageratios' ] ) ){
				// Translate Image Ratio
				$image_ratio = layers_translate_image_ratios( $widget['design'][ 'imageratios' ] );
				if( 'layout-boxed' == $this->check_and_return( $widget , 'design', 'layout' ) && $col_count > 2 ){
					$use_image_ratio = $image_ratio . '-medium';
				} elseif( 'layout-boxed' != $this->check_and_return( $widget , 'design', 'layout' ) && $col_count > 3 ){
					$use_image_ratio = $image_ratio . '-large';
				} else {
					$use_image_ratio = $image_ratio . '-large';
				}
			} else {
				$use_image_ratio = 'large';
			}
			
			// Begin query arguments
			// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#query-and-display-post-content
			
			$query_args = array();
			
			// Setup Pagination
			if( get_query_var('paged') ) {
				$query_args[ 'paged' ] = get_query_var('paged') ;
			} else if ( get_query_var('page') ) {
				$query_args[ 'paged' ] = get_query_var('page');
			} else {
				$query_args[ 'paged' ] = 1;
			}
			
			$query_args[ 'post_type' ] = $this->post_type;
			
			$query_args[ 'posts_per_page' ] = $widget['posts_per_page'];
			
			if( isset( $widget['order'] ) ) {
				$decode_order = json_decode( $widget['order'], true );
				if( is_array( $decode_order ) ) {
					foreach( $decode_order as $key => $value ){
						$query_args[ $key ] = $value;
					}
				}
			}
			
			// Do the special taxonomy array()
			if( isset( $widget['category'] ) && '' != $widget['category'] && 0 != $widget['category'] ){
				$query_args['tax_query'] = array(
					array(
						"taxonomy" => $this->taxonomy,
						"field" => "id",
						"terms" => $widget['category']
					)
				);
			} elseif( !isset( $widget['hide_category_filter'] ) ) {
				$terms = get_terms( $this->taxonomy );
			} // if we haven't selected which category to show, let's load the $terms for use in the filter
			
			// Do the WP_Query
			$post_query = new WP_Query( $query_args );
			
			// Set the meta to display
			// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#getmeta-data
			global $layers_post_meta_to_display;
			$layers_post_meta_to_display = array();
			if( isset( $widget['show_dates'] ) ) $layers_post_meta_to_display[] = 'date';
			if( isset( $widget['show_author'] ) ) $layers_post_meta_to_display[] = 'author';
			if( isset( $widget['show_topics'] ) ) $layers_post_meta_to_display[] = 'categories';
			
			
			// Generate the widget container class
			// Do not edit
			$widget_container_class = array();
			$widget_container_class[] = 'widget row content-vertical-massive';
			$widget_container_class[] = $this->check_and_return( $widget , 'design', 'advanced', 'customclass' );
			$widget_container_class[] = $this->get_widget_spacing_class( $widget );
			$widget_container_class = implode( ' ', apply_filters( 'layers_post_widget_container_class' , $widget_container_class ) ); 


			/**
			*  Widget Markup
			*  http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#widget-html
			*/
			?> 
            
			<section class=" <?php echo $widget_container_class; ?>" id="<?php echo $widget_id; ?>">
				<?php if( '' != $this->check_and_return( $widget , 'title' ) ||'' != $this->check_and_return( $widget , 'excerpt' ) ) { ?>
					<div class="container clearfix">	
						<?php 
					    // Generate the Section Title Classes
						$section_title_class = array();
						$section_title_class[] = 'section-title clearfix';
						$section_title_class[] = $this->check_and_return( $widget , 'design', 'fonts', 'size' );
						$section_title_class[] = $this->check_and_return( $widget , 'design', 'fonts', 'align' );
						$section_title_class[] = ( $this->check_and_return( $widget, 'design', 'background' , 'color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $widget, 'design', 'background' , 'color' ) ) ? 'invert' : '' );
						$section_title_class = implode( ' ', $section_title_class ); ?>
                        
						<div class="<?php echo $section_title_class; ?>">
							<?php if( '' != $widget['title'] ) { ?>
								<h3 class="heading"><?php echo esc_html( $widget['title'] ); ?></h3>
							<?php } ?>
							<?php if( '' != $widget['excerpt'] ) { ?>
								<div class="excerpt"><?php echo $widget['excerpt']; ?></div>
							<?php } ?>
						</div>    
					</div>
				<?php }
				
				// Begin Post Structure ?>	
                <div class="row <?php echo $this->get_widget_layout_class( $widget ); ?> <?php echo $this->check_and_return( $widget , 'design', 'liststyle' ); ?>">
					<?php if( $post_query->have_posts() ) { ?>
						<?php while( $post_query->have_posts() ) {
							$post_query->the_post();
							if( 'list-list' == $widget['design'][ 'liststyle' ] ) { ?>
								<article id="post-<?php the_ID(); ?>" class="row push-bottom-large">
									<?php if( isset( $widget['show_titles'] ) ) { ?>
										<header class="section-title large">
											<h1 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
										</header>
									<?php } ?>

									<?php // Layers Featured Media
									if( isset( $widget['show_media'] ) ) {
										echo layers_post_featured_media(
											array(
												'postid' => get_the_ID(),
												'wrap_class' => 'thumbnail push-bottom span-5 column' .  ( ( isset( $column['design'][ 'imageratios' ] ) && 'image-round' == $column['design'][ 'imageratios' ] ) ? ' image-rounded' : '' ),
												'size' => $use_image_ratio
											)
										);
									} // if Show Media ?>

									<?php if( isset( $widget['show_excerpts'] ) || isset($widget['show_call_to_action'] ) || !empty( $layers_post_meta_to_display ) ) { ?>
										<div class="column span-7">
											<?php if( isset( $widget['show_excerpts'] ) ) {
												if( isset( $widget['excerpt_length'] ) && '' == $widget['excerpt_length'] ) {
													echo '<div class="copy push-bottom">';
														the_content();
													echo '</div>';
												} else if( isset( $widget['excerpt_length'] ) && 0 != $widget['excerpt_length'] && strlen( get_the_excerpt() ) > $widget['excerpt_length'] ){
													echo '<div class="copy push-bottom">' . substr( get_the_excerpt() , 0 , $widget['excerpt_length'] ) . '&#8230;</div>';
												} else if( '' != get_the_excerpt() ){
													echo '<div class="copy push-bottom">' . get_the_excerpt() . '</div>';
												}
											}; ?>

											<?php layers_post_meta( get_the_ID(), $layers_post_meta_to_display, 'footer' , 'meta-info push-bottom ' . ( '' != $this->check_and_return( $widget, 'design', 'column-background-color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $widget, 'design', 'column-background-color' ) ) ? 'invert' : '' ) );?>
                                            
											<?php // Grab our Custom Meta
                                                    // http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/
                                                    global $post;
                                                    $show_credit = get_post_meta( $post->ID, 'my_photo_credit', true );
                                                    $credit_url = get_post_meta( $post->ID, 'my_credit_url', true );
                                                    if( isset( $widget['show_credit'] ) && $this->check_and_return( $widget , 'show_credit' ) ){?>
                                                        <a href="<?php echo $credit_url; ?>" ><div class="meta-credit"><span class="meta-item"><?php echo $show_credit; ?></span></div></a>
                                             <?php } // show custom meta ?>

											<?php if( isset( $widget['show_call_to_action'] ) && $this->check_and_return( $widget , 'call_to_action' ) ) { ?>
												<p><a href="<?php the_permalink(); ?>" class="button"><?php echo $widget['call_to_action']; ?></a></p>
											<?php } // show call to action ?>
                                            
										</div>
									<?php } ?>
								</article>
							<?php } else {
								
								// Set Individual Column CSS
								// Don't edit
								$post_column_class = array();
								$post_column_class[] = 'layers-masonry-column thumbnail';
								$post_column_class[] = ( 'list-masonry' == $this->check_and_return( $widget, 'design', 'liststyle' ) ? 'no-gutter' : '' );
								$post_column_class[] = 'column' . ( 'on' != $this->check_and_return( $widget, 'design', 'gutter' ) ? '-flush' : '' );
								$post_column_class[] = $span_class;
								$post_column_class[] = ( 'overlay' == $this->check_and_return( $widget , 'text_style' ) ? 'with-overlay' : ''  ) ;
								$post_column_class[] = ( '' != $this->check_and_return( $widget, 'design', 'column-background-color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $widget, 'design', 'column-background-color' ) ) ? 'invert' : '' );
								$post_column_class = implode( ' ' , $post_column_class ); ?>

								<article class="<?php echo $post_column_class; ?>" data-cols="<?php echo $col_count; ?>">
									<?php // Layers Featured Media
									if( isset( $widget['show_media'] ) ) {
										echo layers_post_featured_media(
											array(
												'postid' => get_the_ID(),
												'wrap_class' => 'thumbnail-media' .  ( ( isset( $column['design'][ 'imageratios' ] ) && 'image-round' == $column['design'][ 'imageratios' ] ) ? ' image-rounded' : '' ),
												'size' => $use_image_ratio,
												'hide_href' => false
											)
										);
									} // if Show Media ?>
									<?php if( isset( $widget['show_titles'] ) || isset( $widget['show_excerpts'] ) ) { ?>
										<div class="thumbnail-body">
											<div class="overlay">
												<?php if( isset( $widget['show_titles'] ) ) { ?>
													<header class="article-title">
														<h4 class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
													</header>
												<?php } ?>
												<?php if( '' != $this->check_and_return( $widget, 'text_style' ) ) { ?>
                                                    <?php layers_post_meta( get_the_ID(), $layers_post_meta_to_display, 'footer' , 'meta-info ', 'meta-credit' . ( '' != $this->check_and_return( $widget, 'design', 'column-background-color' ) && 'dark' == layers_is_light_or_dark( $this->check_and_return( $widget, 'design', 'column-background-color' ) ) ? 'invert' : '' ) );?>
                                                    
													<?php // Grab our Custom Meta
                                                    // http://docs.layerswp.com/how-to-add-custom-fields-to-posts-and-pages/
                                                    global $post;
                                                    $show_credit = get_post_meta( $post->ID, 'my_photo_credit', true );
                                                    $credit_url = get_post_meta( $post->ID, 'my_credit_url', true );
                                                    if( isset( $widget['show_credit'] ) && $this->check_and_return( $widget , 'show_credit' ) ){?>
                                                        <a href="<?php echo $credit_url; ?>" ><div class="meta-credit"><span class="meta-item"><?php echo __('Photos by: ', 'layerswp').$show_credit; ?></span></div></a>
                                                    <?php } // show custom meta ?>
                                                    
                                                <?php } // Don't show meta if we have chosen overlay ?>
                                                <?php if( isset( $widget['show_call_to_action'] ) && $this->check_and_return( $widget , 'call_to_action' ) ) { ?>
                                                    <a href="<?php the_permalink(); ?>" class="button"><?php echo $widget['call_to_action']; ?></a>
                                                <?php } // show call to action ?>
												
											</div>
										</div>
									<?php } // if show titles || show excerpt ?>
								</article>
							<?php }; // if list-list == liststyle ?>
						<?php }; // while have_posts ?>
					<?php }; // if have_posts ?>
				</div>
				<?php if( isset( $widget['show_pagination'] ) ) { ?>
					<div class="row products container list-grid">
						<?php layers_pagination( array( 'query' => $post_query ), 'div', 'pagination row span-12 text-center' ); ?>
					</div>
				<?php } ?>
			</section>

			<?php if( 'list-masonry' == $this->check_and_return( $widget , 'design', 'liststyle' ) ) { ?>
				<script>
					jQuery(function($){
						layers_masonry_settings[ '<?php echo $widget_id; ?>' ] = [{
								itemSelector: '.layers-masonry-column',
								gutter: <?php echo ( isset( $widget['design'][ 'gutter' ] ) ? 20 : 0 ); ?>
							}];
						$('#<?php echo $widget_id; ?>').find('.list-masonry').layers_masonry( layers_masonry_settings[ '<?php echo $widget_id; ?>' ][0] );
					});
				</script>
			<?php } // masonry trigger ?>

			<?php // Reset WP_Query
				wp_reset_postdata();
			?>
		<?php }
		
    } // Class
 
    // Register our widget
	// http://docs.layerswp.com/development-tutorials-layers-builder-widgets/#register-and-initialize
    register_widget('Layers_Demo_Widget'); 
}