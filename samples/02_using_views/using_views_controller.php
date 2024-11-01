<?php
/**
 * Sample controller
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
/**
 * Using Views
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
class using_views_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The default function
     *
     * Set up some variables and add them to the view file data
     */
    public function index() {
        
        /**
         * A simple variable
         *
         * Use $this->add_var_e() to add a virable needing escaping
         */
        $this->add_var_e( 'hello_world', 'Hello World!' );
        
        /**
         * An array variable
         *
         * Again we use add_var_e(). It will recursively escape the data for display.
         */
        $array_data = array(
            'first' => 'Tina',
            'second' => 'MVC',
            'third' => 'rocks!',
        );
        $this->add_var_e( 'the_array', $array_data );
        
        /**
         * A link
         */
        $link = '<a href="http://www.SeeIT.org/tina-mvc-for-wordpress/" target="_blank">Tina MVC at SeeIT.org</a>';
        
        /**
         * Correct - the link shouldn't be escaped
         */
        $this->add_var( 'link1', $link );
        
        /**
         * Wrong - this will escape the HTML
         */
        $this->add_var_e( 'link2', $link );
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Using view files');
        
        /**
         * Sets the post content
         *
         * The first parameter to the load_view function is the name of the view file. If you leave out this parameter
         * the name of the view file is automagically searched for based on the class and function that made the call to
         * load_view. In this case, the filename being looked for would be 'using_views_index_view.php, i.e.
         * {classname}_{functionname}_view.php
         */
        $this->set_post_content( $this->load_view('using_views') );
        
    }

}
