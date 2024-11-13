=== Import Users from CSV ===
Contributors: soflyy, wpallimport, sorich87, andrewza, paidmembershipspro
Tags: import users from csv, import users, import csv, users, csv
Requires at least: 3.1
Requires PHP: 7.0
Tested up to: 6.7
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Import users from a CSV into WordPress

== Description ==

This plugin allows you to import users from an uploaded CSV file. It will add users with basic information as well as meta fields and user role.

You can also choose to send a notification to the new users and to display password nag on user login.

[Check out our other free plugins.](https://profiles.wordpress.org/wpallimport/#content-plugins)

= Features =

* Imports all users fields
* Imports user meta
* Update existing users by specifying ID field
* Allows setting user role
* Sends new user notification (if the option is selected)
* Shows password nag on user login (if the option is selected)

For feature request and bug reports, [please use the forums](https://wordpress.org/support/plugin/import-users-from-csv).
Code contributions are welcome [on Github](https://github.com/soflyy/import-users-from-csv/).

= Import Users From Any CSV/Excel/XML with WP All Import Pro =
[youtube https://www.youtube.com/watch?v=OrMzPw0p-EU /]

**WP All Import Pro** can import WordPress users, WooCommerce products, ACF, custom post types, custom fields, taxonomies, and everything else:

* **Drag & Drop to Import Any File:** Give any CSV or XML to WP All Import, then drag and drop to map data from your file into WordPress.
* **Any Custom Post Type, Every Data Type:** Import data to custom post types, with support for WooCommerce, ACF, custom fields, taxonomies, and everything else.
* **Import Images & Galleries:** Images can be uploaded, downloaded, or matched to media already in WordPress. Full support for WooCommerce product images and variation galleries.
* **Import Files from URL:** Download and import files from external websites, even if they are password protected. URL imports are can be re-run to add, edit, and delete posts.
* **Scheduled Imports:** WP All Import Pro can check periodically check a file for updates and then add, update, or delete to the imported posts accordingly.
* **Developer Friendly:** Pass data through custom PHP functions. For example, use something like [my_function( {user_email[1]} )] in your template, to pass the value of {user_email[1]} to my_function and display whatever it returns.
* **Priority Support:** Personal support from our team of expert developers with over a decade of experience importing and exporting WordPress data.
* **90 Day Money Back Guarantee:** Not 100% happy? Let us know, and we’ll promptly send you a refund. No questions asked.

Check out [WP All Import](https://www.wpallimport.com/?utm_source=import-plugin-free&utm_medium=readme&utm_campaign=upgrade-to-pro) today.

* **[Import users with WP All Import Pro](https://www.wpallimport.com/import-wordpress-users/?utm_source=import-plugin-free&utm_medium=readme&utm_campaign=upgrade-to-pro).**
* Need to [import XML and CSV to WooCommerce](http://www.wpallimport.com/woocommerce-product-import/?utm_source=import-plugin-free&utm_medium=readme&utm_campaign=upgrade-to-pro)? Check out our WooCommerce add-on.
* How to export WordPress users? Drag & drop to [export WordPress users](http://www.wpallimport.com/export-wordpress/?utm_source=import-plugin-free&utm_medium=readme&utm_campaign=upgrade-to-pro) (and everything else) to a custom CSV, Excel, or XML with WP All Export Pro.

== Installation ==

For an automatic installation through WordPress:

1. Go to the 'Add New' plugins screen in your WordPress admin area
1. Search for 'Import Users from CSV'
1. Click 'Install Now' and activate the plugin
1. Upload your CSV file in the 'Users' menu, under 'Import From CSV'


For a manual installation via FTP:

1. Upload the `import-users-from-csv` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' screen in your WordPress admin area
1. Upload your CSV file in the 'Users' menu, under 'Import From CSV'


To upload the plugin through WordPress, instead of FTP:

1. Upload the downloaded zip file on the 'Add New' plugins screen (see the 'Upload' tab) in your WordPress admin area and activate.
1. Upload your CSV file in the 'Users' menu, under 'Import From CSV'

== Frequently Asked Questions ==

= How to use? =

Click on the 'Import From CSV' link in the 'Users' menu, choose your CSV file, choose if you want to send a notification email to new users and if you want the password nag to be displayed when they login, then click 'Import'.

Each row in your CSV file should represent a user; each column identifies user data or meta data.

If a column name matches a field in the user table, data from this column is imported in that field; if not, data is imported in a user meta field with the name of the column.

Look at the example.csv file in the plugin directory to have a better understanding of how the your CSV file should be organized.

You can try importing that file and look at the result.

= Credits =
Thanks to Ulrich Sossou for initially creating this plugin. Be sure to [check out his other WordPress plugins](https://profiles.wordpress.org/sorich87/) or [GitHub profile](https://github.com/sorich87).

== Screenshots ==

1. User import screen

== Changelog ==

= 1.3.1 =
* bug fix: restore missing notice file

= 1.3 =
* bug fix: don't instantiate serialized objects

= 1.2 =
* BUG FIX: Ensure user running import has the ability to add users
* Update tested to
* minor UI update

= 1.1 =
* ENHANCEMENT: Added support for WPCLI (@mircobabini)
* ENHANCEMENT: Added support for multiple roles during import, use comma-separated value for multiple roles (@mircobabini)
* ENHANCEMENT: Removed password nag option as this is no longer supported.
* ENHANCEMENT: New filter added 'is_iu_errors_filter' for throwing error messages. Helpful for third party developers extending onto IUCSV functionality.

= 1.0.1 =
* Fixed timeout bug on import.
* Improved settings area layout.
* General code refactor and improved security.
* Screenshot update.

= 1.0.0 =
* Fixed bug where importing fields with "0" value doesn't work
* Added option to update existing users by username or email

= 0.5.1 =
* Removed example plugin file to avoid invalid header error on
installation

= 0.5 =
* Changed code to allow running import from another plugin

= 0.4 =
* Switched to RFC 4180 compliant library for CSV parsing
* Introduced IS_IU_CSV_DELIMITER constant to allow changing the CSV delimiter
* Improved memory usage by reading the CSV file line by line
* Fixed bug where any serialized CSV column content is serialized again
on import

= 0.3.2 =
* Fixed php notice when importing

= 0.3.1 =
* Don't process empty columns in the csv file

= 0.3 =
* Fixed bug where password field was overwritten for existing users
* Use fgetcsv instead of str_getcsv
* Don't run insert or update user function when only user ID was
provided (performance improvement)
* Internationalization
* Added display name to example csv file

= 0.2.2 =
* Added role to example file
* Fixed bug with users not imported when no user meta is set

= 0.2.1 =
* Added missing example file
* Fixed bug with redirection after csv processing
* Fixed error logging
* Fixed typos in documentation
* Other bug fixes

= 0.2 =
* First public release.
* Code cleanup.
* Added readme.txt.

= 0.1 =
* First release.

== Upgrade Notice ==

= 1.0.1 =
* Security and performance improvements.

= 0.5.1 =
* Installation error fix.

= 0.5 =
* Code improvement for easier integration with another plugin.

= 0.4 =
* RFC 4180 compliance, performance improvement and bug fix.

= 0.3 =
* Bug fix, performance improvement and internationalization.

= 0.2.2 =
* Fix bug with users import when no user meta is set.

= 0.2.1 =
* Various bug fixes and documentation improvements.

= 0.2 =
* Code cleanup. Added readme.txt.

= 0.1 =
* First release.
