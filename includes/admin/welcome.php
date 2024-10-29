<?php
/**
 * Weclome Page Class
*
* @package     ALLIOC
* @subpackage  Admin/Welcome
* @copyright   Copyright (c) 2017, Daniel Powney
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       0.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * ALLIOC_Welcome Class
 *
 * A general class for About and Credits page.
 *
 * @since 0.1
 */
class ALLIOC_Welcome {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';

	/**
	 * Get things started
	 *
	 * @since 0.1
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ), 11 );
	}

	/**
	 * Register the Dashboard Pages which are later hidden but these pages
	 * are used to render the Welcome and Credits pages.
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function admin_menus() {

		// Changelog Page
		add_dashboard_page(
				__( 'Allio Changelog', 'Allio' ),
				__( 'Allio Changelog', 'Allio' ),
				$this->minimum_capability,
				'allioc-changelog',
				array( $this, 'changelog_screen' )
		);

		// Getting Started Page
		add_dashboard_page(
				__( 'Getting started with Allio', 'Allio' ),
				__( 'Getting started with Allio', 'Allio' ),
				$this->minimum_capability,
				'allioc-getting-started',
				array( $this, 'getting_started_screen' )
		);

		// Credits Page
		add_dashboard_page(
				__( 'The people that build Allio', 'Allio' ),
				__( 'The people that build Allio', 'Allio' ),
				$this->minimum_capability,
				'allioc-credits',
				array( $this, 'credits_screen' )
		);

		// Now remove them from the menus so plugins that allow customizing the admin menu don't show them
		remove_submenu_page( 'index.php', 'allioc-changelog' );
		remove_submenu_page( 'index.php', 'allioc-getting-started' );
		remove_submenu_page( 'index.php', 'allioc-credits' );
	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function admin_head() {
		?>
		<style type="text/css" media="screen">
			/*<![CDATA[*/
			.allioc-about-wrap .allioc-badge { float: right; border-radius: 4px; margin: 0 0 15px 15px; max-width: 100px; }
			.allioc-about-wrap #allioc-header { margin-bottom: 15px; }
			.allioc-about-wrap #allioc-header h1 { margin-bottom: 15px !important; }
			.allioc-about-wrap .about-text { margin: 0 0 15px; max-width: 670px; }
			.allioc-about-wrap .feature-section { margin-top: 20px; }
			.allioc-about-wrap .feature-section-content,
			.allioc-about-wrap .feature-section-media { width: 50%; box-sizing: border-box; }
			.allioc-about-wrap .feature-section-content { float: left; padding-right: 50px; }
			.allioc-about-wrap .feature-section-content h4 { margin: 0 0 1em; }
			.allioc-about-wrap .feature-section-media { float: right; text-align: right; margin-bottom: 20px; }
			.allioc-about-wrap .feature-section-media img { border: 1px solid #ddd; }
			.allioc-about-wrap .feature-section:not(.under-the-hood) .col { margin-top: 0; }
			/* responsive */
			@media all and ( max-width: 782px ) {
				.allioc-about-wrap .feature-section-content,
				.allioc-about-wrap .feature-section-media { float: none; padding-right: 0; width: 100%; text-align: left; }
				.allioc-about-wrap .feature-section-media img { float: none; margin: 0 0 20px; }
			}
			/*]]>*/
		</style>
		<?php
	}

	/**
	 * Welcome message
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function welcome_message() {
		list( $display_version ) = explode( '-', ALLIOC_VERSION );
		?>
		<div id="allioc-header">

			<h1><?php printf( __( 'Welcome to Allio v%s', 'Allio' ), $display_version ); ?></h1>
			<p class="about-text">
				<?php _e( 'Chatbot for businesses powered by Allio', 'Allio' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Navigation tabs
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function tabs() {
		$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'allioc-about';
		?>
		<h1 class="nav-tab-wrapper">
			<a class="nav-tab <?php echo $selected == 'allioc-getting-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'allioc-getting-started' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Getting Started', 'Allio' ); ?>
			</a>			
		</h1>
		<?php
	}

	/**
	 * Render Changelog Screen
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function changelog_screen() {
		?>
		<div class="wrap about-wrap allioc-about-wrap">
			<?php
				// load welcome message and content tabs
				$this->welcome_message();
				$this->tabs();
			?>
			<div class="changelog">
				<div class="feature-section">
					<?php echo $this->parse_readme(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Getting Started Screen
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function getting_started_screen() {
		?>
		<div class="wrap about-wrap allioc-about-wrap">
			<?php
				// load welcome message and content tabs
				$this->welcome_message();
				$this->tabs();
			?>

			<div class="changelog">
				<div class="feature-section">
					<div class="feature-section-media">
						<img src="<?php echo ALLIOC_PLUGIN_URL . 'assets/images/screenshots/screenshots1.png'; ?>" class="allioc-welcome-screenshots"/>
					</div>
					<div class="feature-section-content">
						<h4><?php _e( 'Installation & Setup', 'Allio' ); ?></h4>
						<ol>
							<li><?php _e( 'Install and activate the plugin.', 'Allio' ); ?></li>
							<li><?php _e( 'Contact sales@allio.io to obtain a license key and an engine token. Install the plugin and activate it.  Go to Allio Settings menu, enter the license key, engine token (dialogflow) and logo.   Save Add the [allio_chatbot] shortcode inside the contents of a page  View your page and engage in conversation with your chatbot. ', 'Allio' ); ?></li>							
						</ol>

						<h4><?php _e( '[allio_chatbot] Shortcode', 'Allio' );?></h4>					
						<p><?php _e( 'Please contact  <a href="https://sales@allio.io">Allio</a>', 'Allio' ); ?></p>
						
					</div>
				</div>
			</div>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'Allio' ), 'options-general.php' ) ) ); ?>"><?php _e( 'Go to Options', 'Allio' ); ?></a> &middot;
			</div>

		</div>
		<?php
	}

	/**
	 * Render Credits Screen
	 *
	 * @access public
	 * @since 0.1
	 * @return void
	 */
	public function credits_screen() {
		?>
		<div class="wrap about-wrap allioc-about-wrap">
			<?php
			// load welcome message and content tabs
			$this->welcome_message();
			$this->tabs();
			?><br /><?php
			echo $this->contributors();
			?>
		</div>
		<?php
	}


	/**
	 * Parse the ALLIOC readme.txt file
	 *
	 * @since 0.1
	 * @return string $readme HTML formatted readme file
	 */
	public function parse_readme() {
		$file = file_exists( ALLIOC_PLUGIN_DIR . 'readme.txt' ) ? ALLIOC_PLUGIN_DIR . 'readme.txt' : null;

		if ( ! $file ) {
			$readme = '<p>' . __( 'No valid changelog was found.', 'Allio' ) . '</p>';
		} else {
			$readme = file_get_contents( $file );
			$readme = nl2br( esc_html( $readme ) );
			$readme = explode( '== Changelog ==', $readme );
			$readme = end( $readme );

			$readme = preg_replace( '/`(.*?)`/', '<code>\\1</code>', $readme );
			$readme = preg_replace( '/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $readme );
			$readme = preg_replace( '/[\040]\*(.*?)\*/', ' <em>\\1</em>', $readme );
			$readme = preg_replace( '/= (.*?) =/', '<h4>\\1</h4>', $readme );
			$readme = preg_replace( '/\[(.*?)\]\((.*?)\)/', '<a href="\\2">\\1</a>', $readme );
		}

		return $readme;
	}

	/**
	 * Sends user to the Welcome page on first activation of ALLIOC as well as each
	 * time ALLIOC is upgraded to a new version
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function welcome() {
		// Bail if no activation redirect
		if ( ! get_transient( '_allioc_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_allioc_activation_redirect' );

		// Bail if activating from network, or bulk
		// if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
		//	return;

		//$upgrade = get_option( 'allioc_version_upgraded_from' );

		//if( ! $upgrade ) { // First time install
			wp_safe_redirect( admin_url( 'index.php?page=allioc-getting-started' ) ); exit;
		//} else { // Update
		//	wp_safe_redirect( admin_url( 'index.php?page=allioc-about' ) ); exit;
		//}
	}
}
new ALLIOC_Welcome();
