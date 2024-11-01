<?php
/**
* Email template File: Sends username and password to a newly registered user
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
Hi,

You (or someone else) registered for an account on "<?= get_bloginfo('name', 'Display') ?>" using the email address "<?= $to ?>".

Username: <?= $username ?> 
Password: <?= $password ?> 

If you didn't register for an account (or if you registered by mistake), you can ignore this message.

Sincerely,
<?= get_bloginfo('name', 'Display') ?>
<?= site_url() ?>
