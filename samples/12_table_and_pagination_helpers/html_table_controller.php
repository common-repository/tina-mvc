<?php
/**
* Demo of the table helper in use
*
* @package    Tina-MVC
* @subpackage Samples
*/

/**
* An example of the table helper in use
*
* @package    Tina-MVC
* @subpackage Samples
*/
class html_table_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * Generate some random data and use the tina_mvc_html_table_helper to generate HTML
     * tables
     */
    function index() {
        
        /**
         * Include the helper
         */
        TINA_MVC\include_helper('table');
        
        /**
         * Quick and dirty... the HTML we'll output
         *
         * No fancy view files here...
         */
        $html_out = '';
        
        /**
         * The first table
         * 
         * Generate an array of data to format
         */
        $table_headings = array( 'column_one' , 'Column 2' , '<col-3>' );
        $table_data = array();
        for( $i=0; $i<5; $i++ ) {
            
            foreach( $table_headings AS $j => $heading ) {
                $table_data[$i][$heading] = rand();
            }
            
        }
        
        $table = new TINA_MVC\table( 'first_table' );
        $table->set_data( $table_data );
        
        $html_out .= '<h2>The First Table</h2>';
        $html_out .= $table->get_html();
        
        /**
         * All done
         */
        unset( $table );
        
        /**
         * The second table
         * 
         * Generate an object of data to format
         */
        $table_headings = array( '<a href="#">column_one</a>' , 'Column 2(&euro;)' , 'Now you see me -&gt; &lt;col-3&gt; and now you don\'t -&gt; <col-3>' );
        $table_data = new stdClass;
        for( $i=0; $i<12; $i++ ) {
            
            foreach( $table_headings AS $j => $heading ) {
                $table_data->$i->$heading = rand();
            }
            
        }
        
        $table = new TINA_MVC\table( 'second_table' );
        $table->set_data( $table_data );
        
        /**
         * Because we have proper HTML in the headers, we don't want to escape the
         * table headings
         */
        $table->do_not_esc_th( TRUE );
        
        $html_out .= '<h2>The Second Table</h2>';
        $html_out .= $table->get_html();
        
        /**
         * Method chaining is also supported. You can do
         * 
         * $html_out .= $table->set_data( $table_data )->do_not_esc_th( TRUE )->get_html();
         */
        
        $this->set_post_title('My Beautiful Tables');
        
        $this->set_post_content($html_out);
        
    }
    
}

