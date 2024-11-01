<?php
/**
* Template File: Tina MVC Wordpress admin pages - list of field types and validation rules.
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

<h2>Form Helper Reference</h2>

<p>This list is generated dynamically from the source file: <code>tina_mvc_form_helper_class.php</code>. Please report any parsing errors.</p>

<h3>Field Types</h3>

<p>The Form Helper Introduction page shows the use of all these fields.</p>

<p>You can add your own fields by defining them in classes. See the class <code>field_text</code> definition in
<code>tina_mvc_form_helper_class.php</code> to see how this is done.</p>

<p>
<?php foreach( $field_types AS $name => $f_arr ): ?>
<?= $f_arr['type_for_helper'] ?><br />
<?php endforeach; ?>
</p>

<h3>Validation Types</h3>

<p>You can add your own validation types by defining them in classes. See the class <code>validate</code> definition in
<code>tina_mvc_form_helper_class.php</code> to see how this is done.</p>

<a name="tina_mvc_top_f"></a>
<p>
<?php foreach( $validate_types AS $name => $f_arr ): ?>
<a href="#<?= str_replace( '\\', '', $name ) ?>"><?= $f_arr['type_for_helper'] ?></a><br />
<?php endforeach; ?>
</p>

<div style="font-family: monospace">
<?php foreach( $validate_types AS $name => $f_arr ): ?>
<p>
<a name="<?= str_replace( '\\', '', $name ) ?>"><strong><?= $f_arr['type_for_helper'] ?></strong></a> <a href="#tina_mvc_top_f">(top)</a><br />
<?php
    $docblock = str_replace( "\n", '<br />', str_replace( "\r\n", '<br />', $f_arr['docblock'] ) );
?>
<?= $docblock ?>
</p>
<?php endforeach; ?>
</div>

