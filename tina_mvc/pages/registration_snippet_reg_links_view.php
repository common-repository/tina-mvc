<?php
/**
* Template File: View file snippet for registration links
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
<ul>
    <li><?= TINA_MVC\get_controller_link( '', 'Login here') ?>.</li>
    <?php if( get_option('users_can_register')): ?>
        <li><?= TINA_MVC\get_controller_link('registration', 'Register here') ?>.</li>
    <?php endif; ?>
    <li><?= TINA_MVC\get_controller_link('registration/reset-password', 'Reset your password here') ?>.</li>
</ul>
