<?php
/**
 * Plugin Name: Announce on Publish
 * Description: When publishing a new post (for a filtered list of post types), a modal box is presented for creating an additional announcement post.
 * Depends:     WP REST API
 * Author:      mauvilsa
 * Author URI:  https://github.com/mauvilsa
 * Plugin URI:  https://github.com/mauvilsa/announce-on-publish
 * Text Domain: announce-on-publish
 * Domain Path: /lang
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Version:     2016.10.30
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

/**
 * Class for the AnnounceOnPublish plugin
 */
class AnnounceOnPublish {
  static $instance = false;
  var $announce = array();
  var $mandatory = array();

  /**
   * Gets the instance of the plugin.
   */
  public static function getInstance () {
    if ( ! self::$instance )
      self::$instance = new self;
    return self::$instance;
  }

  /**
   * AnnounceOnPublish constructor
   */
  public function __construct () {
    /// Enqueue admin script ///
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueueScript' ) );

    /// Register settings ///
    add_action( 'admin_init', function () {
        register_setting( 'announce-on-publish', 'aop_announce' );
        register_setting( 'announce-on-publish', 'aop_mandatory' );
      } );

    /// Get current settings ///
    $announce = get_option( 'aop_announce' );
    if ( $announce )
      $this->announce = array_flip($announce);
    $mandatory = get_option( 'aop_mandatory' );
    if ( $mandatory )
      $this->mandatory = array_flip($mandatory);

    /// Add settings page ///
    add_action( 'admin_menu', function () {
        add_options_page(
          __( 'Announce on Publish', 'announce-on-publish' ),
          __( 'Announce on Publish', 'announce-on-publish' ),
          'manage_options',
          'announce-on-publish',
          function () { include 'announce-on-publish-admin.php'; } );
      } );

    /// Add settings as plugin action link ///
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
        array_unshift( $links, '<a href="options-general.php?page=announce-on-publish">'.__('Settings').'</a>' );
        return $links;
      } );
  }

  /**
   * Function for admin_enqueue
   */
  function enqueueScript( $hook ) {
    if ( // Return if non-posting page
         ( $hook !== 'post-new.php' && $hook !== 'post.php' )
         // Return if user can't publish
         || ! current_user_can( 'publish_posts' )
         // Return if post already published
         || get_post()->post_status === 'publish'
         // Return if post type not in sources list
         || ! isset( $this->announce[get_post()->post_type] ) )
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
        'mandatory' => isset( $this->mandatory[get_post()->post_type] ),
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
        'publish' => __( 'Announce', 'announce-on-publish' ),
        'cancel' => __( 'Cancel', 'announce-on-publish' ),
        'skip' => __( 'Skip', 'announce-on-publish' ),
        'modbox_title' => __( 'Announcement post', 'announce-on-publish' ),
        'announce_title' => __( 'Title for announcement post', 'announce-on-publish' ),
        'announce_text' => __( 'Text for announcement post', 'announce-on-publish' ),
        'announce_info' => sprintf(__( 'Input the details for the additional post announcing the creation of this %s. Please <b>check it carefully</b> since any mistake can only be corrected by going to the respective announcement target(s).', 'announce-on-publish' ), get_post()->post_type),
        'empty_content' => __( 'The title and content are required to be non-empty!', 'announce-on-publish' ),
        'same_content' => __( 'The title and content are required to be different from the main post!', 'announce-on-publish' ),
        'post_success' => __( 'Announcement post created successfully', 'announce-on-publish' ),
        'post_problem' => __( 'There was a problem creating the announcement post: ', 'announce-on-publish' ),
        'no_rest_api' => is_plugin_active('rest-api/plugin.php') ? '' : sprintf(__( 'Announce on Publish enabled for %s, but WP REST API plugin is required to be installed and active and apparently it is not. Announcements will not be posted.', 'announce-on-publish' ),get_post()->post_type)
      )
    );

    /// Enqueue the stylesheet ///
    wp_enqueue_style( 'announce-on-publish', plugin_dir_url( __FILE__ ) . 'announce-on-publish.css' );

    /// Enqueue the script ///
    wp_enqueue_script( 'announce-on-publish' );
  }
}

$AnnounceOnPublish = AnnounceOnPublish::getInstance();

?>
