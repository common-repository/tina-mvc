<?php
/**
 * The Tina MVC admin pages - main controller for producing documentation
 *
 * @package    Tina-MVC
 * @subpackage Docs
 */
/**
 * Wordpress admin pages for Tina MVC
 * @package    Tina-MVC
 * @subpackage Docs
 */
class admin_pages_controller extends TINA_MVC\tina_mvc_controller_class {
    
    /**
     * Constructor is not required
     */
    function __construct() {}
    
    /**
    * Default controller - the main Tina MVC admin page
    */
    function index() {
        
        $this->set_post_content( $this->load_view( 'admin_pages_index' , $this->view_data, 'tina_mvc/admin_pages' ) );
        
    }

    /**
    * Hello World Tutorial
    */
    function hello_world() {
        
        // load up the code samples
        $code01 = highlight_file( TINA_MVC\plugin_folder().'/samples/01_hello_world_tutorial/index_controller.php', TRUE );
        $this->add_var( 'code01', $code01 );
        
        $code02 = highlight_file( TINA_MVC\plugin_folder().'/samples/01_hello_world_tutorial/hello_world_controller.php', TRUE );
        $this->add_var( 'code02', $code02 );
        
        $this->set_post_content( $this->load_view( 'admin_pages_hello_world' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * Using views tutorial
    */
    function using_views() {
        
        // load up the code samples
        $code03 = highlight_file( TINA_MVC\plugin_folder().'/samples/02_using_views/using_views_view.php', TRUE );
        $this->add_var( 'code03', $code03 );
        
        $code04 = highlight_file( TINA_MVC\plugin_folder().'/samples/02_using_views/using_views_controller.php', TRUE );
        $this->add_var( 'code04', $code04 );
        
        $code04a = highlight_file( TINA_MVC\plugin_folder().'/samples/02_using_views/using_views_2_controller.php', TRUE );
        $this->add_var( 'code04a', $code04a );
        
        $this->set_post_content( $this->load_view( 'admin_pages_using_views' , $this->view_data, 'tina_mvc/admin_pages' ) );
        
    }
    
    /**
    * Adding models tutorial
    */
    function adding_models() {
        $this->set_post_content( $this->load_view( 'admin_pages_adding_models' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * Code hooks tutorial
    */
    function code_hooks() {
        
        // load up the code samples
        $code041 = highlight_file( TINA_MVC\plugin_folder().'/samples/04_code_hooks/tina_mvc_app_install.php', TRUE );
        $this->add_var( 'code041', $code041 );
        
        $this->set_post_content( $this->load_view( 'admin_pages_code_hooks' , $this->view_data, 'tina_mvc/admin_pages' ) );
        
    }
    
    /**
    * File Locations
    */
    function file_locations() {
        $this->set_post_content( $this->load_view( 'admin_pages_file_locations' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * Custom login functionality
    */
    function custom_login() {
        $this->set_post_content( $this->load_view( 'admin_pages_custom_login' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * Helper Functions
    */
    function helper_functions() {
        
        // grab user defined functions
        $functions = get_defined_functions();
        
        unset( $functions['internal'] );
        $tina_functions = array();
        
        $functions_file = file_get_contents( \TINA_MVC\tina_mvc_folder().'/helpers/tina_mvc_functions.php' );
        
        foreach( $functions['user'] AS $f ) {
            
            if( strpos( $f, 'tina_mvc\\' ) === 0  AND strpos( $f, 'tina_mvc\\utils' ) === FALSE ) {
                
                $function_offset = strpos( $functions_file, 'function '.str_replace( 'tina_mvc\\', '', $f ) );
                
                $functions_file_part = substr( $functions_file, 0, $function_offset);
                
                $start_char = strrpos( $functions_file_part, '/**' );
                $end_char = strrpos( $functions_file_part, '*/' );
                $docBlockComment = substr( $functions_file, $start_char, $end_char-$start_char+2 );
                
                $start_char = strpos( $functions_file, '(', $function_offset );
                $end_char = strpos( $functions_file, ')', $function_offset );
                $function_params = substr( $functions_file, $start_char, $end_char-$start_char+1 );
                
                $tina_functions[$f]['function'] = $f.$function_params;
                $tina_functions[$f]['docblock'] = $docBlockComment;
                
            }
            
        }
        
        $this->add_var_e( 'tina_functions', $tina_functions );
        
        $this->set_post_content( $this->load_view( 'admin_pages_helper_functions' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * Widgets and Shortcodes
    */
    function widgets_shortcodes() {
        
        $code_snippet  = '<?php'."\r\n";
        $code_snippet .= "\r\n";
        $code_snippet .= 'function admin_page_widgets_shortcodes() {'."\r\n";
        $code_snippet .= '    global $Tina_MVC;'."\r\n";
        $code_snippet .= "    echo \$Tina_MVC->call_controller('admin-pages/widgets-shortcodes', FALSE, 'manage_options', \\TINA_MVC\\tina_mvc_folder().'/admin_pages' );"."\r\n";
        $code_snippet .= '}'."\r\n";
        $code_snippet = highlight_string( $code_snippet, TRUE );
        $this->add_var( 'code_snippet', $code_snippet );
        
        $this->set_post_content( $this->load_view( 'admin_pages_widgets_shortcodes' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * The Form Helper - Basic Use
    */
    function form_helper_intro() {
        
        // load up the code samples
        $code081 = highlight_file( TINA_MVC\plugin_folder().'/samples/08_form_helper_intro/test_form_controller.php', TRUE );
        $this->add_var( 'code081', $code081 );
        
        $code082 = highlight_file( TINA_MVC\plugin_folder().'/samples/08_form_helper_intro/test_form_view.php', TRUE );
        $this->add_var( 'code082', $code082 );
        
        $this->set_post_content( $this->load_view( 'admin_pages_form_helper_intro' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * The Form Helper - Advanced Use
    */
    function form_helper_advanced() {
        
        // load up the code samples
        $code091 = highlight_file( TINA_MVC\plugin_folder().'/samples/09_form_helper_advanced/test_form_2_controller.php', TRUE );
        $this->add_var( 'code091', $code091 );
        
        $code092 = highlight_file( TINA_MVC\plugin_folder().'/samples/09_form_helper_advanced/test_form_2_view.php', TRUE );
        $this->add_var( 'code092', $code092 );
        
        $this->set_post_content( $this->load_view( 'admin_pages_form_helper_advanced' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * The Form Helper - List of field types and validation rules
    */
    function form_helper_fields_and_validation() {
        
        $classes_file = file_get_contents( \TINA_MVC\helpers_folder().'/tina_mvc_form_helper_class.php' );
        TINA_MVC\include_helper('form');
        
        $classes = \get_declared_classes();
        
        // grab the field types
        $field_types = array();
        $validate_types = array();
        
        // loop through the classes and filter out the ones that begin with 'TINA_MVC\field_' or 'TINA_MVC\validate_'
        // this loop builds the two arrays - $field_types and $validate_types
        foreach( $classes AS $c ) {
            
            $class_type = FALSE;
            // have we a match?
            if( strpos( strtolower($c), 'tina_mvc\\field_' ) === 0 ) {
                $class_type = 'field';
            }
            elseif( strpos( strtolower($c), 'tina_mvc\\validate_' ) === 0 ) {
                $class_type = 'validate';
            }
            
            // yep - we do
            if( $class_type ) {
                
                $short_c_name = str_replace( 'TINA_MVC\\', '', $c );
                
                $class_offset = strpos( $classes_file, "class $short_c_name" );
                
                $classes_file_part = substr( $classes_file, 0, $class_offset);
                
                $start_char = strrpos( $classes_file_part, '/**' );
                $end_char = strrpos( $classes_file_part, '*/' );
                $docBlockComment = substr( $classes_file, $start_char, $end_char-$start_char+2 );
                
                $array_var_name = $class_type.'_types';
                
                ${$array_var_name}[$short_c_name]['class'] = $short_c_name;
                ${$array_var_name}[$short_c_name]['type_for_helper'] = str_replace( ($class_type.'_'), '', $short_c_name );
                ${$array_var_name}[$short_c_name]['docblock'] = $docBlockComment;
                
            }
            
        }
        
        $this->add_var_e( 'field_types', $field_types );
        $this->add_var_e( 'validate_types', $validate_types );
        
        $this->set_post_content( $this->load_view( 'admin_pages_form_helper_fields_and_validation' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
    /**
    * The Table and Pagination helper function
    */
    function table_and_pagination_helpers() {
        
        $this->add_var( 'code121', highlight_file( TINA_MVC\plugin_folder().'/samples/12_table_and_pagination_helpers/html_table_controller.php', TRUE ) );
        $this->add_var( 'code122', highlight_file( TINA_MVC\plugin_folder().'/samples/12_table_and_pagination_helpers/page_test_1_controller.php', TRUE ) );
        $this->add_var( 'code123', highlight_file( TINA_MVC\plugin_folder().'/samples/12_table_and_pagination_helpers/page_test_2_controller.php', TRUE ) );
        $this->add_var( 'code124', highlight_file( TINA_MVC\plugin_folder().'/samples/12_table_and_pagination_helpers/page_test_2_view.php', TRUE ) );
        $this->add_var( 'code125', highlight_file( TINA_MVC\plugin_folder().'/samples/12_table_and_pagination_helpers/page_test_3_controller.php', TRUE ) );
        
        $this->set_post_content( $this->load_view( 'admin_pages_table_and_pagination_helpers' , $this->view_data, 'tina_mvc/admin_pages' ) );
        
    }
    
    /**
    * The TODO List
    */
    function todo_list() {
        $this->set_post_content( $this->load_view( 'admin_pages_todo_list' , $this->view_data, 'tina_mvc/admin_pages' ) );
    }
    
}
