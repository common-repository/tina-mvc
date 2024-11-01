<?php
/**
* Template File: Register as a user
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
<h2>Registration</h2>

<?= $registration_form ?>

<p>We will email a password to you.</p>

<p>
    <?= $this->load_view('registration_snippet_reg_links') ?>
</p>
