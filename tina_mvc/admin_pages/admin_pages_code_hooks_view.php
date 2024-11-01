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

<h2>Code &amp; Cron Hooks</h2>

<p>Tina MVC provides several hooks for running arbitrary code. These allow you to have your code run at certain points. They act as wrappers to Wordpress hooks.</p>

<p>These hooks must be enabled in <code>app_settings.php</code>.</p>

<h3>The Tina MVC Plugin Activate/Deactivate hook</h3>

<p>Run whenever Tina MVC is activated/deactivated. This feature is always enabled.</p>

<h4>Running code when Tina MVC is activated</h4>

<p>Create a file called <code>tina_mvc_app_install.php</code> in the <code>install_remove</code> folder. Put your code in a function called <code>tina_mvc_app_install()</code>.</p>

<h4>Running code when Tina MVC is deactivated</h4>

<p>Create a file called <code>tina_mvc_app_remove.php</code> in the <code>install_remove</code> folder. Put your code in a function called <code>tina_mvc_app_remove()</code>.</p>

<h4>Uses</h4>

<p>Setting up database tables for your application or setting up custom roles and permissions. For example:</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code041 ?></div>

<h3>The Tina MVC Bootstrap hook</h3>

<p>The bootstrap functionality must be enabled in <code>app_settings.php</code>. It allows you to run arbitrary code on the "the_posts" action hook (before browser output starts).</p>

<p>If you do not need this functionality then do not enable it. Tina MVC will check for the existence of your code on every page request (not just on Tina MVC pages).
This is necessary because you might be using Tina MVC only for shortcodes or widgets.</p>

<p>This allows you to use functions like <code>wp_enqueue_script()</code> and <code>wp_enqueue_style()</code> before browser output starts.</p>

<h4>Where to put your code</h4>

<p>Put your code in the <code>tina_mvc_bootstrap</code> folder. Tina MVC will include any PHP file (other than index.php) it finds in there. It will then attempt to call a function
based on the name of the included file. For example: if Tina MVC finds a file called <code>my_custom_code.php</code> it will call the function <code>my_custom_code()</code>.</p>

<h4>Uses</h4>

<p>Enqueue css and javascript files so they are available from withing your shortcode and widgets.</p>

<h3>The Tina MVC Init Bootstrap hook</h3>

<p>Enable in <code>app_settings.php</code>.</p>

<p>Again Tina MVC will check for the existence of your code on every page request, so there is a performance penalty.</p>

<h4>Where To Put Your Code</h4>

<p>Put your code in the <code>init_bootstrap</code> folder. Tina MVC will include and run your code in the same way as it does for code in the Tina MVC Bootstrap hook (see above).</p>

<h4>Uses</h4>

<p>Setting up custom posts and taxonomies.</p>

<h3>The Tina MVC Hourly Cron hook</h3>

<p>A simple hook that allows you to schedule wp-cron jobs. If you require more functionality it is recommended that you include your code using the Init Bootstrap hook.</p>

<h4>Where to put your code</h4>

<p>Put your code in the <code>hourly_cron</code> folder. Tina MVC will include any PHP file (other than index.php) it finds in there. It will then attempt to call a function
based on the name of the included file. For example: if Tina MVC finds a file called <code>my_custom_cron.php</code> it will call the function <code>my_custom_cron()</code>.</p>

</div>