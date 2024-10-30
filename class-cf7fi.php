<?php
/**
 * Plugin Name.
 *
 * @package   PluginName
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class.
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package PluginName
 * @author  Your Name <email@example.com>
 */
class CF7FI {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'plugin-name';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	
	protected static $conversion_code_pre = '<img src=https://f.formisimo.com/conv/';
	
	protected static $conversion_code_after = '>';
	
	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		add_action('wpcf7_admin_after_general_settings', array($this, 'add_meta_box'));
		add_action('wpcf7_after_save', array($this, 'save_settings'));
		
		
		add_action( 'wp_footer' , array( $this , 'add_tracking_code' ));
		add_action( 'wpcf7_before_send_mail' , array( $this, 'wpcf7_before_action' ));

	}

	/**
	* Save Methode
	* just filtered input will save in postmeta
	*
	*/
	
	public function save_settings($value) {

		$conversionCode = $_POST['cf7fi-conversioncode'];
	
		preg_match('/^\<img src=https:\/\/f.formisimo.com\/conv\/([0-9]*)\>/', $conversionCode, $hit);
		if(!empty($hit)){
		
			update_post_meta((int)$_POST['post_ID'],'_cf7fi-conversioncode',$hit[1]);
		}

	}

	/**
	* Add Metabox for advance Contact 7 Form
	* 
	*/
	
	public function add_meta_box($post_id) {
		if (! current_user_can('publish_pages'))
			return;
		
		add_meta_box(
			'cf7fi',
			__('Formisimo Integration'),
			 array( __CLASS__, 'cf7fi_metabox' ),
			'cfseven',
			'advanced',
			'core'
		);

		do_meta_boxes('cfseven', 'advanced', array());
	}
	
	/**
	* Metabox for advance Contact 7 Form
	* 
	* Shows the active Conversion Tracking Code
	*
	*/
	
	public function cf7fi_metabox() {
		
		$conversionCode = get_post_meta((int)$_GET['post'],'_cf7fi-conversioncode',true);
				
		if($conversionCode != '') {
			$conversionCode = self::$conversion_code_pre.$conversionCode.self::$conversion_code_after;
		}
		?>

			<form action="">
				<input size="50" name="cf7fi-conversioncode" type="text" value="<?php echo $conversionCode; ?>" placeholder="insert your conversion code here" />
			</form>
		
		<?php

	}
	
	/**
	* Hooks into the Contact 7 Form and override the on_send_ok Method
	* 
	*/
	
	public function wpcf7_before_action($cfdata) {
		
		$conversionCode = get_post_meta((int)$cfdata->id,'_cf7fi-conversioncode',true);
			
		$cfdata->additional_settings = 'on_sent_ok:  "$(\'body\').prepend(\''.self::$conversion_code_pre.$conversionCode.self::$conversion_code_after.'\');"';
		
		return $cfdata;

	}

	/**
	* add the TrackingCode into wp_footer
	* 
	*/
	
	public function add_tracking_code(){

		echo stripslashes(get_option('_cf7fi_script',''));
	
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_plugins_page(
			__( 'Contact Form 7 Formisimo Integation', $this->plugin_slug ),
			__( 'cf7fi Options', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_cf7fi_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_cf7fi_admin_page() {
		include_once( 'views/admin.php' );
	}

}