<?php
/**
 * General Tina MVC helper functions
 *
 * @package    Tina-MVC
 * @subpackage Core
 * @author     Francis Crossen <francis@crossen.org>
 */

namespace TINA_MVC {

    /**
     * Returns the path to the main Tina MVC plugin folder
     * 
     * @return string
     */
    function plugin_folder() {
        return (TINA_MVC_PLUGIN_DIR );
    }

    /**
     * Returns the path to the tina_mvc folder
     * 
     * @return string
     */
    function tina_mvc_folder() {
        return (TINA_MVC_PLUGIN_DIR . '/tina_mvc');
    }

    /**
     * Returns the path to the tina_mvc/helpers folder
     * 
     * @return string
     */
    function helpers_folder() {
        return (TINA_MVC_PLUGIN_DIR . '/tina_mvc/helpers');
    }

    /**
     * Find the path to the application folder
     *
     * In multisite, this is in tina-mvc/user_apps/multisite/example (for a blog called 'example'
     * or in tina-mvc/user_apps/multisite/[root] for the main/root site.
     *
     * In a non-multisite, in tina-mvc/user_apps/default.
     *
     * @param boolean $get_secondary_folder TRUE to get a location for the secondary app folder (if multisite_app_cascade is enabled)
     * @return  string the path to your app folder (no trailing slash)
     * @uses    tina_mvc_find_plugin_folder()
     * @todo Remove $secondary_app_folder - it shouldn't be required ever
     */
    function app_folder( $get_secondary_folder=FALSE ) {
        
        $primary_app_folder = FALSE;
        $secondary_app_folder = FALSE;
        
        //pr( get_option( 'tina_mvc_settings' ) );
        //pr( plugin_folder() );
        
        if( is_multisite() ) {
            
            if( is_dir( ($d1=plugin_folder().'/user_apps/multisite/'.get_multisite_blog_name()) ) ) {
                $primary_app_folder = $d1;
            }
            elseif( get_tina_mvc_setting('multisite_app_cascade') AND is_dir( ($d2=plugin_folder().'/user_apps/default') ) ) {
                $primary_app_folder = $d2;
            }
            else {
                $msg = "Can't find a suitable primary app folder in 'user_apps'. Looked for $d1";
                if( ! empty($d2) ) {
                    $msg .= " and $d2";
                }
                error($msg);
            }
            
        }
        else {
            
            $primary_app_folder = plugin_folder().'/user_apps/default';
            
        }
        
        if( $get_secondary_folder ) {
            $ret = $secondary_app_folder;
        }
        else {
            $ret = $primary_app_folder;
        }
        
        return $ret;
        
    }

    /**
     * Gets the blog name in a Wordpress Multisite installation
     *
     * @return strng the blog name or '[root]' for the root blog
     */
    function get_multisite_blog_name() {
        
        if( ! is_multisite() ) return FALSE;
        
        global $blog_id;
        
        // first blog is always root...
        if( $blog_id == 1 ) return '[root]';
        
        $blog_details = get_blog_details( array( 'blog_id' => $blog_id ) );
        
        if( SUBDOMAIN_INSTALL ) {
            $blog_name =  substr( $blog_details->domain , 0 , strpos( $blog_details->domain , '.' ) );
        }
        else {
            $blog_name =  str_replace( '/', '', $blog_details->path );
        }
        
        return $blog_name;
        
    }
    
    /**
     * Gets a Tina MVC setting
     *
     * This is stored in the Wordpress option table as 'tina_mvc_settings'
     *
     * @param string $option_name
     * @return mixed default FALSE
     */
    function get_tina_mvc_setting( $option_name = '' ) {
        
        $settings = get_option( 'tina_mvc_settings' );
        
        if( isset( $settings->$option_name ) ) {
            return $settings->$option_name;
        }
        
        return FALSE;
        
    }
    
    /**
     * Gets an email address for use by the Tina MVC mailer function.
     *
     * Emails are sent from this address
     * 
     * @return string
     */
    function get_mailer_from_address() {
        return get_bloginfo( 'name' ).' Mailer <'.'no-reply@'.$_SERVER['SERVER_NAME'].'>';
    }
    
    /**
     * Wrapper for print_r()
     *
     * A convenient way of examining variables during development.
     *
     * @param mixed $arg Stuff to dump to screen...
     * @param string $label An optional label to output e.g. the variable name...
     * @return string
     * @see pr()
     * @see prd()
     */
    function tina_mvc_print_r( $arg=NULL , $label = 'Variable Dump', $return=FALSE ) {
        
        static $times_used = 0;
        $times_used++;
        
        $bt = debug_backtrace();
        
        ob_start();
        
        echo "<pre><small><hr><strong>[".str_replace(WP_PLUGIN_DIR,'',$bt[1]['file'])."::".$bt[1]['line']." tina_mvc_print_r() pass $times_used] $label :</strong>";
        var_dump( $arg );
        echo "</small></pre>";
        
        $r = ob_get_clean();
        
        if( ! $return ) {
            echo $r;
        }
        
        return $r;
        
    }
    
    /**
     * Alias to tina_mvc_print_r(), returns content instead of printing it
     *
     * @param mixed $a Stuff to get
     * @param string $l An optional label to output e.g. the variable name...
     * @return string
     * @see tina_mvc_print_r
     */
    function get_pr( & $a=NULL , $l= 'Variable Dump' ) {
        return tina_mvc_print_r($a,$l,TRUE);
    }
    
    /**
     * Alias to tina_mvc_print_r()
     *
     * @param mixed $a Stuff to dump to screen...
     * @param string $l An optional label to output e.g. the variable name...
     * @return string
     * @see tina_mvc_print_r
     */
    function pr( $a=NULL , $l= 'Variable Dump' ) {
        return tina_mvc_print_r($a,$l);
    }
    
    /**
     * Alias to tina_mvc_print_r() and die();
     *
     * @param mixed $a Stuff to dump to screen...
     * @param string $l An optional label to output e.g. the variable name...
     * @return string
     * @see tina_mvc_print_r
     */
    function prd( $a=NULL , $l= 'Variable Dump' ) {
        tina_mvc_print_r($a,$l);
        die();
    }
    
    /**
     * Display a message as a PHP E_USER_NOTICE Error
     *
     * Handy for quick debugging when you can't use tmpr() or tmprd() but can view
     * your PHP error logs instead
     *
     * @param string $msg The message to log
     * @uses trigger_error()
     */
    function log( $msg = '' ) {
        
        $reporting = ini_get('error_reporting');        
        ini_set( 'error_reporting' , E_ALL );
        $bt = debug_backtrace();
        $msg = '[TINA_MVC: ' . str_replace( TINA_MVC_PLUGIN_DIR.'/' , '' , $bt[0]['file'] ) . ':' . $bt[0]['line'] ."] $msg";
        trigger_error( $msg );
        ini_set( 'error_reporting' , $reporting );
        
    }
    
    /**
     * Check if a user has been assigned to a role
     *
     * This is safe to use when users can have multiple roles
     * 
     * @param   array|string $roles_to_check a list roles to check (array or comma separated string)
     * @return  bolean
     */
    function user_has_role( $roles_to_check=array() ) {
        
        if( ! $roles_to_check ) return FALSE;
        
        if( ! is_array( $roles_to_check ) ) {
            $roles_to_check = explode( ',', $roles_to_check );
        }
        
        global $current_user;
        if( ! empty( $current_user->ID ) ) {
            $user_id = intval( $current_user->ID );
        }
        else {
          return FALSE;
        }
        
        $user = new \WP_User( $user_id ); // $user->roles
        
        foreach( $roles_to_check AS $r) {
          if( in_array( strtolower($r), $user->roles, FALSE) ) {
            return TRUE;
          }
        }
        
        return FALSE;
    
    }
    
    /**
     * Check if a user has been assigned a capability
     *
     * @param   array|string $cap_to_check a list capabilities to check (array or comma separated string)
     * @return  bolean
     */
    function user_has_capability( $cap_to_check=array() ) {
        
        if( ! $cap_to_check ) return FALSE;
        
        if( ! is_array( $cap_to_check ) ) {
            $cap_to_check = explode( ',', $cap_to_check );
        }
        
        global $current_user;
        if( ! empty( $current_user->ID ) ) {
            $user_id = intval( $current_user->ID );
        }
        else {
          return FALSE;
        }
        
        $user = new \WP_User( $user_id );
        
        foreach( $cap_to_check AS $c ) {
            if( current_user_can( strtolower($c) ) ) return TRUE;
        }
        
        return FALSE;
        
    }
    
    if( !function_exists('error') ) {
        
        /**
         * A basic error handler
         *
         * Tina calls this whenever it encounters an error. Hack/override this to help you
         * debug your applications
         * 
         * @param  string The error message
         * @param  boolean Suppress escaping of the message
         */
        function error( $msg='', $suppress_esc=FALSE ) {
            
            $backtrace = debug_backtrace();
            $base_folder = ABSPATH;
            
            $error  = "<h2>Tina MVC Error</h2>\r\n";
            $error .= "<p><strong>".( $suppress_esc ? $msg : esc_html_recursive($msg) )."</strong></p>\r\n";
            $error .= "<p><strong>Backtrace:</strong><br><em>NB: file paths are relative to '".esc_html_recursive($base_folder)."/wp-content/plugins/tina-mvc'</em></p>";
            
            $bt_out  = '';
            
            array_shift( $backtrace ); // this is the call to this function
            
            foreach( $backtrace AS $i => & $b ) {
                
                // tiwen at rpgame dot de comment in http://ie2.php.net/manual/en/function.debug-backtrace.php#65433
                if (!isset($b['file'])) $b['file'] = '[PHP Kernel]';
                if (!isset($b['line'])) {
                    $b['line'] = 'n/a';   
                }
                else {
                    $b['line'] = vsprintf('%s',$b['line']);
                }
                
                $b['function'] = isset($b['function']) ? esc_html_recursive( $b['function'] ) : '';
                $b['class'] = isset($b['class'])  ? esc_html_recursive( $b['class'] ) : '';
                $b['object'] = isset($b['object']) ? esc_html_recursive( $b['object'] ) : '';
                $b['type'] = isset($b['type']) ? esc_html_recursive( $b['type'] ) : '';
                $b['file'] = isset($b['file']) ? esc_html_recursive(str_replace( $base_folder, '', $b['file'])) : '';
                
                if( !empty($b['args']) ) {
                    $args = '';
                    foreach ($b['args'] as $j => $a) {
                        if (!empty($args)) {
                            $args .= "<br>";
                        }
                        $args .= ' - Arg['.vsprintf('%s',$j).']: ('.gettype($a) . ') '
                              .'<span style="white-space: pre">'.esc_html_recursive(print_r($a,1)).'</span>';
                    }
                    
                    $b['args'] = $args;
                    
                }
                
                $bt_out .= '<strong>['.vsprintf('%s',$i).']: '.$b['file'].' ('.$b['line'].'):</strong><br>';
                $bt_out .= ' - Function: '.$b['function'].'<br>';
                $bt_out .= ' - Class: '.$b['class'].'<br>';
                $bt_out .= ' - Type: '.print_r($b['type'],1).'<br>';
                $bt_out .= ' - Object: '.print_r($b['type'],1).'<br>';
                if( is_string( $b['args'] ) ) {
                    $bt_out .= $b['args'].'<hr>';
                }
                else {
                    $bt_out .= print_r( $b['args'], TRUE );
                }
                $bt_out .= "\r\n";
                
            }
            
            // $error .= "<pre><small>".esc_html_recursive(print_r($backtrace,1))."</small></pre>\r\n";
            $error .= '<div style="font-size: 70%;">'.$bt_out."</div>\r\n";
                      
            wp_die( $error );
            exit();
        }
    }
    
    /**
     * Escape a data structure for rendering in a browser
     *
     * Recurses into arrays and objects
     * @param   mixed $data An array or object containing data to be escaped
     * @return  mixed The $escaped $data
     * @uses    ent2ncr() to escape non-XML entities
     */
    function esc_html_recursive( $data=FALSE ) {
        
        if( ! $data ) return FALSE;
        
        if( is_array($data) OR is_object($data) ) {
            
            foreach( $data AS $key => & $value ) {
                
                // $key = htmlentities($key,ENT_QUOTES);
                $key = esc_html( $key );
                
                // $value = ent2ncr(htmlentities($data,ENT_QUOTES));
                $value = esc_html_recursive( $value );
                
            }
            
        }
        else {
            $data = htmlentities($data,ENT_QUOTES);
        }
        
        return $data;
        
    }
    
    /**
     * Gets a value from $_POST
     *
     * Wordpress has some funky ways of treating global $_POST and $_GET variables.
     * Look at wp-settings.php (line 624 for v2.9.1):
     * <code>
     * // If already slashed, strip.
     * if ( get_magic_quotes_gpc() ) {
     *	$_GET    = stripslashes_deep($_GET   );
     *	$_POST   = stripslashes_deep($_POST  );
     *	$_COOKIE = stripslashes_deep($_COOKIE);
     * }
     *
     * // Escape with wpdb.
     * $_GET    = add_magic_quotes($_GET   );
     * $_POST   = add_magic_quotes($_POST  );
     * $_COOKIE = add_magic_quotes($_COOKIE);
     * $_SERVER = add_magic_quotes($_SERVER);
     * </code>
     * Tina assumes that you want to deal with unescaped data. If you want to store
     * it in a DB then do your own escaping or use the $wpdb class (recommended)
     *
     * @param   string  $var Variable name to retrieve
     * @return  mixed The $_POST var
     */
    function get_post($var=NULL) {
        
        // get from post...
        if(is_null($var)) {
            error(__FILE__.' :: '.__FUNCTION__.' required a non NULL argument');
        }
        
        if( array_key_exists( $var , $_POST ) ) {
            return stripslashes($_POST["$var"]);
        }
        else {
            return FALSE;
        }
        
    }
    
    /**
     * Gets a value from $_GET
     *
     * @param   string  $var The variable name to retrieve
     * @return  mixed The $_GET var
     * @see     Notes in get_post()
     */
    function get_get($var=NULL) {
        
        if(is_null($var)) {
            tina_mvc_error(__FILE__.' :: '.__FUNCTION__.' required a non NULL argument');
        }
        
        if( array_key_exists( $var , $_GET ) ) {
            return stripslashes($_GET["$var"]);
        }
        else {
            return FALSE;
        }
        
    }
    
    /**
     * Gets a value from $_GET and if not set look in $_POST 
     *
     * @param   string  $var The variable name to retrieve
     * @return  mixed The $_GET/$_POST var
     * @uses    tina_mvc_get_Get() and tina_mvc_get_Post()
     * @see     Notes in tina_mvc_get_post()
     */
    function get_getpost($var) {
        
        // get from get or post...
        if($ret=get_get($var)) {
            return $ret;
        }
        else {
            return get_post($var);
        }
        
    }
    
    /**
     * Gets the url to the Tina MVC plugin folder
     *
     * Handy for adding js or css files using wp_enqueue_* functions
     * 
     * @return string
     */
    function get_tina_mvc_folder_url() {
        return plugins_url().'/tina-mvc';
    }
    
    /**
     * Gets a url to a Tina MVC controller
     *
     * For example, you want to call 'my-app/my-action' and your controller is accessed through the Tina MVC page 'tina-mvc',
     * this function will return http://example.com/tina-mvc/my-app/my-action. This will fail when used from shortcodes and widgets
     * (because they are not accessed through a Tina MVC page). In that case you can specify
     * the absolute path to your controller by setting $absolute_controller_path to 'true'
     *
     * This function can be called directly from with your controllers (for example if you want to do a browser header redirect)
     * but is typically used in view files (templates) with get_controller_link() and get_abs_controller_link() functions
     *
     * @param   string $controller The 'controller/action/data' we want to call
     * @param   boolean $absolute_controller_path set to `true` to prevent pre-pending the current Tina MVC page to the url
     * (for use in shortcodes and widgets). In this case you must specify the Tina MVC page in the contoller path
     * e.g. 'tina-mvc/my-page-controller/some-action'
     *
     * @see     get_controller_link() and get_abs_controller_link()
     * @return  string an absolute URL to the controller
     */
    function get_controller_url($controller='', $absolute_controller_path=false) {
        
        $home = get_home_url();
        
        if( $absolute_controller_path ) {
            $_page_link = "$home/$controller";
        }
        else {
            if( defined('TINA_MVC_PAGE_CONTROLLER_ID') ) { // no permalinks
                
                // need to get the tina-mvc-page
                $tina_pages = get_tina_mvc_setting('tina_mvc_pages');
                $tina_page = $tina_pages[TINA_MVC_PAGE_CONTROLLER_ID]['page_name'];
                
                $_page_link = get_page_link( TINA_MVC_PAGE_CONTROLLER_ID ) . "&tina_mvc_request=$tina_page";
                
                if( $controller ) {
                    $_page_link .= "/$controller";
                }
                
            }
            elseif( defined('TINA_MVC_PAGE_CONTROLLER_NAME') ) {
                
                $_page_link = $home . '/' . TINA_MVC_PAGE_CONTROLLER_NAME;
                if( $controller ) {
                    $_page_link .= "/$controller";
                }
            }
            else {
                error('`$absolute_controller_path==false` can ony be used when called from a front end controller page (i.e. NOT from a Widget or shortcode and NOT from a hook or action).');
            }
        }
        
        // ssl?
        if( is_ssl() and strpos( $_page_link , 'https://' ) !== FALSE ) {
            $_page_link = str_replace( 'http://' , 'https://' , $_page_link );
        }
        
        return  $_page_link;
    }
    
    /**
     * Gets a HTML link a Tina MVC controller
     *
     * Uses the current active Tina MVC front end page controller.
     *
     * @param   string $controller The 'controller/action/data' we want to call
     * @param   string $link_text The link text to display in the <a> element (default $controller)
     * @param   string $extra_attribs Attributes to put in the <a> tag. e.g. style or script attributes
     * @return  string An <a> element ready for browser output
     * @uses    get_controller_url()
     */
    function get_controller_link($controller='', $link_text=FALSE, $extra_attribs='') {
        
        $ret = '<a href="' . get_controller_url($controller) . '"';
        $ret .=  (  $extra_attribs  ?  ' ' . $extra_attribs  : '' );
        $ret .= '>' . (  $link_text  ?  esc_html($link_text)  :  esc_html(get_controller_url($controller)) )  . '</a>';
        return  $ret;
        
    }
    
    /**
     * Makes an HTML link a Tina MVC controller.
     *
     * Makes links to arbitrary Tina MVC controllers. You should use this function in
     * your widget and shortcode page controllers when there is no active Tina MVC page.
     * 
     * @param   string $controller The 'tina-page/controller/method/data' we want to call
     * @param   string $link_text The link text to display in the <a> element (default $controller)
     * @param   string $extra_attribs Attributes to put in the <a> tag. e.g. style or script attributes
     * @return  string An <a> element ready for browser output
     * @uses    get_controller_url()
     */
    function get_abs_controller_link($controller='', $link_text=FALSE, $extra_attribs='') {
        
        $ret = '<a href="' . get_controller_url($controller, true) . '"';
        $ret .=  (  $extra_attribs  ?  ' ' . $extra_attribs  : '' );
        $ret .= '>' . (  $link_text  ?  esc_html($link_text)  :  esc_html(get_controller_url($controller)) )  . '</a>';
        return  $ret;
        
    }
    
    /**
     * Gets a link to the users 'My Profile' page
     * @param String $link_text
     * @return string
     */
    function get_my_profile_link( $link_text = FALSE ) {
        
        if( ! $link_text ) {
            $link_text = 'Click here to view/edit your profile.';
        }
        
        if( get_tina_mvc_setting('custom_login') ) {
            
            if( defined('TINA_MVC_PAGE_CONTROLLER_NAME') OR defined('TINA_MVC_PAGE_CONTROLLER_ID') ) {
                $link = get_controller_link( 'my-profile', $link_text );
            }
            else {
                $link = get_abs_controller_link( get_tina_mvc_setting('custom_login').'/my-profile/', $link_text );
            }
            
        }
        else {
            
            $link = '<a href="'.admin_url('profile.php').'">'.$link_text.'</a>';
            
        }
        
        return $link;
        
    }

    /**
     * Searches for a file in the standard Tina MVC application folders
     *
     * In case of error the locations we looked in are stored in $find_app_file_error for
     * use in an error message later.
     *
     * @param string $filename
     * @param string $tina_mvc_page the page the controller is accessed through
     * @param string $called_from PAGE_FILTER, WIDGET, SHORTCODE, CALLABLE_CONTROLLER
     * @param array $find_app_file_error contains a list of places searched (for use in case of error)
     * @return mixed FALSE or the full path and filename to the file to be included
     */
    function find_app_file( $filename='', $tina_mvc_page='', $called_from='PAGE_FILTER', & $find_app_file_error ) {
        
        $find_app_file_error = array();
        $looked_for = array();
        
        if( $called_from == 'PAGE_FILTER' ) {
            
            // this is left empty if a custom folder location is used
            if( $tina_mvc_page ) {
                $page_folder = $tina_mvc_page . '/';
            }
            
            /**
             * Is demo mode enabled?
             */
            if( get_tina_mvc_setting('demo_mode') ) {
                if( file_exists( ($f=plugin_folder()."/demo/pages/tina_mvc_demo_and_docs/$filename" ) ) ) {
                    return $f;
                }
                $looked_for[] = $f;
            }
            
            if( file_exists( ($f = app_folder().'/pages/'.$page_folder.$filename) ) ) {
                return $f;
            }
            $looked_for[] = $f;
            
            // don't bother looking if there is no $tina_mvc_page - we've already looked in the location in the check above
            if( $tina_mvc_page AND get_tina_mvc_setting('app_cascade') ) {
                if( file_exists( ($f = app_folder().'/pages/'.$filename) ) ) {
                    return $f;
                }
                $looked_for[] = $f;
            }
            if( $secondary_folder=app_folder(TRUE) ) {
                
                // multisite and multisite app cascade enabled
                if( file_exists( ($f=$secondary_folder.'/pages/'.$page_folder.$filename ) ) ) {
                    return $f;
                }
                $looked_for[] = $f;
                
                if( get_tina_mvc_setting('app_cascade') ) {
                    if( file_exists( ($f=$secondary_folder.'/pages/'.$filename ) ) )  {
                        return $f;
                    }
                }
                $looked_for[] = $f;
            }
            
            if( file_exists( ($f=tina_mvc_folder().'/pages/'.$filename ) ) ) {
                return $f;
            }
            $looked_for[] = $f;
            
        }
        elseif( $called_from == 'WIDGET' OR $called_from == 'SHORTCODE' OR $called_from == 'CALLABLE_CONTROLLER' ) {
            
            $search_folder = strtolower( $called_from ) . 's'; // i.e. "widget" -> "widgets", etc
            
            if( file_exists( ($f = app_folder()."/$search_folder/$filename" ) ) ) {
                return $f;
            }
            $looked_for[] = $f;
            
            if( $secondary_folder=app_folder(TRUE) ) {
                
                // multisite and multisite app cascade enabled
                if( file_exists( ($f=$secondary_folder."/$search_folder/$filename" ) ) ) {
                    return $f;
                }
                $looked_for[] = $f;
                
            }
            
        }
        else {
            error("Invalid parameter. No action for '\$called_from' == '$called_from'");
        }
        
        // we have an error
        $find_app_file_error = $looked_for;
        
        return FALSE;
        
    }
    
    /**
     * Locates and loads a helper. Returns an instantiated object.
     *
     * @param string $h the helper to include (for, table, pagination, etc)
     * @return object
     */
    function include_helper( $h='' ) {
        
        static $included_helpers = array();
        
        if( ! $h ) error('Helper parameter $h required');
        
        $f = helpers_folder().'/tina_mvc_'.$h.'_helper_class.php';
        
        if( ! file_exists( $f ) ) {
            error( "Helper '$h' not found. Looked for '$f'" );
        }
        elseif( ! in_array( $h, $included_helpers ) ) {
            $included_helpers[] = $h;
            include( $f );
        }
        
        return TRUE;
        
    }
    
    /**
     * Converts a Tina MVC role or permission entry to an array or FALSE
     *
     * Used by controllers to merge permissions. You will never need to call this function.
     *
     * @param mixed $p
     * @return mixed
     * @see merge_permissions()
     */
    function permissions_to_array( $p=FALSE ) {
        
        if( is_array( $p ) ) {
            return $p;
        }
        if( $p === FALSE ) {
            return FALSE;
        }
        if( is_string( $p ) ) {
            if( $p === '' ) {
                return $p;
            }
            else {
                return explode( ',', $p);
            }
        }
        
    }
    
    /**
     * Merge roles and capabilities passed to the Tina MVC controller
     *
     * Roles and capabalities may be specified as a string, a comma seperated list or an array. This function
     * checks the format of the arguments and merges them into an array. It may also return '' or FALSE
     *
     * You should never need to call this function.
     *
     * @param mixed $p1
     * @param mixed $p2
     * @return mixed
     */
    function merge_permissions( $p1=FALSE, $p2=FALSE ) {
        
        $p1 = permissions_to_array( $p1 );
        $p2 = permissions_to_array( $p2 );
        
        // we may have FALSE or '' passed
        if( ! $p1 ) {
            
            if( $p2 === FALSE ) {
                return $p1; // returns FALSE or ''
            }
            
            return $p2; // is an array of permissions or '' or FALSE
            
        }
        else { //  $p1 is array of permissions
            
            if( ! $p2 ) {
                return $p1;
            }
            
            return array_unique( array_merge( $p1, $p2 ) );
            
        }
        
    }
    
}

