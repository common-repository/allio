<?php

/**
 * Adds Allio post meta box to all existing post types
 *
 * @since 1.4
 */
function allioc_post_meta_box() {

    add_meta_box( 'allioc-post-meta-box', __( 'Allio', 'Allio' ), 'allioc_post_meta_box_callback', '', 'side', 'default' );

}
add_action( 'add_meta_boxes', 'allioc_post_meta_box' );


/**
 * Displays the Allio post meta box on the Edit post screen
 */
function allioc_post_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'allioc_post_meta_box_nonce', 'allioc_post_meta_box_nonce' );

    $chatbot_overlay = get_post_meta( $post->ID, 'allioc_chatbot_overlay', true );
    if ( $chatbot_overlay == null) {
    	$chatbot_overlay = '';
    }

    ?>

    <p>
    	<label class="post-attributes-label" for="allioc_chatbot_overlay"><?php _e( 'Chatbot Overlay', 'Allio' ); ?></label>
    </p>

    <select name="allioc_chatbot_overlay" id="allioc_chatbot_overlay">
	    	<option value="" <?php selected( '', $chatbot_overlay, true ); ?>><?php _e( 'Use global settings', 'Allio' ); ?></option>
	    	<option value="enable" <?php selected( "enable", $chatbot_overlay, true ); ?>><?php _e( 'Enable', 'Allio' ); ?></option>
	    	<option value="disable" <?php selected( "disable", $chatbot_overlay, true ); ?>><?php _e( 'Disable', 'Allio' ); ?></option>
	    </select>

    <p class="howto"><?php _e( 'Do you want to enable or disable the chatbot overlay specifically for this post?', 'Allio' ); ?></p>

	<?php

}


/**
 * When the post is saved, saves the Allio post meta box data.
 *
 * @param int $post_id
 */
function allioc_save_post_meta_box( $post_id ) {

    // Check if our nonce is set
    if ( ! isset( $_POST['allioc_post_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid
    if ( ! wp_verify_nonce( $_POST['allioc_post_meta_box_nonce'], 'allioc_post_meta_box_nonce' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now */

    // Sanitize input
    $chatbot_overlay = ''; // default to general settings
    if ( isset( $_POST['allioc_chatbot_overlay'] )
    	&& ( $_POST['allioc_chatbot_overlay']  === "enable" || $_POST['allioc_chatbot_overlay']  === "disable" ) ) {
	    $chatbot_overlay = sanitize_text_field($_POST['allioc_chatbot_overlay']);
	}

    // Update the post meta field
	update_post_meta( $post_id, 'allioc_chatbot_overlay', $chatbot_overlay );
}
add_action( 'save_post', 'allioc_save_post_meta_box' );
