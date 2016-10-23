<?php
/**
 * Plugin Name: Announce on Publish
 * Description: When publishing a new post (for a given list of post types), a modal box is presented for creating an additional announcement post.
 * Depends:     WP REST API
 * Author:      mauvilsa
 * Author URI:  https://github.com/mauvilsa
 * Plugin URI:  https://github.com/mauvilsa/wp-announce-on-publish
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Version:     2016.10.21
 */

/*
Copyright (C) 2016 Mauricio Villegas <mauricio_ville@yahoo.com>

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc., 51 Franklin
Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) || exit;

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
    return;

  /// Register the wpapi script ///
  wp_register_script(
    'admin-wpapi',
    plugin_dir_url( __FILE__ ) . 'wpapi.min.js'
  );

  /// Register the announce-on-publish script ///
  wp_register_script(
    'announce-on-publish',
    plugin_dir_url( __FILE__ ) . 'announce-on-publish.js',
    array( 'admin-wpapi' )
  );

  /// Localize the script to inject a NONCE for authenticating ///
  wp_localize_script(
    'announce-on-publish',
    'announce_on_publish',
    array(
      'target' => $announce_target,
      'post_type' => get_post()->post_type,
      'root' => esc_url_raw( rest_url() ),
      'nonce' => wp_create_nonce( 'wp_rest' ),
      'rest_api' => is_plugin_active('rest-api/plugin.php') ? 'true' : 'false'
    )
  );

  /// Enqueue the stylesheet ///
  wp_enqueue_style( 'announce-on-publish', plugin_dir_url( __FILE__ ) . 'announce-on-publish.css' );

  /// Enqueue the script ///
  wp_enqueue_script( 'announce-on-publish' );
} );
?>
