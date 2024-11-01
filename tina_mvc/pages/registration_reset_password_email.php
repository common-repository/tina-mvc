<?php
/**
* Email template File: The password reset link (sent after a user has tried to reset their password)
*
* @package    Tina-MVC
* @subpackage Core
* @author     Francis Crossen <francis@crossen.org>
*/

/**
 * You should include this check in every view file you write. The constant is defined in
 * tina_mvc_base_page->load_view() 
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) die("Tut, tut. You wouldn't be trying to hack my site now would you?");
?>
Hi <?= esc_html($username) ?>,

You (or someone else) requested a password reset for your account with <?= get_bloginfo('name', 'Display') ?>.

If you did not request a password reset, then just ignore this email and nothing will happen. Otherwise click the following link to continue:
<?= TINA_MVC\get_controller_url('registration/password-reset-confirmation/' . urlencode($username) . '/' . urlencode($hash) ) ?>.

If the above link doesn't work for you, then go to <?= TINA_MVC\get_controller_url('registration/password-reset-confirmation') ?> and use the following code to reset your password:
<?= $hash ?>

Sincerely,
<?= get_bloginfo('name', 'Display') ?>
<?= site_url() ?>
