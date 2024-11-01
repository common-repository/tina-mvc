<?php
/**
 * Sample controller 3 for the pagination helper
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
/**
 * Yet another sample paginator
 *
 * Illustrates the use of the tina_mvc_pagination_helper_class
 *
 * This shows how to supress sortable columns. In cases where your SQL statement is
 * complex the Pagination Helper will munge your SQL code and cause errors when
 * you click on a heading
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
class page_test_3_controller extends TINA_MVC\tina_mvc_controller_class {
    
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
        
        $P->set_base_url( TINA_MVC\get_controller_url('page-test-3') );
        
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
         *
         * In this case we are just building some HTML. You would normally use a
         * view file instead of putting HTML in your page controller.
         */
        $rows = $P->get_sql_rows();
        
        // TINA_MVC\prd( $rows );
        
        /**
         * Iterate throught the results and build some HTML
         */
        foreach( $rows AS $i => & $r ) {
            
            if( $i % 2 ) {
                $bg = '#333';
                $fg = '#ccc';
            }
            else {
                $bg = '#ccc';
                $fg = '#333';
            }
            
            $r->{'Database ID'} = '<span style="color:'.$fg.';background:'.$bg.'">'.$wpdb->escape($r->{'Database ID'}).'</span>';
            $r->{'User Login'} = '<span style="color:'.$fg.';background:'.$bg.'"><a href="#" title="'.$wpdb->escape($r->{'Display Name'}).'">'.$r->{'User Login'}.'</a></span>';
            $r->{'A non-DB field (non-sortable)'} = '<span style="color:'.$fg.';background:'.$bg.'"><a href="#" title="'.$wpdb->escape($r->{'Display Name'}).'">'.$i.'</a></span>';
            
            /**
             * You can also unset() an entry here
             */
            // $r->{'Display Name'} = '<span style="color:'.$fg.';background:'.$bg.'">'.$wpdb->escape($r->{'Display Name'}).'</span>';
            unset( $r->{'Display Name'} );
            
        }
        
        /**
         * Set the rows, overriding the use of the html table helper
         */
        $P->set_html_rows( $rows );
        
        /**
         * This will prevent the helper from adding sortable column HTML to the
         * column heading 'A non-DB field (non-sortable)'.
         *
         * You cannot sort on columns that do not come directly from the database.
         */
        $P->suppress_sort( array('A non-DB field (non-sortable)') );
        
        $this->set_post_title('Pagination');
        $this->set_post_content( $P->get_html() );
        
    }
    
}

