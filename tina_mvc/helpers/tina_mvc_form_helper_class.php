<?php
/**
* Form Helper class file
*
* Builds a form, checks and validates $_POST and $_GET variables and generates HTML for
* you
*
* @package    Tina-MVC
* @subpackage Core
*/

namespace TINA_MVC {
    
    /**
     * Renderer for INPUT type 'text'
     *
     * You can extend the form helper by using these classes as an example. You must
     * define a html() function which returns the escaped HTML required to display the element.
     * Look at field_googlemap or field_recaptcha for an example of a more complex field type.
     *
     * You can also define a function setup() which accepts the field object as a parameter. See
     * field_recaptcha to see how this works.
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class field_text {
        
        /**
         * Set FALSE to prevent a LABEL element being generated before this element. If not
         * defined $render_label is assumed TRUE
         */
        var $render_label = TRUE; // not needed - default behaviour
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            return '<input type="text" id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" value="'.esc_html($f->get_value()).'" '.$f->get_extra_attribs().' '.$f->get_xhtml_slash().'>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'submit'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_submit {
        
        /**
         * Prevents a label from being generated for this element
         * @see class field_text->render_label
         */
        var $render_label = FALSE;
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            // we use caption as button text (unless someone has the cop on to remember that the value is the button text)
            if( $f->get_value() ) {
                $text = $f->get_value();
            }
            else {
                $text = $f->get_caption();
            }
            return '<input type="submit" id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" value="'.esc_html($text).'" '.$f->get_extra_attribs().' '.$f->get_xhtml_slash().'>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'reset'
     *
     * Note that this does not reset reCaptcha fields.
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_reset {
        
        /**
         * Prevents a label from being generated for this element
         * @see class field_text->render_label
         */
        var $render_label = FALSE;
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            // we use caption as button text (unless someone has the cop on to remember that the value is the button text)
            if( $f->get_value() ) {
                $text = $f->get_value();
            }
            else {
                $text = $f->get_caption();
            }
            return '<input type="reset" id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" value="'.esc_html($text).'" '.$f->get_extra_attribs().' '.$f->get_xhtml_slash().'>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'textblock'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_textblock {
        
        /**
         * Set as TRUE to prevent the render function from trying to render a label/input pair. The output
         * will be within the form pair html block. See $form_base->html_form_pair
         */
        var $plain_html = TRUE;
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            return '<div id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" '.$f->get_extra_attribs().'>'.esc_html($f->get_value()).'</div>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'fieldset_open'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_fieldset_open {
        
        /**
         * Set as TRUE to prevent the render function from wrapping the output within a form pair html
         * block. See $form_base->html_form_pair
         */
        var $no_html_wrappers = TRUE;
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            return '<fieldset id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" '.$f->get_extra_attribs().'><legend>'.esc_html($f->get_value()).'</legend>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'fieldset_close'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_fieldset_close {
        
        /**
         * Set as TRUE to prevent the render function from wrapping the output within a form pair html
         * block. See $form_base->html_form_pair
         */
        var $no_html_wrappers = TRUE;
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            return '</fieldset>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'password'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_password {
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            return '<input type="password" id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" value="'.esc_html($f->get_value()).'" '.$f->get_extra_attribs().' autocomplete="off" '.$f->get_xhtml_slash().'>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'hidden'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_hidden {
        
        /**
         * Prevents the label from being generated
         * @var boolean
         */
        var $render_label = FALSE;
        
        /**
         * Set as TRUE to prevent the render function from wrapping the output within a form pair html
         * block. See $form_base->html_form_pair
         */
        var $no_html_wrappers = TRUE;
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            return '<input type="hidden" id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" value="'.esc_html($f->get_value()).'" '.$f->get_extra_attribs().' '.$f->get_xhtml_slash().'>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'textarea'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_textarea {
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            return '<textarea id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" '.$f->get_extra_attribs().'>'.esc_html($f->get_value()).'</textarea>';
        }
        
    }
    
    /**
     * Renderer for INPUT type 'checkbox'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_checkbox {
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            $_h = '<input type="checkbox" id="'.$f->get_id()."\" name=\"".$f->get_post_var_name()."\" value=\"1\"";
            if( $f->get_value() ) { 
                $_h .= " CHECKED";      
            }
            $_h .= ' '.$f->get_extra_attribs().' '.$f->get_xhtml_slash() . '>';
            return $_h;
        }
        
    }
    
    /**
     * Renderer for INPUT type 'googlemap'
     *
     * The 'value' for a Googlemap is "latitude,longitude,zoom"
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_googlemap {
        
        /**
         * In case the default is not set in the app_settings.php file
         */
        public $failsafe_location_default = '53.3406,-6.2752,6';
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            
            if( ! ( $google_api = get_tina_mvc_setting('google_api_key_v3') ) ) {
                error('GOOGLEMAP field type requires \'google_api_key_v3\' to be set in app_settings.php');
            }
            
            $html = '';
            
            if( ! $lat_lng_zoom = get_tina_mvc_setting('google_maps_default_location') ) {
                $lat_lng_zoom = $this->failsafe_location_default;
            }
            
            $lat_lng_zoom = $f->get_value();
            if( empty($lat_lng_zoom) ) {
                if( ! $lat_lng_zoom = get_tina_mvc_setting('google_maps_default_location') ) {
                    $lat_lng_zoom = $this->failsafe_location_default;
                }
            }
            
            $lat_lng_zoom = explode( ',', $lat_lng_zoom);
            if( ! is_array($lat_lng_zoom) AND count($lat_lng_zoom) != 3 ) {
                error('GOOGLEMAP field requires value in the form "decimal_latitude, decimal_longitude, zoom_level"');
            }
            
            // js added?
            if ( ! isset( $f->form->googlemap_files_added ) ) {
                $js_src = 'https://maps.googleapis.com/maps/api/js?key='.$google_api.'&sensor=false';
                if( headers_sent() ) {
                    $html .= '<script type="text/javascript" src=".$js_src."></script>';
                }
                else {
                    wp_enqueue_script('googlemaps', $js_src, array(), NULL, true);
                    wp_enqueue_style( 'tina_mvc_form_helper_googlemap_css' , get_tina_mvc_folder_url().'/tina_mvc/helpers/tina_mvc_form_helper_googlemap_css.css' );
                    $f->form->googlemap_files_added = TRUE;
                }
            }
            
            $w = ( $f->get_map_width() ? $f->get_map_width() : '400px' );
            $h = ( $f->get_map_height() ? $f->get_map_height() : '300px' );
            
            $html .= '<div style="width: '.$w.'; height: '.$h.';"><div id="map_canvas_'.$f->get_id().'" class="googlemap_canvas" style="width: 100%; height: 100%"></div></div>';
            $html .= '<input type="hidden" id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" value="'.esc_html($f->get_value()).'" '.$f->get_xhtml_slash().'>';
            $html .= '<script type="text/javascript">
                        var centre_'.$f->get_id().';
                        var marker_'.$f->get_id().';
                        var map_'.$f->get_id().';
                        
                        function gmaps_initialise_'.$f->get_id().'() {
                            centre_'.$f->get_id().' = new google.maps.LatLng('.$lat_lng_zoom[0].', '.$lat_lng_zoom[1].');
                            var mapOptions = {
                                zoom: '.$lat_lng_zoom[2].',
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                center: centre_'.$f->get_id().'
                            };
                            map_'.$f->get_id().' = new google.maps.Map(document.getElementById(\'map_canvas_'.$f->get_id().'\'), mapOptions);
                            marker_'.$f->get_id().' = new google.maps.Marker({
                                map:map_'.$f->get_id().',
                                draggable:true,
                                animation: google.maps.Animation.DROP,
                                position: centre_'.$f->get_id().'
                            });
                            google.maps.event.addListener(marker_'.$f->get_id().', \'click\', toggleBounce_'.$f->get_id().' );
                            google.maps.event.addListener(marker_'.$f->get_id().', \'dragend\', updateDiv_'.$f->get_id().' );
                            google.maps.event.addListener(map_'.$f->get_id().', \'zoom_changed\', updateDiv_'.$f->get_id().' );
                            function toggleBounce_'.$f->get_id().'() {
                                if (marker_'.$f->get_id().'.getAnimation() != null) {
                                    marker_'.$f->get_id().'.setAnimation(null);
                                }
                                else {
                                    marker_'.$f->get_id().'.setAnimation(google.maps.Animation.BOUNCE);
                                }
                            }
                            function updateDiv_'.$f->get_id().'() {
                                lat_lng = marker_'.$f->get_id().'.getPosition();
                                document.getElementById(\''.$f->get_id().'\').value = lat_lng.lat() + "," + lat_lng.lng() + "," + map_'.$f->get_id().'.getZoom();
                            }
                        }
                        window.onload = gmaps_initialise_'.$f->get_id().';
                      </script>';
                      
            return $html;
            
        }
        
    }
    
    /**
     * Renderer for INPUT type 'recaptcha'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     * @see http://recaptcha.net
     */
    class field_recaptcha {
        
        /**
         * The setup function can be used to perform setup actions on a field.
         *
         * In this case we are using it to include library files and add a validation rule
         *
         * @param object $f field
         * @return void
         */
        function setup( $f ) {
            
            // include the recaptcha libs
            include_once( tina_mvc_folder().'/3rd_party/recaptcha/recaptchalib.php');
            
            $f->add_validation( array('recaptcha'=>NULL) );
            
        }
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            
            if( ! ( $recaptcha_pub_key = get_tina_mvc_setting('recaptcha_pub_key') ) OR ! ( $recaptcha_pri_key = get_tina_mvc_setting('recaptcha_pri_key') ) ) {
                error('RECAPTCHA field type requires \'recaptcha_pub_key\' and \'recaptcha_pri_key\' to be set in app_settings.php');
            }
            
            // include the recaptcha libs - already done in field definition
            // require_once( tina_mvc_folder().'/3rd_party/recaptcha/recaptchalib.php');
            
            $f->add_validation( array( 'RECAPTCHA' => NULL ) );
            
            $html = recaptcha_get_html( get_tina_mvc_setting('recaptcha_pub_key') );
            
            return $html;
            
        }
        
    }
    
    /**
     * Renderer for INPUT type 'select'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_select {
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            $_h = '<select id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" '.$f->get_extra_attribs().">\r\n";
            if( $options = $f->get_options() ) {
                $_h .= '<option value=""></option>'."\r\n";
                
                foreach( $f->get_options() AS $option ) {
                    
                    // option is array( 'post_val'=>'blah' , 'display_val'=>meh )
                    if( $f->get_value() == $option['post_value'] ) {
                        $selected = " selected ";
                    }
                    else {
                        $selected = "";
                    }
                    
                    $_h .= '<option id="'.$f->get_id().'_'.esc_html($option['post_value']).'" '.$selected.'value="'.esc_html($option['post_value']).'">'.esc_html($option['display_value']).'</option>'."\r\n";
                }
            }
            $_h .= '</select>';
            return $_h;
        }
        
    }
    
    /**
     * Renderer for INPUT type 'radio'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_radio {
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            $html = '';
            if( $f->get_options() ) {
                foreach( $f->get_options() AS $option ) {
                    // option is array( 'post_val'=>'blah' , 'display_val'=>meh )
                    
                    if( $f->get_value() == $option['post_value'] ) {
                        $selected = " checked ";
                    }
                    else {
                        $selected = "";
                    }
                    $h = '';
                    $h .= '<label for="'.$f->get_id().'_'.esc_html($option['post_value']).'">'.esc_html($option['display_value']).'</label>: ';
                    $h .= '<input id="'.$f->get_id().'_'.esc_html($option['post_value']).'" type="radio" name="'.$f->get_post_var_name().'" value="'.esc_html($option['post_value']).'" '.$selected.'/>';
                    $h = sprintf( $f->html_form_radio_set, $h );
                    $html .= $h;
                }
            }
                
            return $html;
        }
        
    }
    
    /**
     * Renderer for INPUT type 'file'
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see field_text object
     */
    class field_file {
        
        /**
         * Prevents a label from being generated for this element
         * @see class field_text->render_label
         */
        // var $render_label = FALSE;
        
        /**
         * The setup function can be used to perform setup actions on a field.
         *
         * In this case we are using it to add a custom validation rule
         *
         * @param object $f field
         * @return void
         */
        function setup( $f ) {
            
            $f->add_validation( array('file_upload'=>NULL) );
            
        }
        
        /**
         * Generates the HTML to display this element
         *
         * @param object $f the field object
         * @return string
         */
        function html($f) {
            
            if( ! ini_get('file_uploads') ) {
                error('File uploads are disabled on this webserver.');
            }
            
            $max_filesize = ini_get('upload_max_filesize');
            $last = strtolower($max_filesize[strlen($max_filesize)-1]);
            switch($last) {
                case 'g':
                    $max_filesize *= 1024;
                case 'm':
                    $max_filesize *= 1024;
                case 'k':
                    $max_filesize *= 1024;
            }
            
            $h = '';
            
            if( ! $f->form->get_file_input_added() ) {
                $f->form->set_file_input_added( TRUE );
                $h .= '<input type="hidden" name="MAX_FILE_SIZE" value="'.$max_filesize.'" '.$f->get_xhtml_slash().'>';
            }
            
            return $h . '<input type="file" id="'.$f->get_id().'" name="'.$f->get_post_var_name().'" '.$f->get_extra_attribs().' '.$f->get_xhtml_slash().'>';
            
        }
        
    }
    
    /**
     * All validate_* classes extend this class
     *
     * Add your own validation classes by calling them 'validate_myrule' and
     * declaring them in the TINA_MVC namespace. See the 'validate_required' for
     * an example.
     *
     * @package    Tina-MVC
     * @subpackage Core
     * @see validate_required
     */
    class validate {
        
        /**
         * The error message (if any).
         *
         * Your class should set this variable if your validation function fails
         *
         * @see validate_required class
         */
        public $validation_message = '';
        
        /**
         * Checks for validation errors
         *
         * @return boolean
         */
        function validation_errors() {
            return ( ! empty( $this->validation_message ) );
        }
        
    }
    
    /**
     * Validate a field as 'required'
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_required extends validate {
        
        /**
         * Validation code
         *
         * Sets $this->validation_message if validation fails
         * 
         * @param object $field_obj
         * @param mixed $params not used
         * @return void
         */
        function __construct( $field_obj, $params ) {
            
            if( $field_obj->get_type() == 'file' ) {
                
                // file uploads are special...
                if( ! $field_obj->get_value() ) {
                    $this->validation_message = '\'' . $field_obj->get_caption() . '\'' . ' is a required field';
                }
                
            }
            elseif( ! strval( $field_obj->get_value() ) ) {
                $this->validation_message = '\'' . $field_obj->get_caption() . '\'' . ' is a required field';
            }
            
        }
        
    }
    
    /**
     * Validate as empty string
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_empty extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param string not used
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( strval( $field->get_value() ) != '' ) {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be left blank';
            }
            
        }
        
    }
    
    /**
     * Validate as maximum string length
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_max_length extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param string $params maximum string length allowed
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( strlen( $field->get_value() ) > $params ) {
                if( $params == 1 ) {
                    $characters = 'character';
                }
                else {
                    $characters = 'characters';
                }
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be shorter than '.$params." $characters.";
            }
            
        }
        
    }
    
    /**
     * Validate as minimum string length
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_min_length extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param string $params minimum length
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( strlen( $field->get_value() ) < $params ) {
                if( $params == 1 ) {
                    $characters = 'character';
                }
                else {
                    $characters = 'characters';
                }
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be at least '.$params." $characters.";
            }
            
        }
        
    }
    
    /**
     * Validate as maximum value
     * 
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_max_value extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param mixed $params max value allowed
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( $field->get_value() > $params ) {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be less than '.$params;
            }
            
        }
        
    }
    
    /**
     * Validate as minimum value
     * 
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_min_value extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param string $params min value allowed
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( $field->get_value() < $params ) {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be greater than '.$params;
            }
            
        }
        
    }
    
    /**
     * Validate as email
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_email extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param mixed $params not used
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( ! is_email( $field->get_value() ) ) {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' is not a valid email address';
            }
            
        }
        
    }
    
    /**
     * Validate as SQL Datetime (YYY-MM-DD hh:mm:ss)
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_sql_datetime extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param mixed $params not used
         * @return void
         */
        function __construct( $field, $params ) {
            
            if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $field->get_value(), $matches)) {
                if (checkdate($matches[2], $matches[3], $matches[1])) {
                    return;
                }
                // allow mysql zero
                if ( $val == '0000-00-00 00:00:00' ) {
                    return;
                }
            }
            else {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be in "YYYY-MM-DD hh:mm:ss" datetime format';
            }
            
        }
        
    }
    
    /**
     * Validate as SQL Date (YYY-MM-DD)
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_sql_date extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param mixed $params not used
         * @return void
         */
        function __construct( $field, $params ) {
            
            if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $field->get_value(), $matches)) {
                if (checkdate($matches[2], $matches[3], $matches[1])) {
                    return FALSE;
                }
                // allow mysql zero
                if ( $val == '0000-00-00' ) {
                    return FALSE;
                }
            }
            else {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be in "YYYY-MM-DD" date format';
            }
            
        }
        
    }
    
    /**
     * Validate as SQL Date (hh:mm:ss)
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_sql_time extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param mixed $params not used
         * @return void
         */
        function __construct( $field, $params ) {
            
            $err = '\'' . $field->get_caption() . '\'' . ' must be in "HH:mm:ss" time format';
            
            if( strlen($field->get_value()) != 8 OR count(($t=explode(':',$field->get_value()))) != 3 ) {
                $this->validation_message = $err;
            }
            else {
                // allow mysql zero
                if ( $field->get_value() == '00:00:00' ) {        
                    return;
                }
                if( $t[0] >= 0 AND $t[0] <= 23 AND $t[1] >= 0 AND $t[1] <= 59 AND $t[2] >= 0 AND $t[2] <= 59 ) {
                    return;
                }
            }
            
            $this->validation_message = $err;
            
        }
        
    }
    
    /**
     * Validate as equal to another field
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_equal_to_field extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param object $params the other field object
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( $field->get_value() != $params->get_value() ) {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be equal to \'' . $params->get_caption() . '\'';
            }
            
        }
        
    }
    
    /**
     * Validate as less than another field
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_less_than_field extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param object $params the other field object
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( $field->get_value() >= $params->get_value() ) {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be less than \'' . $params->get_caption() . '\'';
            }
            
        }
        
    }
    
    /**
     * Validate as greater than another field
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_greater_than_field extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param object $params the other field
         * @return void
         */
        function __construct( $field, $params ) {
            
            if( $field->get_value() <= $params->get_value() ) {
                $this->validation_message = '\'' . $field->get_caption() . '\'' . ' must be greater than \'' . $params->get_caption() . '\'';
            }
            
        }
        
    }
    
    /**
     * Validate using a regular expression
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_regexp extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field
         * @param array $params array of array( 'regexp' => 'validation message' )
         * @return void
         */
        function __construct( $field, $params ) {
            
            foreach( $params AS $regexp => $message ) {
                if( ! preg_match('/'.$regexp.'/', $field->get_value() ) ) {
                    // check for a custom validation message
                    if( ! empty( $message ) ) {
                        $this->validation_message = $message;
                    }
                    else {
                        $this->validation_message = '\'' . $field->get_caption() . '\'' . ' is not valid';
                    }
                }
                break; // only ever expect one element in the array
            }
            
        }
        
    }
    
    /**
     * Validate a field as 'recaptcha'
     *
     * You should not use this validation rule directly - it is added automatically by the form class
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_recaptcha extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field_obj
         * @param mixed $params not used
         * @return void
         */
        function __construct( $field_obj, $params ) {
            
            $resp = recaptcha_check_answer ( get_tina_mvc_setting('recaptcha_pri_key') ,
                                    $_SERVER['REMOTE_ADDR'],
                                    get_Post( 'recaptcha_challenge_field' ),
                                    get_Post( 'recaptcha_response_field' ) );
            if (!$resp->is_valid) {
                $this->validation_message = 'The reCaptcha was incorrect. Please try again.';
            }
            
        }
        
    }
    
    /**
     * Validate a field as 'file_upload'
     *
     * You should not use this validation rule directly - it is added automatically by the form class
     *
     * @package    Tina-MVC
     * @subpackage Core
     */
    class validate_file_upload extends validate {
        
        /**
         * Validation code
         * @see $this->validate_required()
         * @param object $field_obj
         * @param mixed $params not used
         * @return void
         */
        function __construct( $field_obj, $params ) {
            
            // if successful or no file at all, we just return... you can use the required validation rule to enforce a submission
            if( ! $_FILES[$field_obj->get_post_var_name()]['error'] OR $_FILES[$field_obj->get_post_var_name()]['error'] == UPLOAD_ERR_NO_FILE ) return;
            
            switch( $_FILES[$field_obj->get_post_var_name()]['error'] ) {
                
                case UPLOAD_ERR_INI_SIZE: 
                case UPLOAD_ERR_FORM_SIZE:
                    $this->validation_message = 'The file exceeds the maximum upload size of '.ini_get('upload_max_filesize');
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->validation_message = 'The file was only partially uploaded';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->validation_message = 'Missing a temporary folder for PHP fileuploads. Contact your server administrator.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->validation_message = 'Cannot write file to disk. Contact your server administrator.';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $this->validation_message = 'A PHP extension prevented the file upload. Contact your server administrator.';
                    
            }
            
        }
        
    }
    
    /**
     * The base object for forms and fields
     * 
     * @package    Tina-MVC
     * @subpackage Docs
     */
    class form_base {
        
        /**
         * The name for the form of the field. Userd to construct POST variable names and CSS id's
         * @var string
         */
        protected $name = '';
        
        /**
         * Validation rules
         * @var array
         */
        protected $validation_rules = array();
        
        /**
         * Whether or not to use XML short tags
         * @var bool
         */
        public $do_xml = TRUE;
        
        /**
         * HTML strings used for various elements generated by this helper. Use sprintf() format.
         * @var string
         */
        public $html_form = '<div class="tina_mvc_form">%s</div>',
               $html_form_message = '<div class="tina_mvc_form_message">%s</div>',
               $html_form_messages = '<div class="tina_mvc_form_messages">%s</div>',
               $html_form_error = '<div class="tina_mvc_form_error_message">%s</div>',
               $html_form_pair = '<div class="tina_mvc_form_pair">%s</div>',
               $html_form_required_after_label = '',
               $html_form_required_after_input = ' (required) ',
               $html_form_label = '<span class="tina_mvc_form_label">%s </span>',
               $html_form_input = '<span class="tina_mvc_form_input">%s</span>',
               $html_form_radio_set = '<span class="tina_mvc_form_radio_set">%s</span>',
               $html_form_field_error = '<span class="tina_mvc_field_error">%s </span>',
               $html_form_field_message = '<div class="tina_mvc_form_field_message">%s</div>', // unused
               $html_form_button = '<div class="tina_mvc_form_button">%s</div>';
           
        /**
         * Checks the name for the form or field element
         * 
         * @param String $name The name
         * @return object
         */
        public function set_name( $name='' ) {
            if( ! $name ) {
                error("'$name' parameter is required.");
            }
            if( ! preg_match( "/^[a-zA-Z0-9_]*$/", $name ) ) {
                error("\$name '$name' can only contain alphanumeric characters and underscores");
            }
            $this->name = $name;
            return $this;
        }
        
        /**
         * Gets the field or form name
         * 
         * @return String
         */
        public function get_name() {
            return $this->name;
        }
        
        /**
         * Returns a XML slash for self closing tags
         * 
         * @return String
         */
        public function get_xhtml_slash() {
            return ( $this->do_xml ? ' /' : '' );
        }
        
        /**
         * Sets any extra attributes you wish to pass to the form or field
         * 
         * @param mixed $attribs An array of attribs or a string to be added directly into the element e.g. 'attrib="value"'
         * @return object
         */
        public function set_extra_attribs( $attribs=FALSE ) {
            
            if( $attribs === FALSE ) error("\$attribs is required");
            
            if( ! isset( $this->extra_attribs ) ) {
                $this->extra_attribs = '';
            }
            else {
                $this->extra_attribs .= ' ';
            }
            
            if( is_array($attribs) ) {
                foreach( $attribs AS $attrib ) {
                    $this->extra_attribs .= $attrib . ' ';
                }
                $this->extra_attribs = rtrim( $this->extra_attribs );
            }
            else {
                $this->extra_attribs .= $attribs;
            }
            
            return $this;
            
        }
        
        /**
         * Getter
         * @return string
         */
        public function get_extra_attribs() {
            return ( empty( $this->extra_attribs ) ? '' : $this->extra_attribs );
        }
        
    }
    
    /**
     * The field class
     * @package    Tina-MVC
     * @subpackage Docs
     */
    class field extends form_base {
        
        /**
         * The form object this field belongs to
         * @var object form
         */
        public
            $form;
            
        /**
         * All variables should be accessed using setters and getters. Direct access is not supported.
         * @var mixed
         */
        protected
            $name,
            $type,
            $caption,
            $db_table,
            $db_field,
            $default_value,
            $set_value,
            $value,
            $posted_value,
            $extra_attribs,
            $protected,
            $validation_errors, // array of messages
            $options, // for select or radio
            $map_width, // only for use with GOOGLEMAP field
            $map_height; // only for use with GOOGLEMAP field
            
        /**
         * Sets up the field
         * 
         * @param object $form The form object the field is part of 
         * @param String $name A name (alphanumeric plus underscore only)
         * @param String $type See the field_* classes for valid field types. You can also check the test_form_controller.php file in the samples folder
         * @param String $caption Field caption used in a label (default caption is based on the name of the field)
         * @param String $db_table Used to group fields
         * @param String $db_field If the database is different from the field name for some reason you can set it here.
         * @param String $default_value 
         * @param mixed $extra_attribs a string or array of strings
         * @param boolean $protected Prevent load_data() from overwriting values in this field
         * @return object field
         */
        function __construct( $form, $name='', $type='', $caption='', $db_table='', $db_field='', $default_value='', $extra_attribs='', $protected=FALSE ) {
            
            $this->set_form( $form );
            $this->set_name( $name );
            $this->set_type( $type );
            $this->set_caption( $caption );
            if( $db_table ) {
                $this->set_db_table( $db_table );
            }
            if( $db_field ) {
                $this->set_db_field( $db_field );
            }
            else {
                $this->set_db_field( $this->name );
            }
            $this->set_default_value( $default_value );
            $this->set_value( $default_value ); // current value
            $this->set_extra_attribs( $extra_attribs );
            
            $this->protected = $protected;
            
            return $this;
            
        }
        
        /**
         * Sets the form the field belongs to
         * @param object $form_object
         * @return object field
         */
        private function set_form( $form_object ) {
            $this->form = $form_object;
            return $this;
        }
        
        /**
         * Sets the field type
         * 
         * @param string $t See field_* classes for valid field types
         * @return object field
         */
        public function set_type( $t ) {
            
            if( ! class_exists( ($_c='\TINA_MVC\field_'.$t) ) ) error("\$type '$t' not implemented.");
            
            // some fields might need code run on setup.. e.g. reCaptcha field needs a recaptcha
            // validation rule added
            if( method_exists( $_c, 'setup' ) ) {
                $_c::setup( $this );
            }
            
            $this->type = $t;
            return $this;
            
        }
        
        /**
         * Getter for the type of field
         * @return string
         */
        public function get_type() {
            return $this->type;
        }
        
        /**
         * Set the caption for an input field
         * 
         * @param string $caption
         * @return object field
         */
        public function set_caption( $caption='' ) {
            if( ! $caption ) {
                $this->caption = ucwords( str_replace( '_', ' ', $this->name ) );
            }
            else {
                $this->caption = $caption;
            }
            return $this;
        }
        
        /**
         * Sets the db_table value for a field
         *
         * Allows fields with the same db_table value to be retrieved together
         *
         * @param string $table_name
         * @return object field
         */
        public function set_db_table( $table_name='' ) {
            $this->db_table = $table_name;
            return $this;
        }
        
        /**
         * Sets the database field name (if different from the field name)
         * 
         * @param string $field_name
         * @return object field
         */
        public function set_db_field( $field_name='' ) {
            if( ! $field_name ) {
                $this->db_field = $this->name;   
            }   
            else {
                $this->db_field = $field_name;
            }
            return $this;
        }
        
        /**
         * Sets the default value for a field
         * 
         * @param mixed $value
         * @return object field
         */
        public function set_default_value( $value=FALSE ) {
            
            if( $this->get_type() == 'file' ) return $this;
            
            $this->default_value = $value;
            return $this;
        }
        
        /**
         * Sets a value for the field. Overrides the default value
         * 
         * @param mixed $value
         * @return object field
         */
        public function set_set_value( $value=FALSE ) {
            
            if( $this->get_type() == 'file' ) return $this;
            
            $this->set_value = $value;
            return $this;
        }
        
        /**
         * Sets a posted value for the field. Overrides the set value
         * @param mixed $value
         * @return object field
         */
        public function set_posted_value( $value=FALSE ) {
            
            if( $this->get_type() == 'file' ) return $this;
            
            $this->posted_value = $value;
            return $this;
        
        }
        
        /**
         * Sets a value for the field
         * 
         * @param mixed $value
         * @return object field
         */
        public function set_value( $value=FALSE ) {
            
            if( $this->get_type() == 'file' ) return $this;
            
            if( strtolower($this->type) == 'hidden' ) {
                $this->set_default_value( $value );
            }
            
            $this->value = $value;
            return $this;
        }
        
        /**
         * Getter
         *
         * Gets the posted value, set value or default value (in that order)
         * 
         * @return mixed
         */
        public function get_value() {
            
            if( $this->form->form_posted() OR $this->get_type() == 'file' ) {
                
                // doesn;t make sense to return anything else for a file...
                if( $this->get_type() == 'file' ) {
                    return $_FILES[$this->get_post_var_name()];
                }
                
                $this->set_value( $this->posted_value );
                return $this->posted_value;
                
            }
            elseif( isset( $this->set_value ) ) {
                return $this->set_value;
            }
            else {
                return $this->default_value;
            }
            
        }
        
        /**
         * Gets the $_POST value for the field
         * @return mixed array for a file or string
         */
        public function get_posted_value() {
            
            if( $this->get_type() == 'file' ) {
                return $_FILES[$this->get_post_var_name()];
            }
            
            return get_Post( $this->get_post_var_name() );
        
        }
        
        /**
         * Gets the options for a radio or select field
         * 
         * @return mixed
         */
        public function get_options() {
            return $this->options;
        }
        
        /**
         * Getter
         * 
         * @return string
         */
        public function get_db_field() {
            return $this->db_field;
        }
        
        /**
         * Getter
         * 
         * @return string
         */
        public function get_db_table() {
            return $this->db_table;
        }
        
        /**
         * Gets the POST/GET variable name used in HTML
         * 
         * @return string
         */
        public function get_post_var_name() {
            return $this->form->get_name() . '_' . $this->get_name();
        }
        
        /**
         * Getter
         * 
         * @return string
         */
        public function get_id() {
            return $this->get_post_var_name();
        }
        
        /**
         * Getter
         * 
         * @return string
         */
        public function get_caption() {
            return $this->caption;
        }
        
        /**
         * Sets the map width for field type GOOGLEMAP
         *
         * @param string $w a valid css width
         */
        public function set_map_width( $w ) {
            $this->map_width = $w;
            return $this;
        }
        
        /**
         * Sets the map height for field type GOOGLEMAP
         * 
         * @param string $h a valid css height
         */
        public function set_map_height( $h ) {
            $this->map_height = $h;
            return $this;
        }
        
        /**
         * Getter
         * 
         * @return string
         */
        public function get_map_width() {
            return $this->map_width;
        }
        
        /**
         * Getter
         * 
         * @return string
         */
        public function get_map_height() {
            return $this->map_height;
        }
        
        /**
         * Sets options for select and radio field types
         *
         * Options are array( array( 'post_value' , 'display_value' ) ). This allows feeding an array of
         * array( 'id'=>'name' ) from a database query for example.
         * 
         * @param array
         * @return object field
         */
        public function set_options( $options=array() ) {
            
            if( ! $options OR ! is_array( $options ) ) {
                error('$options must be of form array( array( "post_value", "display_value" ) )');
            }
            
            $this->options = array();
            $options = array_values( $options );
            
            foreach( $options AS $option ) {
                
                // might be an object
                if( is_object($option) ) {
                    // convert to array
                    $option = get_object_vars($option);
                }
                
                $this->options[] = array( 'post_value'=>array_shift($option), 'display_value'=>array_shift($option) );
            }
            
            return $this;
            
        }
        
        /**
         * Checks if a fields value is protected and shouldn't be changed by a user
         * 
         * @return boolean
         */
        public function is_protected() {
            return $this->protected;
        }
        
        /**
         * Adds a validation error message to a field
         * 
         * @param string $message
         * @return object  field
         */
        public function add_validation_error( $message ) {
            
            if( ! $message ) error( '$message parameter is required.' );
            
            $this->validation_errors[] = $message;
            $this->form->add_error_count();
            
            return $this;
            
        }
        
        /**
         * Adds validation rule(s) to a field
         * 
         * @param mixed $rules A single rule ( array( 'rule_name' => 'rule_parameters'.... ). See validate_* classes for valid rules.
         * @return object  field
         */
        public function add_validation( $rules=FALSE ) {
            
            if( $rules === FALSE ) error( "'\$rules' is required" );
            
            if( is_array( $rules ) ) {
                foreach( $rules AS $rule => $params ) {
                    $this->check_validation_rule( $rule );
                    $this->validation_rules[$rule] = $params;
                }
            }
            else error( "'\$rules' must be an array" );
            
            return $this;
            
        }
        
        /**
         * Checks a validation rule exists
         * 
         * @param string
         * @return mixed
         */
        private function check_validation_rule($v) {
            if( ! class_exists( 'TINA_MVC\\validate_'.$v ) ) {
                error( "Validation rule '$v' is not implemented" );
            }
            return TRUE;
        }
        
        /**
         * Gets the number of validation errors on a field
         * 
         * @return integer
         */
        public function get_error_count() {
            return count( $this->validation_errors );
        }
        
        /**
         * Runs validation rules on a field
         * 
         * @return boolean
         */
        public function validate() {
            
            $this->validation_errors = array();
            
            foreach( $this->validation_rules AS $rule => $params ) {
                
                $fn = '\TINA_MVC\validate_'.$rule;
                $validator = new $fn( $this, $params );
                
                if( $validator->validation_errors() ) {
                    $this->add_validation_error( $validator->validation_message );
                }
                
            }
            
            return ( $this->validation_errors == array() ); // false if errors
            
        }
        
        /**
         * Renders a label for a field
         * 
         * @return string escaped HTML
         */
        public function render_label() {
            
            $h = '';
            $h .= "<label for=\"".$this->get_id()."\">".esc_html($this->get_caption())."</label>";
            $h = sprintf( $this->html_form_label, $h );
            if( array_key_exists( 'REQUIRED', $this->validation_rules ) ) {
                $h .= $this->html_form_required_after_label;
            }
            return $h;
        }
        
        /**
         * Renders the field
         * 
         * @return string escaped HTML
         */
        public function render() {
            
            $fld_class = 'TINA_MVC\field_'.$this->type;
            $f = new $fld_class($this);
            
            $h = '';
            
            if( ! empty( $f->no_html_wrappers ) ) {
                return $f->html( $this );
            }
            
            if( isset($f->plain_html) AND $f->plain_html ) {
                
                // render as a plain piece of html within the $this->html_form_pair block
                $h .= $f->html($this);
                
            }
            else {
                
                // we are rendering a normal form pair
                if( ! isset( $f->render_label ) OR $f->render_label ) {
                    $h .= $this->render_label();
                }
                
                $h .= sprintf( $this->html_form_input, $f->html($this) );
                
                if( array_key_exists( 'REQUIRED', $this->validation_rules ) ) {
                    $h .= $this->html_form_required_after_input;
                }
                
                // validation messages?
                if( $this->validation_errors ) {
                    foreach( $this->validation_errors AS $e ) {
                        $h .= sprintf( $this->html_form_field_error, $e );
                    }
                }
                
                
            }
            
            return sprintf( $this->html_form_pair, $h );
            
        }
        
    }
    
    /**
     * The form class
     * @package    Tina-MVC
     * @subpackage Docs
     */
    class form extends form_base {
        
        /**
         * Use setters and getters to access. Tina MVC does not support direct access or class variables.
         * @var mixed
         */
        protected
            $fields,
            $protected_fields,
            $action,
            $method,
            $wp_nonce_field_name,
            $form_posted,
            $validation_errors,
            $messages,
            $error_messages,
            $fieldset_open,
            $file_input_added;
        
        /**
         * Sets up the form
         * 
         * @param String $name
         * @param String $action
         * @param Object $extra_attribs
         */
        function __construct( $name='', $action='', $extra_attribs=array() ) {
            
            $this->set_name($name);
            
            $this->action = ( $action ? $action : $_SERVER['REQUEST_URI'] );
            $this->method = 'POST';
            $this->set_file_input_added( FALSE ); // default
            $this->do_xml = TRUE; // default
            
            // used to check if the form has been posted - will be set in $this->add_base_fields()
            $this->wp_nonce_field_name = '';
            
            $this->set_extra_attribs( $extra_attribs );
            
            $this->form_posted = FALSE;
            
            $this->fieldset_open = FALSE; // name of any open fieldset
            
            $this->fields = new \StdClass;
            
            $this->add_base_fields();
            
            if( ! headers_sent() ) {
                wp_enqueue_style( 'tina_mvc_form_helper_css' , get_tina_mvc_folder_url().'/tina_mvc/helpers/tina_mvc_form_helper_css.css' );
            }
            
        }
        
        /**
         * Adds a field to the form
         * 
         * @param String $name
         * @param String $type
         * @param String $caption
         * @param String $db_table
         * @param String $db_field
         * @param String $default_val
         * @param String $extra_attribs
         * 
         * @return object  field
         */
        public function add_field( $name='', $type='text', $caption='', $db_table='', $db_field='', $default_val='', $extra_attribs='' ) {
            
            if( ! $name OR ! $type ) {
                error('$name parameter is required for $form->add_field().');
            }
            
            if( isset( $this->fields->$name ) ) {
                error('Field name is already in use.');
            }
            
            $this->fields->$name = new field( $this, $name, $type, $caption, $db_table, $db_field, $default_val, $extra_attribs);
            
            return $this->fields->$name;
            
        }
        
        /**
         * Adds a block of text to the form
         * 
         * @param String $name
         * @param String $text
         * @param Mixed $extra_attribs array of name => value pairs or a string
         * 
         * @return object  field
         */
        public function add_text( $name='', $text='', $extra_attribs='' ) {
            
            if( ! $name OR ! $text ) {
                error('$name and $text parameters are required for $form->add_text().');
            }
            
            if( isset( $this->fields->$name ) ) {
                error('Field name is already in use.');
            }
            
            $this->fields->$name = new field( $this, $name, 'textblock', NULL, NULL, NULL, $text, $extra_attribs);
            
            return $this->fields->$name;
            
        }
        
        /**
         * Opens a fieldset
         * 
         * @param String $name
         * @param String $legend the caption to display
         * @param Mixed $extra_attribs array of name => value pairs or a string
         * 
         * @return object  field
         */
        public function add_fieldset( $name='', $legend='', $extra_attribs='' ) {
            
            if( isset( $this->fields->$name ) ) {
                error('Field name is already in use.');
            }
            
            if( $this->fieldset_open ) {
                
                $this->fieldset_close();
                
            }
            
            $this->fields->$name = new field( $this, $name, 'fieldset_open', NULL, 'tina_mvc_fields', NULL, $legend, $extra_attribs, $protected=TRUE);
            
            $this->fieldset_open = $this->fields->$name->get_name();
            
            return $this->fields->$name;
            
        }
        
        /**
         * Alias to add_fieldset()
         * 
         * @param String $name
         * @param String $legend the caption to display
         * @param Mixed $extra_attribs array of name => value pairs or a string
         * 
         * @return object  field
         */
        public function fieldset_open( $name='', $legend='', $extra_attribs='' ) {
            
            return $this->add_fieldset( $name, $legend, $extra_attribs );
            
        }
        
        /**
         * Closes an open fieldset
         * 
         * @param mixed $extra_attribs array of name => value pairs or a string
         * 
         * @return object  field
         * @todo Mmmm, is $extra_attribs valid for a close fieldset?
         */
        public function fieldset_close( $extra_attribs='' ) {
            
            if( empty( $this->fieldset_open ) ) {
                error('There is no open fieldset element.');
            }
            
            $fname = $this->fieldset_open . '_close';
            
            $this->fields->$fname = new field( $this, $fname, 'fieldset_close', NULL, 'tina_mvc_fields', NULL, '', $extra_attribs, TRUE);
            
            return $this->fields->$fname;
            
        }
        
        /**
         * Adds the system field
         *
         * It is a WP_NONCE value and is used to check if the form was posted within the validity of the WP_NONCE.
         */
        private function add_base_fields() {
            
            $this->wp_nonce_field_name = 'tina_mvc_wp_nonce';
            $this->fields->{$this->wp_nonce_field_name} = new field( $this, $this->wp_nonce_field_name, 'hidden', 'hidden needs no caption', 'tina_mvc_fields', 'wp_nonce', wp_create_nonce( $this->get_name() ), NULL, $protected=TRUE );
            
        }
        
        /**
         * Loads an array or object of data into the form
         *
         * Usually a recordset from a database in the form array( array( 'field' => 'value' ) )
         * 
         * @param mixed $data array or object
         */
        public function load_data( $data=FALSE ) {
            
            if( $data === FALSE ) {
                error( '$data parameter is required' );
            }
            
            // loop through fields
            foreach( $this->fields AS $field_obj ) {
                
                foreach( $data AS $key => $value ) {
                    
                    if( ! $field_obj->is_protected() ) {
                        
                        // if db field name or name match, we set value
                        if( $field_obj->get_db_field() == $key ) {
                            $field_obj->set_set_value( $value );
                        }
                        elseif( $field_obj->get_name() == $key ) {
                            $field_obj->set_set_value( $value );
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
        /**
         * Loads any data from $_POST
         */
        private function load_posted_data() {
            
            foreach( $this->fields AS $f ) {
                
                if( $val = get_Post( $f->get_post_var_name() ) ) {
                    $f->set_posted_value( $val );
                    $f->set_value( $val );
                }
                
            }
            
        }
        
        /**
         * Gets an array of submitted data.
         *
         * The field names are based on the db_field values in the field
         *
         * @param string $db_table the db_table value for the fields you want returned
         * @return object  Description
         */
        public function get_posted_db_data( $db_table='' ) {
            return $this->get_posted_data( $db_table, TRUE );
        }
        
        /**
         * Flags that a file input has been added to the form
         * @param boolean $b
         * @return object file
         */
        public function set_file_input_added( $b=FALSE ) {
            $this->file_input_added = (boolean) $b;
            return $this;
        }
        
        /**
         * Checks a file input has been added to the form
         * @return boolean
         */
        public function get_file_input_added() {
            return $this->file_input_added;
        }
        
        /**
         * Checks if the form has been posted by verifying the wp_nonce field
         * 
         * @return boolean
         */
        public function form_posted() {
            
            $v = \wp_verify_nonce( get_post( $this->fields->{$this->wp_nonce_field_name}->get_post_var_name() ), $this->get_name() );
            return ( $v );
            
        }
        
        /**
         * Gets an array of data if the form was posted. Returns boolean FALSE otherwise
         *
         * Fields can be grouped by assigning a value to the db_table field property. The data returned is an array of
         * field_name => field_value. If you want to get an array with the keys based on the db_field property, set
         * $get_db_fields TRUE.
         * 
         * @param string $db_table
         * @param boolean $get_db_fields
         * 
         * @return array
         * @see $this->get_posted_db_value()
         */
        public function get_posted_data( $db_table='', $get_db_fields=FALSE ) {
            
            $data = FALSE;
            
            if( $this->form_posted() ) {
                
                $this->load_posted_data();
                
                if( $this->check_validation() ) {
                    
                    foreach( $this->fields AS $field ) {
                        
                        if( $field->get_db_table() == $db_table ) {
                            
                            if( $get_db_fields ) {
                                
                                if( $field->get_type() == 'file' ) {
                                    $data[ $field->get_db_field() ] = $_FILES[ $field->get_id() ];
                                }
                                else {
                                    $data[ $field->get_db_field() ] = $field->get_value();
                                }
                                
                            }
                            else {
                                
                                if( $field->get_type() == 'file' ) {
                                    $data[ $field->get_name() ] = $_FILES[ $field->get_id() ];
                                }
                                else {
                                    $data[ $field->get_name() ] = $field->get_value();
                                }
                                
                            }
                            
                        }
                        
                        
                    }
                    
                }
                
            }
            
            return $data;
            
        }
        
        /**
         * Processes validation rules attached to each field
         * 
         * @return boolean TRUE if the rules passed
         */
        private function check_validation() {
            
            $this->validation_errors = 0;
            
            foreach( $this->fields AS $field ) {
                
                if( ! $field->validate() ) {
                    $this->validation_errors += $field->get_error_count();
                }
                
            }
            
            if( $this->validation_errors ) {
                return FALSE;
            }
            
            return TRUE;
            
        }
        
        /**
         * Renders the opening form tag and any messages
         * 
         * @return string
         */
        private function render_open_form() {
            
            $h = '';
            
            $h .= '<form id="'.$this->get_name().'" method="'.$this->method.'" action="'.$this->action.'" enctype="multipart/form-data" '. $this->get_extra_attribs() .'>'."\r\n";
            
            if( $this->messages ) {
                $messages = '';
                foreach( $this->messages AS $m ) {
                    $messages .= sprintf( $this->html_form_message, esc_html($m) );
                }
                $h .= sprintf( $this->html_form_messages, $messages );
            }
            
            return $h;
            
        }
        
        /**
         * Renders the closing form tag
         * 
         * @return string
         */
        private function render_close_form() {
            return '</form>';
        }
        
        /**
         * Renders the forms HTML
         *
         * @return String the HTML
         */
        public function render() {
            
            $html = '';
            
            $html .= $this->render_open_form();
            
            if( $this->error_messages ) {
                foreach( $this->error_messages AS $e ) {
                    $html .= sprintf( $this->html_form_error, $e );
                }
            }
            
            foreach( $this->fields AS $f ) {
                
                $html .= $f->render();
                
                
            }
            
            // have we an open fieldset?
            if( ! empty( $this->fieldset_open ) ) {
                
                $fset_close = $this->fieldset_close();
                $html .= $fset_close->render();
            }
            
            $html .= $this->render_close_form();
            
            $html = sprintf( $this->html_form, $html );
            return $html;
            
        }
        
        /**
         * Adds an error message or messages to the form.
         *
         * @param $m string the error message
         */
        public function add_error( $m = FALSE ) {
            
            if( $m ) {
                $this->error_messages[] = $m;
                $this->add_error_count();
            }
            
        }
        
        /**
         * Increments the error counter
         */
        public function add_error_count() {
            
            $this->validation_errors++;
            
        }
        
        /**
         * Checks for errors
         */
        public function get_error_count() {
            
            return $this->validation_errors;
            
        }
        
        /**
         * Checks for errors
         */
        public function get_errors() {
            
            return $this->validation_errors;
            
        }
        
    }

}



