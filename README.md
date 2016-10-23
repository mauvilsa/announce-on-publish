# Announce on Publish #
* Contributors:      mauvilsa
* Tags:              publish, posts, announcement, news
* Donate link:       https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DX5CDCA5FTRAY
* Requires at least: 4.6
* Tested up to:      4.6.1
* Stable tag:        2016.10.23
* License:           GPLv2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.html

When publishing a new post (for a given list of post types), a modal box is
presented for creating an additional announcement post.


## Description ##

On sites that have custom post types defined, it might be desired that when
publishing (i.e., changing the status to publish) an additional post be
created informing of this event. For example, in a site a defined post type
could be *project*, and the ordinary posts are used as a news feed. This
plugin can be used so that when a user creates a new *project*, the user is
requested to create the announcement post. Additional to this plugin, a great
companion can be one of the plugins that post to social networks, so that the
announcement also gets propagated to these.

The usage of this plugin is quite simple. Only for the post types configured
for announcements, when pressing the *Publish* button a modal box will appear,
with fields to edit the announcement title and content. Once the post is in
publish status, the announcement modal box will not appear anymore for any
edits done the original post.

[//]: # (### Support ###)
[//]: # (* Community support via the [support forums on wordpress.org](https://wordpress.org/support/plugin/announce-on-publish).)

### Contribute ###
* Development of this plugin [on GitHub](https://github.com/mauvilsa/wp-announce-on-publish).
[//]: # (* If you think you’ve found a bug (e.g. you’re experiencing unexpected behavior), please post at the [support forums](https://wordpress.org/support/plugin/announce-on-publish) first.)
[//]: # (* If you want to help translating this plugin, you can do so [on WordPress Translate](https://translate.wordpress.org/projects/wp-plugins/announce-on-publish).)

### Credits ###
* Author: [Mauricio Villegas](https://github.com/mauvilsa)
* Development inspired by the [Publish Confirm](https://wordpress.org/plugins/publish-confirm) plugin and the [node-wpapi](http://wp-api.org/node-wpapi) javascript library for interacting with the WordPress REST API.


## Installation ##
* Instructions on how to install WordPress plugins at [codex.wordpress.org](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

### Requirements ###
* WordPress 4.6 or greater (might work in older versions, but not tested)
* WordPress REST API (Version 2) plugin


## Frequently Asked Questions ##

### By default what post types are announced? ###

All except ordinary posts, i.e., custom post types and pages.

### Can the post types for announcements be configured? ###

Yes, via a PHP filter included in a functions.php of an active plugin or
theme. For example to include only a few specific post types, the following
filter would be added:

<pre>add_filter( 'announce_sources', function ( $post_types ) {
  return array( 'post_type_1' => true, 'post_type_2' => true, ... );
});</pre>

The following filter would exclude a single post type:

<pre>add_filter( 'announce_sources', function ( $post_types ) {
  if ( isset( $post_types[ 'post_to_exclude' ] ) )
    unset( $post_types[ 'post_to_exclude' ] );
  return $post_types;
});</pre>


## Changelog ##

### 2016.10.23 ###
* Initial version.
