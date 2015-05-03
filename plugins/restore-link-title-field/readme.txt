=== Restore Link Title Field === 
Contributors: Otto42, SergeyBiryukov
Tags: title, link, tinymce, editor
Requires at least: 4.2
Tested up to: 4.2
Stable Tag: trunk
License: GPLv2
License URI: http://www.opensource.org/licenses/GPL-2.0

Adds back the missing "title" field in the Insert/Edit URL box in WordPress 4.2

== Description ==

In core ticket [#28206](http://core.trac.wordpress.org/ticket/28206), the editor was modified to have a Link Text field instead of a Title field.

This plugin adds the Title field back.

== Installation ==

1. Upload the files to the `/wp-content/plugins/restore-link-title-field/` directory or install through WordPress directly.
1. Activate the "Restore Link Title Field" plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.3 =
* Fix the updateFields issue. When you click on a link from the search results, it should now populate the title correctly, like the old versions did.

= 1.2 =
* Changed the JS to use original wpLink functions for everything except htmlUpdate, to improve compatibility. Ref: https://core.trac.wordpress.org/ticket/32180
* Fix issue with title field not resetting on a new link

= 1.1 =
* Rewrite mostly by SergeyBiryukov to be somewhat smaller and more forward compatible.

= 1.0 = 
* First version.
