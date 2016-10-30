<?php
/**
 * Settings page the announce-on-publish wordpress plugin.
 *
 * @version $Version: 2016.10.30$
 * @author Mauricio Villegas <mauvilsa@upv.es>
 * @copyright Copyright(c) 2016, Mauricio Villegas <mauricio_ville@yahoo.com>
 * @license GPLv2 or later
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

/// Enqueue the stylesheet ///
wp_enqueue_style( 'announce-on-publish', plugin_dir_url( __FILE__ ) . 'announce-on-publish.css' );

/// Get possible announcement post types ///
$post_types = get_post_types(
  array(
    'public' => true,
    'show_ui' => true
  ),
  'objects' );

global $AnnounceOnPublish;

$announce = $AnnounceOnPublish->announce;
$mandatory = $AnnounceOnPublish->mandatory;

?>

<div class="wrap" id="announce-settings">
  <h2><?php echo __('Announce on Publish Settings','announce-on-publish'); ?></h2>

  <form method="post" action="options.php">
    <?php settings_fields( 'announce-on-publish' ); ?>
    <?php do_settings_sections( 'announce-on-publish' ); ?>

    <table>
      <tr>
        <th><?php echo __('Post Type','announce-on-publish'); ?></th>
        <th class="ccol"><?php echo __('Announce','announce-on-publish'); ?></th>
        <th class="ccol"><?php echo __('Mandatory','announce-on-publish'); ?></th>
      </tr>
      <?php 
        foreach ( $post_types as $p ) {
          $pt = $p->name;
          if ( $pt == 'post' )
            continue;
          $a_checkbox = 'name="aop_announce[]"' . ( isset($announce[$pt]) ? ' checked' : '' );
          $m_checkbox = 'name="aop_mandatory[]"' . ( isset($mandatory[$pt]) ? ' checked' : '' );
          echo '<tr>';
          echo '  <td>'.$p->label.'</td>';
          echo '  <td class="ccol"><input type="checkbox" value="'.$pt.'" '.$a_checkbox.'/></td>';
          echo '  <td class="ccol"><input type="checkbox" value="'.$pt.'" '.$m_checkbox.'/></td>';
          echo '</tr>';
        }
      ?>
    </table>
    <?php submit_button(); ?>
  </form>
</div>

<script>
(function() {
  var $ = jQuery;
  function handle_checkbox () {
    $('[name="aop_mandatory[]"][value="'+$(this).val()+'"]')
      .prop( 'disabled', ! $(this).prop('checked') );
  }
  $('[name="aop_announce[]"]')
    .on( 'click', handle_checkbox )
    .each( handle_checkbox );
})();
</script>
