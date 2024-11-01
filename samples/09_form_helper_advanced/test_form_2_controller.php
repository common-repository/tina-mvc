<?php
/**
 * A page controller - The forms helper - advanced use
 *
 * @package    Tina-MVC
 * @subpackage Samples
 * @author     Francis Crossen <francis@crossen.org>
 */

/**
 * An example of how to use more advanced features of the tina_mvc_form_helper_class
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
class test_form_2_controller extends TINA_MVC\tina_mvc_controller_class {
    
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
         * Adds a text field
         */
        $text_field_1 = $form->add_field('text_field_1', $type='TEXT');
        
        /**
         * It is possible to retrieve the $_POST value for a field without checking if the form has been posted
         * using the get_posted_value() method.
         */
        $text_message = $form->add_text( 'posted_message', 'The $_POST value for \'Text Field 1\' is: "'. $text_field_1->get_posted_value() .'"');
        
        /**
         * Adds a validation rule
         *
         * All validation rules are of the form array( 'rule_name' => parameters ). Check the form helper file
         * for classes called validate_* for rules, parameters and for how to add your own validation rules.
         */
        $text_field_1->add_validation( array( 'required'=>NULL, 'min_length'=>3, 'max_length'=>7 ) );
        
        /**
         * The same using method chaining...
         */
        $text_field_2 = $form->add_field('text_field_2', $type='TEXT')->add_validation( array( 'required'=>NULL ) );
        
        /**
         * Adding a field showing all available parameters:
         *
         * $name - the field name. Used as a key in the array of posted data returned by $form->get_posted_data().
         * $type - see the field_* classes in the form helper file for a complete list of fields (or see
         *         the samples in 08_form_helper_intro)
         * $caption - The label. Default label is based on the $name parameter. For example 'first_name' => 'First Name'
         * $db_table - used to group fields. For example, posted fields grouped according to $db_table = 'my_table' can
         *         be retrieved using $form->get_posted_db_data('my_table') (gets data with keys based on the $db_field value)
         *         or $form->get_posted_data('my_table') (gets data with keys based on the field $name value)
         * $db_field - the database field name (if different from the $name value). Use with $form->get_posted_db_data()
         * $default_value
         * $extra_attribs - a string (for example 'attribute="value"') or array of strings
         */
        $text_field_3 = $form->add_field( 'text_field_3', 'text', 'Please enter a value for this field', 'my_table', 'my_field', 'Default value', 'style="color: white; background: black;"' );
        
        /**
         * Similar to above, but using method chaining and passing several extra html attributes to the input
         */
        $text_field_4 = $form->add_field('field_4', $type='TEXT')
                             ->set_caption('Field Four')
                             ->set_db_table('my_table')
                             ->set_db_field('field_four')
                             ->set_default_value('Another default')
                             ->set_extra_attribs('style="background: #ccc;"')
                             ->set_extra_attribs('title="Like a tooltip, touched for the very first time..."');
        
        /**
         * Extra attributes can be passed as an array too
         */
        $text_field_5 = $form->add_field('field_5', $type='TEXT')->set_db_table('my_table')
                             ->set_extra_attribs( array( 'style="font-style: italic;"', 'title="Another title attribute"') );
        
        /**
         * A submit button
         */
        $f_submit = $form->add_field('submit_field', $type='SUBMIT', $caption='This is the submit caption' );
        
        /**
         * A reset button
         */
        $f_reset = $form->add_field('reset_field', $type='RESET', $caption='This is the resetcaption' );
        
        /**
         * Returns an array of posted data, FALSE if the form has not been posted.
         */
        if( $posted_data_1 = $form->get_posted_data() ) {
            
            /**
             * $posted_data_1 is an array of posted values. Keys are the field names. Values are taken from fields with a blank 'db_table' value
             */
            $content = 'Posted Data: retrieved with $form->get_posted_data()<br />';
            $content .= "<pre>" . print_r( $posted_data_1, TRUE ) . "</pre><br />";
            
            /**
             * Returns an array of posted values. Keys are the field names. Values are taken from fields where 'db_table' = 'my_table'
             */
            $posted_data_2 = $form->get_posted_data('my_table');
            $content .= "Posted Data: retrieved with \$form->get_posted_data('my_table')<br />";
            $content .= "<pre>" . print_r( $posted_data_2, TRUE ) . "</pre><br />";
            
            /**
             * Returns an array of posted values. Keys are taken from 'db_field' value. Values are taken from fields with a blank 'db_table' value
             */
            $posted_data_3 = $form->get_posted_db_data();
            $content .= "Posted Data: retrieved with \$form->get_posted_db_data()<br />";
            $content .= "<pre>" . print_r( $posted_data_3, TRUE ) . "</pre><br />";
            
            /**
             * Returns an array of posted values. Keys are taken from 'db_field' value. Values where fields 'db_table' = 'my_table'
             */
            $posted_data_4 = $form->get_posted_db_data('my_table');
            $content .= "Posted Data: retrieved with \$form->get_posted_db_data('my_table')<br />";
            $content .= "<pre>" . print_r( $posted_data_4, TRUE ) . "</pre><br />";
            
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
        $this->set_post_content( $this->load_view('test_form_2') );
        
    }

}

