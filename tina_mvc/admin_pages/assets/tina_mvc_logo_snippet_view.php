<?php
/**
* Template File: HTML Snippet for the Tina MVC logo
*
* @package    Tina-MVC
* @subpackage Tina-Core-Views
* @author     Francis Crossen <francis@crossen.org>
*/

/**
 * You should include this check in every view file you write. The constant is defined in
 * tina_mvc_base_page->load_view() 
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();

/**
 * What is the plugin folders name?
 */
$plugin_folder_name = substr( TINA_MVC_PLUGIN_DIR , (strrpos( TINA_MVC_PLUGIN_DIR , '/' ))+1 );
?>
<div style="padding: 0; margin:10px 0 10px 0;"><img style="float: left;" title="Tina MVC for Wordpress logo" src="<?php echo WP_CONTENT_URL; ?>/plugins/<?php echo $plugin_folder_name ?>/tina_mvc/admin_pages/assets/Tina-MVC-for-Wordpress-logo.jpg" alt="Tina MVC for Wordpress logo" width="80" height="80" /><span style="color:#002E50;font-family:arial;font-size:60px;font-weight:bold;line-height:45px;padding:0 0 0 5px;">Tina MVC</span><br /><span style="color:#20719E;font-family:'Times New Roman';font-size:46px;font-style:italic;line-height:35px;padding:0 0 0 5px;">for Wordpress</span></div>
