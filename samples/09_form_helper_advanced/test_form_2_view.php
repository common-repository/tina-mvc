<?php
/**
 * A trivial view file for the test_form_2 controller
 *
 * @package    Tina-MVC
 * @subpackage Samples
 * @author     Francis Crossen <francis@crossen.org>
 * @see        test_form_page.php the page controller
 */
/**
 * You should include this check in every view file you write. The constant is defined in
 * tina_mvc_base_page->load_view() 
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>

<p>The form is posted back to this page. The page is updated with the results of POSTed data without sending a browser redirect.
This is intentional so you can hit reload on your browser and re-POST the forms.</p>

<p>Remember it is sensible practice to send a browser redirect (use the Wordpress wp_redirect() function) after updating
a persistent data store (usually MySQL). This prevents a browser reload from resubmitting the form.</p>

<p>If any form is POSTed sucessfully the data submitted will be shown after that form.</p>

<?= $view_html ?>
