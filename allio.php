<?php
/**
 * Plugin Name: Allio
 * Plugin URI: https://Allio.io
 * Description: Artificial intelligent chatbot for WordPress powered by Dialogflow 
 * Author: Allio.io
 * Author URI: https://Allio.io
 * Version: 1.0.7
 * Text Domain: Allio
 * Domain Path: languages
 *
 * @package     Allio
 * @author 		Allio.io
 * @version		1.0.7
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Allio' ) ) :

/**
 * Main Allio Class.
 *
 * @since 1.0
 */
final class Allio {

	/**
	 * ALLIOC API Object.
	 *
	 * @var object|EDD_API
	 * @since 1.5
	 */
	public $api;

	/** Singleton *************************************************************/

	/**
	 * @var Allio The one true Allio
	 * @since 1.4
	 */
	private static $instance;

	/**
	 * Used to identify multiple chatbots on the same page...
	 */
	public static $sequence = 0;


	/**
	 * Main Allio Instance.
	 *
	 * Insures that only one instance of Allio exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 0.1
	 * @static
	 * @staticvar array $instance
	 * @uses Allio::setup_constants() Setup the constants needed.
	 * @uses Allio::includes() Include the required files.
	 * @uses Allio::load_textdomain() load the language files.
	 * @see ALLIOC()
	 * @return object|Allio The one true Allio
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Allio ) ) {

			self::$instance = new Allio;
			self::$instance->setup_session();
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			$include = self::$instance->includes();
			if ($include)
				self::$instance->api = new ALLIOC_API();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.6
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'Allio' ), '1.6' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 1.6
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'Allio' ), '1.6' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.4
	 * @return void
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'ALLIOC_VERSION' ) ) {
			define( 'ALLIOC_VERSION', '1.0' );
		}

		// Plugin slug.
		if ( ! defined( 'ALLIOC_SLUG' ) ) {
			define( 'ALLIOC_SLUG', 'Allio' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'ALLIOC_PLUGIN_DIR' ) ) {
			define( 'ALLIOC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'ALLIOC_PLUGIN_URL' ) ) {
			define( 'ALLIOC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'ALLIOC_PLUGIN_FILE' ) ) {
			define( 'ALLIOC_PLUGIN_FILE', __FILE__ );
		}
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.4
	 * @return void
	 */
	private function includes() {
		global $allioc_options;

		require_once ALLIOC_PLUGIN_DIR . 'includes/admin/settings/register-settings.php';
		$allioc_options = allioc_get_settings();
		
		if (allioc_include_all()) {
			require_once ALLIOC_PLUGIN_DIR . 'includes/actions.php';
			if( file_exists( ALLIOC_PLUGIN_DIR . 'includes/deprecated-functions.php' ) ) {
				require_once ALLIOC_PLUGIN_DIR . 'includes/deprecated-functions.php';
			}

			require_once ALLIOC_PLUGIN_DIR . 'includes/ajax-functions.php';
			require_once ALLIOC_PLUGIN_DIR . 'includes/api/class-allioc-api.php';
			require_once ALLIOC_PLUGIN_DIR . 'includes/template-functions.php';
			require_once ALLIOC_PLUGIN_DIR . 'includes/widgets.php';
			require_once ALLIOC_PLUGIN_DIR . 'includes/misc-functions.php';
			require_once ALLIOC_PLUGIN_DIR . 'includes/shortcodes.php';
			require_once ALLIOC_PLUGIN_DIR . 'includes/scripts.php';
			require_once ALLIOC_PLUGIN_DIR . 'includes/post-meta-box.php';

			if ( is_admin() ) {
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/admin-actions.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/admin-pages.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/settings/display-settings.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/upgrades/upgrade-functions.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/welcome.php';

			}

			require_once ALLIOC_PLUGIN_DIR . 'includes/install.php';
		} else {
			if ( is_admin() ) {
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/admin-actions.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/admin-pages.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/settings/display-settings.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/upgrades/upgrade-functions.php';
				require_once ALLIOC_PLUGIN_DIR . 'includes/admin/welcome.php';

			}
			
			return false;
		}
		return true;
	}

	/**
	 * Loads the plugin language files.
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function load_textdomain() {
		global $wp_version;

		// Set filter for plugin's languages directory.
		$allioc_lang_dir  = dirname( plugin_basename( ALLIOC_PLUGIN_FILE ) ) . '/languages/';
		$allioc_lang_dir  = apply_filters( 'allioc_languages_directory', $allioc_lang_dir );

		// Traditional WordPress plugin locale filter.

		$get_locale = get_locale();

		if ( $wp_version >= 4.7 ) {

			$get_locale = get_user_locale();
		}

		/**
		 * Defines the plugin language locale used in AffiliateWP.
		 *
		 * @var $get_locale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
		 *                  otherwise uses `get_locale()`.
		 */
		$locale        = apply_filters( 'plugin_locale',  $get_locale, 'Allio' );
		$mofile        = sprintf( '%1$s-%2$s.mo', 'Allio', $locale );

		// Look for wp-content/languages/allioc/Allio-{lang}_{country}.mo
		$mofile_global1 = WP_LANG_DIR . '/allioc/Allio-' . $locale . '.mo';

		// Look for wp-content/languages/allioc/allioc-{lang}_{country}.mo
		$mofile_global2 = WP_LANG_DIR . '/allioc/allioc-' . $locale . '.mo';

		// Look in wp-content/languages/plugins/Allio
		$mofile_global3 = WP_LANG_DIR . '/plugins/Allio/' . $mofile;

		if ( file_exists( $mofile_global1 ) ) {

			load_textdomain( 'Allio', $mofile_global1 );

		} elseif ( file_exists( $mofile_global2 ) ) {

			load_textdomain( 'Allio', $mofile_global2 );

		} elseif ( file_exists( $mofile_global3 ) ) {

			load_textdomain( 'Allio', $mofile_global3 );

		} else {

			// Load the default language files.
			load_plugin_textdomain( 'Allio', false, $allioc_lang_dir );
		}

	}

	/**
	 * Ensures ALLIOC session cookie exists
	 */
	public function setup_session() {
		if ( ! ( isset( $_COOKIE['allioc_session_id'] ) && strlen( $_COOKIE['allioc_session_id'] ) > 0 ) ) {
			$session_id = md5( uniqid( 'allioc-' ) );
			setcookie( 'allioc_session_id', $session_id, time() + ( 86400 * 30 ), '/' ); // 86400 = 1 day
		}
	}

}

endif; // End if class_exists check.

/**
 * Checks whether function is disabled.
 *
 * @param string  $function Name of the function.
 * @return bool Whether or not function is disabled.
 */
function allioc_is_func_disabled( $function ) {
	$disabled = explode( ',',  ini_get( 'disable_functions' ) );

	return in_array( $function, $disabled );
}


/**
 * The main function for that returns Allio
 *
 * The main function responsible for returning the one true Allio
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $allioc = ALLIOC(); ?>
 *
 * @since 1.4
* @return object|Allio The one true Allio Instance.
 */
function ALLIOC() {
	return Allio::instance();
}

// Get ALLIOC Running.
ALLIOC();

register_activation_hook( __FILE__, 'allioc_activator' );

function allioc_activator() {
	$page_title = "Allioc Chat Bot";
	$page_content = "[allio_chatbot]";
	$chatbot_page = array(
	    'post_type' => 'page',
	    'post_title' => $page_title,
	    'post_content' => $page_content,
	    'post_status' => 'publish',
	    'post_author' => 1,
	    'post_slug' => 'allio_chatbot'
	);
	
	$page_check = get_page_by_title($page_title);
	if(!isset($page_check->ID)){
       wp_insert_post($chatbot_page);
	}
	
}