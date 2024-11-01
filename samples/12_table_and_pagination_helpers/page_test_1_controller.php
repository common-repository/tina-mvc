<?php
/**
 * Sample controller 1 for the pagination helper
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
/**
 * Test Pagination helper
 * @package    Tina-MVC
 * @subpackage Samples
 */
class page_test_1_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The default function
     *
     * Set up some variables and add them to the view file data
     */
    public function index() {
        
        $content = '';
        
        TINA_MVC\include_helper('pagination');
        
        global $wpdb;
        
        /**
         * We are using custom SQL here to demonstrate the use of the pager.
         *
         * Have a look at the Demo Data Creator plugin if you want to create many
         * dummy users for use with this example... or even better play with your own data
         *
         * The $base_sql is a properly escaped statement that will get you all the rows you want. ORDER BY and LIMIT clauses
         * are not set - they will be set automatically.
         *
         * Pay particular attention to the field names and their aliases. These are relevent when it comes to setting
         * sortable columns and a filter box (below).
         */
        $base_sql = 'SELECT ID AS `Database ID`, user_login AS `User Login`, display_name AS `Display Name` FROM '.$wpdb->users;
        
        /**
         * A SQL statement to get the total number of rows returned in the above statement.
         */
        $count_sql = 'SELECT COUNT(ID) FROM '.$wpdb->users;
        
        /**
         * The HTML table ID is set from the parameter you ppass to the constructor
         */
        $P = new TINA_MVC\pagination('my_paginator');
        
        $P->set_count_from_sql( $count_sql );
        
        /**
         * The url required to get to the default table view
         */
        $P->set_base_url( TINA_MVC\get_controller_url('page-test-1') );
        
        /**
         * Set up the filter.
         *
         * This allows a user to search on various fields (even if they are not
         * selected for display). This will output a form at the top of the table
         * of results.
         * 
         * Parameter is array ( 'Display Name' => 'mysql_field_name' )
         *
         * These must match the field names and aliases in $base_sql
         */
        $P->filter_box_on_fields( array(
                                        'User Login' => 'user_login',
                                        'Display Name' => 'display_name',
                                        'user_email' => 'user_email'
                                        )
                                 );
        
        $P->set_base_sql( $base_sql );
        
        /**
         * For the pagination links
         */
        $P->set_mid_range( 9 )->set_items_per_page( 10 )
                              ->set_default_sort_by( 'User Login' )
                              ->set_default_sort_order( 'desc' );
        
        /**
         * Grab the html.
         */
        $content = $P->get_html();
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Testing Pagination');
        
        /**
         * Sets the post content. Note we are not using a view file for this example
         */
        $this->set_post_content( $content );
        
    }

}
