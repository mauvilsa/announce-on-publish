<?php
/**
 * Plugin Name: Announce on Publish
 * Description: When publishing a new post (for a filtered list of post types), a modal box is presented for creating an additional announcement post.
 * Depends:     WP REST API
 * Author:      mauvilsa
 * Author URI:  https://github.com/mauvilsa
 * Plugin URI:  https://github.com/mauvilsa/wp-announce-on-publish
 * Text Domain: announce-on-publish
 * Domain Path: /lang
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Version:     2016.10.23
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

/*
 @todo Create settings page: post types, annotation mandatory, modal box messages
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

  /// Localize the script for translations and REST API information ///
  load_plugin_textdomain( 'announce-on-publish', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
  wp_localize_script(
    'announce-on-publish',
    'announce_on_publish',
    array(
      //'target' => $announce_target,
      'root' => esc_url_raw( rest_url() ),
      'nonce' => wp_create_nonce( 'wp_rest' ),
      'rest_api' => is_plugin_active('rest-api/plugin.php') ? 'true' : 'false',
      'publish' => __( 'Publish', 'announce-on-publish' ),
      'cancel' => __( 'Cancel', 'announce-on-publish' ),
      'modbox_title' => __( 'Announcement post', 'announce-on-publish' ),
      'announce_title_prefix' => sprintf(__( 'New %s: ', 'announce-on-publish' ), get_post()->post_type),
      'announce_title' => __( 'Title for announcement post', 'announce-on-publish' ),
      'announce_text' => __( 'Text for announcement post', 'announce-on-publish' ),
      'announce_info' => sprintf(__( 'Input the details for the additional post announcing the creation of this %s. Please <b>check it carefully</b> since any mistake can only be corrected by going to the respective announcement target(s).', 'announce-on-publish' ), get_post()->post_type),
      'empty_content' => __( 'The title and content are required to be non-empty!', 'announce-on-publish' ),
      'post_problem' => __( 'There was a problem creating the announcement post: ', 'announce-on-publish' ),
      'no_rest_api' => sprintf(__( 'Announce on Publish enabled for %s, but WP REST API plugin is required to be installed and active and apparently it is not. Announcements will not be posted.', 'announce-on-publish' ),get_post()->post_type)
    )
  );

  /// Enqueue the stylesheet ///
  wp_enqueue_style( 'announce-on-publish', plugin_dir_url( __FILE__ ) . 'announce-on-publish.css' );

  /// Enqueue the script ///
  wp_enqueue_script( 'announce-on-publish' );
} );
?>
