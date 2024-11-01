<?php
/**
* Template File: Your password has been reset
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
<?php if( ! empty($error_msg) ): ?>

    <h2>Password Reset Error</h2>
    <p><?= $error_msg ?></p>
    
<?php else: // $message is set, but it just means password change was successful ?>

    <h2>Password Reset</h2>
    <p>Your password has been reset and we have emailed details to you.</p>
    <p>If you can't find our email, look in your SPAM folder.</p>
    
<?php endif; ?>

<p>
    <?= $this->load_view('registration_snippet_reg_links') ?>
</p>
