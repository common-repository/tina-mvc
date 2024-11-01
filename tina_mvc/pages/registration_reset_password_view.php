<?php
/**
* Template File: Password reset page
*
* @package    Tina-MVC
* @subpackage Core
*/

/**
 * You should include this check in every view file you write. The constant is defined in
 * tina_mvc_base_page->load_view() 
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>
<h2>Password Reset</h2>

<?= $password_reset_form ?>

<p>We will email you with further instructions.</p>

<p>
    <?= $this->load_view('registration_snippet_reg_links') ?>
</p>
