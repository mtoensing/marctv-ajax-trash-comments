=== MarcTV Moderate Comments ===
Contributors:  MarcDK, lefalque
Tags: comments, admin, ajax, flag, report, moderate, trash, replace
Requires at least: 3.0
Tested up to: 6.0
Stable tag: 1.9
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

= 1.9 =
Migration to GitHub actions.

= 1.2.4 =

* Replaced comments in the backend are not marked as ok automatically. This saves clicks for moderators.

= 1.2.3 =

* Fixed anchor tag missing href.

= 1.2.2 =

* Links will no longer show up if Javascript is disabled.
* New HTML structure for the links.
* Small localisation changes.

= 1.2 =

* This is a feature merge of the plugin "ReportComments" by lefalque and "Ajax Trash and Replace
Comments" by myself. I added a german translation and fixed php notices.

= 1.1.3 =

Fixed empty moderation text on plugin activation.

= 1.1.2 =

Fixed renaming bug. Seems I am not allowed to rename the plugin file. I forgot to remove the old files.

= 1.1.0 =

* Fixed bug that did not show any comments for users who were not logged in.
* New feature: replace comment text with your custom moderation text.
* Link to new settings page in the frontend.
* Renamed plugin.

= 1.0.3 =

* Added screenshot.

= 1.0.2 =

* Added ajax activity indicators.

= 1.0.1 =

* Fixed: Trash button was visible in the backend.

== Screenshots ==

1. The plugin in action.