<?php
/**
* Template File: Tina MVC Wordpress admin pages - form helper advanced use
*
* @package  Tina-MVC
* @subpackage Docs
*/
/**
 * Security check - make sure we were included by the main plugin file
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>
<div class="wrap"><br />

<h2>The Form Helper - Advanced Usage</h2>

<p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p>

<p>The following controller (in <code>samples/09_form_helper_advanced/test_form_2_controller.php</code>) shows more advanced
useage of the forms helper.</p>

<h3>test_form_2_controller.php</h3>

<p>This example illustrates the use of:
<ul>
    <li>Using method chaining to add field properties</li>
    <li>Adding multiple validation rules</li>
    <li>Using 'db_table' and 'db_field' to group submitted data (see <code>$form->get_posted_data()</code> and
        <code>$form->get_posted_db_data()</code> towards the end of the this controller file)</li>
</ul>
</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code091 ?></div>

<h3>test_form_2_view.php</h3>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code092 ?></div>

