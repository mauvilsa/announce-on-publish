<?php
/**
 * Plugin Name: Announce on publish
 * Description: When publishing a new post (for a given list of post types), a modal box is presented for creating an additional announcement post.
 * Author:      mauvilsa
 * Author URI:  http://mvillegas.info
 * Plugin URI:  https://github.com/mauvilsa/wp-announce-on-publish
 * License:     MIT License
 * License URI: https://github.com/mauvilsa/wp-announce-on-publish/blob/master/LICENSE.md
 * Version:     2016.10.21
 */

defined( 'ABSPATH' ) || exit;

/*add_filter( 'announce_sources', function ( $post_types ) {
  if ( isset( $post_types[ 'post' ] ) )
    unset( $post_types[ 'post' ] );
  return $post_types;
});*/

/// Enqueue in admin ///
add_action( 'admin_enqueue_scripts', function ( $hook ) {

  /// Post type for announcing ///
  $announce_target = apply_filters( 'announce_target', 'post' );

  /// Source post types to announce ///
  $announce_sources = apply_filters( 'announce_sources', get_post_types() );
  unset( $announce_sources[$announce_target] );

  if ( // Return if non-posting page
       ( $hook !== 'post-new.php' && $hook !== 'post.php' )
       // Return if user can't publish
       || ! current_user_can( 'publish_posts' )
       // Return if post already published
       || get_post()->post_status === 'publish'
       // Return if post type not in sources list
       || ! in_array( get_post()->post_type, (array)$announce_sources ) )
       //|| get_post()->post_type === $announce_target )      // Return if it is ordinary post
    return;

  /// Return if post type not in list ///
  //if ( ! in_array( get_post()->post_type, (array)$announce_sources ) )
  //  return;

  /// Register the wpapi script ///
  wp_register_script(
    'admin-wpapi',
    plugin_dir_url( __FILE__ ) . 'wpapi.min.js'
  );

  /// Register the announce-on-publish script ///
  wp_register_script(
    'announce-on-publish',
    plugin_dir_url( __FILE__ ) . 'announce-on-publish.js',
    array( 'admin-wpapi' )/*,
    false,
    true // enqueue in footer*/
  );

  /// Localize the script to inject a NONCE for authenticating ///
  wp_localize_script(
    'announce-on-publish',
    'announce_on_publish',
    array(
      'target' => $announce_target,
      'post_type' => get_post()->post_type,
      'root' => esc_url_raw( rest_url() ),
      'nonce' => wp_create_nonce( 'wp_rest' )
    )
  );

  /// Enqueue the stylesheet ///
  wp_enqueue_style( 'announce-on-publish', plugin_dir_url( __FILE__ ) . 'announce-on-publish.css' );

  /// Enqueue the script ///
  wp_enqueue_script( 'announce-on-publish' );
} );
?>
