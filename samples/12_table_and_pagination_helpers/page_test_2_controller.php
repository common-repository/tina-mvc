<?php
/**
 * Sample controller 2 for the pagination helper
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
/**
 * Test Pagination helper
 *
 * Shows how to replace table rows with your own HTML
 * @package    Tina-MVC
 * @subpackage Samples
 */
class page_test_2_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The default function
     *
     * Set up some variables and add them to the view file data
     */
    public function index() {
        
        /**
         * This part is the same as the previous example. For brevity comments are removed.
         */
        $content = '';
        TINA_MVC\include_helper('pagination');
        global $wpdb;
        
        $base_sql = 'SELECT ID AS `Database ID`, user_login AS `User Login`, display_name AS `Display Name` FROM '.$wpdb->users;
        $count_sql = 'SELECT COUNT(ID) FROM '.$wpdb->users;
        
        $P = new TINA_MVC\pagination('my_paginator');
        $P->set_count_from_sql( $count_sql );
        
        /**
         * OK, not quite the same. The base url is obviously different!
         */
        $P->set_base_url( TINA_MVC\get_controller_url('page-test-2') );
        
        $P->filter_box_on_fields( array(
                                        'User Login' => 'user_login',
                                        'Display Name' => 'display_name',
                                        'user_email' => 'user_email'
                                        )
                                 );
        
        $P->set_base_sql( $base_sql );
        $P->set_mid_range( 9 )->set_items_per_page( 10 )
                              ->set_default_sort_by( 'User Login' )
                              ->set_default_sort_order( 'desc' );
                              
        /**
         * We will get the results and format custom HTML rows rather than allow
         * the pagination helper to give us a generic table. You should use escaped
         * HTML here.
         */
        $rows = $P->get_sql_rows();
        
        // TINA_MVC\prd( $rows );
        
        /**
         * Iterate throught the results and build some HTML
         */
        foreach( $rows AS $i => & $r ) {
            
            //echo $r->{'User Login'};
            
            /**
             * We'll use this is the view file to produce the alternating row styles
             */
            $this->add_var( 'i', $i );
            
            /**
             * The row of data
             *
             * You need to be careful here if your array keys (or object variables) require escaping.
             */
            $this->add_var_e( 'r', $r );
            
            /**
             * The view file here is only a HTML snippet. It renders only one row.
             *
             * It is the first example of using a view snippet to produce output.
             *
             * In this case, the view file is used to render headings or rows based on the value of $view_file_part
             */
            $this->add_var( 'view_file_part', 'rows' );
            $r = $this->load_view( 'page_test_2' );
            
        }
        
        /**
         * Set the rows, overriding the use of the html table helper.
         *
         * This will also supress the table headings.
         */
        $P->set_html_rows( $rows );
        
        /**
         * Set the table headings. This is optional.
         *
         * In this case, the view file is used to render headings or rows based on the value of $view_file_part
         */
        $this->add_var( 'view_file_part', 'headings' );
        $P->set_html_headings( $this->load_view( 'page_test_2' ) );
        
        /**
         * Grab the html.
         */
        $content = $P->get_html();
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Testing Pagination 2');
        
        /**
         * Sets the post content. Note we are not using a view file for this example
         */
        $this->set_post_content( $content );
        
    }

}
