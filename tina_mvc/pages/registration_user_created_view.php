<?php
/**
* Template File: User has been successfully registered
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
<h2>Registration Complete for <?= $username ?></h2>

<p>We have emailed you your username and password. Check your SPAM folder if you do not see the email.</p>

<p>It was sent from: <?= esc_html( TINA_MVC\get_mailer_from_address() ) ?>.</p>

<p>
    <?= $this->load_view('registration_snippet_reg_links') ?>
</p>
