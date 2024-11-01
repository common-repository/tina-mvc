<?php
/**
* Template File: Tina MVC Wordpress admin pages - widgets and shortcodes
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

<p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p>

<h2>Widgets</h2>

<img src="<?= plugins_url( 'assets' , __FILE__ ) ?>/tina_mvc_widget.png" style="float: right">
<p>
The Tina MVC widget allows you to call a controller located in <code>user_apps/widgets</code>. If you want to duplicate the output of
a controller located elsewhere, create the file in the <code>user_apps/widgets</code> folder as normal and use a PHP <code>include()</code>
statement.
</p>

<p>The widget will not be displayed if permissions checks do not pass. Role and capabalities can be entered as a comma seperated list,
and are in addition to the permissions set in the controller.</p>

<p>In the example on the left, the widget will look for a class named <code>widget_controller</code> in a file named <code>widget_controller.php</code>.
The default <code>index()</code> will be called in this case.</p>

<h2>Shortcodes</h2>

<p>
The Tina MVC shortcode <code>[tina_mvc]</code> allows you to call a controller from a self-closing or enclosing shortcode. It
accepts the following parameters:<br />
<ul>
    <li>c: the controller to call (from the <code>user_apps/shortcodes</code> folder)</li>
    <li>role: role(s) to view (comma seperated list)</li>
    <li>cap: capability (capabalities) to view (comma seperated list)</li>
</ul>
Permissions are additive (as with the Tina MVC Widget), and if permissions requirements are not met, controller output will not
be displayed.
</p>

<p>Example: <code>[tina_mvc c="my_shortcodes/lister" role="administrator,editor"]</code></p>

<h2>$Tina_MVC->call_controller()</h2>

<p>Tina MVC includes a <code>call_controller()</code> function for use in themes and other plugins. The function is part of the
main Tina_MVC plugin class. For example:<br />
<?= $code_snippet ?>
is the code used to display this help page.
</p>

<p>You can also call this function statically: <code>TINA_MVC::call_controller()</code>.</p>

<p>Permissions are additive (as with widgets and shortcodes).</p>

<p>Syntax:<br />
<code>call_controller( $controller, $role_to_view=FALSE, $capability_to_view=FALSE, $custom_folder='' )</code><br />
If the $custom_folder parameter is omitted, Tina MVC will attempt to load the controller from the <code>user_apps/callable_controllers</code>
folder.</p>