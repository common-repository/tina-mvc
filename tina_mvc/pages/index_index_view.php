<?php
/**
* Template File: Default page for the tina-mvc-for-wordpress page
*
* @package  Tina-MVC
* @subpackage Core
*/

/**
 * Make sure the TINA_MVC_PAGE_CONTROLLER_NAME is set so we can produce example links...
 */
if( !defined('TINA_MVC_PAGE_CONTROLLER_NAME') ) {
 define( 'TINA_MVC_PAGE_CONTROLLER_NAME' , 'tina-mvc-for-wordpress' );
}
/**
 * Security check - make sure we were included by the main plugin file
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>
<div class="wrap">

<h3>My Profile</h3>

<p><?= TINA_MVC\get_my_profile_link( 'View and/or edit your profile (email address, display name, password) here' ) ?>.</p>

<h3>Tina MVC for Wordpress</h3>

<?php // another example of how to use a view file from within a view file... ?>
<?php echo $this->load_view('tina_mvc_logo_snippet', ($dummy=false), "tina_mvc/admin_pages/assets");  ?>

<p>Tina MVC is a development framework for Wordpress. It is designed to implement a MVC approach to developing plugins, widgets and shortcodes.</p>

<p>Tina MVC is released under the GPL v2 license by Francis Crossen, <a href="http://www.seeit.org" target="_blank">SeeIT.org</a>.</p>

<p>For commercial support or for alternative licensing, <a href="http://www.seeit.org" target="_blank">contact us</a>.</p>

<h3>Debug Information</h3>

<p><em>Controller file is: <?= $controller_file ?>.</em></p>

<h3>Intended Audience</h3>

<p>Tina MVC is for developers. If you do not intend to code your own plugin, widget or shortcode this plugin is not for you.</p>

<h3>Getting Started</h3>

<p>Documentation is now included with the plugin and is accessed from the <a href="<?= site_url('wp-admin/admin.php?page=tina_mvc_for_wordpress') ?>">Tina MVC Wordpress admin pages</a>.</p>

<h3>About Tina MVC</h3>

<p>Tina MVC was written to replace a home grown framework that I had been using for about 2<sup>1</sup>/<sub>2</sub> years. It had served me well but had got to the stage
where further development was pointless. At the same time, Wordpress was becoming very popular and it made sense to use it as a base for a new application framework. I was
getting tired maintaining code for basic user management and content management functions and Wordpress took that load away from me.</p>

<p>Tina MVC started as a plugin to implement a Model-View-Controller (MVC) approach to plugin development. I was not a great fan of Wordpress (and still have major problems
with some of the architectural choices in Wordpress) but it is ubiquitous, well supported and developed and has very large base of developers, plugins and users. Tina MVC
was my way of addressing some of these shortcomings and putting some structure on plugin development.</p>

<p>Tina MVC version 1.x is a complete rewrite. It began about 2 years after the first release of Tina MVC. At that time a decision was made to ditch backwards compatibility
with the previous branch, but there is not much much to migrating old Tina MVC aplications to the v1.x branch. This allowed for a complete reorganisation of the plugin and
for use of more adanced PHP features. The rewritten helpers are designed to be much more flexible and extensible.</p>


</div>
