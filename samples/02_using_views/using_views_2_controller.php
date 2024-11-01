<?php
/**
 * Sample controller
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
/**
 * Using Views 2 - passing output directly to the page/post
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
class using_views_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The default function
     *
     * Set up some variables and add them to the page content
     */
    public function index() {
        
        $multiline_content = "This is a string
        
        that spans several lines. We'll let
        wpautop() take care of it";
        
        $html_content = '<strong>This is text wrapped in STRONG tags</strong>';
        
        $h2_content = "This text will be wrapped in h2 tags";
        
        $h3_content = "This text will be wrapped in h3 tags";
        
        /**
         * Sets the post title
         */
        $this->set_title( 'Testing add_content() and add_raw_content()' );
        
        /**
         * Add the various strings above to the page
         *
         * Because we are not calling load_view() Tina MVC will look for content that has
         * been added using add_content() and add_raw_content()
         */
        $this->add_content( $multiline_content );
        $this->add_raw_content( $html_content );
        $this->add_content( $h2_content, 'h2' );
        $this->add_content( $h3_content, 'h3' );
        
    }

}
