<?php
/**
 * A page controller - The forms helper
 *
 * @package    Tina-MVC
 * @subpackage Samples
 * @author     Francis Crossen <francis@crossen.org>
 */

/**
 * An example of how to use tina_mvc_form_helper_class
 *
 * This file illustrates the use of all form elements
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
class test_form_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The default controller
     * @return void
     */
    public function index() {
        
        /**
         * Load the helper file
         */
        TINA_MVC\include_helper('form');
        
        /**
         * Create the new form
         */
        $form = new TINA_MVC\form('my_form');
        
        /**
         * Open a fieldset
         */
        $fset_open = $form->fieldset_open('fset_name_pw', 'Name and password please');
        
        /**
         * Adds a text field
         */
        $f_name = $form->add_field('your_name', $type='TEXT');
        
        $f_name->add_validation( array( 'required'=>NULL ) );
        
        /**
         * Adds a password field
         */
        $f_password = $form->add_field('the_password', $type='PASSWORD');
        
        /**
         * Close a fieldset
         *
         * This is optional. If you do not close a fieldset, it is closed whenever you open a new one or
         * when you render the form
         */
        $fset_close = $form->fieldset_close();
        
        /**
         * add_field() and all field methods return a field object so you can use method chaining
         */
        $f_hidden = $form->add_field( 'a_hidden_field', 'hidden')->set_value('Hidden field value');
        
        /**
         * Some sample data for the select type
         */
        $options = array(
            array( '1', 'dog'),
            array( '21', 'cat'),
            array( '1234', 'mouse'),
            array( '3', 'cake'),
            array( '4', 'house'),
            array( '5', 'meh'),
        );
        
        /**
         * Adds the select field
         */
        $f_select = $form->add_field('select_field', 'SELECT')->set_options($options);
        
        /**
         * Adds the radio field using the same options as for the select field above
         */
        $f_radio = $form->add_field('radio_field', 'RADIO')->set_options($options);
        
        /**
         * A checkbox field
         */
        $f_check = $form->add_field('check_field', $type='CHECKBOX', $caption='This is the checkbox caption' );
        
        /**
         * A textarea field
         */
        $f_textarea = $form->add_field('textarea_field', $type='TEXTAREA', $caption='This is the textarea caption' );
        
        $f_textblock = $form->add_text( 'textblock_field', 'This is a block of text included in the form using the "textblock" field type.' );
        
        /**
         * A Googlemap location field
         *
         * You must enter your API key into app_settings.php
         */
        if( TINA_MVC\get_tina_mvc_setting('google_api_key_v3') ) {
            
            $f_map = $form->add_field( 'map_field', 'GOOGLEMAP');
            
            /**
             * Sets the height and width of the page in CSS units
             */
            $f_map->set_map_height( 200 );
            $f_map->set_map_height( 400 );
            
        }
        else {
            
            $f_gmap_note = $form->add_text( 'gmap_note', 'You must enter your Google API key (v3) into app_settings.php to use the Google Map location field type.' );
            
        }
        
        /**
         * A reCaptcha field
         *
         * The recaptcha field type requires your API keys to be set in app_settings.php
         *
         * @see http://www.reCaptcha.net
         */
        if( TINA_MVC\get_tina_mvc_setting('recaptcha_pub_key') AND TINA_MVC\get_tina_mvc_setting('recaptcha_pri_key') ) {
            
            $f_recaptcha = $form->add_field('recaptcha_field', $type='RECAPTCHA', $caption='Are you human?' );
            
        }
        else {
            
            $f_recaptcha_note = $form->add_text( 'recaptcha_note', 'You must enter your reCaptcha API keys into app_settings.php to use the reCaptcha field type.' );
            
        }
        
        /**
         * A file upload field
         */
        $f_file_upload = $form->add_field( 'a_file_upload', 'file' );
        
        /**
         * A second file upload field
         */
        $f_second_file_upload = $form->add_field( 'a_second_file_upload', 'file' );
        
        /**
         * A submit button
         */
        $f_submit = $form->add_field('submit_field', $type='SUBMIT', $caption='This is the submit caption' );
        
        /**
         * A reset button
         */
        $f_reset = $form->add_field('reset_field', $type='RESET', $caption='This is the reset caption' );
        
        /**
         * Some data to pre-load into the form.
         *
         * This could come from a database query for example.
         */
        $data = array('name'=>'Joe Bloggs', 'textarea_field'=>"Some multiline\r\ntext", 'radio_field'=>3 );
        
        /**
         * Loads the data into the form, overriding any default values previously set
         *
         * You would use this function to load a recordset from (for example a database query) into
         * the form. First, any keys in the $data array will match field names. After this any 'db_field' 
         * values matching keys in $data have their values set too.
         */
        $form->load_data( $data );
        
        /**
         * Returns an array of posted data, FALSE if the form has not been posted.
         */
        if( $posted_data = $form->get_posted_data() ) {
            
            $content = "Posted Data<br />";
            $content .= "<pre>" . print_r( $posted_data, TRUE ) . "</pre>";
            
            /**
             * Generally you process your form here and finish off with a wp_redirect() and exit()
             */
            // wp_redirect( 'wherever' );
            // exit();
            
        }
        else {
            
            /**
             * Returns the HTML required to disply the form
             */
            $content = $form->render();
            
        }
        
        /**
         * Assign as template data
         */
        $this->add_var( 'view_html', $content );
        
        /**
         * Set the post (page) title and contents.
         */
        $this->set_post_title('Tina MVC New Form Helper Example');
        $this->set_post_content( $this->load_view('test_form') );
        
    }

}

