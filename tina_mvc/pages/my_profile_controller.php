<?php
/**
 * The 'My Profile' pages
 *
 * @package    Tina-MVC
 * @subpackage Core
 */

/**
 * The view/edit pages for a users pofile
 *
 * @package    Tina-MVC
 * @subpackage Core
 */
class my_profile_controller extends TINA_MVC\tina_mvc_controller_class {
    
    function __construct( $request ) {
        
        $this->role_to_view = '';
        
    }
    
    /**
     * Gets the current logged in user's profile
     * 
     * @return object
     */
    private function get_current_user() {
        
        global $current_user;
        get_currentuserinfo(); // no need to check return value - we know a user is logged in
        return $current_user;
        
    }

    /**
     * Display the users personal data
     */
    public function index() {
        
        $this->add_var_e( 'current_user', $this->get_current_user() );
        
        // message from request?
        if( ! empty( $this->request[1] ) ) {
            $this->add_var_e('message', 'Profile saved.');
        }
        
        $this->set_post_title( 'My Profile' );
        $this->set_post_content( $this->load_view('my_profile') );
        
    }
    
    /**
     * Edit the users personal data
     *
     * @todo    do we need to implement the Wordpress 'password_reset' action hook
     */
    public function edit() {
        
        $current_user = $this->get_current_user();
        
        TINA_MVC\include_helper('form');
        $f = new TINA_MVC\form( 'edit_data_form' );
        
        // $f_user_login = $f->add_field( 'user_login', 'text' );
        
        $f_user_email = $f->add_field( 'user_email', 'text' )->set_default_value( $current_user->user_email );
        
        $f_first_name = $f->add_field( 'first_name', 'text' )->set_default_value( $current_user->user_firstname );
        
        $f_last_name = $f->add_field( 'last_name', 'text' )->set_default_value( $current_user->user_lastname );
        
        $f_display_name = $f->add_field( 'display_name', 'text' )->set_default_value( $current_user->display_name );
        
        $f_current_password = $f->add_field( 'current_password', 'password' )->add_validation( array('required'=>NULL) );
        
        $f_note = $f->add_text( 'f_note', "Leave 'New Password' blank to leave your password unchanged." );
        
        $f_new_password_1 = $f->add_field( 'new_password', 'password' );
        
        $f_new_password_2 = $f->add_field( 'new_password_again', 'password' );
        
        $f_submit = $f->add_field( 'submit', 'submit' );
        
        $f_cancel = $f->add_field( 'cancel', 'submit' );
        
        if( $f_cancel->get_posted_value() ) {
            wp_redirect( TINA_MVC\get_controller_url( 'my-profile' ) );
            exit();
        }
        
        if( $data = $f->get_posted_data() ) {
            
            $user_data = array();
            
            $error = 0;
            
            // check the password for edits...
            if( is_wp_error( wp_authenticate( $current_user->user_login, $data['current_password'] ) ) ) {
                $f_current_password->add_validation_error( 'Incorrect password.' );
                $error++;
            }
            
            if( $data['user_email'] )  {
                
                if( ! is_email( $data['user_email'] ) ) {
                    $f_user_email->add_validation_error( "'".$f_user_email->get_caption."' is not a valid email address." );
                    $error++;
                }
                
                // make sure it isn't attached to another user...
                if( $test_user = get_user_by( 'email', $data['user_email'] ) ) {
                    
                    if( $test_user->ID != $current_user->ID ) {
                        $f_user_email->add_validation_error( 'This email address belongs to another user.' );
                        $error++;
                    }
                    
                }
                
                if( ! $error ) {
                    $user_data['user_email'] = $data['user_email'];
                }
                
            }
            
            if( $data['first_name'] )  {
                $user_data['first_name'] = $data['first_name'];
            }
            
            if( $data['last_name'] )  {
                $user_data['last_name'] = $data['last_name'];
            }
            
            if( $data['display_name'] )  {
                $user_data['display_name'] = $data['display_name'];
            }
            
            if( $data['new_password'] OR $data['new_password_again'] ) {
                if( $data['new_password'] != $data['new_password_again'] ) {
                    $f_new_password_1->add_validation_error('New passwords must match.');
                    $error++;
                }
                else {
                    $user_data['user_pass'] = $data['new_password'];
                }
            }
            
            if( $user_data AND ! $error ) {
                
                $user_data['ID'] = $current_user->ID;
                wp_update_user( $user_data );
                
                wp_redirect( TINA_MVC\get_controller_url( 'my-profile/index/saved' ) );
                exit();
                
            }
            
        }
        
        $this->add_var( 'edit_data_form', $f->render() );
        
        $this->set_post_title( 'Editing Profile for '.$current_user->user_login );
        $this->set_post_content( $this->load_view('my_profile') );
        
    }
    
}

