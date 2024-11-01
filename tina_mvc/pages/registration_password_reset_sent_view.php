<?php
/**
* Template File: After a password has been sent to the user
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
<h2>Password Reset Request For <?= $username ?>.</h2>

<p>We have emailed you with further instructions.</p>

<p>If you do not see our email, please check your SPAM folder for an email from:<br />
<?= esc_html( TINA_MVC\get_mailer_from_address() ) ?>.</p>

<p>
    <?= $this->load_view('registration_snippet_reg_links') ?>
</p>
