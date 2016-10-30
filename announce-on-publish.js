/**
 * Javascript functionality for the announce-on-publish wordpress plugin.
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

(function( WPAPI, $ ) {
  'use strict';

  $(document).ready( function () {
    /// Check that WP REST API plugin is active ///
    if ( announce_on_publish.no_rest_api ) {
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
    modbox_container = $('<div id="announce-modbox-container" style="display: none;"/>'),
    modbox = $('<div id="announce-modbox"/>'),
    title = $('<input type="text" id="announce-title" name="announce-title" size="30" value="" spellcheck="true" autocomplete="off">'),
    content = $('<textarea id="announce-content" name="announce-content" cols="40"></textarea>'),
    skip = $('<span id="announce-skip" class="button button-large">'+announce_on_publish.skip+'</span>'),
    cancel = $('<span id="announce-cancel" class="button button-large">'+announce_on_publish.cancel+'</span>'),
    publish = $('<span id="announce-confirm" class="button button-primary button-large">'+announce_on_publish.publish+'</span>');

    modbox
      .append('<h2>'+announce_on_publish.modbox_title+'</h2>')
      .append('<p>'+announce_on_publish.announce_info+'</p>')
      .append('<label for="announce-title">'+announce_on_publish.announce_title+'</label>')
      .append(title)
      .append('<label for="announce-content">'+announce_on_publish.announce_text+'</label>')
      .append(content)
      .append(publish)
      .append(cancel);

    if ( ! announce_on_publish.mandatory )
      modbox.append(skip);

    modbox_container
      .append('<div id="announce-background"/>')
      .append(modbox)
      .appendTo('body');

    /// Override publish button ///
    $('#publish').on( 'click', publish_request );
    function publish_request( event ) {
      if ( $(event.currentTarget).attr('name') !== 'publish' )
        return;

      title[0].value = typeof curr_title.value !== 'undefined' ? curr_title.value : '';
      content[0].value = typeof curr_content.value !== 'undefined' ? curr_content.value : '';

      modbox_container.css('display','block');

      event.preventDefault();
    }

    /// Setup publish cancel button ///
    cancel.on( 'click', function publish_cancel() {
      modbox_container.css('display','none');
    });

    /// Setup skip button ///
    skip.on( 'click', function skip_announcement() {
      /// Publish main post ///
      modbox_container.css('display','none');
      $('#publish')
        .off( 'click', publish_request )
        .click();
    });

    /// Setup publish announcement button ///
    publish.on( 'click', function publish_announcement() {
      /// Check that title and content are not empty ///
      if ( title[0].value.trim() === '' || content[0].value.trim() === '' )  {
        alert(announce_on_publish.empty_content);
        return;
      }

      /// Check that title and content differs from the main post ///
      function trim( str ) {
        return str.replace( /^\s*|\s(?=\s)|\s*$/g, '' );
      }
      if ( trim(title[0].value) === trim(curr_title.value) ||
           trim(content[0].value) === trim(curr_content.value) ) {
        alert(announce_on_publish.same_content);
        return;
      }

      /// Continue with publishing main post ///
      modbox_container.css('display','none');
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
        alert(announce_on_publish.post_success);
      }).catch(function( err ) {
        console.log(err);
        alert(announce_on_publish.post_problem+err.toString());
      });

    });

  } );

})( window.WPAPI, window.jQuery );
