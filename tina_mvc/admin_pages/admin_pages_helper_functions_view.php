<?php
/**
* Template File: Tina MVC Wordpress admin pages - helper functions.
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

<h2>Helper Functions</h2>

<p>This list is generated dynamically from the source file: <code>tina_mvc_functions.php</code>. Please report any parsing errors.</p>

<a name="tina_mvc_top"></a>
<p>
<?php foreach( $tina_functions AS $name => $function ): ?>
<a href="#<?= str_replace( '\\', '', $name ) ?>"><?= $name ?></a><br />
<?php endforeach; ?>
</p>

<div style="font-family: monospace">
<?php foreach( $tina_functions AS $name => $function ): ?>
<p>
<a name="<?= str_replace( '\\', '', $name ) ?>"><strong><?= $function['function'] ?></strong></a> <a href="#tina_mvc_top">(top)</a><br />
<?php
    $docblock = str_replace( "\n", '<br />', str_replace( "\r\n", '<br />', $function['docblock'] ) );
?>
<?= $docblock ?>
</p>
<?php endforeach; ?>
</div>