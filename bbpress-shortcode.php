<?php
/**
* Get a link to send PM to the given User.
*
* @param int $user_id user id.
*
* @return string
*/
function contact_casting_director_author_url( $user_id ) {
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
function contact_casting_director_author( $atts, $content = '' ) {
 // User is not logged in.
 if ( ! is_user_logged_in() ) {
  return '';
 }

 $atts = shortcode_atts( array(
  'user_id'   => '',
  'username'  => '',
  'label'     => 'Contact this author / casting director',
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
 $button = sprintf('<a href="%1$s">%2$s</a>', contact_casting_director_author_url( $user_id ), $atts['label'] );

 return $button . $content;
}

add_shortcode( 'author-pm-button', 'contact_casting_director_author' );