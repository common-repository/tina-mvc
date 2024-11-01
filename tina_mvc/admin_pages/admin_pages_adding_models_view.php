<?php
/**
* Template File: Tina MVC Wordpress admin pages - running code on hooks.
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

<h2>Adding Models</h2>

<?php /* <p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p> */ ?>

<p>All persistent data should be accessed through a model. The Tina MVC model class is really just a skeleton. Wordpress custom posts can cater for many
requirements. For anything more complicated, (for example if you are using custom tables) you should create functions for yoru CRUD operations
in a model class.</p>

<p>For convenience, the <code>$wpdb</code> object is accessible from within a model as <code>$this->DB</code> and <code>$this->wbpd</code>.</p>

