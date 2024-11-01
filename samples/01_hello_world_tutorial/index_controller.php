<?php
/**
 * Sample controller
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
/**
 * The first "Hello World" example
 * @package    Tina-MVC
 * @subpackage Samples
 */
class index_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The constructor is not required for normal operation of Tina MVC
     */
    public function __construct() {}
    
    /**
     * The default function called
     *
     * Usually you would place the output in a seperate view file. It is in-line here only for
     * clarity.
     */
    public function index() {
        
        /**
         * Some HTML, including a date and time so we can be sure it isn't being cached
         */
        $out = '<div style="border: thick dashed black; color: black; background-color: #ccc;">';
        $out .= 'This text is generated from the \'tina_says_hello\' controller ';
        $out .= 'on '.date('Y-m-d').' at '.date('H:n:s');
        $out .= '</div>';
        
        /**
         * Display the Tina MVC request variable
         */
        $out .= '<pre>The Tina MVC Request was:<br />' . print_r( $this->request , TRUE ) . '</pre>';
        
        /**
         * Display the raw Tina MVC request variable
         */
        $out .= '<pre>The raw Tina MVC Request was:<br />' . print_r( $this->raw_request , TRUE ) . '</pre>';
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Happy '.date('l'));
        
        /**
         * Sets the post content
         */
        $this->set_post_content($out);
        
    }

}
