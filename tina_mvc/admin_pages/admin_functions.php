<?php
/**
* Utility functions for managing the plugin
*
* Install, remove, and other infrequently used actions are stored here to keep
* the main plugin file small.
*
* @package    Tina-MVC
* @subpackage Core
*/

namespace TINA_MVC\utils {
    
    /**
     * Set up or upgrade the plugin
     * 
     * @package    Tina-MVC
     * @subpackage Core
     * @param boolean $upgrading
     * @return void
     */
    function plugin_activate( $upgrading ) {
        
        $tina_mvc_app_settings = new \stdClass;
        
        if( PHP_VERSION_ID < 50306 ) {
            activation_error('Tina MVC for Wordpress requires PHP 5 >= 5.3.6', E_USER_ERROR);
            exit();
        }
        
        if( file_exists( $f = \TINA_MVC\plugin_folder().'/user_apps/default/app_settings.php' ) ) {
            include( $f );
        }
        elseif( file_exists( $f = \TINA_MVC\plugin_folder().'/user_apps/default/app_settings_sample.php' ) )  {
            include( $f );
        }
        
        /**
         * If multisite look for other settings files
         */
        if( is_multisite() ) {
            
            $saved_setting = $tina_mvc_app_settings->multisite_app_cascade;
            
            $blogname = \TINA_MVC\get_multisite_blog_name();
            
            /**
             * Check for app_settings files
             */
            if( file_exists( $f = \TINA_MVC\plugin_folder()."/user_apps/multisite/$blogname/app_settings.php" ) ) {
                include( $f );
            }
            elseif( $saved_setting AND file_exists( $f = \TINA_MVC\plugin_folder().'/user_apps/default/app_settings.php' ) ) {
                include( $f );
            }
            
            $tina_mvc_app_settings->multisite_app_cascade = $saved_setting;
            
        }
        
        if( empty( $tina_mvc_app_settings ) ) {
            activation_error('Failed to find a app_settings.php or app_settings_sample.php file', E_USER_ERROR);
            exit();
        }
        
        // create controller pages
        $tina_mvc_pages = array();
        foreach( $tina_mvc_app_settings->tina_mvc_pages AS $p ) {
            
            // does the page exist...?
            $the_page = get_page_by_path( $p['page_name'] );
            
            // \TINA_MVC\prd( $the_page );
            
            $tina_mvc_request = $p['page_name'];
            
            if ( empty( $the_page ) ) {
                
                // Create post object
                $_p = array();
                $_p['post_title'] = $p['page_title'];
                
                // check if we need to set a parent page.
                $parent_page_id = 0; // default
                $slash_pos = strrpos( $p['page_name'] , '/' ); // is there a path or just a name?
                
                if( $slash_pos !== FALSE) {
                    
                    $the_parent_page_name = substr( $p['page_name'] , 0, $slash_pos );
                    $p['page_name'] = substr( $p['page_name'] , $slash_pos+1 , strlen($p['page_name']) - ($slash_pos+1) );
                    
                    if( $the_parent_page_name ) {
                        $parent_page = get_page_by_path( $the_parent_page_name );
                        $parent_page_id = $parent_page->ID;
                    }
                    
                }
                $_p['post_parent'] = $parent_page_id;
                
                $_p['post_name'] = $p['page_name'];
                $_p['post_content'] = default_controller_page_content();
                $_p['post_status'] = $p['page_status'];
                $_p['post_type'] = 'page';
                
                // MENu order?
                $menu_order = 0; // default
                if( array_key_exists( 'menu_order', $p ) ) {
                    $menu_order = intval( $p['menu_order'] );
                }
                $_p['menu_order'] = $menu_order;
                
                $_p['comment_status'] = 'closed';
                $_p['comment_count '] = 0;
                $_p['ping_status'] = 'closed';
                $_p['post_category'] = array(1);
                
                // Insert the post into the database
                $the_page_id = wp_insert_post( $_p );
                
            }
            else {
                
                $the_page_id = $the_page->ID;
                
                //make sure the page is not trashed...
                $the_page->post_status = $p['page_status'];
                $the_page_id = wp_update_post( $the_page );
                
            }
          
            // get the page in case Wordpress messed with the page_name
            $_p = get_page( $the_page_id );
          
            // for storing in the options table
            $page_name = $_p->post_name;
            $page_id = $_p->ID;
            $page_data = array( 'page_title'=>$_p->post_title, 'page_name'=>$page_name, 'page_id'=>$page_id, 'tina_mvc_request'=>$tina_mvc_request ); // handy to have them for later...
            
            $tina_mvc_pages[$page_name] = $page_data;
            $tina_mvc_pages[$page_id] = $page_data;
            
        }
        
        $tina_mvc_app_settings->tina_mvc_pages = $tina_mvc_pages;
        
        delete_option('tina_mvc_settings');
        add_option('tina_mvc_settings', $tina_mvc_app_settings, '', 'yes');
        
        /**
         * The hourly cron functionality
         */
        if( $tina_mvc_app_settings->enable_hourly_cron ) {
            wp_schedule_event( time()+30, 'hourly', 'tina_mvc_cron_hook' );
        }
        
        if( file_exists( \TINA_MVC\app_folder().'/install_remove/tina_mvc_app_install.php' ) ) {
            
            include( \TINA_MVC\app_folder().'/install_remove/tina_mvc_app_install.php' );
            if( function_exists( 'tina_mvc_app_install' ) ) {
                
                tina_mvc_app_install();
                
            }
            
        }
        
        delete_option('tina_mvc_plugin_active');
        add_option('tina_mvc_plugin_active', 1, '', 'yes');
        
    }
    
    /**
     * Trashes any pages set up as front end page controllers and removes the Tina MVC options
     * 
     * @package    Tina-MVC
     * @subpackage Core
     */
    function plugin_remove() {
        
        $pages_done = array(); // each page is listed twice
        $tina_pages = \TINA_MVC\get_tina_mvc_setting('tina_mvc_pages');
        
        foreach( $tina_pages AS $p ) {
            
            if( ! in_array( $p['page_id'], $pages_done) ) {
                
                if( $page = get_page( $p['page_id'] ) ) {
                    
                    $page = array( 'ID'=>$p['page_id'], 'post_status'=>'trash');
                    $_ok = wp_update_post( $page );
                    
                }
                $pages_done[] = $p['page_id'];
                
            }
        }
        
        // the cron event...
        if ( wp_next_scheduled( 'tina_mvc_cron_hook' ) ) {
            wp_clear_scheduled_hook( 'tina_mvc_cron_hook' );
        }
        
        delete_option('tina_mvc_settings');
        delete_option('tina_mvc_plugin_active');
        
        return TRUE;
        
    }
    
    /**
     * Backup files before plugin upgrade
     *
     * Thanks to Clay Lua (http://hungred.com) for illustrating the technique
     *
     * @uses hpt_copyr()
     * @package    Tina-MVC
     * @subpackage Core
     */
    function hpt_backup() {
        
        $backup_list = array('user_apps');
        
        // we'll need this later. see hpt_recover()
        add_option('tina_mvc_upgrade_backup_list', $backup_list, '', 'no');
        
        // make a folder for backup...
        $bu_fldr = \TINA_MVC\plugin_folder().'/../../upgrade/tina_mvc_upgrade_backup';
        
        // try to create...
        if( ! is_dir($bu_fldr) AND ! mkdir($bu_fldr) ) {
            
            return  new WP_Error('no_permission', __('Wordpress cannot create a backup folder to keep your settings. You will have to manually upgrade Tina MVC.'). ' Tried to create: '.$bu_fldr );
            
        }
        else {
            
            foreach( $backup_list AS $item ) {
                if( file_exists( \TINA_MVC\plugin_folder()."/$item" ) ) {
                    $result = hpt_copyr( \TINA_MVC\plugin_folder()."/$item", "$bu_fldr/$item" );  
                }
            }
            
        }
        
    }
    
    /**
     * Recover files after plugin upgrade
     *
     * Thanks to Clay Lua (http://hungred.com) for illustrating the technique
     *
     * @uses hpt_copyr()
     * @package    Tina-MVC
     * @subpackage Core
     */
    function hpt_recover() {
        
        $recovery_list = get_option('tina_mvc_upgrade_backup_list');
        
        $bu_fldr = \TINA_MVC\plugin_folder().'/../../upgrade/tina_mvc_upgrade_backup';
        
        if( ! is_dir($bu_fldr) ) {
            return  new WP_Error('no_backup_folder', __('The backup folder doesn\'t exist. You will have to manually upgrade Tina MVC.'));
        }
        else {
            
            foreach( $recovery_list AS $item ) {
                if( file_exists("$bu_fldr/$item") ) {
                    $result = hpt_copyr( "$bu_fldr/$item", \TINA_MVC\plugin_folder()."/$item" );
                }
            }
            
        }
        
        if (is_dir($bu_fldr)) {
            hpt_rmdirr($bu_fldr);
        }
        
        // we've perhaps copied a new settings file. run tina_mvc_install() to re-read the
        // new settings... $upgrading=true
        plugin_activate( true );
        
        // finished with this now
        delete_option('tina_mvc_upgrade_backup_list');
        
    }

    /**
     * Generic copy utility
     *
     * Thanks to Clay Lua (http://hungred.com) for illustrating the technique
     * 
     * @package    Tina-MVC
     * @subpackage Core
     * @param   string $source 
     * @param   string $dest 
     * @return  boolean true
     */
    function hpt_copyr($source, $dest) {
        
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }
        
        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }
        
        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest);
        }
        
        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            
            // Deep copy directories
            hpt_copyr("$source/$entry", "$dest/$entry");
        }
        
        // Clean up
        $dir->close();
        return true;
    }
    
    /**
     * Delete a file, or a folder and its contents
     *
     * @author Aidan Lister <aidan@php.net>
     * @version 1.0.2
     * @see http://putraworks.wordpress.com/2006/02/27/php-delete-a-file-or-a-folder-and-its-contents/
     * @package    Tina-MVC
     * @subpackage Core
     * @param string $dirname Directory to delete
     * @return bool Returns TRUE on success, FALSE on failure
     */
    function hpt_rmdirr($dirname) {
        
        // Sanity check
        if (!file_exists($dirname)) {
            return false;
        }
        
        // Simple delete for a file
        if (is_file($dirname)) {
            return unlink($dirname);
        }
        
        // Loop through the folder
        $dir = dir($dirname);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            
            // Recurse
            hpt_rmdirr("$dirname/$entry");
        }
        
        // Clean up
        $dir->close();
        return rmdir($dirname);
        
    }
    
    /**
     * Displays any error on plugin activation
     * 
     * @package    Tina-MVC
     * @subpackage Core
     * 
     * @param string $message
     * @param integer $errno PHP error constant
     * 
     * @return void
     */
    function activation_error($message='', $errno=E_USER_ERROR) {
     
        if( isset($_GET['action']) AND $_GET['action'] == 'error_scrape') {
            
            echo '<strong>' . $message . '</strong>';
            exit;
            
        }
        else {
            trigger_error($message, $errno);
        }
     
    }

    /**
     * The default page/post content for a Tina MVC front end controller page. Used on plugin activation
     *
     * @package    Tina-MVC
     * @subpackage Core
     * 
     * @return  string default page content
     */
    function default_controller_page_content() {
        
        return 
        "This is a Tina MVC page.\r\n\r\n
        Under normal circumstances you should not edit this page.\r\n\r\n
        When the Tina MVC plugin is active this content will be overwritten by the output of the active controller.\r\n\r\n
        If Tina MVC is active, and you can read this from the public side of a Worpdress page (i.e. not from the 'Page edit' admin menu) then
        Tina MVC may have 'lost' this front end controller page (this can happen if you edit this page from within Wordpress).
        Deactivate and reactivate Tina MVC to fix.\r\n\r\n
        If Tina MVC has been deactivated then this becomes a 'normal' Wordpress page and therefore you can read this text.
        You can safely delete or hide it.";
        
    }
    
    /**
     * Sets up the admin menu and pages in the Wordpress administration screen for Tina MVC documentation
     * 
     * @package    Tina-MVC
     * @subpackage Core
     */
    class admin_options_page {
        
        /**
         * Hooks into the Wordpress admin menu
         */
        function __construct() {
            add_action('admin_menu', array( &$this, 'admin_menu') );
        }
        
        /**
         * Sets up the admin menu and entries for Tina MVC documentation
         */
        function admin_menu () {
            add_menu_page( 'Tina MVC', 'Tina MVC', 'manage_options', 'tina_mvc_for_wordpress', array( $this, 'admin_page_index'), $icon_url=NULL, $position=NULL );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Hello World Tutorial', 'Hello World Tutorial', 'manage_options', 'tina_mvc_hello_world', array( $this, 'admin_page_hello_world' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Using Views', 'Using Views', 'manage_options', 'tina_mvc_using_views', array( $this, 'admin_page_using_views' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Adding Models', 'Adding Models', 'manage_options', 'tina_mvc_adding_models', array( $this, 'admin_page_adding_models' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Code & Cron Hooks', 'Code & Cron Hooks', 'manage_options', 'tina_mvc_code_hooks', array( $this, 'admin_page_code_hooks' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'File Locations', 'File Locations', 'manage_options', 'tina_mvc_file_locations', array( $this, 'admin_page_file_locations' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Widgets, Shortcodes, call_controller()', 'Widgets, Shortcodes, call_controller()', 'manage_options', 'tina_mvc_widgets_shortcodes', array( $this, 'admin_page_widgets_shortcodes' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Custom login functionality', 'Custom login functionality', 'manage_options', 'tina_mvc_custom_login', array( $this, 'admin_page_custom_login' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Helper Functions', 'Helper Functions', 'manage_options', 'tina_mvc_helper_functions', array( $this, 'admin_page_helper_functions' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Form Helper - Introduction', 'Form Helper - Introduction', 'manage_options', 'tina_mvc_form_helper_intro', array( $this, 'admin_page_form_helper_intro' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Form Helper - Advanced Use', 'Form Helper - Advanced Use', 'manage_options', 'tina_mvc_form_helper_advanced', array( $this, 'admin_page_form_helper_advanced' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Form Helper - Fields and Validation', 'Form Helper - Fields and Validation', 'manage_options', 'tina_mvc_form_helper_fields_and_validation', array( $this, 'admin_page_form_helper_fields_and_validation' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Table and Pagination Helpers', 'Table and Pagination Helpers', 'manage_options', 'tina_mvc_table_and_pagination_helpers', array( $this, 'admin_page_table_and_pagination_helpers' ) );
            add_submenu_page( 'tina_mvc_for_wordpress', 'Todo List', 'Todo List', 'manage_options', 'tina_mvc_todo_list', array( $this, 'admin_page_todo_list' ) );
        }
        
        /**
         * Calls the index controller for Tina MVC documentation
         */
        function  admin_page_index() {
            
            global $Tina_MVC;
            /**
             * The call_controller() function can also be used statically
             *
             * @see $this->admin_page_hello_world()
             */
            echo $Tina_MVC->call_controller('admin-pages/index', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the hello_world controller for Tina MVC documentation
         */
        function  admin_page_hello_world() {
            echo \Tina_MVC::call_controller('admin-pages/hello_world', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the using_views controller for Tina MVC documentation
         */
        function  admin_page_using_views() {
            echo \Tina_MVC::call_controller('admin-pages/using_views', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the adding_models controller for Tina MVC documentation
         */
        function  admin_page_adding_models() {
            echo \Tina_MVC::call_controller('admin-pages/adding_models', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the code_hooks controller for Tina MVC documentation
         */
        function  admin_page_code_hooks() {
            echo \Tina_MVC::call_controller('admin-pages/code_hooks', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the file_locations controller for Tina MVC documentation
         */
        function admin_page_file_locations() {
            echo \Tina_MVC::call_controller('admin-pages/file-locations', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the widgets_shortcodes controller for Tina MVC documentation
         */
        function admin_page_widgets_shortcodes() {
            echo \Tina_MVC::call_controller('admin-pages/widgets-shortcodes', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the custom_login controller for Tina MVC documentation
         */
        function admin_page_custom_login() {
            echo \Tina_MVC::call_controller('admin-pages/custom-login', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the helper_functions controller for Tina MVC documentation
         */
        function admin_page_helper_functions() {
            echo \Tina_MVC::call_controller('admin-pages/helper-functions', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the form_helper_intro controller for Tina MVC documentation
         */
        function admin_page_form_helper_intro() {
            echo \Tina_MVC::call_controller('admin-pages/form-helper-intro', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the form_helper_advanced controller for Tina MVC documentation
         */
        function admin_page_form_helper_advanced() {
            echo \Tina_MVC::call_controller('admin-pages/form-helper-advanced', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the form_helper_fields_and_validation controller for Tina MVC documentation
         */
        function admin_page_form_helper_fields_and_validation() {
            echo \Tina_MVC::call_controller('admin-pages/form-helper-fields-and-validation', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the table_and_pagination_helpers controller for Tina MVC documentation
         */
        function admin_page_table_and_pagination_helpers() {
            echo \Tina_MVC::call_controller('admin-pages/table-and-pagination-helpers', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
        /**
         * Calls the todo_list controller for Tina MVC documentation
         */
        function admin_page_todo_list() {
            echo \Tina_MVC::call_controller('admin-pages/todo-list', FALSE, 'manage_options', \TINA_MVC\tina_mvc_folder().'/admin_pages' );
        }
        
    }
    
}
