<?php
/**
* Layers Demo Extension
*
* What your plugin does
*
* @package Layers
* @since Layers 1.2.4
*
* http://docs.layerswp.com/create-an-extension-setup-your-plugin-class/#setup-class
*/

class Layers_Demo_Extension {

	// Setup Instance
	// http://docs.layerswp.com/create-an-extension-setup-your-plugin-class/#get_instance
	private static $instance;
	
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
			self::$instance->__construct();
		}
		return self::$instance;
	}
	
	// Constructor
	// http://docs.layerswp.com/create-an-extension-setup-your-plugin-class/#constructor
	
	private function __construct() {
		
					// Internationalize the text strings used. 
					add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );
					
					// Add Template locations
					add_filter( 'layers_template_locations' , array( $this, 'add_template_locations' ) );
	
					// Register Customizer Panels, Sections and Controls
					add_filter( 'layers_customizer_panels' , array( $this, 'demo_customizer_panels' ), 90 );
					add_filter( 'layers_customizer_sections' , array( $this, 'demo_customizer_sections' ), 90 );
					add_filter( 'layers_customizer_controls' , array( $this, 'demo_customizer_controls' ), 90 );

			
					// Add Styles & Scripts
					add_filter( 'init' , array( $this , 'enqueue_scripts' ) );
					
					// Register custom widgets
					add_action( 'widgets_init' , array( $this, 'register_widgets' ), 50 );	
					
					// Run the version checker
					// http://docs.layerswp.com/plugin-guide-version-checking-activation-admin-notices/
					$this->layers_version_check();
			
					// Check for Layers as well as WooCommerce
					add_action( 'admin_notices', array( $this, 'activate_demo_extension_admin_notice' ) );
			
					if( FALSE !== $this->update_required || 'layerswp' != get_template() ) return;
			
					// Add Onboarding Page
					add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 60 );
					
					// Send the user to onboarding upon activation
					register_activation_hook( LAYERS_DEMO_EXTENSION_FILE , array( $this, 'activate' ) );
					add_action( 'init' , array( $this, 'demo_extension_onboard_redirect') );
					
					
			
	}
  	/**
	 * Check for Layers and Layers Version
	 * http://docs.layerswp.com/plugin-guide-version-checking-activation-admin-notices/
	 * @since  1.0
	 * @access public
	 */
		/**
		* Layers Min Version Checker
		*/
		public function layers_version_check(){
	
			$layers = wp_get_theme( 'layerswp' );
	
			if( version_compare( $layers->get( 'Version' ), LAYERS_REQUIRED_VERSION, '<' ) ){
				$this->update_required = LAYERS_REQUIRED_VERSION;
			} else {
				$this->update_required = FALSE;
			}
		}
	
		/**
		* Activate admin notice
		*/
		public function activate_demo_extension_admin_notice(){
			global $blog_id;
			$themes = wp_get_themes( $blog_id );
			if( 'layerswp' !== get_template() ){ ?>
				<div class="updated is-dismissible error">
					<p><?php _e( sprintf( "Layers is required to use the Demo Extension. <a href=\"%s\" target=\"_blank\">Click here</a> to get it.", ( isset( $themes['layerswp'] ) ? admin_url( 'themes.php?s=layerswp' ) : "http://www.layerswp.com" ) ), LAYERS_DEMO_EXTENSION_SLUG ); ?></p>
				</div>
			<?php } else if( FALSE !== $this->update_required ) { ?>
				<div class="updated is-dismissible error">
					<p><?php _e( sprintf( "Demo Extension requires Layers Version ". $this->update_required .". <a href=\"%s\" target=\"_blank\">Click here</a> to get the Layers Updater.", "http://www.layerswp.com/download/layers-updater" ), LAYERS_DEMO_EXTENSION_SLUG ); ?></p>
				</div>
		<?php }
		}

     /**
	 * Add an Admin Page
	 * http://docs.layerswp.com/how-to-add-help-pages-onboarding-to-layers-themes-or-extensions/
	 * @since  1.0
	 * @access public
	 */	
		/**
		* Add Sub Menu Page to the Layers Menu Item
		*/
		public function add_submenu_page(){
			add_submenu_page(
				'layers-dashboard',
				__( 'Demo Extension Help' , LAYERS_DEMO_EXTENSION_SLUG  ),
				__( 'Demo Extension Help' , LAYERS_DEMO_EXTENSION_SLUG  ),
				'edit_theme_options',
				'layers-demo-extension-get-started',
				array( $this, 'load_onboarding' )
			);
		}
	
		public function load_onboarding(){
			// Include Partials, we're using require so that inside the partial we can use $this to access the header and footer
			require LAYERS_DEMO_EXTENSION_DIR .'includes/onboarding.php';
		}
		/**
		* Set Activation Transient
		*/
		public function demo_extension_active(){
			set_transient( 'layers_demo_extension_activated', 1, 30 );
		}
	
		/**
		* Redirect Users to onboarding upon activation
		* http://docs.layerswp.com/how-to-add-help-pages-onboarding-to-layers-themes-or-extensions/#redirecting-on-activation
		*/
		public function demo_extension_onboard_redirect(){
			 // Only do this if the user can activate plugins
			if ( ! current_user_can( 'manage_options' ) )
				return;
	
			// Don't do anything if the transient isn't set
			if ( ! get_transient( 'layers_demo_extension_activated' ) )
				return;
	
			wp_redirect( admin_url( 'admin.php?page=layers-demo-extension-get-started' ) );
		}
	
	/**
	 * Loads the translation files.
	 * http://docs.layerswp.com/create-an-extension-setup-your-plugin-class/#localization
	 * @since  0.1
	 * @access public
	 * @return void
	 */

	public function i18n() {
		// Load the translation of the plugin. 
		load_plugin_textdomain( LAYERS_DEMO_EXTENSION_SLUG, FALSE, dirname( plugin_basename( __FILE__ ) ) . "/lang/" );
	}


	/* Enqueues scripts and styles on the front end.
	* http://docs.layerswp.com/create-an-extension-setup-your-plugin-class/#addingfunctions
	* @since  1.1
	* @access public
	* @return void
	*/


	public function enqueue_scripts(){
		
			wp_enqueue_style(
				LAYERS_DEMO_EXTENSION_SLUG . '-audio-admin-style',
				LAYERS_DEMO_EXTENSION_URI . 'assets/css/layers-demo-admin.css',
				false,
				LAYERS_DEMO_EXTENSION_VER,
				'all'
			); // Admin Styles
			
			wp_enqueue_script(
				LAYERS_DEMO_EXTENSION_SLUG . '-admin-meta',
				LAYERS_DEMO_EXTENSION_URI . 'assets/js/layers-meta.js',
				array('jquery'),
				LAYERS_DEMO_EXTENSION_VER
			); // Admin Styles
	}

	//  Setup Template Locations
	// http://docs.layerswp.com/create-an-extension-adding-custom-post-types-page-templates/#custom-page-templates


	public function add_template_locations( $template_locations ){

		$template_locations[] = LAYERS_DEMO_EXTENSION_DIR . 'templates';

		return $template_locations;
	}	
	
	// Register Widgets
	// Not the same as register_widget()! 
	
	function register_widgets(){
		
	   require_once( LAYERS_DEMO_EXTENSION_DIR. 'widgets/layers-demo-widget.php' );
	}
	
	/**
	* Layers Demo Customizer Options
	* http://docs.layerswp.com/create-an-extension-adding-customizer-controls/
	**/
	
		
	/* Create Custom Customizer Panel
	* http://docs.layerswp.com/reference/layers_customizer_panels/
	* @since  1.1
	* @return $panels
	*/
	
	public function demo_customizer_panels( $panels ){
		$panels['layers-extension-demo'] = array(
				'title' =>__( 'Demo Options' , LAYERS_DEMO_EXTENSION_SLUG ),
				'priority' => 100
					

		);
		return $panels;
	}
	
	/* Create Custom Customizer Section
	* http://docs.layerswp.com/reference/layers_customizer_sections/
	* @since  1.1
	* @return $sections
	*/
	
	public function demo_customizer_sections( $sections ){
	
		$sections[ 'layers-demo-options' ] = array(
				'title' =>__( 'Demo Options' , LAYERS_DEMO_EXTENSION_SLUG ),
				'panel' => 'layers-extension-demo'
		);
	
		return $sections;
	}
	
	
	/* Create Custom Customizer Controls
	* http://docs.layerswp.com/reference/layers_customizer_controls/
	* @since  1.1
	* @return $controls
	*/
	
	public function demo_customizer_controls( $controls ){
		$controls[ 'layers-demo-options' ] = array(
			'demo-controls-heading' => array(
				'type'  => 'layers-heading',
				'description' => __( 'These options allow you to change stuff.' , LAYERS_DEMO_EXTENSION_SLUG ),
			),
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
	
} // END Class
