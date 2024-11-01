<?php
/**
* Default controller
*
* @package    Tina-MVC
* @subpackage Core
*/
/**
* Displays a welcome page in the even that there are no user applications installed
*/
class index_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * The default function called by the dispatcher
     */
    public function index() {
        
        /**
         * Sets the post title
         */
        $this->set_post_title('Welcome to Tina MVC for Wordpress');
        
        $this->add_var_e('controller_file',__FILE__);
        
        /**
         * Sets the post content
         */
        $this->set_post_content( $this->load_view('index_index') );
        
    }
    
}
