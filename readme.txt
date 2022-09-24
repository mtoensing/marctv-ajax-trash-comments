=== MarcTV Moderate Comments ===
Contributors:  MarcDK, lefalque
Tags: comments, admin, ajax, flag, report, moderate, trash, replace
Requires at least: 3.0
Tested up to: 6.0
Stable tag: 2.2
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Grants visitors the ability to report inappropriate comments and admins to replace and trash them in the frontend.

== Description == 

Adds a link next to the reply link below each comment, which allows visitors to flag comments as inappropriate.
A sub page to comments in admin is added, where an administrator may review all the flagged comments and decide
if they should be removed or not.

Admins or logged-in users with comment moderation permissions are able trash or replace comments with
one click in the frontend. This action can not be undone. A "trash" link will appear bellow all comments.
Don't worry: You can untrash them if until you reload. You are also able to replace the comment text with a custom
text which can be set in the settings.

= Features =

* Ability for visitors to report comments they find offensive.
* Once a flagged comment has been deemed ok, it wont be able to be flagged again.
* Flagging is done via ajax for smoother experience for the visitors.
* Decide whether all visitors or only logged in users can report comments.
* Trashing and Replacing with ajax in the frontend for faster moderation.
* Fully localized. Comes with English and German translations.

== Installation ==

1. Install and activate MarcTV Moderate via the WordPress.org repository.
2. Let users flag comments at the front end. Trash and replace them as admin in the frontend, too.
3. Review flagged comments in wp-admin.

== Changelog ==

= 2.2 =
* Fixed localization. 

= 2.0 =
* Fixed compatibility with WordPress 6.0.

= 1.9 =
* Migration to GitHub actions.

= 1.2.4 =

* Replaced comments in the backend are not marked as ok automatically. This saves clicks for moderators.

= 1.2.2 =

* Links will no longer show up if Javascript is disabled.
* New HTML structure for the links.
* Small localisation changes.

== Screenshots ==

1. The plugin in action.