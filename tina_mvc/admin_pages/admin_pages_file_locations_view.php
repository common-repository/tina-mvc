<?php
/**
* Template File: Tina MVC Wordpress admin pages - file locations.
*
* @package  Tina-MVC
* @subpackage Docs
*/
/**
 * Security check - make sure we were included by the main plugin file
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>
<div class="wrap"><br />

<h2>File Locations</h2>

<p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p>

<h3>Tina MVC Site Application Settings</h3>

<p>Tina MVC loads application settings as follows:
<ol>
<li>"user_apps/multisite/default/app_settings.php" (if it exists) is loaded first.</li>
<li>"user_apps/multisite/default/app_settings_sample.php" (distributed with Tina MVC) is loaded if "app_settings.php" is missing.</li>
<li>If multisite is enabled, and "user_apps/multisite/{$blog_name}/app_settings.php" exists.</li>
</ol>
In a multisite environment, some settings cannot be overridden by a site specific settings file, for example,
"user_apps/multisite/my_blog_name/app_settings.php". Read "user_apps/multisite/default/app_settings_sample.php"
for a full explanation of all settings.
</p>

<p><strong>NB:</strong> Application settings are only loaded when Tina MVC is activated. If you edit "app_settings.php" 
you must deactivate and activate the plugin for your changes to take effect.</p>

<h3>How Tina MVC Searches for Controllers</h3>

<p>
Tina MVC controllers can be called in several different ways. Controllers
are used for producing content for your widgets, shortcodes or for the content of
a Tina MVC page.
</p>

<h4>Tina MVC Page</h4>

<p>
A Tina MVC page acts as an entry point into your application. As mentioned, the
default installation creates a WordPress page called "Tina MVC for WordPress".
When a user visits that page, Tina MVC will look for an appropriate controller,
instantiate it and call the appropriate function. The content of the Tina MVC page
is replaced by the controller.
</p>

<p>Tina MVC pages are created when the plugin is activated. They are set up in the "app_settings.php" file.</p>

<p>It is important to understand how Tina MVC searches for controllers. Search settings can be changed in
<code>app_settings.php</code>. All paths are relative to the Tina MVC plugin folder.</p>

<p><strong>On a standard Wordpress site</strong></p>

<ol>
<li>"user_apps/multisite/default/pages/{$tina_mvc_page_slug}"</li>
<li>if Tina MVC setting "$tina_mvc_app_settings->app_cascade" is on:</em> in "user_apps/multisite/default/pages"</li>
<li>"tina_mvc/pages"</li>
</ol>

<h5><strong>On a Wordpress Multisite</strong></h5>

<ol>
<li>"user_apps/multisite/{$blog_name}/pages/{$tina_mvc_page_slug}"</li>
<li><em>if Tina MVC setting "$tina_mvc_app_settings->app_cascade" is on:</em> in "user_apps/multisite/{$blog_name}/pages"</li>
<li><em>if Tina MVC setting "$tina_mvc_app_settings->multisite_app_cascade" is on:</em> in
"user_apps/default/pages/{$tina_mvc_page_slug}"</li>
<li><em>if Tina MVC setting "$tina_mvc_app_settings->app_cascade" is on:</em> in "user_apps/default/pages"</li>
<li>"tina_mvc/pages"</li>
</ol>

<p>
This allows you to share controllers througout your application. For example,
on a Wordpress Multisite installation, you can have a common set of shortcodes and widgets
available to all your multisite users.
</p>

<p><strong>Important: </strong>It is not possible to prevent Tina MVC searching for pages in "tina_mvc/pages" if it cannot
find a controller elsewhere. This is required for basic Tina MVC functionality. If you want to prevent the core Tina MVC
controller from ever being called, create an <code>index_controller.php</code> in a location where it will be loaded in preference to
the core <code>index_controller.php</code> file.</p>

<h4>Tina MVC Widget</h4>

<p>
The Tina MVC widget searches for a controller as follows:
<ol>
<li><em>On a Wordpress Multisite:</em> in
"user_apps/multisite/{$blog_name}/widgets"</li>
<li><em>On a Wordpress Multisite with Tina MVC setting
"$tina_mvc_app_settings->multisite_app_cascade" set:</em> in
"user_apps/multisite/default/widgets"</li>
<li><em>On a normal Wordpress site:</em> in "user_apps/multisite/default/widgets"</li>
</ol>
</p>

<h4>Tina MVC Shortcode</h4>

<p>
The Tina MVC shortcode, <em>[tina_mvc]</em>,searches for a controller as follows:
<ol>
<li><em>On a Wordpress Multisite:</em> in
"user_apps/multisite/{$blog_name}/shortcodes"</li>
<li><em>On a Wordpress Multisite with Tina MVC setting
"$tina_mvc_app_settings->multisite_app_cascade" set:</em> in
"user_apps/multisite/default/shortcodes"</li>
<li><em>On a normal Wordpress site:</em> in "user_apps/multisite/default/shortcodes"</li>
</ol>
</p>

<h4>Tina MVC call_controller() function</h4>

<p>
The Tina MVC call_controller() function can be used in themes. It searches for a controller as follows:
<ol>
<li><em>On a Wordpress Multisite:</em> in
"user_apps/multisite/{$blog_name}/callable_controllers"</li>
<li><em>On a Wordpress Multisite with Tina MVC setting
"$tina_mvc_app_settings->multisite_app_cascade" set:</em> in
"user_apps/multisite/default/callable_controllers"</li>
<li><em>On a normal Wordpress site:</em> in "user_apps/multisite/default/callable_controllers"</li>
</ol>
</p>

<h3>View and Model files</h3>

<p>Tina MVC searches for view and model files in the same place as it searches for controllers.</p>

<h3>Filenames</h3>

<p>Your files should be named as follows:
<ul>
    <li>Controllers: *_controller.php</li>
    <li>Views: *_view.php</li>
    <li>Models: *_model.php</li>
</ul>
</p>