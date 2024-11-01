<?php
/**
* Template File: Tina MVC Wordpress admin pages - form helper introduction
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

<h2>The Form Helper - Introduction</h2>

<p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p>

<p>The form helper is provides a convenient way of creating, validating and processing forms. It provides a handy way of loading recordsets from
database queries into a form.</p>

<p>The form helper is designed to be extensible for new field types and validation rules.</p>

<h3>Basic use</h3>

<p>The following controller (in <code>samples/08_form_helper_intro/test_form_controller.php</code>) shows basic use
of all form field types and of the basic use. The file is well commented. Use it as a starting point for your forms.</p>

<p>The next help page illustrates more advanced features of the form helper.</p>

<h3>test_form_controller.php</h3>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code081 ?></div>

<h3>test_form_view.php</h3>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code082 ?></div>

