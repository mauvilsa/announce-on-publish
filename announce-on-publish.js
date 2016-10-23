/**
 * Javascript functionality for the announce-on-publish wordpress plugin.
 *
 * @version $Version: 2016.10.23$
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

(function( WPAPI, $ ) {
  'use strict';

  $(document).ready( function () {
    /// Check that WP REST API plugin is active ///
    if ( announce_on_publish.rest_api !== 'true' ) {
      alert(announce_on_publish.no_rest_api);
      return;
    }

    /// Create WPAPI object for creating posts ///
    var wp = new WPAPI({
      endpoint: announce_on_publish.root,
      nonce: announce_on_publish.nonce
    });

    /// Create announcement modal box ///
    var
    curr_title = $('#title')[0],
    curr_content = $('#content')[0],
    modbox = $('<div id="announce-modal-box" style="display: none;"/>'),
    title = $('<input type="text" id="announce-title" name="announce-title" size="30" value="" spellcheck="true" autocomplete="off">'),
    content = $('<textarea id="announce-content" name="announce-content" cols="40"></textarea>'),
    cancel = $('<span id="announce-cancel" class="button button-large">'+announce_on_publish.cancel+'</span>'),
    publish = $('<span id="announce-confirm" class="button button-primary button-large">'+announce_on_publish.publish+'</span>');

    modbox
      .append('<div id="announce-background"/>')
      .appendTo('body');
    $('<div id="announce-on-publish" class="postbox-container"/>')
      .append('<h2>'+announce_on_publish.modbox_title+'</h2>')
      .append('<p>'+announce_on_publish.announce_info+'</p>')
      .append('<label for="announce-title">'+announce_on_publish.announce_title+'</label>')
      .append(title)
      .append('<label for="announce-content">'+announce_on_publish.announce_text+'</label>')
      .append(content)
      .append(cancel)
      .append(publish)
      .appendTo(modbox);

    /// Override publish button ///
    $('#publish').on( 'click', publish_request );
    function publish_request( event ) {
      if ( $(event.currentTarget).attr('name') !== 'publish' )
        return;

      title[0].value = announce_on_publish.announce_title_prefix;
      title[0].value += typeof curr_title.value !== 'undefined' ? curr_title.value : '';
      content[0].value = typeof curr_content.value !== 'undefined' ? curr_content.value : '';

      modbox.css('display','block');

      event.preventDefault();
    }

    /// Setup publish cancel button ///
    cancel.on( 'click', function publish_cancel() {
      modbox.css('display','none');
    });

    /// Setup publish confirm button ///
    publish.on( 'click', function publish_confirm() {
      /// Check that title and content are not empty ///
      if ( title[0].value.trim() === '' || content[0].value.trim() === '' )  {
        alert(announce_on_publish.empty_content);
        return;
      }

      /// Continue with publishing main post ///
      modbox.css('display','none');
      $('#publish')
        .off( 'click', publish_request )
        .click();

      /// Create announcement post ///
      wp.posts().create({
        title: title[0].value,
        content: content[0].value,
        status: 'publish'
      }).then(function( response ) {
        console.log(response);
        alert('Announcement post created with id '+response.id);
      }).catch(function( err ) {
        console.log(err);
        alert(announce_on_publish.post_problem+err.toString());
      });

    });

  } );

})( window.WPAPI, window.jQuery );
