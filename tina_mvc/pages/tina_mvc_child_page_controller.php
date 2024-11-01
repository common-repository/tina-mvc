<?php
/**
 * Default controller
 *
 * @package    Tina-MVC
 * @subpackage Core
 */
/**
 * Displays a message for the sample page
 *
 * @package    Tina-MVC
 * @subpackage Core
 *
 */
class tina_mvc_child_page_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The default function called by the dispatcher
     */
    public function index() {
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Welcome to Tina MVC for Wordpress');
        
        /**
         * Sets the post content
         */
        $this->set_post_content( $this->load_view('tina_mvc_child_page_index') );
        
    }
    
}
