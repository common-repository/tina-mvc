=== Plugin Name ===
Author: Francis Crossen (fcrossen)
Contributors: Francis Crossen (fcrossen)
License: GPLv2 or later
Donate link: http://seeit.org/tina-mvc-for-wordpress
Tags: plugin, widget, mvc, shortcode, development, framework, helper
Requires at least: 3.5
Tested up to: 3.5.1
Dev version: 1.0.14
Stable tag: 1.0.13

Tina MVC is a Wordpress framework that allows you to develop plugins, shortcodes and and widgets.

== Description ==

Tina MVC provides you with base classes and helper classes and functions on which you build your Wordpress applications.

It uses a lose model view controller pattern to abstract design and logic and make life easier for you and your HTML designer.

### Features:
* Completely separate your code from Wordpress themes. Your users can change their theme and still retain your application functionality.
* A helper class for generating and processing HTML forms.
* A helper class for generating paginated tables from SQL (for when custom posts are not appropriate).
* A helper class for generating HTML tables from your data.
* Separation of your code from core Tina MVC files for easy upgrades.
* Compact and non-intrusive. Currently 3 filters plus 1 action hook for widgets and 1 shortcode hook are used for basic usage.
* A function to allow you to call a Tina MVC controller from your theme file (breaks the MC) or from another controller.
* Flexible enough for quick procedural prototyping - don't like MVC? No problem!

### Tutorials, Documentation and Code Samples
All Tina MVC documentation is included with the plugin. After activating the plugin you can access it from the Wordpress admin back end. (Look for the Tina MVC administration page.)
Source code is liberally commented for PhpDocumentor.

### License
This version is GPL v2 licensed. If you are interested in alternative licensing models, or in commercial support, please contact the author at http://www.seeit.org/about-us/.

### Support
Support for this version is available at http://wordpress.org/tags/tina-mvc?forum_id=10 or by leaving a comment at http://www.seeit.org/tina-mvc-for-wordpress/.

== Installation ==

1. Install the usual way (if you can't manage that then this plugin isn't for you)
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the `Tina MVC` menu in the Wordpress administration back end for documentation
1. Navigate to the newly created `Tina MVC for Wordpress` page on your Wordpress site

== Known Issue(s) ==

Tina MVC support for permalinks based on post name is experimental. Currently it generates a PHP warning from a core Wordpress file.

== Upgrade Notice ==

The automatic upgrade feature will not work if your web server cannot write to the <code>wp-content</code> folder. * Always backup your apps. * 
You can upgrade manually:

1) Download and unzip; 2) Deactivate the plugin through the 'Plugins' menu in WordPress; 3) Backup the `user_apps` folder and `tina_mvc_app_settings.php`
and any other files and folders you created; 4) Delete the contents of the `tina-mvc` folder and copy back your backed up files; 5) Compare the new `app_settings_sample.php` file with your `app_settings.php` file and merge any changes; 
6) Reactivate the plugin

== Frequently Asked Questions ==

= What is Tina MVC used for? =

It is used for all my Wordpress development at this stage. Wordpress became my development platform of choice in early 2010 and Tina MVC allows me to develop client sites as Wordpress plugins.

= Does Tina MVC work with Wordpress Multisite? =

Yes. You can have all sites use the same application files, or have different application files for each blog within your network.

= My changes to <code>tina_mvc_app_settings.php</code> are being ignored... =

The <code>app_settings.php</code> file is only checked on plugin activation. Deactivate/reactivate the plugin to load the new settings.

= Can Tina MVC create posts on activation like it does pages? =

Yes. Put your code into <code>install_remove/tina_mvc_app_install.php</code>. Tina MVC will include it on activation and call the function <code>tina_mvc_app_install()</code>.

= Can my current controller use a view from inside another controllers folder? =

Yes. Just specify the view file name you want to use when you use the $this->load_view() function. You can also specify a custom location for your view file:
<code>$this->load_view( 'my_view_file' , FALSE , 'path/to/view/file/from/tina-mvc/folder' )</code>

= Why are there HTML template files in the plugin folder? =

Tina MVC applications are designed to be completely independent of the theme you use. The templates only format the post/page/shortcode/widget content.
You should be able to use any theme you want with a deployed Tina MVC application. This allows you to crack on with development while your designer (if there is one) deals with the theme.
Of course there is nothing to stop you putting code and calls to tina_mvc_call_page_controller() in your templates if you prefer, but it is not recommended.

= I'd love to see `insert feature here` in Tina MVC. How can I request this? =

See `Where can I get support?` below and just ask.

= Where can I get support? =

The 'Plugins and Hacks' forum (http://wordpress.org/tags/tina-mvc?forum_id=10) or at http://www.seeit.org/tina-mvc-for-wordpress/.

== Changelog ==

= 1.0.14 =
 * Minor enhancement: tina_mvc_controller_class->parse_view_file() now checks for non-array view data being passed to it
 * Minor bigfix: incorrect (but not broken) use of shortcode_atta() in $Tina_MVC->do_shortcode()
 * Minor enhancement: helper functions user_has_role() and user_has_capability() now convert arguments to lower case
 * Major enhancement: file uploads are now handled properly by the form helper
 * Minor enhancement: tina_mvc_print_r() no longer takes the variable parameter by reference
 * Minor bugfix: Tina MVC was not searching for the application folder in a useful way
 * Major enhancement: Proper multi-site support 
 * Major bugfix: get_multisite_blog_name() was not returning the correct blog name in certain circumstances
 * Major bugfix: multisite_app_cascade feature was not working properly when set
 * Minor enhancement: the load_view() function will look for a view file automatically if none is specified= 1.0.13 =
 * Minor enhancement: made $field->set_options() more robust in the form helper
 * Minor bugfix: load_view() function was not searching for files as advertised when using a custom view file location
 * Major enhancement: New add_content() and add_raw_content() functions to allow passing HTML directly to the page without a view file

= 1.0.12 =
 * Minor bugfix: fixed error checking in table helper set_data() function
 * Minor enhancement: added a render() methid to the pagination and table helpers (for consistency with other helpers)
 * Major enhancement: added a check to the wp_query_render() method to the pagination and table helpers (for consistency with other helpers)
 * Major enhancement: $Tina_MVC->wp_query_checker() returns immediately if it is not the main query
 * Minor enhancement: Prevent a logged in user from accessing register and reset password registration functions
 * Major enhancement: Added a link to view/edit 'my profile', added get_my_profile_link() function
 * Minor enhancement: disable autocomplete for password form field type
 * Minor enhancement: added $field->get_posted_value() function and edit the sample controllers
 * Minor bugfix: setting $tina_mvc_app_settings->roles_ok_for_wp_admin was being ignored

= 1.0.11 =
 * Minor enhancement: Prevent canonical redirects on Tina MVC pages
 * Minor bugfix: $Tina_MVC->wp_query_checker() was not detecting a Tina request properly when $_SERVER['REQUEST_URI'] was unset
 * Minor bugfix: in include_helper() to prevent the same file being included twice
 
= 1.0.10 =
 * Trivial bugfix: TINA_MVC\error() was reporting an incorrect file path
 * Minor bugfix: in tina_mvc_controller_class::include_model() return value
 * Minor bugfix: added error checking to $table->set_data()
 * Minor bugfix: pagination helper now returns '' for an empty SQL result set
 * Major bugfix: fixed a form helper bug that was rendering malformed HTML
 * Minor bugfix: fixed a bug in get_controller_url() (when no permalinks are in use)
 * Minor bugfix: removed get_currentuserinfo() call from user_has_role() and user_has_capability(). Was causing problems with some APC configurations
 
= 1.0.9 =
 * Fixed a bug that was causing init_bootstrap functions to fail

= 1.0.8 =
 * added a 'file' input type to the form helper for file uploads
 * $Tina_MVC->mail() accepts a string for $message_variables. (Allows use of mail() without using a template file.)

= 1.0.7 =
 * fixed bug where permissions for widgets/shortcodes were not being merged properly.

= 1.0.6 =
 * added setting to disabled he Wordpress admin bar for some or all users
 
= 1.0.5 =
 * renamed helpers to be more consistent, added TINA_MVC\include_helper() function
 * added support for permalinks based on %postname% only (experimental)
 * public release
 * updated todo admin page
 
= 1.0 =
 * beta release of Tina MVC (branch 2)

= 0.9 =
 * first beta release for Tina MVC 1.0 based on 0.4.15
