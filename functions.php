<?php
/**
 * Betheme Child Theme
 *
 * @package Betheme Child Theme
 * @author Muffin group
 * @link https://muffingroup.com
 */

/**
 * Child Theme constants
 * You can change below constants
 */

// white label
define('WHITE_LABEL', false);

/**
 * Replace default avatar
 */
define ( 'BP_AVATAR_DEFAULT', 'https://opencurtainscasting.com/images/place-holder.png' );
define ( 'BP_AVATAR_DEFAULT_THUMB', 'https://opencurtainscasting.com/images/place-holder.png' );

/**
 * Disable JetPack Upsells
 */
 
add_filter( 'jetpack_just_in_time_msgs', '_return_false' );

add_filter('jpeg_quality', function($arg){return 100;});
add_filter('wp_editor_set_quality', function($arg){return 100;});

/**
 * Add Shortcodes
 */
include 'shortcodes.php';
include 'bbpress-shortcode.php';
include 'manage-content-access.php';

/**
 * Enqueue Styles
 */

function mfnch_enqueue_styles()
{
	// enqueue the parent stylesheet
	// however we do not need this if it is empty
	// wp_enqueue_style('parent-style', get_template_directory_uri() .'/style.css');

	// enqueue the parent RTL stylesheet

	if (is_rtl()) {
		wp_enqueue_style('mfn-rtl', get_template_directory_uri() . '/rtl.css');
	}

	// enqueue bootstrap css

	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', array(), '5.2.0', 'all');

    wp_enqueue_style('custom', get_stylesheet_directory_uri() . '/assets/scss/custom.css');

	// enqueue the child stylesheet

	wp_dequeue_style('style');
	wp_enqueue_style('style', get_stylesheet_directory_uri() .'/style.css');
}

	// enqueue scripts
    if (!is_admin()) {
     wp_register_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.2.0', true);
     wp_enqueue_script('bootstrap-js');
     wp_register_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), '',
      true);
     wp_enqueue_script('custom-js');
    }


add_action('wp_enqueue_scripts', 'mfnch_enqueue_styles');

/**
 * Load Textdomain
 */

function mfnch_textdomain()
{
	load_child_theme_textdomain('betheme', get_stylesheet_directory() . '/languages');
	load_child_theme_textdomain('mfn-opts', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'mfnch_textdomain');

/*
 * Current User link shortcode
 * Use like so: [current_user_link] This is displayed only if current user is logged in.
 */

add_shortcode( 'current_user_link', 'wppbc_current_user_link' );
function wppbc_current_user_link( $atts, $content ) {
	global $current_user; wp_get_current_user();
	if ( is_user_logged_in() ) {
		//$id = get_current_user_id();
		$user = $current_user->user_login;
		// make sure to change the URL to represent your setup.
		return "<a href='https://opencurtainscasting.com/members/{$user}'>View Your User Page Here</a>";
	}

	return ;
}

/**
 * Logout redirect
 */
add_action('wp_logout','auto_redirect_after_logout');

function auto_redirect_after_logout(){
 wp_safe_redirect( home_url() );
 exit;
}

/**
 * New Excerpt Filter. Allows HTML elements
 */
function lt_html_excerpt($text) { // Fakes an excerpt if needed
 global $post;
 if ( '' == $text ) {
  $text = get_the_content('');
  $text = apply_filters('the_content', $text);
  $text = str_replace('\]\]\>', ']]&gt;', $text);
  /*just add all the tags you want to appear in the excerpt --
  be sure there are no white spaces in the string of allowed tags */
  $text = strip_tags($text,'<p><br><b><a><em><strong>');
  /* you can also change the length of the excerpt here, if you want */
  $excerpt_length = 55;
  $words = explode(' ', $text, $excerpt_length + 1);
  if (count($words)> $excerpt_length) {
   array_pop($words);
   array_push($words, '[...]');
   $text = implode(' ', $words);
  }
 }
 return $text;
}
/* remove the default filter */
remove_filter('get_the_excerpt', 'wp_trim_excerpt');

/* now, add your own filter */
add_filter('get_the_excerpt', 'lt_html_excerpt');

/**
 * Disable Thumbnail Sizes
 */
function shapeSpace_disable_thumbnail_images($sizes) {

 unset($sizes['thumbnail']); // disable thumbnail size
 return $sizes;

}
add_action('intermediate_image_sizes_advanced', 'shapeSpace_disable_thumbnail_images');

add_action( 'pre-upload-ui', function() {
 ?><h4>
 <?php esc_html_e( 'Uploads can take a minute or more depending on image size and internet conditions.', 'wpse' );?>
 </h4>
 <?php
});

add_action( 'post-upload-ui', function() {
 ?><h4>
 <?php esc_html_e( '', 'wpse' );?>
 </h4>
 <?php
});

// Function to change sender name
add_filter( 'wp_mail_from_name', 'occ_sender_name' );
function occ_sender_name( $original_email_from ) {
 return 'Open Curtains Casting';
}