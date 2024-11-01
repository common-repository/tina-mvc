<?php 
/**
* Example: tutorial 4 - code hooks
*
* @package  Tina-MVC
* @subpackage Samples
*/
/**
 * Runs on plugin activation
 * @return void
 */
function tina_mvc_app_install() {
 
    /**
     * Creating custom roles
     */
    global $wp_roles;
 
    $my_roles = array( 'My First Custom Role'=>'my_custom_role_1' , 'My Second Custom Role'=>'my_custom_role_2' );
 
    foreach( $my_roles AS $display_name => $role ) {
        if( ! $wp_roles->get_role( $role ) ) {
            $wp_roles->add_role( $role, $display_name, FALSE );   
        }
    }
 
    /**
     * Creating custom DB tables
     *
     * NB: read http://codex.wordpress.org/Creating_Tables_with_Plugins before messing with dbDelta!!!
     *
     */
    global $wpdb;
    $tbl_name = $wpdb->prefix.'my_custom_table';
 
    $sql_create = "CREATE TABLE $tbl_name (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    text_field_1 varchar(32) NOT NULL,
                    text_field_2 varchar(64) NOT NULL,
                    UNIQUE KEY id (id),
                    KEY text_field_1 (cat_order)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_create);
 
}
