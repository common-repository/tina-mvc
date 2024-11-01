<?php
/**
* Template File: Default page for the tina-mvc-for-wordpress/tina-mvc-child-page page
*
* @package  Tina-MVC
* @subpackage Core
*/

/**
 * Make sure the TINA_MVC_PAGE_CONTROLLER_NAME is set so we can produce example links...
 */
if( !defined('TINA_MVC_PAGE_CONTROLLER_NAME') ) {
 define( 'TINA_MVC_PAGE_CONTROLLER_NAME' , 'tina-mvc-for-wordpress' );
}
/**
 * Security check - make sure we were included by the main plugin file
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>
<div class="wrap"><br />
<?php // an example of how to use a view file from within a view file... ?>
<?php echo $this->load_view('tina_mvc_logo_snippet', ($dummy=false), "tina_mvc/admin_pages/assets");  ?>

<p>This is a sample Tina MVC page.</p>

<p>Check out the <a href="<?= site_url('wp-admin/admin.php?page=tina_mvc_for_wordpress') ?>">Tina MVC Wordpress documentation to get started</a>.</p></p>

</div>
