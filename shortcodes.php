<?php

/**
 * Get a link to send PM to the given User.
 *
 * @param int $user_id user id.
 *
 * @return string
 */
function buddydev_get_send_private_message_to_user_url( $user_id ) {
	return wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) );
}

/**
 * Shortcode [bp-pm-button username=optional_some_user_name]
 *
 * @param array $atts shortcode attributes.
 * @param string $content content.
 *
 * @return string
 */
function buddydev_private_message_button_shortcode( $atts, $content = '' ) {
	// User is not logged in.
	if ( ! is_user_logged_in() ) {
		return '';
	}

	$atts = shortcode_atts( array(
		'user_id'   => '',
		'username'  => '',
		'label'     => 'Contact Actor',
	), $atts );

	$user_id = absint( $atts['user_id'] );
	$user_login = $atts['username'];

	// if the username is given, override the user id.
	if ( $user_login ) {
		$user = get_user_by( 'login', $user_login );
		if ( ! $user ) {
			return '';
		}
		$user_id = $user->ID;
	}

	if ( ! $user_id ) {
		if ( ! in_the_loop() ) {
			return '';
		}

		$user_id = get_the_author_meta('ID' );
	}
	// do not show the PM button for the user, if it is aimed at them.
	if ( bp_loggedin_user_id() === $user_id ) {
		return '';
	}

	// if we are here, generate the button.
	$button = sprintf('<a href="%1$s">%2$s</a>', buddydev_get_send_private_message_to_user_url( $user_id ), $atts['label'] );

	return $button . $content;
}

add_shortcode( 'bp-pm-button', 'buddydev_private_message_button_shortcode' );

// Shortcode to output custom PHP in Elementor
function wpc_elementor_shortcode( $atts ) {

 if ($post->post_author == $current_user->ID) { ?>
   <p><a onclick="return confirm('Are you SURE you want to delete this post?')" href="<?php echo get_delete_post_link( $post->ID ) ?>">Delete post</a></p>
<?php }
}
//add_shortcode( 'my_elementor_php_output', 'wpc_elementor_shortcode');
