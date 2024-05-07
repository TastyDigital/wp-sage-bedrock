<?php
	/**
	 * Tasty Contact Fields
	 *
	 * @package   Tasty_Contact_Fields
	 * @license   GPL-2.0+
	 *
	 * @wordpress-plugin
	 * Plugin Name: Tasty Contact Fields
	 * Plugin URI:  https://github.com/TastyDigital/tasty-contact-fields
	 * Description: Adds an options page for company contact details and location map
	 * Version:     1.0.0
	 * Author:      Tasty Digital
	 * Author URI:  https://tastydigital.com
	 * Text Domain: td-contact-fields
     * Requires Plugins: cmb2, cmb_field_map
	 */


	function td_contact_fields_activate( $network_wide ) {

        $missing_dependencies = array();
		if(!class_exists('CMB2')){
			$missing_dependencies[] = 'CMB2';
		}
		if(!class_exists('PW_CMB2_Field_Google_Maps')){
            $missing_dependencies[] = 'cmb_field_map';
        }

		if ( count($missing_dependencies) > 0 ) {
            $notice = __('Please install plugin dependencies ('. implode(', ', $missing_dependencies).') before activating the Tasty Contact Fields plugin', 'td-contact-fields');
			echo '<h3>'.$notice.'</h3>';

			//Adding @ before will prevent XDebug output
			@trigger_error($notice, E_USER_ERROR);
		}
	}

	register_activation_hook(__FILE__, 'td_contact_fields_activate');

/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class TD_Contact_Admin {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'contact_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'contact_options_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Contact details', 'td-contact-fields' );
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ), 'dashicons-location', 59 );

		// Include CMB CSS in the head to avoid FOUT
		//add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {

		$saved_options = get_option('contact_options') ? get_option('contact_options') : '';
		
		$cmb_options = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'title'   => __( 'Company contact details', 'td-contact-fields' ),
			'hookup'  => false, // Do not need the normal user/post hookup
			'show_on' => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );
	
		/**
		 * Options fields ids only need
		 * to be unique within this option group.
		 * Prefix is not needed.
		 */
		$cmb_options->add_field( array(
			'name'    => __( 'Display name', 'td-contact-fields' ),
			'desc'    => __( 'Company name as it appears in website footer', 'td-contact-fields' ),
			'id'      => 'display-name',
			'type'    => 'text',
		) );

		
		$cmb_options->add_field( array(
			'name'    => __( 'Street address', 'td-contact-fields' ),
			'desc'    => __( 'address appears in website footer', 'td-contact-fields' ),
			'id'      => 'street-address',
			'type'    => 'text',
		) );
		$cmb_options->add_field( array(
			'name'    => __( 'Street address 2', 'td-contact-fields' ),
			'id'      => 'street-address-2',
			'type'    => 'text',
		) );
		$cmb_options->add_field( array(
			'name'    => __( 'City', 'td-contact-fields' ),
			'desc'    => __( 'City or locality', 'td-contact-fields' ),
			'id'      => 'locality',
			'type'    => 'text',
		) );
		$cmb_options->add_field( array(
			'name'    => __( 'Postcode', 'td-contact-fields' ),
			'id'      => 'postal-code',
			'type'    => 'text',
		) );
		$cmb_options->add_field( array(
			'name'    => __( 'Country', 'td-contact-fields' ),
			'desc'    => __( 'Country or nation', 'td-contact-fields' ),
			'id'      => 'country-name',
			'type'    => 'text',
			'default' => 'United Kingdom',
		) );
		$cmb_options->add_field( array(
			'name'    => __( 'Telephone contact', 'td-contact-fields' ),
			'id'      => 'contact-telephone',
			'type'    => 'text',
		) );
		$cmb_options->add_field( array(
			'name'    => __( 'Fax contact', 'td-contact-fields' ),
			'id'      => 'contact-fax',
			'type'    => 'text',
		) );
		$cmb_options->add_field( array(
			'name'    => __( 'Contact email', 'td-contact-fields' ),
			'id'      => 'contact-email',
			'type' => 'text_email',
		) );
		if( isset($saved_options['google-api']) ){
			$cmb_options->add_field( array(
				'name'    => __( 'Location', 'td-contact-fields' ),
				'desc' => 'Drag the marker to set the exact location',
				'id' => 'location',
				'type' => 'pw_map',
				// 'split_values' => true, // Save latitude and longitude as two separate fields
			) );
		}
		$cmb_options->add_field( array(
			'name'    => __( 'Google API Key', 'td-contact-fields' ),
			'desc' => 'You need a <a href="https://developers.google.com/maps/documentation/javascript/get-api-key#step-1-get-an-api-key-from-the-google-api-console" target="_blank">Google API key</a> to use the location map feature',
			'id' => 'google-api',
			'type' => 'text'
		) );
		
	
	
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the TD_Contact_Admin object
 * @since  0.1.0
 * @return TD_Contact_Admin object
 */
function TD_Contact_admin() {
	static $object = null;
	if ( is_null( $object ) ) {
		$object = new TD_Contact_Admin();
		$object->hooks();
	}

	return $object;
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function TD_Contact_get_option( $key = '' ) {
	return cmb2_get_option( TD_Contact_admin()->key, $key );
}

// obfuscate
function encode2($str) {
    $str = mb_convert_encoding($str , 'UTF-32', 'UTF-8');
    $t = unpack("N*", $str);
    $t = array_map(function($n) { return "&#$n;"; }, $t);
    return implode("", $t);
}

function return_address($atts) {
	
/*
	
	$fname = get_option('blogname');
	
	$url = get_option('siteurl');
	
*/
	// these are not obligitory:
	$street = get_option('contact_options') ? get_option('contact_options') : '';
	
		
	$address = '<div class="address">';
	
	$address .= '<div class="vcard">';
	//$address .= '<h5 class="org fn n">'.$fname.'</h5>';
	
	$title = sprintf( '<a href="%s">%s</a>', trailingslashit( home_url() ), get_bloginfo( 'name' ) );
	
	$address .= tasty_write_logotype( $title );
	$address .= '<p class="adr">';

	if( isset($street['display-name']) ){
		$address .= '<span class="company-name org fn n">'.$street['display-name'].'</span>';
	}

	if( isset($street['street-address']) ){
		$address .= '<span class="street-address">'.$street['street-address'].'</span> ';
	}
	if( isset($street['street-address-2']) ){
		$address .= '<span class="street-address-2">'.$street['street-address-2'].'</span> ';
	}
	if( isset($street['locality']) ){
		$address .= '<span class="locality">'.$street['locality'].'</span> ';
	}
	if( isset($street['postal-code']) ){
		$address .= '<span class="postal-code">'.$street['postal-code'].'</span> ';
	}
	if( isset($street['country-name']) ){
		$address .= '<span class="country-name">'.$street['country-name'].'</span> ';
	}
	$address .= '</p>';
	
	$address .= '<p class="instant-contact">';

	if( isset($street['contact-telephone']) ){
		$address .= '<span class="contact-telephone">t <a href="tel:'.str_replace(' ', '',$street['contact-telephone']).'" class="tel">'.$street['contact-telephone'].'</a></span>';
	}
	if( isset($street['contact-fax']) ){
		$address .= '<span class="contact-fax">f <a href="tel:'.str_replace(' ', '',$street['contact-fax']).'" class="fax">'.$street['contact-fax'].'</a></span>';
	}
	if( isset($street['contact-email']) ){
		//$address .= '<div class="instant-contact">Email: <a class="email" href="mailto:'.$street['contact-email'].'">'.$street['contact-email'].'</a></div>';
		$address .= '<span class="contact-email">e <a class="email" href="mailto:'.encode2($street['contact-email']).'">'.encode2($street['contact-email']).'</a></span>';
	}
	
	
	$address .= '</p>';
	$address .= '</div>
				</div>';
				
				
	return $address;
}
function insert_address(){
	echo return_address();
}
	add_shortcode( 'company_contact', 'return_address' );
	// Get it started
	TD_Contact_admin();