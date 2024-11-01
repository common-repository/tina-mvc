<?php
/**
* Template File: Tina MVC Wordpress admin pages - hello world tutorial.
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

<h2>The Hello World Tutorial</h2>

<p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p>

<h3>Set the Tina MVC Site Application Settings</h3>

<p>Tina MVC will use the sample site settings (app_settings_sample.php) located in
<code>tina_mvc/user_apps/default</code> if it cannot find an <code>app_settings.php</code> file.</p>

<p>To prevent your customisations from being overwritten when the plugin is upgraded, 
you should copy <code>app_settings_sample.php</code> to <code>app_settings.php</code>.</p>

<h3>Create The Controller File (for use with a Tina MVC page)</h3>

<p>This controller will be called from the Tina MVC page (created when you install the plugin). The Tina MVC page acts as an entry point
into your application. You can have as many pages as you want, both public and private pages.</p>

<p>The default install creates a Tina MVC page called "Tina MVC for Wordpress" with the page slug "tina-mvc-for-wordpress". Pages
are set up in the app_settings.php file and are created when the plugin is activated.</p>

<p>Create the following file and save it as <code>tina_mvc/user_apps/default/pages/index_controller.php</code>. (You'll find this file in
<code>tina_mvc/samples/01_hello_world_tutorial</code>.</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code01 ?></div>

<h3>Anatomy of a basic controller</h3>

<p>All Tina MVC controllers extend <code>TINA_MVC\tina_mvc_controller_class</code>. The TINA_MVC namespace is used for all core classes and functions.</p>

<p>The constructor is not required (unless you are changing the default permissions to view the controller's output).
Tina MVC creates an instance of your controller before permission checks have been completed so all code in your
constructor will be executed before the dispatcher checks which function to run. Therefore it is good practice to keep the constructor as lightweight as possible.</p>

<p>
Tina MVC will look for a file named <code>controller_name_controller.php</code> (note the hyphens are replaced with underscores) in the following locations:<br />
<code>tina_mvc/user_apps/default/pages/tina_mvc_page_slug</code><br />
<code>tina_mvc/user_apps/default/pages</code><br />
<code>tina_mvc/tina_mvc/pages</code>.
</p>

<p>The search algorithm can be changed in <code>app_settings.php</code>.</p>

<p>Navigate to <?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress' , site_url().'/tina-mvc-for-wordpress/' ) ?> to see the controllers output. Pretty simple so far...</p>

<h3>Passing data to the controller</h3>

<p>A call to a contoller (via a Tina MVC page) is of the following format:<br />
<code>{$site_url}/tina-mvc-page-slug/controller-name/action/arbitrary/data/passed/in/the/uri</code>. In the example above, the default
controller (<code>index</code>) is instantiated and the default public function (<code>index</code>) is called.</p>

<p>Therefore the following links give exacty the same result:<br />
<?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress' , site_url().'/tina-mvc-for-wordpress/' ) ?><br />
<?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress/index' , site_url().'/tina-mvc-for-wordpress/index/' ) ?>
</p>

<p>Any uri elements after the controller function (<code>index()</code> in the case above) are passed to your controller via the Tina MVC request
variable and can be accessed via <code>$this->request</code>. For example, try:<br />
<?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress/index/arbitrary/data-passed/in/the/uri' , site_url().'/tina-mvc-for-wordpress/index/arbitrary-data/passed/in/the/uri/' ) ?>.
(Note that all hyphens are replaced by underscores.)</p>

<h3>Calling different controllers and functions</h3>

<p>You can call a different controller and controller function by specifying it in the uri. For example, try:<br />
<?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress/hello-world/another-function' , site_url().'/tina-mvc-for-wordpress/hello-world/another-function/' ) ?>.<br />
Because the controller file doesn't exist, Tina MVC displays an error, so let's create it.</p>

<p>Create <code>hello_world_controller.php</code> as follows. (You'll find this file in
<code>tina_mvc/samples/01_hello_world_tutorial</code>.</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code02 ?></div>

<p>Now, <?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress/hello-world/another-function' , site_url().'/tina-mvc-for-wordpress/hello-world/another-function/' ) ?> works.</p>

<p>The dispatcher function will only call public functions that do not begin with an underscore. In the second example, you will
see a private function <code>_get_boilerplate()</code>. Try <?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress/index/_get_boilerplate' , site_url().'/tina-mvc-for-wordpress/hello-world/_get_boilerplate/' ) ?>. 
The function cannot be called so the default <code>index()</code> function is called instead.</p>

<p>Next, The dispatcher function will only call public functions that do not begin with an underscore. In the second example, you will
see a private function <code>_get_boilerplate()</code>. Try <?= TINA_MVC\get_abs_controller_link('tina-mvc-for-wordpress/index/_get_boilerplate' , site_url().'/tina-mvc-for-wordpress/hello-world/_get_boilerplate/' ) ?>. 
The function cannot be called so the default <code>index()</code> function is called instead.</p>
