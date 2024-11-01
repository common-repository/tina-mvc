<?php
/**
 * Sample application settings.
 *
 * If you want to override these (and it is assumed that you do) then copy this file to app_settings.php
 * and customise it. If it exists it will be loaded instead of this file.
 *
 * @package    Tina-MVC
 * @author     Francis Crossen <francis@crossen.org>
 */

/**
 * Demo mode
 *
 * If enabled, a Tina MVC page called 'Tina MVC Demo & Docs' will be created. Demo code is in the 'demo' folder. This
 * setting allows Tina MVC to load MVC files from tghe 'demo' folder as well as the usual 'user_apps' folder.
 *
 * If you change this setting you must deactivate/activate Tina MVC
 */
$tina_mvc_app_settings->demo_mode = 1;
 
/**
 * Wordpress Multisite use only
 * 
 * Do you want to allow cascading of app controllers across sites?
 *
 * If you enable this, settings and apps are searched for in the user_apps/multisite/siteid folder and
 * then in user_apps/default if no suitable controller/view/model is found.
 * 
 * If this setting is not enabled, controllers will still be searched for in the tina_mvc/pages folder.
 * 
 * This setting cannot be overridden by other app_settings.php files.
 */
$tina_mvc_app_settings->multisite_app_cascade = 0;

/**
 * The following settings can all be overridden by site specific app folders in a
 * multisite environment.
 */

/**
 * Tina MVC pages. You must define these if you want a controller to be accessed using a url path.
 * 
 * If you only require widget and/or shortcode functionality then you can leave the following array empty.
 */
$tina_mvc_app_settings->tina_mvc_pages = array();

/**
 * The demo page (if enabled)
 *
 * You can leave this code here and add your pages below
 */
if( $tina_mvc_app_settings->demo_mode ) {
    $tina_mvc_app_settings->tina_mvc_pages[] = array( 'page_title'=>'Tina MVC Demo & Docs', 'page_name'=>'tina-mvc-demo-and-docs', 'page_status'=>'publish', 'menu_order'=>100 );
}

/**
 * Add your pages here
 */
$tina_mvc_app_settings->tina_mvc_pages[] = array( 'page_title'=>'Tina MVC for Wordpress', 'page_name'=>'tina-mvc-for-wordpress', 'page_status'=>'publish' );

/**
 * You can also set up child ages and set menu_order:
 *
 * Child pages are setup by specifying the complete page path for the child page e.g. 'page_name' => 'parent-page/child-page'.
 * 
 * $tina_mvc_app_settings->controllers[] = array( 'page_title'=>'A Tina MVC Child Page', 'page_name'=>'tina-mvc-for-wordpress/tina-mvc-child-page', 'page_status'=>'publish', 'menu_order'=>100 );
 */
$tina_mvc_app_settings->tina_mvc_pages[] = array( 'page_title'=>'Tina Private', 'page_name'=>'tina-private', 'page_status'=>'private' );
$tina_mvc_app_settings->tina_mvc_pages[] = array( 'page_title'=>'A Tina MVC Child Page', 'page_name'=>'tina-mvc-for-wordpress/tina-mvc-child-page', 'page_status'=>'publish', 'menu_order'=>100 );

/**
 * Do you want to allow cascading of app controllers within each site?
 *
 * For example, a controller called index_controller.php, accessed through a Tina MVC page called 'my-page', will be searched for in:
 *  - user_apps/default/pages/my_page/
 * If this setting is enabled, and index_controller.php is not found in the location above, it will be seacrhed for in:
 *  - user_apps/default/pages/index_controller.php
 */
$tina_mvc_app_settings->app_cascade = 1;

/**
 * Default role(s) to access a controller
 *
 * Valid values are:
 *  - array('role1', 'role2') or 'role1,role2',
 *  - FALSE to allow anyone to access
 *  - '' (an empty string) user must be logged in to view
 * Permissions may be overridden by an individual controller
 */
$tina_mvc_app_settings->default_role_to_view = FALSE;

/**
 * Default capabilit(y|ies) to access a controller.
 *
 * Overrides the role check. Use this for more fine grained access control
 *
 * Permission may be overridden by the controller
 * 
 * Valid values are array('role1', 'role2'); 'role1,role2', FALSE to allow anyone to access default FALSE
 * @var mixed
 */
$tina_mvc_app_settings->default_capability_to_view = FALSE;

/**
 * Custom login/logout page
 *
 * Enter the name (or ID) of a Tina MVC page to enable. (See $tina_mvc_app_settings->tina_mvc_pages). The page name should
 * be specified without the parent page. For example, 'a-child-page', not 'parent-page/a-child-page'. If the page does not exist
 * you may not be able to log into your Wordpress site, so double check this setting.
 *
 * (You can always disable a plugin by renaming it's folder name if you get stuck.)
 *
 * That page should have permissions set to view or the custom login page will not be displayed.
 *
 * Set to FALSE to disable this feature.
 *
 * @see $tina_mvc_app_settings->no_permission_behaviour
 */
$tina_mvc_app_settings->custom_login = FALSE;

/**
 * If custom login/logout page is in use, what roles are allowed to view the wp-admin backend?
 *
 * Enter a comma seperated list or array of roles.
 * 
 */
$tina_mvc_app_settings->roles_ok_for_wp_admin = 'administrator';

/**
 * If you are not using custom login functionality what happens when permission to view is denied?
 *
 * @var string 'no_permission' to show a message, 'redirect' to home or 'wp_login' to use the Wordpress login and redirect to the protected page
 */
$tina_mvc_app_settings->no_permission_behaviour = 'wp_login';

/**
 * If you use the custom login functionality, where do you want to send your users
 * on logout?
 *
 * Blank to use the page at $tina_mvc_app_settings->custom_login. FALSE to redirect to the home page
 */
$tina_mvc_app_settings->logon_redirect_target = '';

/**
 * If you use the custom login functionality, where do you want to send your users
 * on logout?
 *
 * Blank to use the page at $tina_mvc_app_settings->custom_login. FALSE to redirect to the home page
 */
$tina_mvc_app_settings->logout_redirect_target = '';

/**
 * If you wish to use the reCaptcha input type in the form helper, you must
 * set your keys.
 *
 * @see http://recaptcha.net
 */
$tina_mvc_app_settings->recaptcha_pub_key = '';
$tina_mvc_app_settings->recaptcha_pri_key = '';

/**
 * If you wish to use the Google Maps field type in the form helper, you must enter your v3 API key
 * here.
 *
 * @see https://developers.google.com/maps/documentation/javascript/tutorial#api_key
 */
$tina_mvc_app_settings->google_api_v3_key = '';

/**
 * Default latitude, longitude and zoom for the Google Maps field type
 */
$tina_mvc_app_settings->google_maps_default_location = '53.3406,-6.2752,6';

/**
 * Do you want to enable page_bootstrap functions?
 *
 * Each bootstrap function should be in its' own file (with the same name as the function) in the folder
 * tina_mvc_bootstrap/.
 * 
 * e.g. myBootstrapFuncts() in file  myBootstrapFuncts.php
 * 
 * Page bootstrap functions are executed for every Tina MVC page.
 */
$tina_mvc_app_settings->tina_mvc_bootstrap = 0;

/**
 * These are run on the init action hook. Place your function
 * in a file of the same name in init_bootstrap.
 * 
 * e.g. myInitBootstrapFuncts() in file  myInitBootstrapFuncts.php
 *
 * Bootstrap functions are executed for every Wordpress page (not just the Tina MVC pages).
 */
$tina_mvc_app_settings->init_bootstrap = 0;

/**
 * Used with the front end page controllers. You should define these if you want a public (or private) page to act as a front end controller.
 * If you only require widget and/or shortcode functionality then leave $tina_mvc_pages = array() i.e. an empty array
 *
 * @var $tina_mvc_missing_page_controller_action string 'display_error' displays a missing controller error, 'display_404'
 * shows the Wordpress '404' or 'redirect' to go home.
 *
 * You should use 'display_404' or 'redirect' for production.
 *
 * When widgets and shortcodes just return blank content
 */
$tina_mvc_app_settings->missing_page_controller_action = 'display_error'; 

/**
 * Do you want to disable wpautop() on Tina MVC pages?
 *
 * This Wordpress function can play havock with your view files. If you are happy with the markup in your templates then
 * enable this feature to prevent unexpected messing with your code.
 */
$tina_mvc_app_settings->disable_wpautop = 1;

/**
 * Do you want to use the hourly cron function?
 *
 * This is a wrapper to WP cron. To use, enable this setting and create a file in the hourly_cron folder. Place your
 * functionality in a function with the same name as the filename.
 * 
 * e.g. my_cron_functions() in file  my_cron_functions.php
 */
$tina_mvc_app_settings->enable_hourly_cron = 0;

/**
 * You can put arbitrary data here
 *
 * It is stored in the wp_options table and autoloaded. Use an array, object, whatever you want
 */
$tina_mvc_app_settings->user_data = new stdClass;
