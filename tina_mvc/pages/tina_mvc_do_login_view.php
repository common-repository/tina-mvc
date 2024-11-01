<?php
/**
* View file for the tina_mvc_page_class::do_login() function
*
* Copy this file to a user_apps, pages folder and customise it. It will be used
* instead of this file.
*
* @package    Tina-MVC
* @subpackage Core
* @author     Francis Crossen <francis@crossen.org>
*/

/**
 * You should include this check in every view file you write. The constant is defined in
 * tina_mvc_base_page->load_view() 
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>

<h3>Already a user? Login here</h3>
<?= $login_form ?>

<p>
    <?= $this->load_view('registration_snippet_reg_links') ?>
</p>
