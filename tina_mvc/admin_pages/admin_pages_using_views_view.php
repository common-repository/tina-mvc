<?php
/**
* Template File: Tina MVC Wordpress admin pages - using view files.
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

<h2>Using view files</h2>

<p><em><strong>NB:</strong> Since version 1.0.14 you can add content directly to the Tina MVC page without using a view file. See below.</em></p>

<p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p>

<h3>Create a view file</h3>

<p>View files are placed in the same folders as controller files, and the same search algorithm is used to locate them. They are standard PHP files.</p>

<p>Create <code>using_views_view.php</code> in <code>user_apps/default/pages/tina_mvc_for_wordpress</code> as follows.
(See <code>samples/02_using_views</code> for listings used in this tutorial.</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code03 ?></div>

<h3>Create a controller file</h3>

<p>Create <code>using_views_controller.php</code> in <code>user_apps/default/pages/tina_mvc_for_wordpress</code> as follows.</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code04 ?></div>

<p>Any variables that you assign using <code>add_var()</code> and <code>add_var_e()</code> are extracted into the global scope
of the view file. For example, a variable <code>$firstname</code> added using <code>add_var_e( 'firstname', $firstname )</code> can be referenced in your view file
as <code>$firstname</code>. Note the difference between the two functions.</p>

<p>By default Tina MVC disables the wpautop() function to prevent it from inserting unwanted HTML into the Tina MVC page. (See
<a href="http://codex.wordpress.org/Function_Reference/wpautop">http://codex.wordpress.org/Function_Reference/wpautop</a>.)</p>

<h3>Adding content directly to the page (without using a veiw file)</h3>

<p>Some controllers use a helper to produce almost all the content for a page. Rather than create a visa file that basically just does:
<div style="line-height: 1.1em; background: #eaeaea;"><?= esc_html( '<?= $content ?>' ) ?></div>
and nothing else, you can add the content directly to the page using the add_content() and add_raw_content() functions.
</p>

<h4>add_content( $content='', $tag='', $esc=TRUE, $attribs='' )</h4>

<p>Adds content to the post/page content</p>

<p>By default the content is escaped and then run through wpautop().</p>

<p><strong>Parameters:</strong><br />
(string) $content The content to add to the page<br />
(string) $tag What to wrap the content in, default '' and let wpautop() take care of it, else 'p', h1', etc
(boolean) $esc Escape the data, default TRUE<br />
(string) $attribs attributes to include in the tag
</p>

<h4>add_raw_content( $content='', $tag='', $attribs='' )</h4>

<p>Adds raw (pre-escaped) content to the post/page content. Use this to add content from the form and table helpers for example.</p>

<p><strong>Parameters:</strong><br />
(string) $content The content to add to the page<br />
(string) $tag What to wrap the content in, default '' and let wpautop() take care of it, else 'p', h1', etc
(string) $attribs attributes to include in the tag
</p>

<h4>Example</h4>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code04a ?></div>

