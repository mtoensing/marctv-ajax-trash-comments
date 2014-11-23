=== MarcTV Moderate Comments ===

Contributors:  MarcDK, lefalque
Version: 1.0
Tags: comments, admin, ajax, flag, report, moderate, trash, replace
Requires at least: 3.0
Tested up to: 4.01
Stable tag: 1.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Grants visitors the ability to report inappropriate comments. Admins are able to replace and trash comments in the frontend.

== Description == 

Adds a link next to the reply link below each comment, which allows visitors to flag comments as inappropriate.
A sub page to comments in admin is added, where an administrator may review all the flagged comments and decide
if they should be removed or not.

Allow admins to trash or replace comments with your custom moderation text in one click in the frontend.

You need to be logged in as admin or any user with comment moderation permissions. A "trash" link will appear beneath
all comments in the frontend. Now you can mark comments as "trash" and they will disappear after a page reload.
Don't worry: You can "untrash" them if it was a mistake until you reload.

You are also able to replace the comment text with a custom text which can be set in the settings.
This action can not be undone.

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

= 1.0 =

* Initial release. This is a feature merge of the plugin "ReportComments" by lefalque and "Ajax Trash and Replace
Comments" by myself. I added a german translation and fixed php notices.