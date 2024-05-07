<?php
	/**
	 * Tasty Starter Post Types
	 *
	 * @package   starterPostTypes
	 * @license   GPL-2.0+
	 *
	 * @wordpress-plugin
	 * Plugin Name: Tasty Starter Post Types
	 * Plugin URI:  https://www.starter.io
	 * Description: Adds case studies post type and metaboxes
	 * Version:     1.0.0
	 * Author:      Tasty Digital
	 * Author URI:  https://tastydigital.com
	 * Text Domain: starter-cpts
	 * Requires Plugins: advanced-custom-fields-pro
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	if (!function_exists('write_log')) {

		function write_log($log) {
			if (true === WP_DEBUG) {
				if (is_array($log) || is_object($log)) {
					error_log(print_r($log, true));
				} else {
					error_log($log);
				}
			}
		}

	}

	// Start up the engine
	class Tasty_Starter_PostTypes
	{
		/**
		 * Static property to hold our singleton instance
		 *
		 */
		static $instance = false;

		/**
		 * This is our constructor
		 *
		 * @return void
		 */
		private function __construct() {


			register_activation_hook(__FILE__, array( $this, 'starter_acf_activate') );
			add_filter( 'acf/settings/save_json', array( $this, 'starter_acf_json_save_point') );
			//add_filter( 'acf/settings/save_json/key=post_type_65dc997f8f789', 'starter_acf_json_save_point' );
			add_filter( 'acf/settings/load_json', array( $this, 'starter_acf_json_load_point') );
			add_action( 'wp_loaded',array( $this, 'starter_hide_acf_admin' )  );
			// back end
			add_action		( 'plugins_loaded', array( $this, 'text_domain' ) );
//			add_action		( 'admin_enqueue_scripts',				array( $this, 'admin_scripts'			)			);
//			add_action		( 'do_meta_boxes',						array( $this, 'create_metaboxes'		),	10,	2	);
//			add_action		( 'save_post',							array( $this, 'save_custom_meta'		),	1		);
//
//			// front end
//			add_action		( 'wp_enqueue_scripts',					array( $this, 'front_scripts'			),	10		);
//			add_filter		( 'comment_form_defaults',				array( $this, 'custom_notes_filter'		) 			);

			//add_action( 'init', array( $this, 'starter_add_template_to_case_study') );
			// add_action( 'init', array( $this, 'starter_register_cpt_block_patterns' ));
		}

		/**
		 * If an instance exists, this returns it.  If not, it creates one and
		 * retuns it.
		 *
		 * @return Tasty_Starter_PostTypes
		 */

		public static function getInstance() {
			if ( !self::$instance )
				self::$instance = new self;
			return self::$instance;
		}

		/**
		 * load textdomain
		 *
		 * @return void
		 */
		public function text_domain(): void {
			load_plugin_textdomain( 'starter-cpts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}


		public function starter_acf_activate( $network_wide ): void {

			$missing_dependencies = array();
			if (!class_exists('ACF')) {
				$missing_dependencies[] = 'Advanced Custom Fields';
			}

			if ( count($missing_dependencies) > 0 ) {
				$notice = __('Please install plugin dependencies ('. implode(', ', $missing_dependencies).') before activating the starter Post Types plugin', 'starter-cpts');
				echo '<h3>'.$notice.'</h3>';

				//Adding @ before will prevent XDebug output
				@trigger_error($notice, E_USER_ERROR);
			}
		}

		public function starter_acf_json_save_point( $path ): string {
			//write_log(__DIR__ . '/acf-json');
			return __DIR__ . '/acf-json';
		}



		function starter_acf_json_load_point( $paths ) {
			// Remove the original path (optional).
			unset($paths[0]);
			// Append the new path and return it.
			$paths[] = __DIR__ . '/acf-json';

			return $paths;
		}

		public function starter_hide_acf_admin() {
			//add_filter('acf/settings/show_admin', '__return_false');
			switch ( wp_get_environment_type() ) {
				case 'local':
				case 'development':
					// do_nothing();
					break;

				case 'staging':
				case 'production':
				default:
					// Hide the ACF admin menu item.
					add_filter('acf/settings/show_admin', '__return_false');
					break;
			}

		}

		public function starter_add_template_to_case_study() {
			$post_type_object = get_post_type_object( 'case-study' );
			$post_type_object->template = array(
				array( 'core/paragraph', array(
					'placeholder' => 'Add a root-level paragraph',
				) ),
				array( 'core/columns', array(
					"verticalAlignment" => "top",
					"isStackedOnMobile" => true
				), array(
					array( 'core/column', array(
						"width" => '66.666%'
					), array(
						array( 'core/paragraph', array(
							'placeholder' => 'Add a inner paragraph'
						) ),
					) ),
					array( 'core/column', array(
						"width" => '33.333%'
					), array(
						array( 'core/image', array() ),
					) ),
				) )
			);
			//$post_type_object->template_lock = 'all';
		}

		public function starter_register_cpt_block_patterns() {
		// Load block pattern templates

			// Register block patterns

			register_block_pattern(
				'starter-cpts/service-block-template',
				array(
					'title'       => __( 'Service Block Template', 'starter-cpts' ),
					'categories'  => array( 'services' ),
					'content'     => $this->replace_uris( file_get_contents( plugin_dir_path( __FILE__ ) . 'patterns/service-block-template.html' ) ),
					'inserter'	=> false,
				)
			);

			register_block_pattern(
				'starter-cpts/case-study-block-template',
				array(
					'title'       => __( 'Case Study Block Template', 'starter-cpts' ),
					'categories'  => array( 'case-studies' ),
					'content'     => $this->replace_uris( file_get_contents( plugin_dir_path( __FILE__ ) . 'patterns/case-study-block-template.html' ) ),
					'inserter'	=> false,
				)
			);

			register_block_pattern(
				'starter-cpts/industry-block-template',
				array(
					'title'       => __( 'Industry Block Template', 'starter-cpts' ),
					'categories'  => array( 'industries' ),
					'content'     => $this->replace_uris( file_get_contents( plugin_dir_path( __FILE__ ) . 'patterns/industry-block-template.html' ) ),
					'inserter'	=> false,
				)
			);

			$services_post_type_object = get_post_type_object( 'service' );
			$services_post_type_object->template = array(
				array( 'core/pattern', array(
					'slug' => 'starter-cpts/service-block-template',
				) )
			);
			//$services_post_type_object->template_lock = 'all';

			$cs_post_type_object = get_post_type_object( 'case-study' );
			$cs_post_type_object->template = array(
				array( 'core/pattern', array(
					'slug' => 'starter-cpts/case-study-block-template',
				) )
			);
			//$cs_post_type_object->template_lock = 'all';

			$ind_post_type_object = get_post_type_object( 'industry' );
			$ind_post_type_object->template = array(
				array( 'core/pattern', array(
					'slug' => 'starter-cpts/industry-block-template',
				) )
			);
			//$ind_post_type_object->template_lock = 'all';
	}

		function replace_uris( $markup ) {
			$markup = str_replace( '{{uploads_uri}}', wp_get_upload_dir()['baseurl'], $markup );
			$markup = str_replace( '{{site_uri}}', site_url(), $markup );
			$markup = str_replace( '{{theme_uri}}', get_stylesheet_directory_uri(), $markup );
			$markup = str_replace( '{{plugin_uri}}', untrailingslashit(plugin_dir_url(__FILE__ )), $markup );
			return $markup;
		}


	}

// Instantiate our class
$starter_Systems_PostTypes = Tasty_Starter_PostTypes::getInstance();