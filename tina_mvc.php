<?php
/*
Plugin Name: Tina MVC
Plugin URI: http://seeit.org/tina-mvc-for-wordpress/
Description: Tina MVC is a Wordpress development framework that allows you to develop plugins, shortcodes and and widgets. PHP 5.3+ & Wordpress 3.5+.
Author: Francis Crossen <francis@crossen.org>
Version: 1.0.13
Author URI: http://SeeIT.org/
License: GPL v2
*/
/*
Copyright 2010 - 2013 Francis Crossen (email: francis@crossen.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Used to prevent upgrading to a non-backwards compatible version.
 */
define( 'TINA_MVC_VERSION', '1.0110' );

/**
 * The main Tina MVC plugin file.
 *
 * Tina MVC provides you with base classes and helper classes and functions on
 * which you build your Wordpress applications.
 *
 * It uses a lose model view controller pattern to abstract design and logic
 * and make life easier for you and your HTML designer.
 * 
 * Tina controllers are accessed through any of:
 *  - a Tina MVC Wordpress page
 *  - a Tina MVC widget
 *  - a Tina MVC shortcode
 *  - the function $Tina_MVC->tina_mvc_call_page_controller() function from your theme file (or even another page controller)
 *
 *  Tina MVC includes helper functions and classes:
 *   - a form helper for creating, displaying and processing HTML forms
 *   - a pagination helper for producing paginated lists from your custom SQL
 *   - a HTML table helper
 *   - general functions to help streamline your development efforts
 *
 * PHP 5.3+ & Wordpress 3.5+. Earlier versions of Wordpress may work, but have not been tested.
 *
 * @package    Tina-MVC
 * @subpackage Core
 * @author     Francis Crossen <francis@crossen.org>
 * @copyright  Francis Crossen <francis@crossen.org>
 * @license    GPL2
 */

/**
 * Sets the path to the Tina MVC folder
 */
define( 'TINA_MVC_PLUGIN_DIR', dirname( __FILE__ ) );

/**
 * Includes the various Tina MVC files.
 */
include( TINA_MVC_PLUGIN_DIR . '/tina_mvc/helpers/tina_mvc_functions.php' );
include( TINA_MVC_PLUGIN_DIR . '/tina_mvc/base_classes/tina_mvc_base_classes.php' );

/**
 * The main plugin class
 *
 * Checks the wordpress query for a 404 and checks for a Tina MVC page controller
 * 
 * @package    Tina-MVC
 * @subpackage Core
 */
class Tina_MVC {
    
    public $default_role_to_view, $default_cap_to_view;
    
    /**
     * Sets up the WP hooks
     */
    public function __construct() {
        
        /**
         * For dev only...
         */
        // ini_set('error_reporting',E_ALL);
        
        add_shortcode( 'tina_mvc', array($this,'shortcode') );
        add_filter( 'parse_query', array($this,'wp_query_checker') );
        add_filter( 'the_posts', array($this,'posts_filter') );
        add_action( 'widgets_init', array($this,'setup_widget') );
        add_filter( 'the_content', array($this,'setup_shortcode'), 7 );
        if ( TINA_MVC\get_tina_mvc_setting('init_bootstrap') ) {
            add_action( 'init', array($this,'do_init_bootstrap_funcs') );
        }
        
        /**
         * Following are only required for Administration panel
         */
        if ( is_admin() ) {
            
            include_once( TINA_MVC\tina_mvc_folder() . '/admin_pages/admin_functions.php' );
            
            new TINA_MVC\utils\admin_options_page;
            
            register_activation_hook( __FILE__, array( $this, 'plugin_install') );
            register_deactivation_hook( __FILE__, array( $this, 'plugin_remove') );
            
            add_filter( 'upgrader_pre_install', array($this,'hpt_backup'), 10, 2 );
            add_filter( 'upgrader_post_install', array($this,'hpt_recover'), 10, 2 );
            
        }
        
        /**
         * Following are only valid if Tina MVC is active
         */
        if( get_option('tina_mvc_plugin_active') ) {
            
            // are we using custom login?
            if( TINA_MVC\get_tina_mvc_setting('custom_login') ) {
                
                add_action( 'login_init', array( $this, 'tina_mvc_login' ) );
                add_action( 'wp_logout', array( $this, 'redirect_logout' ) );
                
            }
            
            if( TINA_MVC\get_tina_mvc_setting('roles_ok_for_wp_admin') ) {
                add_action( 'admin_init', array($this,'restrict_wp_admin') );
            }
            
            // custom cron?
            if( TINA_MVC\get_tina_mvc_setting('enable_hourly_cron') ) {
                add_action('tina_mvc_cron_hook', array( $this,'hourly_cron') );
            }
            
            // wp_admin bar?
            $admin_bar_setting = TINA_MVC\get_tina_mvc_setting('wp_admin_bar');
            if( $admin_bar_setting === FALSE OR ($admin_bar_setting AND ! TINA_MVC\user_has_role( $admin_bar_setting )) ) {
                show_admin_bar( FALSE );
            }
            
        }
        
        $this->default_role_to_view = TINA_MVC\get_tina_mvc_setting('default_role_to_view');
        $this->default_cap_to_view = TINA_MVC\get_tina_mvc_setting('default_capability_to_view');
        
    }
    
    /**
     * Installs or upgrades the plugin on activation
     *
     * Takes settings from 'tina_mvc_app_settings_sample.php' or 'tina_mvc_app_settings.php'
     * and creates Wordpress pages and options accordingly.
     * 
     * This function will be run whenever the plugin is upgraded AFTER any saved settings
     * have been copied back to the tina-mvc plugin folder. Check the $tina_mvc_upgrade_backups
     * variable in the settings file
     *
     * @param boolean $upgrading set to true if function is called during plugin upgrade
     *
     * @see  tina_mvc_app_settings_sample.php
     * @uses TINA_MVC\utils\plugin_activate()
     */
    public function plugin_install( $upgrading = FALSE ) {
        
        TINA_MVC\utils\plugin_activate( $upgrading );
        
    }
    
    /**
     * Removes the plugin on deactivation
     *
     * @uses TINA_MVC\utils\plugin_remove()
     */
    public function plugin_remove() {
        
        TINA_MVC\utils\plugin_remove();
        
    }
    
    /**
     * Sets up the Tina MVC widget
     */
    public function setup_widget() {
        register_widget( 'tina_mvc_widget' );
    }
    
    /**
     * Restricts access to /wp-admin
     */
    public function restrict_wp_admin() {
        if( ! TINA_MVC\user_has_role( TINA_MVC\get_tina_mvc_setting('roles_ok_for_wp_admin') ) ) {
            wp_redirect( site_url() );
            exit();
        }
    }
    
    /**
     * Checks the hourly_cron folder in a users application folder and parses files for cron functions.
     *
     * If you want to use something other than hourly crons, either add a check in your cron function to check the
     * last time the function was run or set up your own custom cron in an init_bootstrap function.
     */
    public function hourly_cron() {
        
        $files = scandir( ($folder = TINA_MVC\app_folder().'/hourly_cron') );
        
        foreach ( $files AS $file ) {
            
            $file = strtolower( $file );
            if ( !in_array( $file, array(
                 '.',
                '..',
                'index.php' 
            ) ) AND ( strpos( $file, '.php' ) !== FALSE ) ) {
                
                include( "$folder/$file" );
                
                $function = str_replace( '.php', '', $file );
                if( function_exists($function) ) {
                    call_user_func( $function );
                }
                
            }
            
        }
    }
    
    /**
     * Forces use of the Tina MVC login framework. Prevents a user from ever seeing a Wordpress backend.
     *
     * This works by directing to a Tina MVC page. If there are no permissions to view on that page
     * the user will not see a login form. Make sure your login page is protected to avoid this
     * issue.
     *
     * The Tina MVC setting 'logon_redirect_target' is used to direct users to a Tina MVC page. If it is
     * empty this finds the first available page Tina MVC page (which may not make any premissions to view).
     */
    public function tina_mvc_login() {
        
        if( TINA_MVC\get_getpost('action') !== 'logout' ) {
            
            $login_page = TINA_MVC\get_tina_mvc_setting('custom_login');
            
            if( ! ( $tina_mvc_pages = TINA_MVC\get_tina_mvc_setting('tina_mvc_pages') ) ) {
                TINA_MVC\error('You must define at least one Tina MVC page to use the custom login functionality. See the $tina_mvc_app_settings->tina_mvc_pages setting.');
            }
            
            if( ! array_key_exists( $login_page, $tina_mvc_pages ) ) {
                TINA_MVC\error('The page in $tina_mvc_app_settings->custom_login does not exist or is not a Tina MVC page.');
            }
            
            wp_safe_redirect( site_url().'?p='.$tina_mvc_pages[$login_page]['page_id'] );
            exit();
            
        }
        
    }
    
    /**
     * Redirect on logout
     */
    public function redirect_logout() {
        
        if( is_user_logged_in() ) {
            
            $target = TINA_MVC\get_tina_mvc_setting('logout_redirect_target');
            
            if( $target === '' ) {
                $target = TINA_MVC\get_tina_mvc_setting('custom_login');
            }
            elseif( $target === FALSE ) {
                wp_redirect( site_url() );
                exit();
            }
            
            if( ! array_key_exists( $target, ($tina_pages=TINA_MVC\get_tina_mvc_setting('tina_mvc_pages')) ) ) {
                TINA_MVC\error('Page in $tina_mvc_app_settings->logon_redirect_target is not a Tina MVC page.');
            }
            
            wp_safe_redirect( site_url().'?p='.$tina_pages[$target]['page_id'] );
            exit();
            
        }
        
    }
    
    /**
     * Checks permalinks and returns an appropriate redirect url
     *
     * @param string $t the page name or path
     * @return string the url after checking permalinks
     */
    private function get_redirect_target( $t='' ) {
        
        if ( get_option( 'permalink_structure' ) ) {
            $t = TINA_MVC\get_controller_url( $t, true );
        } else {
            // we need to find the page id...
            if( $page = get_page_by_path( $t ) ) {
                $t = '?page_id=' . $page->ID;
            }            
        }
        
        if( ! $t ) {
            return site_url();
        }
        
        return $t;
        
    }
    
    /**
     * Checks the $wp_query object for a call to a Tina MVC page controller
     * @param object $q wp_query object
     * @return object wp_query
     */
    public function wp_query_checker( $q ) {
        
        if( ! $q->is_main_query() ) {
            return $q;
        }
        
        $tina_pages = TINA_MVC\get_tina_mvc_setting('tina_mvc_pages');
        
        /**
         * Used to prevent multiple runs of this controller (for example if a widget or shortcode calls it)
         *
         * It is set TRUE for a page request and set FALSE when the request is complete in the posts_filter()
         * function
         *
         * @see $this->posts_filter()
         */
        global $tina_mvc_page_request_active;
        
        /**
         * Checks if permalinks are enabled
         */
        if ( ! empty( $q->query_vars['page_id'] ) AND ( array_key_exists( $q->query_vars['page_id'], $tina_pages ) ) ) {
            
            /**
             * Not using permalinks
             */
            $tina_mvc_page_request_active = TRUE;
            
            if( ! ($tina_mvc_request = TINA_MVC\get_get('tina_mvc_request')) ) {
                $tina_mvc_request = $tina_pages[$q->query_vars['page_id']]['tina_mvc_request'];
            }
            
            // is there a leading slash?
            if ( $tina_mvc_request AND $tina_mvc_request[0] == '/' ) {
                $tina_mvc_request = substr( $tina_mvc_request, 1 );
            }
            
            if( ! defined('TINA_MVC_PAGE_CONTROLLER_ID') ) {
                define( 'TINA_MVC_PAGE_CONTROLLER_ID' , $q->query_vars['page_id'] );
            }
            
            $q->set( 'tina_mvc_request', $tina_mvc_request );
            
            return $q;
            
        }
        elseif( isset( $_SERVER['REQUEST_URI'] ) ) {
            
            $request_uri = $_SERVER['REQUEST_URI'];
            
            /**
             * If there is no trailing slash, add it. Makes logic for sniffing Tina MVC requests more consistent.
             */
            if( substr( $request_uri, -1 ) !== '/' ) {
                $request_uri .= '/';
            }
            
            if( is_multisite() AND ( ! defined(SUBDOMAIN_INSTALL) OR ! SUBDOMAIN_INSTALL ) ) {
                //lose the site name from the path...
                $request_uri = str_replace( tina_mvc\get_multisite_blog_name().'/', '', $request_uri );
            }
            
            if ( ( array_key_exists( ( $tina_mvc_request=ltrim($request_uri,'/') ), $tina_pages ) ) OR ( ( $_sl_pos = strpos( $tina_mvc_request, '/' ) ) AND ( array_key_exists( ( $this_controller_name = substr( $tina_mvc_request, 0, $_sl_pos ) ), $tina_pages )) ) ) {
                
                // lose any GET vars from the page request
                if( ($_q = strpos( $tina_mvc_request, '?' )) !== FALSE ) {
                    $tina_mvc_request = substr( $tina_mvc_request, 0, $_q );
                }
                
                /**
                 * Using permalinks with or without the full page path
                 * i.e. sniffed from $_SERVER['REQUEST_URI']
                 */
                if( ! isset( $this_controller_name ) ) {
                    $this_controller_name = substr( $tina_mvc_request, 0, strpos( $tina_mvc_request, '/' ) );
                }
                
                if( ! defined('TINA_MVC_PAGE_CONTROLLER_NAME') ) {
                    define('TINA_MVC_PAGE_CONTROLLER_NAME',$this_controller_name);
                }
                
                $tina_mvc_page_request_active = TRUE;
                $tina_mvc_request = rtrim( $tina_mvc_request, '/' );
                $q->set( 'tina_mvc_request', $tina_mvc_request );
                
                return $q;
                
            }
            
        }
        elseif ( ( array_key_exists( $q->query_vars['pagename'], $tina_pages ) ) OR ( ( $_sl_pos = strpos( $q->query_vars['pagename'], '/' ) ) AND ( array_key_exists( ( $this_controller_name = substr( $q->query_vars['pagename'], 0, $_sl_pos ) ), $tina_pages ) ) ) ) {
            
            /**
             * Using permalinks and $_SERVER['REQUEST_URI'] is not set
             * i.e. sniffed from $q->query_vars['pagename']
             */
            if( ! isset( $this_controller_name ) ) {
                $this_controller_name = $q->query_vars['pagename'];
            }
            
            define('TINA_MVC_PAGE_CONTROLLER_NAME',$this_controller_name);
            $tina_mvc_page_request_active = TRUE;
            
            $tina_mvc_request = $q->query_vars['pagename'];
            $tina_mvc_request = rtrim( $tina_mvc_request, '/' );
            $q->set( 'tina_mvc_request', $tina_mvc_request );
            
            return $q;
            
        }
        else {
            
            /**
             * Not a Tina MVC page
             */
            $q->set( 'tina_mvc_request', FALSE );
            // $q->set( 'page_id', $q->query_vars['page_id'] ); // redundant?
            
            return $q;
        }
        
    }
    
    /**
     * Posts filter to detect a call to our controller and to pass control to it
     *
     * Checks $wp_query->get('tina_mvc_request') and if set triggers a call to
     * the Tina MVC framework. Sets the Wordpress post_title and post_content.
     * The (object) $wp_query is previously marked tina_mvc_query_parser()
     * to flag a call to our controller
     *
     * @param   array $posts A single element array of posts. This can be empty for pages like 'tina/some-page'
     * @return  array $posts
     * @uses tina_mvc_check_bootstrap_funcs()
     */
    function posts_filter( $posts ) {
        
        global $wp_query;
        
        if ( TINA_MVC\get_tina_mvc_setting( 'tina_mvc_bootstrap' ) ) {
            $this->do_bootstrap_funcs();
        }
        
        /**
         * Detect if we already have an active request.
         *
         * A page request can only be run once per request. Shortcodes and Widgets can run
         * multiple times per request
         *
         * @see $this->wp_query_checker()
         */
        global $tina_mvc_page_request_active;
        
        if ( $tina_mvc_page_request_active ) {
            
            // $tina_mvc_request=$wp_query->get( 'tina_mvc_request' );
            
            if ( TINA_MVC\get_tina_mvc_setting( 'disable_wpautop' ) ) {
                remove_filter( 'the_content', 'wpautop' );
            }
            
            /**
             * Prevent canonical redirects on Tina MVC pages
             */
            remove_action('template_redirect', 'redirect_canonical');
            
            /**
             * $posts will be empty for a controller/subcontroller/whatever call. If so this generates one
             */
            if ( !$posts ) {
                if( defined('TINA_MVC_PAGE_CONTROLLER_NAME') ) {
                    $posts = array( get_page_by_path( $_id=TINA_MVC_PAGE_CONTROLLER_NAME ) );
                }
                else {
                    $posts = array( get_page( $_name=TINA_MVC_PAGE_CONTROLLER_ID ) );
                }
            }
            
            // in case we have a permalink based on %postname% only...
            // query will be on e.g. /tina_page/controller/method... controller and method don't exist as
            // wp pages and the wp_query object will be set up as a 404
            $wp_query->query_vars['error'] = FALSE;
            $wp_query->is_404 = FALSE;
            
            $APP = new TINA_MVC\tina_mvc_page_class( $wp_query->get( 'tina_mvc_request' ), $this->default_role_to_view, $this->default_cap_to_view, $called_from = 'PAGE_FILTER' );
            
            /**
             * Var to prevent multiple page controllers running at the same time
             *
             * Set in $this->wp_query_checker()
             *
             * Not in use...
             */
            $tina_mvc_page_request_active = FALSE;
            
            /**
             * @todo check this statement
             *
             * if we have no app, we unset the posts and allow WP to use whatever 404 page it can find... 
             * this check should now be redundant since we are managing missing page_controllers in the tina_mvc_get_instance_of() function
             * 
             */
            if ( isset($APP) and ! $APP->is_404() ) {
                // we only have one post at $posts[0]
                if ( $APP->get_post_title() ) {
                    $posts[0]->post_title = $APP->get_post_title();
                }
                $posts[0]->post_content = $APP->get_post_content();
                
            } else {
                // $posts = array();
                $wp_query->is_404 = TRUE;
                $wp_query->post_count = 0;
            }
            
        }
        
        // tina_mvc\log( __LINE__ );
        
//        tina_mvc\prd( $posts );
        
        return $posts;
        
    }

    /**
     * Directly calls a Tina MVC controller.
     *
     * This can be used from within your own app or in a template file in your theme
     * 
     * @param   string $controller the page controller to call (you must also specify the Tina MVC page if your controller resides there)
     * @param   mixed $role_to_view a (string) role, (string) comma seperated list or (array) of roles
     * @param   string $capability_to_view a (string) cap, (string) comma seperated list or (array) of caps
     * @param   string $custom_folder specify location of controller file
     * 
     * @return  string the page content from the controller
     */
    public static function call_controller( $controller, $role_to_view=FALSE, $capability_to_view=FALSE, $custom_folder='' ) {
        
        /**
         * For compatibility with shortcde calls and with old Tina MVC conventions
         */
        if( $role_to_view == '-1' ) {
          $role_to_view = FALSE;
        }
        
        $APP = new TINA_MVC\tina_mvc_page_class( $controller, $role_to_view, $capability_to_view, $called_from='CALLABLE_CONTROLLER', $custom_folder ); // use shortcode here... same difference
        
        return $APP->get_post_content();
        
    }
    
    /**
     * Checks if we have any bootstrap scripts to be run
     *
     * Scripts live in the user_apps folder in bootstrap. Every PHP file (except index.php) in the directory
     * (but not subdirectories), is included and a PHP function named the same as the filename
     * is called.
     *
     * This allows you to do things like use wp_enqueue_script() with shortcodes and
     * widgets, or to use other Wordpress action hooks. The functions will be called on
     * every page load, not just with Tina MVC pages, so use sparingly.
     *
     * This feature can be disabled in tina_mvc_app_settings.php
     *
     * @param integer $check_init_bootstrap set to true to check the app_init_bootstrap folder instead
     */
    public function do_bootstrap_funcs( $check_init_bootstrap = FALSE ) {
        
        if ( $check_init_bootstrap ) {
            $folder = TINA_MVC\app_folder().'/init_bootstrap';
        } else {
            $folder = TINA_MVC\app_folder().'/tina_mvc_bootstrap';
        }
        $files = scandir( $folder );
        
        foreach ( $files AS $file ) {
            
            $file = strtolower( $file );
            if ( !in_array( $file, array(
                 '.',
                '..',
                'index.php' 
            ) ) AND ( strpos( $file, '.php' ) !== FALSE ) ) {
                
                include( "$folder/$file" );
                
                $function = str_replace( '.php', '', $file );
                if( function_exists($function) ) {
                    call_user_func( $function );
                }
                
            }
            
        }
        
    }

    /**
     * Checks is there are init bootstrap actions to be run
     *
     * @uses $this->do_bootstrap_funcs()
     */
    public function do_init_bootstrap_funcs() {
        $this->do_bootstrap_funcs( TRUE );
    }
    
    /**
     * Wrapper function - sets up the 'tina_mvc' shortcode
     *
     * Function is wrapped to prevent wp_texturize() messing with the content
     *
     * @param string $content The content in an enclosing shortcode
     * 
     * @return string The processed content
     * 
     * @see http://www.viper007bond.com/2009/11/22/wordpress-code-earlier-shortcodes/
     */
    public function setup_shortcode( $content ) {
        
        global $shortcode_tags;
        
        // Backup current registered shortcodes and clear them all out
        $saved_shortcode_tags = $shortcode_tags;
        $shortcode_tags = array();
        
        add_shortcode( 'tina_mvc', array($this,'do_shortcode') );
        
        // Do the shortcode (at this stage only the tina_mvc shortcode is registered)
        $content = do_shortcode( $content );
        
        // Put the original shortcodes back
        $shortcode_tags = $saved_shortcode_tags;
        
        return $content;
        
    }
    
    /**
     * Parses the Tina MVC shortcodes
     *
     * Shortcodes may be self-closing or enclosing. At least the controller parameter 'c' is required:
     * <code>[tina_mvc c="my-controller/my-method"]</code>
     *
     * Complete parameters:
     *  - 'c' => The controller to call
     *  - 'role' => role to view (enter '-1' for boolean FALSE to allow anyone to view)
     *  - 'cap' => capability to view
     * 
     * @param   array $attributes the shortcode attributes. $attributes['c'] is required
     * @param   string $content the content of an enclosing shortcode
     * @return  string The pre-escaped HTML to display
     */
    public function do_shortcode( $attributes, $content = '' ) {
        
        $atts = shortcode_atts( array('c'=>'', 'role'=>$this->default_role_to_view, 'cap'=>$this->default_cap_to_view ), $attributes );
        
        
        if( ! $atts['c'] ) {
            return '<em><strong>Shortcode error:</strong> the parameter \'c\' is required.</em>';
        }
        
        // check for -1 being passed to us by the shortcode call
        if ( $atts['role'] == '-1' ) {
            $atts['role'] = FALSE;
        }
        if ( $atts['cap'] == '-1' ) {
            $atts['cap'] = '';
        }
        
        $role_to_view = TINA_MVC\merge_permissions( $this->default_role_to_view, $atts['role'] );
        $capability_to_view = TINA_MVC\merge_permissions( $this->default_cap_to_view, $atts['cap'] );
        
        // global $tina_mvc_page_request; // flags a page request, NOT a widget or shortcode request
        // $tina_mvc_page_request = FALSE;
        $APP = new TINA_MVC\tina_mvc_page_class( $atts['c'], $role_to_view, $capability_to_view, $called_from = 'SHORTCODE', $custom_folder = '', $content );
        
        // TINA_MVC\pr( $APP );
        
        $ret = $APP->get_post_content();
        
        if ( ! $ret ) {
            return $content;
        } else {
            return $ret;
        }
        
    }

    /**
     * Backup files before plugin upgrade
     *
     * Thanks to Clay Lua (http://hungred.com) for illustrating the technique
     *
     * @uses tina_mvc_admin_hpt_backup()
     */
    function hpt_backup() {
        TINA_MVC\utils\hpt_backup();
    }
    
    /**
     * Recover files after plugin upgrade
     *
     * Thanks to Clay Lua (http://hungred.com) for illustrating the technique
     *
     * @uses tina_mvc_admin_hpt_recover()
     * 
     */
    function hpt_recover() {
        TINA_MVC\utils\hpt_recover();
    }
    
}

/**
 * The Tina MVC Widget
 * 
 * @package    Tina-MVC
 * @subpackage    Core
 */
class tina_mvc_widget extends WP_Widget {
    
    /**
     * Widget setup.
     */
    function __construct() {
        
        // settings
        $widget_ops = array(
            'classname' => 'tina_mvc',
            'description' => __( 'Call a Tina MVC framework controller', 'tina_mvc' ) 
        );
        
        // Widget control settings
        $control_ops = array(
            'width' => 250,
            'height' => 300,
            'id_base' => 'tina_mvc_widget' 
        );
        
        // Create the widget
        $this->WP_Widget( 'tina_mvc_widget', __( 'Tina MVC Widget', 'tina_mvc' ), $widget_ops, $control_ops );
        
    }
    
    /**
     * Displays the widget on the screen.
     *
     * @param array $args Wordpress widget arguments
     * @param array $instance Arguments from the Tina MVC Widget
     */
    function widget( $args, $instance ) {
        
        // Our variables from the widget settings are in $instance
        $controller         = $instance['controller'];
        
        $role_to_view = TINA_MVC\get_tina_mvc_setting('default_role_to_view');
        if( ! $instance['no_role_to_view']  ) { // we have a non FALSE default
            $role_to_view = TINA_MVC\merge_permissions( $role_to_view, $instance['role_to_view'] );
        }
        
        $capability_to_view = TINA_MVC\get_tina_mvc_setting('default_capability_to_view');
        if( $instance['capability_to_view'] ) {
            $capability_to_view = TINA_MVC\merge_permissions( $capability_to_view, $instance['capability_to_view'] );
        }
        
        $APP = new TINA_MVC\tina_mvc_page_class( $controller, $role_to_view, $capability_to_view, $called_from = 'WIDGET' );
        
        // content is empty is permissions checks fail
        $content = $APP->get_post_content();
        
        if ( $content ) {
            
            // Before widget (defined by themes)
            echo $args['before_widget'];
            
            // Display the widget title if one was input (before and after defined by themes)
            if ( !( $tina_mvc_title = $APP->get_post_title() ) ) {
                $tina_mvc_title = $instance['title'];
            }
            $tina_mvc_title = apply_filters( 'widget_title', $tina_mvc_title );
            
            echo $args['before_title'] . $tina_mvc_title . $args['after_title'];
            
            echo $content;
            
            // After widget (defined by themes)
            echo $args['after_widget'];
            
        }
        
    }
    
    /**
     * Update the widget settings.
     *
     * @param array $new_instance Arguments from the Tina MVC Widget
     * @param array $old_instance
     * @return array
     */
    function update( $new_instance, $old_instance ) {
        
        $instance = $old_instance;
        
        // Strip tags for title and name to remove HTML (important for text inputs)
        $instance['title']              = strip_tags( $new_instance['title'] );
        $instance['controller']         = strip_tags( $new_instance['controller'] );
        $instance['no_role_to_view']    = ( $new_instance['no_role_to_view'] ? 1 : 0 );
        $instance['role_to_view']       = strip_tags( $new_instance['role_to_view'] );
        $instance['capability_to_view'] = strip_tags( $new_instance['capability_to_view'] );
        
        return $instance;
        
    }
    
    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     * 
     * @param array $instance Arguments from the Tina MVC Widget
     */
    function form( $instance ) {
        
        /* Set up some default widget settings. */
        $defaults = array(
             'title' => __( 'The default widget title', 'tina_mvc' ),
            'controller' => __( 'tina-mvc', 'tina_mvc' ) 
        );
        
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>
        <!-- Widget Title: Text Input -->
        <p>
        <label for="<?= $this->get_field_id( 'title' ); ?>"><?php _e( 'Default Widget Title:', 'hybrid' ); ?></label>
        <input id="<?= $this->get_field_id( 'title' ); ?>" name="<?= $this->get_field_name( 'title' ); ?>"
        value="<?= $instance['title']; ?>" style="width:100%;" />
        </p>
        <!-- Controller: Text Input -->
        <p>
        <label for="<?= $this->get_field_id( 'controller' ); ?>"><?php _e( 'Controller:', 'tina_mvc' ); ?></label>
        <input id="<?= $this->get_field_id( 'controller' ); ?>" name="<?= $this->get_field_name( 'controller' ); ?>"
        value="<?= $instance['controller']; ?>" style="width:100%;" />
        </p>
        <!-- Role To View: Checkbox -->
        <p>
        <label for="<?= $this->get_field_id( 'no_role_to_view' ); ?>">
        <?php _e( 'Visible to all users (overrides all settings below)?', 'tina_mvc' ); ?>
        </label>
        <input id="<?= $this->get_field_id( 'no_role_to_view' ); ?>" name="<?= $this->get_field_name( 'no_role_to_view' ); ?>"
        type="checkbox" value="1" <?= ( $instance['no_role_to_view'] ? 'checked' : '' ) ?> />
        </p>
        <!-- Role To View: Text Input -->
        <p>
        <label for="<?= $this->get_field_id( 'role_to_view' ); ?>">
        <?php _e( 'Role To View (empty means user must be logged in):', 'tina_mvc' ); ?>
        </label>
        <input id="<?= $this->get_field_id( 'role_to_view' ); ?>" name="<?= $this->get_field_name( 'role_to_view' ); ?>"
        value="<?= $instance['role_to_view']; ?>" style="width:100%;" />
        </p>
        <!-- Capability To View: Text Input -->
        <p>
        <label for="<?= $this->get_field_id( 'capability_to_view' ); ?>">
        <?php _e( 'Capability To View (overrides the above settings):', 'tina_mvc' ); ?>
        </label>
        <input id="<?= $this->get_field_id( 'capability_to_view' ); ?>" name="<?= $this->get_field_name( 'capability_to_view' ); ?>"
        value="<?= $instance['capability_to_view']; ?>" style="width:100%;" />
        </p>
        <?php
        
    }
}

$Tina_MVC = new Tina_MVC;
