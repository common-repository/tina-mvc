<?php
/**
 * Sample controller
 *
 * @package    Tina-MVC
 * @subpackage Samples
 */
/**
 * The second "Hello World" example
 * @package    Tina-MVC
 * @subpackage Samples
 */
class hello_world_controller extends TINA_MVC\tina_mvc_controller_class {
    
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
        
        $out = $this->_get_boilerplate();
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Hello World on '.date('l').' from index()');
        
        /**
         * Sets the post content
         */
        $this->set_post_content($out);
        
    }
    
    /**
     * Display a message and the Tina MVC request
     */
    public function another_function() {
        
        $out = $this->_get_boilerplate();
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Hello from another_function()');
        
        /**
         * Sets the post content
         */
        $this->set_post_content($out);
        
    }
    
    /**
     * A private function cannot be called by the Tina MVC dispatcher.
     *
     * It uses the ReflectionMethod class to check if the function is not private. You can shortcut
     * that check by naming your function with an underscore as the first character.
     */
    private function _get_boilerplate() {
        
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
        
        return $out;
        
    }

}
