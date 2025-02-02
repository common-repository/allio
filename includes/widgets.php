<?php
/**
 * Widgets
*
* Widgets related funtions and widget registration.
*
* @package     ALLIOC
* @subpackage  Widgets
* @copyright   Copyright (c) 2017, Daniel Powney
* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
* @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Allio Widget
 */
class ALLIOC_Chatbot_Widget extends WP_Widget {

	/**
	 * Constructor
	 */

	function __construct( ) {

		$id_base = 'allioc_chatbot_widget';
		$name = __( 'Chatbot', 'Allio' );
		$widget_opts = array(
				'classname' => 'allioc-chatbot-widget',
				'description' => __( 'Adds a chatbot powered by Dialogflow.', 'Allio' )
		);
		$control_ops = array( 'width' => 400, 'height' => 350 );

		parent::__construct( $id_base, $name, $widget_opts, $control_ops );
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_Widget::widget()
	 */
	function widget( $args, $instance ) {

		extract( $args );

		$general_settings = (array) get_option( 'allioc_general_settings' );
		$debug = isset( $general_settings['debug'] ) ? $general_settings['debug'] : false;

		$header = isset( $instance['header'] ) ? $instance['header'] : 'h3';
		$title = apply_filters( 'widget_title', ! isset( $instance['title'] ) ? __( 'Chatbot', 'Allio' ) : $instance['title'], $instance, $this->id_base );

		$before_title = '<' . $header . ' class="widget-title">';
		$after_title = '</' . $header . '>';

		echo $before_widget;

		allioc_get_template_part( 'chatbot-widget', null, true, array(
				'title' 					=> $title,
				'before_title' 				=> $before_title,
				'after_title' 				=> $after_title,
				'class' 					=> 'allioc-chatbot-widget',
				'debug' 					=> $debug,
				'input_text'				=> $general_settings['input_text'],
				'sequence'					=> Allio::$sequence++
		) );

		echo $after_widget;
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_Widget::update()
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$headers = array ('h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		if ( in_array( $new_instance['header'], $headers ) ) {
			$instance['header'] = $new_instance['header'];
		}
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * (non-PHPdoc)
	 * @see WP_Widget::form()
	 */
	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
				'title' => __( 'Chatbot', 'Allio'),
				'header' => 'h3'
		) );

		$header = $instance['header'];
		$title = $instance['title'];

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'Allio' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'header' ); ?>"><?php _e( 'Header', 'Allio' ); ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name( 'header' ); ?>" id="<?php echo $this->get_field_id( 'header' ); ?>">
				<?php
				$header_options = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );

				foreach ( $header_options as $header_option ) {
					$selected = '';
					if ( $header_option == $header ) {
						$selected = ' selected="selected"';
					}
					echo '<option value="' . $header_option . '" ' . $selected . '>' . strtoupper( $header_option ) . '</option>';
				}
				?>
			</select>
		</p>
		<?php
	}
}

/**
 * Register widgets
 */
function allioc_register_widgets() {
	register_widget( 'ALLIOC_Chatbot_Widget' );
}
add_action( 'widgets_init', 'allioc_register_widgets' );
