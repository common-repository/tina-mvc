<?php
/**
 * The Tina MVC registration controller
 *
 * Looks after all user registration and related functions.
 * 
 * Use this file to see how to build applications in Tina MVC. If you copy this file to
 * your user_apps folder it will be used instead of this one.
 *
 * @package    Tina-MVC
 * @subpackage Core
 */
/**
 * Sets a handy alias. All Tina MVC classes and functions are in the TINA_MVC namespace.
 */
use TINA_MVC as T;

/**
 * Manages all registration, password, login functions
 * 
 * @package    Tina-MVC
 * @subpackage Core
 */
class registration_controller extends T\tina_mvc_controller_class {
    
    /**
     * Overrides the default permissions to view the page. Let anyone view this controller output
     */
    function __construct() {
        
        // permission override - this controller should be accessible to all...
        $this->role_to_view = FALSE;
        
    }
    
    /**
     * Checks if a user is logged and redirects to home page. Prevents
     * logged in users from accessing user registration and password reminder links
     */
    private function check_if_logged_in() {
        
        global $current_user;
        $current_user = wp_get_current_user();
        if ( !($current_user instanceof WP_User) ) {
            wp_redirect( site_url() );
            exit();
        }
        
    }
    
    /**
    * Default controller
    *
    * Displays and processes the registration form. Create a user and emails
    * their login details to them on success
    */
    function index() {
        
        $this->check_if_logged_in();
        
        /**
         * Check registration is enabled
         */
        if( ! get_option('users_can_register')) {
            wp_redirect( site_url() );
            exit();
        }
        
        /**
         * Includes the form helper. Helpers are not loaded by default.
         *
         * The use of the form helper is documented in the 'tina-mvc/samples/sample_form_controller.php' file.
         */
        T\include_helper('form');
        
        $f = new T\form('registration');
        
        $f_username = $f->add_field( 'username', 'text' );
        $f_username->add_validation( array( 'required'=>NULL ) );
        
        $f_email = $f->add_field( 'email', 'text' );
        $f_email->add_validation( array( 'required'=>NULL, 'EMAIL'=>NULL ) );
        
        if( T\get_tina_mvc_setting('recaptcha_pub_key') AND T\get_tina_mvc_setting('recaptcha_pri_key') ) {
            $f_recaptcha = $f->add_field( 'are_you_human', 'recaptcha', 'Are you human?' );
        }
        else {
            $f->add_text( 'recaptcha_notice', __('NB: This form would be more secure if you entered your reCaptcha keys in $tina_mvc_app_settings.php.'));
        }
        
        $f->add_field( 'submit_button', 'submit', 'Submit' );
        
        /**
         * If form has passed validation the submitted data is available using get_table_data().
         */
        if( $applicant = $f->get_posted_data() ) {
            
            // username string is OK?
            if( ! validate_username( $applicant['username'] ) ) {
                $f_username->add_validation_error('username contains invalid characters.'); // sets $f->validation_errors too
            }
            
            // username already exists?
            if( username_exists( $applicant['username'] ) ) {
                $f_username->add_validation_error('username already exists in the system.');
            }
            
            // email already exists?
            if( email_exists( $applicant['email'] ) ) {
                $f_email->add_validation_error('email already exists in the system.');
            }
            
            /**
             * The add_field_error_message() function will also set $validation_errors = TRUE
             */
            if( ! $f->get_error_count() ) {
                
                $user_pass = wp_generate_password();
                $user_id = wp_create_user( $applicant['username'], $user_pass, $applicant['email'] );
                
                if ( !$user_id ) {
                    wp_redirect( T\get_controller_url('registration/user-not-created') );
                    exit();
                }
                
                /**
                 * Sets up an array for merging into the email template
                 * @see TINA_MVC\mail()
                 */
                $V = array( 'username'=>$applicant['username'], 'password'=>$user_pass );
                $this->mail( $applicant['email'] , NULL, NULL, '['.$_SERVER['SERVER_NAME'].'] User registration details', 'registration_index' , $V );
                
                wp_redirect( T\get_controller_url('registration/user-created/'.urlencode( $applicant['username'] )  ) );
                exit();
                
            }
            
        }
        
        /**
         * Sets the post title.
         */
        $this->set_post_title('User Registration');
        
        /**
         * Add a variable for use in the view file. Use $this->add_var() to add an already escaped variable and
         * $this->add_var_e() to add an unescaped variable. For example:
         * <code>
         * $this->add_var( 'name' , 'Joe Bloggs' );
         * </code>
         * makes the variable available in the global scope of the view file:
         * <code>
         * <div>Hi <?= $name ?></div>
         * </code>
         */
        $this->add_var( 'registration_form', $f->render() );
        $this->set_post_content( $this->load_view( 'registration_index' ) );
        
    }

    /**
    * Password reset page
    *
    * Displays and process the reset form. Generates a Wordpress activation
    * key and emails the user a url to the password reset page
    * 
    * @todo    See comments c. line 234 about security of non expiring hashes
    */
    function reset_password() {
        
        $this->check_if_logged_in();
        
        T\include_helper('form');
        $f = new T\form( 'reset_pass_form' );
        
        $f_username = $f->add_field( 'username', 'text' );
        
        $f_email = $f->add_field( 'email', 'text' );
        
        if( T\get_tina_mvc_setting('recaptcha_pub_key') AND T\get_tina_mvc_setting('recaptcha_pri_key') ) {
            $f->add_field( 'are_you_human', 'recaptcha', 'Are you human?' );
        }
        else {
            $f->add_text( 'recaptcha_notice', __('NB: This form would be more secure if you entered your reCaptcha keys in $tina_mvc_app_settings.php.'));
        }
        
        $f->add_field( 'submit_button', 'submit', 'Reset Password' );
        
        /**
         * If $recover is set the form has passed validation
         */
        if( $recover = $f->get_posted_data() ) {
            
            global $wpdb;
            
            /**
             * Check email or username posted.
             */
            if( ! $recover['username'] AND ! $recover['email'] ) {
                $f->add_error('username or email is required to reset your password.');
            }
            
            /**
             * $f->validation_errors is set TRUE by $f->add_field_error_message above
             */
            if( ! $f->get_error_count ) {
                
                /**
                 * The user object. Default false
                 */
                $u = FALSE;
                
                /**
                 * Look for a user by email or username
                 */
                if( $recover->email ) {
                    
                    $u = get_user_by_email( $recover['email'] );
                    
                }
                else {
                    
                    $u = get_userdatabylogin( $recover['username'] );
                    
                }
                
                if( ! $u ) {
                    
                    $f_username->add_validation_error('username','/\'Email\' does not exist in here. Sorry, try again.');
                    
                }
                
                if( ! $f->get_error_count() ) { // are we still OK?
                    
                    /**
                     * The User object us in $u
                     *
                     * $u->user_login;
                     * $u->user_email;
                     */
                    
                    /**
                     * Play nice with other Wordpress plugins
                     */
                    do_action('lostpassword_post'); // play nice with other wp plugins...
                    do_action('retreive_password', $u->user_login);  // Misspelled and deprecated  // play nice with other wp plugins...
                    do_action('retrieve_password', $u->user_login);
                    
                    /**
                     * Check Wordpress setting
                     */
                    $allow = apply_filters('allow_password_reset', true, $u->ID);
                    
                    if( ! $allow ) {
                        
                        $f_username->add_validation_error('username',' \''.$u->user_login.'\' is not allowed to reset their password.');
                        
                    }
                    
                    if( ! $f->get_error_count() ) { // are we still OK?
                        
                        /**
                         * I don't like this... why keep a hash valid forever?
                         *
                         * Perhaps we can allow a delay between repeated requests ...?
                         * $hash = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $u->user_login));
                         * 
                         * We should figure out a way of preventing DOS
                         */
                        if ( TRUE OR empty($hash) ) {
                            
                            $hash = wp_generate_password(20, false);
                            do_action( 'retrieve_password_key', $u->user_login, $hash );
                            $wpdb->update($wpdb->users, array('user_activation_key' => $hash), array('user_login' => $u->user_login));
                            
                        }
                        
                        /**
                         * Send the details to the user
                         *
                         * $V is for email merge data
                         */
                        $V = array( 'username'=>$u->user_login, 'hash'=>$hash );
                        $this->mail( $u->user_email, FALSE, FALSE, 'We received a request to reset your password.', 'registration_reset_password' , $V );
                        
                        wp_redirect( TINA_MVC\get_controller_url('registration/password-reset-sent/'.urlencode( $u->user_login )  ) );
                        exit();
                        
                    }
                    
                }
                
            }
            
        }
        
        /**
         * If we reach here we have a form to display
         */
        $this->set_post_title('Reset Password');
        
        $this->add_var( 'password_reset_form', $f->render() );
        $this->set_post_content( $this->load_view('registration_reset_password') );
        
    }

    /**
     * Parse the reset password link that a user was sent in their email
     *
     * Verifies the hash/key in the $request and resets the users password on
     * success and emails a new password
     */
    function password_reset_confirmation() {
        
        $this->check_if_logged_in();
        
        array_shift( $this->request );
        
        if( $this->request AND $this->request[0] ) {
            $username = strip_tags( urldecode( $this->request[0] ) );
        }
        else {
            $username = FALSE;
        }
        
        if( $this->request AND $this->request[1] ) {
            $hash = strip_tags( urldecode( $this->request[1] ) );
        }
        else {
            $hash = FALSE;
        }
        
        if( ! $hash AND ! $username ) {
            
            $this->add_var_e( 'error_message', 'The link you followed to this page is invalid.' );
            
        }
        else {
            
            // find the user...
            global $wpdb;
            
            if( ! ( $hash = preg_replace( '/[^a-z0-9]/i', '', $hash ) ) ) { // based on WP function...
                
                $this->add_var_e( 'error_message', 'The link you followed contained an invalid code.' );
                
            }
            else {
                
                $u = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $hash, $username ) );
                
                if ( empty( $u ) ) {
                    
                    $this->add_var_e( 'error_message', 'The link you followed to this page is invalid.' );
                    
                }
                else {
                    
                    $new_password = wp_generate_password();
                    do_action('password_reset', $u, $new_password);  // play nice with other plugins
                    
                    wp_set_password($new_password, $u->ID);
                    update_user_meta($u->ID, 'default_password_nag', true); //Set up the Password change nag. // in case person logs into WP
                    
                    $this->add_var_e( 'message' , 'Your password has been reset.' );
                    
                    // email the user...
                    $vars = array( 'user_login'=>$u->user_login, 'password'=>$new_password );
                    
                    $this->mail( $u->user_email, FALSE, FALSE, 'Your new password', 'registration_password_reset_confirmation' , $vars );
                    
                }
                
            }
        }
        
        // finished. display the result page...
        $this->set_post_title('Password Reset');
        $this->set_post_content( $this->load_view('registration_password_reset_confirmation') );
        
    }

    /**
     * Displays a user created page
     *
     * The username is urlendoded in the url
     */
    function user_created() {
        
        array_shift( $this->request ); // should be 'user_created'...
        
        if( $this->request AND $this->request[0] ) {
            
            $this->add_var_e( 'username', urldecode( $this->request[0] ) );
            $this->set_post_content( $this->load_view('registration_user_created') );
            
        }
        else {
            
            // was page called directly?
            wp_redirect( TINA_MVC\get_controller_url() );
            exit();
            
        }
        
    }

    /**
     * Display a user NOT created page
     */
    function user_not_created() {
        
        $this->set_post_content( $this->load_view('registration_user_not_created' ) );
        
    }

    /**
     * Displays 'password reset sent' page
     *
     * Displayed after a user has sucessfuly requested a password reset
     */
    function password_reset_sent() {
        
        array_shift( $this->request ); // should be 'password_reset_sent'...
        
        if( $this->request AND $this->request[0] ) {
            
            $this->add_var_e( 'username', urldecode( $this->request[0] ) );
            $this->set_post_content( $this->load_view('registration_password_reset_sent') );
            
        }
        else {
            
            // was page called directly?
            wp_redirect( TINA_MVC\get_controller_url() );
            exit();
            
        }
        
    }

}
