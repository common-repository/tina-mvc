<?php
/**
* Template File: User not created due to a Wordpress error
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
<h2>Registration Failed</h2>

<p>We experienced an error creating your account. If this problem persists please contact the site administrator for assistance.</p>

<p>
    <?= $this->load_view('registration_snippet_reg_links') ?>
</p>
