<?php
/**
 * The base page, model, view and controller classes
 *
 * @package    Tina-MVC
 * @subpackage Core
 * @author     Francis Crossen <francis@crossen.org>
 */

namespace TINA_MVC {
    
    /**
     * The page class
     *
     * Checks the controller request and permissions.
     * 
     * If permissions are not met, and the $tina_mvc_app_settings->login_redirect is in use
     * redirects to a custom login page.
     *
     * Else $tina_mvc_app_settings->no_permission_behaviour is checked to see what way to handle the
     * condition.
     * 
     * If permissions are met, locates and includes the controller and instantiates it. The
     * $tina_mvc_request is passed to the controller class. The controller
     * set the post title and content. The calling code can later retrive these variables.
     * 
     * @package    Tina-MVC
     * @subpackage Core
     * 
     * @param   string $request /tina_mvc-page/controller/method/and/other/data/to/pass
     * @param   mixed $role_to_view comma separated string or string or array of roles.
     * @param   mixed $capability_to_view FALSE allows general access, OR comma separated string or string or array of capabilities. Overrides the role check
     * @param   mixed $called_from 'PAGE_FILTER', 'WIDGET', 'SHORTCODE' or 'CALL_CONTROLLLER_FUNC'
     * @param   string $custom_folder an overriding location to look for the controller in
     * @param   string $shortcode_content the content encapsulated by the tina_mvc shortcode (if any)
     * 
     */
    class tina_mvc_page_class {
        
        /**
         * Tina MVC does nopt support direct access to class variables. Use setters and getters.
         * @var mixed
         */
        protected 
            $allow_http_redirects,
            $MODEL, $CONTROLLER,
            $view_data,
            $called_from,
            $tina_mvc_page,
            $request, $raw_request,
            $find_app_file_error,
            $is_404,
            $the_post_title, $the_post_content;
        
        /**
         * Checks permissions and instantiates the controller. Sets the page title and content.
         * 
         * @param String $raw_request a path /tina_mvc-page/controller/method/and/other/data/to/pass
         * @param Boolean $role_to_view FALSE allows all to view; '' must be loogged in to view; array or comma separated list of roles to view
         * @param Boolean $capability_to_view as for $role_to_view. Overrides the $role_to_view
         * @param String $called_from 'PAGE_FILTER', 'WIDGET', 'SHORTCODE' or 'CALL_CONTROLLLER_FUNC'
         * @param Boolean $custom_folder custom location for the controller
         * @param Object $shortcode_content
         */
        function __construct( $raw_request='', $role_to_view=NULL, $capability_to_view=NULL, $called_from='PAGE_FILTER', $custom_folder=FALSE, $shortcode_content=FALSE ) {
            
            $this->raw_request = $raw_request;
            $this->request = str_replace( '-', '_', $raw_request );
            $this->request = explode( '/', $this->request );
            
            $this->called_from = $called_from;
            
            /**
             * Locate and instantiate a controller. It can override the default permissions so we need it now
             */
            $countroller_found = $this->get_instance_of( $custom_folder, $shortcode_content );
            
            $this->CONTROLLER->set_request( $this->request, $this->raw_request );
            $this->CONTROLLER->set_called_from( $this->called_from );
            
            if( isset( $this->CONTROLLER->role_to_view ) ) {
                $role_to_view = merge_permissions( $role_to_view, $this->CONTROLLER->role_to_view );
            }
            if( isset( $this->CONTROLLER->capability_to_view ) ) {
                $capability_to_view = merge_permissions( $capability_to_view, $this->CONTROLLER->capability_to_view );
            }
            
            // pr( $role_to_view ); pr( $capability_to_view );
            
            $view_ok = FALSE; // default
            
            // watch the boolean checks here. Loose type checking for $capability_to_view
            if( ! $capability_to_view AND $role_to_view === FALSE ) {
                $view_ok = 'View OK for all';
            }
            elseif( $capability_to_view === FALSE AND $role_to_view == '' AND is_user_logged_in() ) {
                $view_ok = 'View OK for all logged in';
            }
            elseif( ($capability_to_view !== FALSE) AND user_has_capability($capability_to_view) ) {
                $view_ok = 'View OK by CAPABILITY';
            }
            elseif( ($role_to_view !== FALSE) AND user_has_role($role_to_view) ) {
                $view_ok = 'View OK by ROLE';
            }
            elseif( $this->called_from === 'PAGE_FILTER' ) {
                
                /**
                 * Are we using custom login functionality?
                 */
                if( ($custom_login = get_tina_mvc_setting('custom_login')) ) {
                    
                    if( $this->do_login() ) {
                        
                        /**
                         * User is successfully authenticated.
                         * 
                         * We only do a redirect from a Tina MVC page, i.e. not from a widget or a shortcode where
                         * browser output has already started.
                         *
                         * If login returns TRUE we have an authenticated user and need to go through the various
                         * role and capability checks again.
                         */
                        $r = get_tina_mvc_setting('logon_redirect_target');
                        $tina_pages = get_tina_mvc_setting('tina_mvc_pages');
                        if( $r ) {
                            if( ! array_key_exists( $r, $tina_pages ) ) {
                                error('Page in $tina_mvc_app_settings->logon_redirect_target is not a Tina MVC page.');
                            }
                            wp_safe_redirect( site_url().'?p='.$tina_pages[$r]['page_id'] );
                            exit();
                        }
                        elseif( $r === '' ) {
                            wp_safe_redirect( site_url().'?p='.$tina_pages[$custom_login]['page_id'] );
                            exit();
                        }
                        else {
                            wp_redirect( site_url() );
                            exit();
                        }
                        
                    }
                    
                }
                elseif( get_tina_mvc_setting('no_permission_behaviour') == 'wp_login' ) {
                    $login_redir = wp_login_url( site_url( $this->raw_request ) );
                    wp_redirect( $login_redir );
                    exit();
                }
                else {
                    
                    /**
                     * If do_login() returns FALSE or we are not using the custom login functionality, a user is not
                     * logged in and a login form is in $this->the_post_content.
                     */
                    $view_ok = FALSE;
                    $this->the_post_title = $this->CONTROLLER->get_post_title();
                    $this->the_post_content = $this->CONTROLLER->get_post_content();
                    
                }
                
            }
            else {
                $view_ok = FALSE;
            }
            
            // log( $view_ok ); // debugging permissions
            // prd( $this->the_post_content );
            
            /**
             * Check the $view_ok setting
             *
             * In cases where the constructor has set the post_title and post_content we don't want them
             * accidently displayed if permission checks do not pass.
             */
            if( $view_ok ) {
                
                $this->CONTROLLER->dispatch();
                $this->the_post_title = $this->CONTROLLER->get_post_title();
                $this->the_post_content = $this->CONTROLLER->get_post_content();
                
            }
            elseif( $this->called_from === 'PAGE_FILTER' ) {
                
                if( get_tina_mvc_setting('custom_login') ) {
                    
                    $this->the_post_title = $this->CONTROLLER->get_post_title();
                    $this->the_post_content = $this->CONTROLLER->get_post_content();
                    
                }
                else {
                    
                    if( get_tina_mvc_setting('no_permission_behaviour') == 'redirect' )  {
                        
                        wp_redirect( site_url() );
                        exit;
                        
                    }
                    else { // 'no_permission'
                        
                        $this->the_post_title = 'No Permission';
                        $this->the_post_content = 'Sorry, you do not have permission to view this content.';
                        
                    }
                    
                }
                
            }
            else {
                
                $this->the_post_title = '';
                $this->the_post_content = '';
                
            }
            
            
        }
        
        /**
         * Include a controller and set up the Tina MVC request
         * 
         * @param Boolean $controller_file Full path and filename of controller to include
         * @param String $controller_name the class to instantiate
         * @return object  Tina MVC controller
         */
        private function include_controller( $controller_file=FALSE, $controller_name='' ) {
            if( ! class_exists( $_c = $controller_name.'_controller' )) {
                include( $controller_file );
            }
            if( class_exists( $_c ) ) {
                $this->CONTROLLER = new $_c( $this->request, $this->called_from );
                $this->CONTROLLER->set_request( $this->request, $this->raw_request );
                return TRUE;
            }
            else {
                error( "Class '$controller_name' is not defined in '$controller_file'." );
            }
        }
        
        /**
         * An autoloader
         * 
         * @param string $folder
         * @param string $shortcode_content
         * @uses $this->include_controller()
         *
         * @return boolean TRUE on success. FALSE only if called from a shortcode. Other failures trigger an error.
         */
        private function get_instance_of( $folder='', $shortcode_content ) {
            
            // $this->called_from = PAGE_FILTER,WIDGET,SHORTCODE,CALL_CONTROLLLER
            // $this->request
            // pr( $this->called_from );
            
            $this->tina_mvc_page = FALSE; // default - used for page_filter requests
            
            if( $this->called_from == 'PAGE_FILTER' ) {
                if( ! $page =  $this->request[0] ) {
                    error( "Invalid request" );
                }
                else {
                    $this->tina_mvc_page = $page;
                    if( isset($this->request[1]) ) {
                        $controller = $this->request[1];
                    }
                    else {
                        $controller = $this->request[1] = 'index';
                    }
                }
            }
            else {
                $controller = $this->request[0];
            }
            
            $controller_filename = $controller.'_controller.php';
            
            /**
             * Checks the custom folder
             */
            if( $folder ) {
                if( file_exists( "$folder/$controller_filename" ) ) {
                    $this->include_controller( "$folder/$controller_filename", $controller );
                    return TRUE;
                }
                
                $err_msg = "<strong>Controller file '$folder/$controller_filename' does not exist.</strong>";
                if( $this->called_from == 'SHORTCODE' OR $this->called_from == 'WIDGET' ) {
                    $this->CONTROLLER = new tina_mvc_controller_class();
                    $this->CONTROLLER->set_post_content($err_msg);
                    return FALSE;
                }
                else {
                    error($err_msg);
                }
                
            }
            
            /**
             * Is demo mode enabled?
             */
            if( get_tina_mvc_setting('demo_mode') ) {
                if( file_exists( ($f=plugin_folder()."/demo/pages/tina_mvc_demo_and_docs/$controller_filename" ) ) ) {
                    $this->include_controller( $f, $controller );
                    return TRUE;
                }
            }
            
            $search_errors = array(); // default
            $controller_file = find_app_file( $controller_filename, $this->tina_mvc_page, $this->called_from, $search_errors );
            
            if( $controller_file !== FALSE ) {
                $this->include_controller( $controller_file, $controller );
                return TRUE;
            }
            else {
                    
                /**
                 * If we got here we have failed to find a controller
                 */
                if( get_tina_mvc_setting('missing_page_controller_action') == 'display_error' ) {
                    
                    $e  = "<strong>Can't find controller for request:</strong>";
                    $e .= "<small><pre>".print_r( $this->request, 1 )."</pre></small>\r\n";
                    $e .= "Looked for file '".$controller."_controller.php' in:";
                    $e .= "<small><pre>";
                    foreach( $search_errors AS $d ) {
                        $e .= str_replace( plugin_folder(), '', $d ) . "\r\n";
                    }
                    $e .= "</pre></small>\r\n";
                    
                    if( $this->called_from == 'SHORTCODE' OR $this->called_from == 'WIDGET' ) {
                        $this->request = array();
                        $this->CONTROLLER = new tina_mvc_controller_class();
                        $this->CONTROLLER->set_post_content($e);
                        return FALSE;
                    }
                    else {
                        error( $e, $suppress_esc=TRUE );
                    }
                    
                }
                elseif( get_tina_mvc_setting('missing_page_controller_action') == 'display_404' ) {
                    $this->request = array();
                    $this->CONTROLLER = new tina_mvc_controller_class();
                    $this->CONTROLLER->set_post_content(FALSE);
                    $this->is_404 = TRUE;
                    return FALSE;
                }
                elseif( get_tina_mvc_setting('missing_page_controller_action') == 'redirect' ) {
                    wp_redirect( site_url() );
                    exit();
                }
                else{
                    
                    error('TODO: Error setting '.get_tina_mvc_setting('missing_page_controller_action').' not implemented');
                    
                }
                
            }
            
        }
        
        /**
         * Displays and processes a login form
         * 
         * Used if a user is not authorised to view. Forces a login
         * 
         * @return Boolean TRUE if user passed authentication
         */
        private function do_login() {
            
            include_helper('form');
            $f = new form( 'login_form' );
            
            $f_user_login = $f->add_field( 'user_login', 'text' )->add_validation( array('required'=>NULL) );
            
            $f_password = $f->add_field( 'user_password', 'password' )->add_validation( array('required'=>NULL) );
            
            $f_remember_me = $f->add_field( 'remember', 'checkbox' );
            
            $f_submit = $f->add_field( 'wp_submit', 'submit', $label='Login');
            
            if( $credentials = $f->get_posted_data() ) { // form was submitted successfully
                
                $u = wp_signon( $credentials, false );
                
                if( !is_wp_error($u) ) {
                    return TRUE; // we are logged in and done ...
                }
                else {
                    $f->add_error(  'The username and/or password is incorrect.' );
                }
                
            }
            
            // if we got here we are not authenticated - display the form...
            // if there is a _do_login view we will use it...
            
            $login_form = $f->render();
            
            $this->CONTROLLER->add_var( 'login_form', $login_form );
            
            $this->CONTROLLER->set_post_title( 'Login to Continue' );
            $this->CONTROLLER->set_post_content( $this->CONTROLLER->load_view( 'tina_mvc_do_login' ) );
            
            return FALSE;
            
        }
        
        /**
         * Setter
         */
        private function set_404() {
            $this->is_404 = TRUE;
        }
        
        /**
         * Getter
         * @return boolean
         */
        public function is_404() {
            return $this->is_404;
        }
        
        /**
         * Gets the Wordpress Tina MVC page title
         * 
         * @return string
         */
        public function get_post_title() {
            return $this->the_post_title;
        }
        
        /**
         * Gets the Wordpress Tina MVC page content
         * 
         * @return string
         */
        public function get_post_content() {
            return $this->the_post_content;
        }
        
    }
    
    /**
     * The controller class
     *
     * Setter and getter for the Wordpress $the_post_title, $the_post_content
     * variables. Loads HTML view files (templates), merging them with PHP variables
     *
     * If you want to access the Tina MVC request from the constructor of your derived
     * class, you must call the parent constructor. Otherwise use the dispatcher functionality
     * and place your code the class methods.
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @param   array $request extracted from $_GET - /controller/action/some/data
     * @return boolean
     */
    class tina_mvc_controller_class {
        
        /**
         * @var array the Tina MVC request array( 'tina_page', 'controller', 'method', 'data1', 'data2', ... )
         */
        protected $request;
        
        /**
         * @var string 'tina_page/controller/method/data1/data2'
         */
        protected $raw_request;
        
        /**
         * @var array the Tina MVC page
         */
        protected $tina_mvc_page;
        
        /**
         * @var string the controllers output
         */
        protected $the_post_title, $the_post_content;
        
        /**
         * @var boolean whether to use the dispatcher method after creating an instance
         * of the derived class.
         * @see $this->dispatcher()
         */
        public $use_dispatcher = TRUE;
        
        /**
         * @var string if called from a non self-closing shortcode, the content
         * @see tina_mvc_shortcode_func()
         */
        public $shortcode_content = '';
        
        /**
         * @var object View data for passing to a template. Contains 2 objects, one
         * for escaped data and one for non escaped data
         * @see $this->add_var()
         * @see $this->add_var_e()
         */
        protected $template_vars;
        
        /**
         * @var array overrides the defaults from app_settings.php
         */
        public $role_to_view, $capability_to_view;
        
        /**
         * Sets the Tina MVC request.
         *
         * If you are using the dispatcher functionality then you do not need to call this
         * constructor from yoru child classes. The Tina MVC request is set by the base controller
         * before calling the dispatch() function.
         *
         * @param array $request the Tina MVC request
         * @param string $called_from 'PAGE_FILTER', 'WIDGET', 'SHORTCODE' or 'CALL_CONTROLLLER_FUNC'
         */
        function __construct( $request=array(), $called_from='PAGE_FILTER' ) {
            
            $this->called_from = $called_from;
            
            if( $request ) {
                $this->request = $request;
                if( count( $request ) ) {
                    $this->tina_mvc_page = $this->request[0];
                }
            }
            
        }
        
        /**
         * An empty function to prevent errors when the dispatcher function attempts to call index().
         *
         * This function would normally be redefined by your own controller
         */
        public function index() {}
        
        /**
         * Calls controller functions based on the Tina MVC request (page/controller/function/data1/data2/...)
         * 
         * If there is no function call in the Tina MVC request, $this->index() is called. Otherwise looks 
         * for a class method based on the function part of the request. This allows you to name your class
         * methods according to your actions. e.g. page/controller/my-action will be mapped on to $this->my_action().
         * Default action is always $this->index()
         * 
         * The dispatcher is used by default.
         * 
         * Make any methods that you do not want called by the dispatcher method 'private'
         * for security and name them with a leading underscore to prevent the dispatcher
         * from trying to load them. E.g. '_my_method'
         */
        public function dispatch() {
            
            if( ! $this->use_dispatcher ) {
                return FALSE;
            }
            
            if( $this->called_from == 'PAGE_FILTER' ) {
                // pop the first array element from $request - this will be the Tina MVC page
                array_shift( $this->request );
            }
            // next is the controller...
            array_shift( $this->request );
            
            if( !$this->request OR ! is_array($this->request) OR ! $this->request[0] ) {
                
                $this->index();
                
            }
            else {
                
                $method = $this->request[0];
                
                if( method_exists( $this , $method ) AND $method[0] !== '_' ) {
                    
                    // check it isn't private
                    $m = new \ReflectionMethod( $this, $method );
                    if( $m->isPublic() ) {
                        $this->$method( $this->request );
                    }
                    
                }
                else {
                    // tina_mvc_error( 'Method '.$method.' doesn\'t exist.' );   
                    $this->index( $this->request );
                }
                
            }
            
        }
        
        /**
         * Sets the Tina MVC raw_request (handy to have if you need the original uri)
         *
         * @param string $request
         * @param string $raw_request
         */
        public function set_request( $request=array(), $raw_request='' ) {
            $this->request = $request;
            $this->raw_request = $raw_request;
            if( count( $request ) ) {
                $this->tina_mvc_page = $request[0];
            }
        }
        
        /**
         * Sets the Tina MVC page title from your application
         *
         * @param string
         */
        public function set_post_title($str) {
            $this->the_post_title = $str;
        }
        
        /**
         * Alias to set_post_title()
         *
         * @see $this->set_post_title()
         */
        public function set_title($str) {
            $this->set_post_title( $str );
        }
        
        /**
         * Setter
         *
         * @param string
         */
        public function set_called_from( $c='PAGE_FILTER' ) {
            $this->called_from = $c;
        }
        
        /**
         * Sets the Tina MVC page content from your application
         *
         * @param string
         */
        public function set_post_content($str) {
            $this->the_post_content = $str;
        }
        
        /**
         * Alias to $this->set_post_content()
         *
         * @see $this->set_post_content()
         */
        public function set_content($str) {
            $this->set_post_content( $str );
        }
        
        /**
         * Gets the Wordpress Tina MVC page title
         * 
         * Used by tina_mvc_controller_class
         * 
         * @return string
         */
        public function get_post_title() {
            return $this->the_post_title;
        }
        
        /**
         * Gets the Wordpress Tina MVC page content
         * 
         * Used by tina_mvc_controller_class
         * 
         * @return string
         */
        public function get_post_content() {
            return $this->the_post_content;
        }
        
        /**
         * Includes and parses a view file or an email file
         *
         * @param string $f full path and filename to file
         * @param mixed $V the view data
         * @param boolean $add_html_comments Add HTML comments at the start and end of the view file
         * @return mixed FALSE or the parsed view file
         */
        private function parse_view_file( $f, &$V, $add_html_comments=TRUE ) {
            
            if( ! file_exists( $f ) ) {
                return FALSE;
            }
            else {
                
                if( ! defined('TINA_MVC_LOAD_VIEW') ) define('TINA_MVC_LOAD_VIEW',true);
                
                ob_start();
                if( $add_html_comments ) echo "<!--// TINA_MVC VIEW FILE START: $f //-->\r\n";
                if( ! empty( $V ) ) {
                    if( ! is_array($V) ) {
                        $V = get_object_vars($V);
                    }
                    extract( $V );
                }
                include( $f );
                if( $add_html_comments ) echo "<!--// TINA_MVC VIEW FILE END: $f //-->\r\n";
                return ob_get_clean();
            }
            
        }
        
        /**
         * Includes/Parses a HTML file and return as a string
         *
         * Looks for a file in the same folder as the controller named {$view}_view.php and
         * includes it. Any variables passed in $V are extracted into the global scope of the
         * HTML view file. This allows the use of <code><?php echo $whatever ?></code> and 
         * <code><?= $somearray[0] ?></code> template constructs.
         *  
         * In fact we can use any <code><?php foreach($array as $element): ?> .. do something .. <?php endforeach; ?></code> 
         * just like in normal PHP mixed HTML/PHP templating.
         * {$view}_view.php is intended to be a HTML file
         *
         * If $view is FALSE and $V is string, the string data is used as the parsed view file. i.e. without
         * requiring a view file.
         *
         * Alternatively you can assign template data to $this->template_vars using $this->add_var()
         * and $this->add_var_e(). If data is in $this->template_vars it will  be used in preference
         * to data passed to this function.
         *
         * Since Tina MVC 1.0.14, you can leave the $view parameter empty and Tina MVC will look for a view file based
         * on the controller name and method. For example, $contact_us_controller->index() would imply a view file
         * called contact_us_index_view.php
         *
         * You should use <code>if( !defined('TINA_MVC_LOAD_VIEW') ) die;</code> or something
         * similar to avoid users being able to call the template directly.
         *
         * @param  string $view the name of the view file (without '_view.php')
         * @param  mixed $V variable (usually array or object) passed to the included file for parsing by the template
         * @param  string $custom_folder an overriding location to load the view from (relative to the Tina MVC plugin folder)
         *
         * @return string the parsed view file (usually HTML)
         *
         * @see $this->view_data
         */
        public function load_view($view=FALSE, $V=FALSE, $custom_folder=FALSE) {
            
            if( ! $V ) {
                $V = & $this->view_data;
            }
            
            $post_content = '';
            
            if( ! $view ) {
                // automagically deduce it... what file called this function?
                $db = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
                if( empty($db[1]['function']) OR empty($db[1]['class']) ) {
                    error( "Can't automatically deduce \$view filename. debug_backtrace() is not returning the calling class and function." );
                }
                else {
                    $view = str_replace('_controller', '', $db[1]['class']) . '_' . $db[1]['function'];
                }
                
            }
            
            $view_filename = $view.'_view.php';
            
            //custom location?
            if( $custom_folder ) {
                
                if( ! ( $post_content = $this->parse_view_file( ($f=plugin_folder()."/$custom_folder/$view_filename"), $V ) ) ) {
                    error( "Can't find custom view file: $f." );
                }
                
            }
            else {
                
                $search_errors = array();
                $view_file = find_app_file( $view_filename, $this->tina_mvc_page, $this->called_from, $search_errors );
                
                if( ! $view_file ) {
                    error( 'Can\'t find view file: '.$view_filename.'. Looked in: '.var_export( $search_errors, 1) );
                }
                
                if( $view_file !== FALSE ) {
                    $post_content = $this->parse_view_file( $view_file, $V );
                }
                
            }
            
            
            if( ! $post_content ) {
                
                // if the view data is a string, we'll just output it...
                if( ! $view_file AND is_string( $V ) ) {
                    $post_content  = "<!--// TINA_MVC VIEW FILE START: [string data used instead of a file] //-->\r\n";
                    $post_content .= $V;
                    $post_content .= "<!--// TINA_MVC VIEW FILE END: [string data used instead of a file] //-->\r\n";
                }
                else {
                    
                    // error
                    $e  = "Can't find view file:\r\n";
                    $e .= "<small><pre>".$view_filename."</pre></small>\r\n";
                    $e .= "Looked in:\r\n";
                    $e .= "<small><pre>";
                    foreach( $search_errors AS $d ) {
                        $e .= str_replace( plugin_folder(), '', $d ) . "\r\n";
                    }
                    $e .= "</pre></small>\r\n";
                    
                    error( $e, $suppress_esc=TRUE );
                    
                }
                
            }
            
            return $post_content;
            
        }
        
        /**
         * Add content to the post/page content
         *
         * @param string $content The content to add to the page
         * @param string $tag What to wrap the content in, default '' and let wpautop() take care of it, else 'p', h1', etc
         * @param boolean $esc Escape the data, default TRUE
         * @param string $attribs attributes to include in the tag
         */
        public function add_content( $content='', $tag='', $esc=TRUE, $attribs='') {
            
            if( $esc ) {
                $content = esc_html( $content );
            }
            
            if( $tag ) {
                
                $ope_tag = '<'.$tag;
                if( $attribs ) {
                    $ope_tag .= " $attribs";
                }
                $ope_tag .= '>';
                
                $content = $ope_tag.$content.'</'.$tag.'>';
                
            }
            else {
                $content = wpautop( $content );
            }
            
            $this->the_post_content .= $content;
            
            return TRUE;
            
        }
        
        /**
         * Add pre-escaped content to the page/post content
         *
         * @param string $content The content to add
         * @param string $tag What to wrap the content in
         * @param string $attribs HTML attributes to include in the tag
         */
        public function add_raw_content( $content='', $tag='', $attribs='' ) {
            
            return $this->add_content( $content, $tag, $esc=FALSE, $attribs );
            
        }
        
        /**
         * Add a variable to $this->template_vars
         *
         * Allows you to drop your template variables into an object for retrieval by
         * $this->load_vew()
         *
         * The key is added as a property of (object) $this->template_vars
         *
         * @param   string $key the object property name to use when adding data
         * @param   mixed $v variable to add
         * @param   boolean $esc whether to escape data or not
         * @return  boolean
         * @see $this->template_vars
         * @see $this->add_var_e()
         * @see $this->load_view()
         */
        public function add_var( $key=NULL, $v=NULL , $esc=FALSE ) {
            
            if( is_null($key) ) {
                tina_mvc_error( '$key parameter is required.' );
            }
            
            if( $esc ) {
                $v = esc_html_recursive( $v );
            }
            
            $this->view_data["$key"] = $v;
            
        }
        
        /**
         * Add an escaped variable to $this->view_data
         *
         * Any variables added using this will be escaped. Allows you to drop your
         * template variables into an object for retrieval by $this->load_vew()
         *
         * @param   string $key the object property name to use when adding data
         * @param   mixed $v variable to add
         * @return  boolean
         * @see $this->view_Data
         * @see $this->add_var()
         * @see $this->load_view()
         */
        public function add_var_e( $key=NULL, $v=NULL ) {
            
            if( is_null($key) ) {
                tina_mvc_error( '$key parameter is required.' );
            }        
            
            $this->add_var( $key , $v , TRUE );
            
            return TRUE;
            
        }
        
        /**
         * Includes a model
         *
         * The same search order is used as for controllers
         * 
         * @param string $f the full path and filname
         * @param string $m the model name
         * 
         * @return mixed the model object or FALSE
         */
        private function include_model( $f, $m ) {
            
            include_once( $f );
            if( class_exists( $m ) ) {
                
                $model = new $m;
                
                /**
                 * Duplicates the tina_mvc_model_class constructor() function. Derived models do not
                 * need to call the parent constructor.
                 */
                global $wpdb;
                $model->DB = & $wpdb;
                $model->wpdb = & $wpdb;
                
                return $model;
                
            }
            else {
                
                error( "Model '$m' is not defined in file '$f'." );
                
            }
            
        }
        
        /**
         * Locates a Tina MVC model file and return an instance of the model class
         *
         * Looks for a file in the same order as for controllers and view files. Model files are named {$model}_model.php and
         * define a class called {$model}
         *
         * @param   string $model the name of the model file (without '_model.php')
         * @param string $custom_folder path to load the model from
         * @return  object an instance of the model class
         */
        public function load_model( $model='', $custom_folder=FALSE ) {
            
            if( ! $model ) {
                tina_mvc_error(__FILE__.' :: '.__FUNCTION__.' ('.__LINE__.') requires $model argument.');
            }
            
            $model_filename = $model.'_model.php';
            
            //custom location?
            if( $custom_folder ) {
                if( file_exists( ($f="$custom_folder/$model_filename") ) ) {
                    return $this->include_model( $f, $model );
                }
                error( "File '$model_filename' does not exist in location '$custom_folder'." );
            }
            
            $search_errors = array();
            if( $f = find_app_file( $model_filename, $this->tina_mvc_page, $this->called_from, $search_errors ) ) {
                return $this->include_model( $f, $model );
            }
            
            // we have an error if we get here...
            
            // error
            $e  = "Can't find model file:\r\n";
            $e .= "<small><pre>".$model_filename."</pre></small>\r\n";
            $e .= "Looked in:\r\n";
            $e .= "<small><pre>";
            foreach( $search_errors AS $d ) {
                $e .= str_replace( plugin_folder(), '', $d ) . "\r\n";
            }
            $e .= "</pre></small>\r\n";
            
            error( $e, $suppress_esc=TRUE );
            
        }
        
        /**
         * Grabs an email message template and merges it with any variables you pass. Use this for
         * any emails you want to send through Tina MVC. Templates are like normal Tina MVC view files except
         * they are named *_email.php.
         *
         * The templates are stored in the user or Tina MVC 'pages', 'shortcodes' and 'widgets' folders
         * (just like controllers, views and models).
         *
         * Variables are extract() ed into the local scope of the message template. $to, $cc, $bcc and $subject will
         * also be added to that scope. Beware: this will overwrite variables of the same name passed through
         * $message_variables.
         *
         * You can also send an email without using a template by passing the content of your email as a string to
         * $message_variables and leaving $message_template FALSE.
         * 
         * @param   mixed $to The recipients address (array or string)
         * @param   mixed $cc
         * @param   mixed $bcc
         * @param   string $subject
         * @param   string $message_template Template to use (without the '_email.php')
         * @param   array $message_variables Data to be merged into the message (usually array or object )
         * @todo    sending attachments
         * @return  boolean
         */
        function mail( $to=FALSE, $cc=FALSE, $bcc=FALSE, $subject, $message_template=FALSE, $message_variables=array() ) {
            
            if( !$to OR ( is_string($to) AND ! is_email($to) ) ) {
                return FALSE;
            }
            
            $headers = array();
            $headers[] = 'From: ' . get_mailer_from_address();
            
            // make sure the to, cc, bcc and subject data is included in the message variables
            $message_variables['to'] = $to;
            $message_variables['cc'] = $cc;
            $message_variables['bcc'] = $bcc;
            $message_variables['subject'] = $subject;
            
            if( $message_template ) {
                
                $search_errors = array();
                $tpl_file = find_app_file( $message_template.'_email.php', $this->tina_mvc_page, $this->called_from, $search_errors );
                
                if( ! $tpl_file ) {
                    
                    // if we get here we've an error
                    $e  = "Can't find email file:\r\n";
                    $e .= "<small><pre>".$tpl_file."</pre></small>\r\n";
                    $e .= "Looked in:\r\n";
                    $e .= "<small><pre>";
                    foreach( $search_errors AS $d ) {
                        $e .= str_replace( plugin_folder(), '', $d ) . "\r\n";
                    }
                    $e .= "</pre></small>\r\n";
                    
                    error( $e, $suppress_esc=TRUE );
                    
                }
                else {
                    
                    $email_body = $this->parse_view_file( $tpl_file, $message_variables, FALSE );
                    
                }
                
            }
            elseif( is_string( $message_variables ) ) {
                
                $email_body = $message_variables;
                
            }
            else {
                
                // $message_variables is not a string and no template file passed to us
                error( '$message_variables must be a string if $message_template is FALSE.' );
                
            }
            
            if( $cc ) {
                if( is_array( $cc) ) {
                    foreach( $cc AS $email ) {
                        $headers[] = 'Cc: '.$email;
                    }
                }
                else {
                    $headers[] = 'Cc: '.$cc;
                }
            }
            
            if( $bcc ) {
                if( is_array( $bcc) ) {
                    foreach( $bcc AS $email ) {
                        $headers[] = 'Bcc: '.$email;
                    }
                }
                else {
                    $headers[] = 'Bcc: '.$bcc;
                }
            }
            
            return wp_mail( $to , $subject, $email_body, $headers, $attachments=FALSE);
            
        }
        
    }

    /**
     * The Model Class
     *
     * Derive your models from this class. So far the only thing we do is to globalise the $wpdb variable
     * and assign it as a class variable.
     * 
     * @package    Tina-MVC
     * @subpackage Core
     */
    class tina_mvc_model_class {
        
        /**
         * The $wpdb object. It is public in case we want to run arbitrary SQL
         * 
         * @var object $DB A reference to $wpdb object
         */
        public $DB;
        
        /**
         * The $wpdb object. It is public in case we want to run arbitrary SQL
         * 
         * @var object $wpdb A reference to $wpdb object
         */
        public $wpdb;
        
        /**
         * Constructor
         *
         * The load_model() function will assign these variables in case you forget to call the parent
         * constructor from your derived model class.
         */
        public function __construct() {
            
            global $wpdb;
            $this->DB = & $wpdb;
            $this->wpdb = & $wpdb;
            
        }
        
    }

}
