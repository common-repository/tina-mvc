<?php
/**
* Template File: tutorial 2 - using views.
*
* @package  Tina-MVC
* @subpackage Samples
*/
/**
 * Security check - make sure we were included by the main plugin file
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>

<h2>This HTML is generated from a view file</h2>

<p>A simple variable: <?= $hello_world ?></p>

<p>Iterating over an array or object:<br />
<?php foreach( $the_array AS $key => $val ): ?>
<?= $key ?> =&gt; <?= $val ?><br />
<?php endforeach; ?>
</p>

<p>A pre-escaped variable (added properly with $this->add_var():<br />
<?= $link1 ?></p>

<p>A pre-escaped variable (added the wrong way with $this->add_var_e():<br />
<?= $link2 ?></p>
