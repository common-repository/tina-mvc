<?php
/**
* Template File: Tina MVC Wordpress admin pages - using the custom login functionality.
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

<h2>Custom Login Functionality</h2>

<p>If you want to hide the Wordpress login and registration pages from your users, you can use these custom login. It prevents them from ever
seeing the Wordpress back-end if you wish. This is configured from the <code>app_settings.php</code> file.</p>

<h2>Custom Login Settings in app_settings.php</h2>

<h3>$tina_mvc_app_settings->custom_login</h3>

<p>This enables the Tina MVC login, registration and password reset pages.</p>

<p>You must enter the name (or page ID) of a Tina MVC page to enable this feature. (See <code>$tina_mvc_app_settings->tina_mvc_pages</code>).
The page name must be specified without the parent page. For example, 'a-child-page', not 'parent-page/a-child-page'. If the page does not exist 
you may not be able to log into your Wordpress site, so make sure this setting is correct. (You can always disable a plugin by renaming
it's folder name if you get stuck.)</p>

<p>The page must have permissions set to view it or the custom login page will not be displayed.</p>

<p>See also <code>$tina_mvc_app_settings->roles_ok_for_wp_admin</code>.</p>

<p>Set to <code>FALSE</code> to disable this feature.</p>

<p>See <code>$tina_mvc_app_settings->no_permission_behaviour</code> for more information.</p>

<h3>$tina_mvc_app_settings->roles_ok_for_wp_admin</h3>

<p>A comma separated list of roles that are permitted to access <?= site_url('wp-admin') ?>. Other users will be redirected to the home page.</p>

<p>If you use this functionality you must also allow your users to view and edit their profile. Tina MVC provides this functionality in the
<code>my_profile_controller.php</code> controller. It is located in the <code>tina_mvc/pages</code> directory. The view file <code>index_index_view.php</code>
links to it. You can copy that file into your own <code>pages</code> folder and use it as a base for your own plugin.</p>

<h3>$tina_mvc_app_settings->no_permission_behaviour</h3>

<p>If you are not using custom login functionality, how should Tina MVC behave if a user does not have permission to view a page? Valid
options are: 'no_permission' to show a message, 'redirect' to home or 'wp_login' to use the standard Wordpress login functionality
and redirect to the protected page.</p>

<h3>$tina_mvc_app_settings->logon_redirect_target</h3>

<p>If you use the custom login functionality, where do you want to send your users on logout? This page must be a Tina MVC page and specified
in the same way as for <code>$tina_mvc_app_settings->custom_login</code>. Leave this setting empty (or set to <code>FALSE</code> to use the
same page specified by <code>$tina_mvc_app_settings->custom_login</code>.</p>

<h3>$tina_mvc_app_settings->logout_redirect_target</h3>

<p>If you use the custom login functionality, this setting controls where to send users on logout. See <code>$tina_mvc_app_settings->custom_login</code>. Leave this setting empty (or set to <code>FALSE</code>
to direct users to the home page on logout.</p>

</div>