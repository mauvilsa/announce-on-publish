(function( WPAPI, $ ) {
  'use strict';

  $(document).ready( function () {
    /// Create WPAPI object for creating posts ///
    var wp = new WPAPI({
      endpoint: announce_on_publish.root,
      nonce: announce_on_publish.nonce
    });

    // You can now authenticate, and read/write private data!
    /*wp.users().me().then(function( me ) {
      console.log( 'I am ' + me.name + '!' );
    });*/

    /// Create announcement modal box ///
    var
    curr_title = $('#title')[0],
    curr_content = $('#content')[0],
    modbox = $('<div id="announce-modal-box" style="display: none;"/>'),
    title = $('<input type="text" id="publish-title" name="publish-title" size="30" value="" spellcheck="true" autocomplete="off">'),
    content = $('<textarea id="publish-content" name="publish-content" cols="40"></textarea>'),
    cancel = $('<span id="publish-cancel" class="button button-large">Cancel</span>'),
    publish = $('<span id="publish-confirm" class="button button-primary button-large">Publish</span>');

    $('<div id="announce-on-publish" class="postbox-container"/>')
      .append('<h2>Announcement post</h2>')
      .append('<p>Input the details for the additional post announcing the creation of this '+announce_on_publish.post_type+'. Please <b>check it carefully</b> since any mistake can only be corrected by going to the respective announcement targets.</p>')
      .append('<label for="publish-title">Title for announcement post</label>')
      .append(title)
      .append('<label for="publish-content">Text for announcement post</label>')
      .append(content)
      .append(cancel)
      .append(publish)
      .appendTo(modbox);
    modbox.appendTo('body');

    /// Override publish button ///
    $('#publish').on( 'click', publish_request );
    function publish_request( event ) {
      if ( $(event.currentTarget).attr('name') !== 'publish' )
        return;

      title[0].value = 'New '+announce_on_publish.post_type+': ';
      title[0].value += typeof curr_title.value !== 'undefined' ? curr_title.value : '';
      content[0].value = typeof curr_content.value !== 'undefined' ? curr_content.value : '';

      modbox.css('display','block');

      event.preventDefault();
    }

    /// Setup publish cancel button ///
    cancel.on( 'click', function publish_cancel() {
      console.log('publish_cancel called');
      modbox.css('display','none');
    });

    /// Setup publish confirm button ///
    publish.on( 'click', function publish_confirm() {
      console.log('publish_confirm called');
      /// Check that title and content are not empty ///
      if ( title[0].value.trim() === '' || content[0].value.trim() === '' )  {
        alert('The title and content are required to be non-empty!');
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
        // "response" will hold all properties of your newly-created post,
        // including the unique `id` the post was assigned on creation
        console.log( 'response.id: ' + response.id );
      });

    });

  } );

})( window.WPAPI, window.jQuery );
