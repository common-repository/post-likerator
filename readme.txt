=== Post Likerator ===
Contributors: flipeleven
Tags: like, like posts, thumbs up, developer friendly
Requires at least: 4.8.2
Tested up to: 4.8.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Simple like/unlike function for posts. No dislikes. Bring your own CSS.


== Description ==

Enables a simple like/unlike feature to be added to posts, pages, etc. There is no dislike feature. You are expected to style it however you like so it matches your theme; it has none of its own CSS. There is also no admin page or options for this plugin.

Add the like button to your template using the included PHP function:

`<?php post_likerator(); ?>`

By default it will use the current post ID and echo the like button's markup. You can change this by passing (integer) Post ID and (boolean) echo arguments to the function:

`<?php $like_button = post_likerator(2501, false); ?>`

The above will attribute likes to post ID `2501` and will assign the string of markup to the `$like_button` variable instead of echoing it.

Once you've got it looking awesome, click to like the post. Clicking again will unlike it. You can go back and forth as much as you want, but it will probably get boring after a little while.


== Installation ==

1. Go to Plugins in your admin and click Add New.
1. Search for "Post Likerator".
1. Install it and activate.
1. Add the post_likerator() function to your post or page templates.

When you activate the plugin, it will create a new database table for keeping track of the likes on posts. This table will use your database prefix, so if you use the default prefix the table will be called `wp_post_likerator_likes`. You'll need to delete this table manually if you want to completely uninstall the plugin.


== Frequently Asked Questions ==

= I don't see my like button! =

The markup is there, you need to create your own CSS to make it look however you want.

= Where are the plugin's options or settings? =

There aren't any, it's really simple and just does its thing.

= Why can't I dislike something? =

You really want people disliking your own posts? Brave. Well, this isn't the plugin for you then.

= What makes this better than all the other ones? =

It's simple and developer friendly. All the other ones had too many features or had CSS that was a pain to override, so we made our own that only did what we needed. Now we're sharing it so you can enjoy it too.


== Changelog ==

= 1.0.0 =
* Initial release.