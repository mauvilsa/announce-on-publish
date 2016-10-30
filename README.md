# Announce on Publish #
* Contributors:      mauvilsa
* Tags:              publish, posts, announcement, news
* Donate link:       https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DX5CDCA5FTRAY
* Requires at least: 4.6
* Tested up to:      4.6.1
* Stable tag:        2016.10.30
* License:           GPLv2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.html

When publishing a new post (for a filtered list of post types), a modal box is
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

### Support ###
* Community support via the [support forums on wordpress.org](https://wordpress.org/support/plugin/announce-on-publish).

### Contribute ###
* Development of this plugin [on GitHub](https://github.com/mauvilsa/announce-on-publish).
* If you think you’ve found a bug (e.g. you’re experiencing unexpected behavior), please post at the [support forums](https://wordpress.org/support/plugin/announce-on-publish) first.
* If you want to help translating this plugin, you can do so [on WordPress Translate](https://translate.wordpress.org/projects/wp-plugins/announce-on-publish).

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

None. These need to be configured explicitly.

### How do I configure the post types to be announced? ###

Go to Settings -> Announce on Publish. Then mark the checkboxes for the post
types you want to announce and click on Save Changes.


## Screenshots ##

1. The settings page for configuring the post types to announce.
2. The modal box that is presented for creating the announcements.


## Changelog ##

### 2016.10.30 ###
* Added settings page to ease configuration of the post types.
* Announcements can now be skipped if marked as not mandatory.

### 2016.10.23 ###
* Initial version.
