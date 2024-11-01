<?php
/**
* Template File: Tina MVC Wordpress admin pages - form helper advanced use
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

<h2>The Table and Pagination Helpers</h2>

<p><em><strong>NB:</strong> All paths are relative to the Tina MVC plugin folder: <code><?= TINA_MVC\plugin_folder() ?></code>.</em></p>

<p>The table helper creates a HTML table based on key and value pairs of an array or object. It is used by the pagination helper.</p>

<p>The pagination helper created a sortable, searchable paged list based on your data and custom SQL calls.</p>

<h3>The Table Helper</h3>

<p>The following listing <code>samples/12_table_and_pagination_helpers/html_table_controller.php</code> demonstrates the use of the
table helper.</p>

<p>Table headings are taken from array keys or object variables. All values are escaped. The table helper also allows you to set 
column headings by passing an array ot the helper. The second example in the listing below shows how to achieve this.</p>

<p>Finally you can prevent the values in table cells from being escaped by using the function <code>do_not_esc_td( TRUE )</code>.</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code121 ?></div>

<p>The output will look something like this (depending on your themes stylesheet):</p>

<h2>The First Table</h2><table id="first_table" class="tina_mvc_table"><thead><tr><th>column_one</th><th>Column 2</th><th>&lt;col-3&gt;</th></tr></thead><tbody><tr><td>548820814</td><td>45141429</td><td>2123594143</td></tr><tr><td>1525498250</td><td>393353600</td><td>931051124</td></tr><tr><td>2146127365</td><td>101269595</td><td>1216228927</td></tr><tr><td>267463870</td><td>1500609621</td><td>2071282106</td></tr><tr><td>1277638230</td><td>346776766</td><td>617695374</td></tr></tbody></table><h2>The Second Table</h2><table id="second_table" class="tina_mvc_table"><thead><tr><th><a href="#">column_one</a></th><th>Column 2(&euro;)</th><th>Now you see me -&gt; &lt;col-3&gt; and now you don&#8217;t -&gt; <col-3></th></tr></thead><tbody><tr><td>1801669305</td><td>798258155</td><td>1943426802</td></tr><tr><td>1182178789</td><td>869883474</td><td>680626749</td></tr><tr><td>1889473510</td><td>1542330766</td><td>1440649556</td></tr><tr><td>432352788</td><td>1275076701</td><td>1377461109</td></tr><tr><td>1034800140</td><td>1652667844</td><td>641666595</td></tr><tr><td>445222141</td><td>1677554056</td><td>142195685</td></tr><tr><td>1497874427</td><td>2003669126</td><td>963921126</td></tr><tr><td>949942656</td><td>658389999</td><td>604554798</td></tr><tr><td>1751966758</td><td>43377642</td><td>1138870196</td></tr><tr><td>346731456</td><td>46897645</td><td>963412243</td></tr><tr><td>1098136340</td><td>517871114</td><td>677000079</td></tr><tr><td>1404955584</td><td>1237357245</td><td>1430090184</td></tr></tbody></table>

<h3>The Pagination Helper</h3>

<p><em>The pagination helper is based on code written by and copyright <a href="mailto:admin@catchmyfame.com">admin@catchmyfame.com</a>
published on <a href="http://www.catchmyfame.com/2011/10/23/php-pagination-class-updated-version-2/" target="_blank">http://www.catchmyfame.com/2011/10/23/php-pagination-class-updated-version-2/</a>.
It is altered to fit the Tina MVC framework.</em></p>

<p>The pagination helper builds a sortable, searchable, paged list of data based on your data or custom SQL.</p>

<h3>Basic Operation</h3>

<p>The following listing <code>samples/12_table_and_pagination_helpers/page_test_1_controller.php</code> demonstrates basic operation.</p>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code122 ?></div>

<p>The output will look something like this (this is not a working example):</p>

<div id="my_paginator_pager">
<FORM ACTION="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/#my_paginator_pager" METHOD="post"><strong>Filter by User Login, Display Name, user_email: </strong><br /><INPUT TYPE="TEXT" NAME="my_paginator_filter_terms" VALUE="" ><INPUT TYPE="SUBMIT" NAME="my_paginator_filter_terms_filter" VALUE="Filter"><INPUT TYPE="SUBMIT" NAME="my_paginator_filter_terms_clear" VALUE="Clear"></FORM><span class="inactive" href="#">&laquo; Previous</span> <span title="Go to page 1 of 106" class="current inactive" href="#">1</span> <a class="paginate" title="Go to page 2 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=2&#038;ipp=10">2</a> <a class="paginate" title="Go to page 3 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=3&#038;ipp=10">3</a> <a class="paginate" title="Go to page 4 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=4&#038;ipp=10">4</a> <a class="paginate" title="Go to page 5 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=5&#038;ipp=10">5</a> <a class="paginate" title="Go to page 6 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=6&#038;ipp=10">6</a> <a class="paginate" title="Go to page 7 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=7&#038;ipp=10">7</a> <a class="paginate" title="Go to page 8 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=8&#038;ipp=10">8</a> <a class="paginate" title="Go to page 9 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=9&#038;ipp=10">9</a>  &#8230; <a class="paginate" title="Go to page 106 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=106&#038;ipp=10">106</a> <a class="paginate" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=2&#038;ipp=10">Next &raquo;</a>
<a class="paginate" style="margin-left:10px" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=1&#038;ipp=All">All</a> 
<table id="sample_table" class="tina_mvc_table"><thead><tr><th><a href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/1/0/sort-Database+ID-asc#">Database ID</a><span style="width:2em;">&nbsp;&nbsp;</span></th><th><a href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/1/0/sort-User+Login-asc#">User Login</a><span style="width:2em;">&nbsp;&nbsp;</span></th><th><a href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/1/0/sort-Display+Name-asc#">Display Name</a><span style="width:2em;">&nbsp;&nbsp;</span></th></tr></thead><tbody><tr><td>1</td><td>admin</td><td>Dean</td></tr><tr><td>697</td><td>AlanBoyd</td><td>Alan</td></tr><tr><td>247</td><td>AlanBurton</td><td>Alan</td></tr><tr><td>757</td><td>AlanCruz</td><td>Alan</td></tr><tr><td>355</td><td>AlanGeorge</td><td>Alan</td></tr><tr><td>939</td><td>AlanHarris</td><td>Alan</td></tr><tr><td>420</td><td>AlanKing</td><td>Alan</td></tr><tr><td>208</td><td>AlanPeters</td><td>Alan</td></tr><tr><td>145</td><td>AlbertArnold</td><td>Albert</td></tr><tr><td>469</td><td>AlbertFrazier</td><td>Albert</td></tr></tbody></table><span class="inactive" href="#">&laquo; Previous</span> <span title="Go to page 1 of 106" class="current inactive" href="#">1</span> <a class="paginate" title="Go to page 2 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=2&#038;ipp=10">2</a> <a class="paginate" title="Go to page 3 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=3&#038;ipp=10">3</a> <a class="paginate" title="Go to page 4 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=4&#038;ipp=10">4</a> <a class="paginate" title="Go to page 5 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=5&#038;ipp=10">5</a> <a class="paginate" title="Go to page 6 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=6&#038;ipp=10">6</a> <a class="paginate" title="Go to page 7 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=7&#038;ipp=10">7</a> <a class="paginate" title="Go to page 8 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=8&#038;ipp=10">8</a> <a class="paginate" title="Go to page 9 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=9&#038;ipp=10">9</a>  &#8230; <a class="paginate" title="Go to page 106 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=106&#038;ipp=10">106</a> <a class="paginate" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=2&#038;ipp=10">Next &raquo;</a>
<a class="paginate" style="margin-left:10px" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-1/?ppage=1&#038;ipp=All">All</a> 
</div></div>

<h3>Using Custom HTML Instead of a Table</h3>

<p>The pagination helper allows you to use arbitrary HTML for rows instead of a table. The <code>get_sql_rows()</code>
function returns an array of objects. These are the rows of data for the current page.</p>

<p>In the example below, each row is rendered using a view file: <code>samples/12_table_and_pagination_helpers/page_test_2_view.php</code>, but there is nothing to stop you using plain HTML.
The view file is used to render a custom heading as well as each row of data. The template variable <code>$view_file_part</code> is used to select
which piece of HTML is used in the view file.</p>

<p>Once you have finished creating the HTML rows, the HTML is returned to the paginator object using the
<code>set_html_rows()</code> function. If you use custom HTML for your rows, and do not pass an array back to the paginator,
the table headings are not rendered. In this case, if you wish to set a heading, use the <code>set_html_headings()</code> function.</p>

<h4>page_test_2_controller.php</h4>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code123 ?></div>

<h4>page_test_2_view.php</h4>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code124 ?></div>

<p>The output will look something like this (this is not a working example):</p>

<div id="my_paginator_pager">
<FORM ACTION="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/#my_paginator_pager" METHOD="post"><strong>Filter by User Login, Display Name, user_email: </strong><br /><INPUT TYPE="TEXT" NAME="my_paginator_filter_terms" VALUE="" ><INPUT TYPE="SUBMIT" NAME="my_paginator_filter_terms_filter" VALUE="Filter"><INPUT TYPE="SUBMIT" NAME="my_paginator_filter_terms_clear" VALUE="Clear"></FORM><span class="inactive" href="#">&laquo; Previous</span> <span title="Go to page 1 of 106" class="current inactive" href="#">1</span> <a class="paginate" title="Go to page 2 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=2&#038;ipp=10">2</a> <a class="paginate" title="Go to page 3 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=3&#038;ipp=10">3</a> <a class="paginate" title="Go to page 4 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=4&#038;ipp=10">4</a> <a class="paginate" title="Go to page 5 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=5&#038;ipp=10">5</a> <a class="paginate" title="Go to page 6 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=6&#038;ipp=10">6</a> <a class="paginate" title="Go to page 7 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=7&#038;ipp=10">7</a> <a class="paginate" title="Go to page 8 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=8&#038;ipp=10">8</a> <a class="paginate" title="Go to page 9 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=9&#038;ipp=10">9</a>  &#8230; <a class="paginate" title="Go to page 106 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=106&#038;ipp=10">106</a> <a class="paginate" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=2&#038;ipp=10">Next &raquo;</a>
<a class="paginate" style="margin-left:10px" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=1&#038;ipp=All">All</a> 
<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
    <h2>My Custom Table Heading</h2>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #222; background: #aaa">
    <small><em>This is row number 0</em></small><br />
    <strong><big>Display Name: Dean</big></strong><br />
    User Login: admin (Database ID: 1    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #aaa; background: #222">
    <small><em>This is row number 1</em></small><br />
    <strong><big>Display Name: Alan</big></strong><br />
    User Login: AlanBoyd (Database ID: 697    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #222; background: #aaa">
    <small><em>This is row number 2</em></small><br />
    <strong><big>Display Name: Alan</big></strong><br />
    User Login: AlanBurton (Database ID: 247    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #aaa; background: #222">
    <small><em>This is row number 3</em></small><br />
    <strong><big>Display Name: Alan</big></strong><br />
    User Login: AlanCruz (Database ID: 757    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #222; background: #aaa">
    <small><em>This is row number 4</em></small><br />
    <strong><big>Display Name: Alan</big></strong><br />
    User Login: AlanGeorge (Database ID: 355    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #aaa; background: #222">
    <small><em>This is row number 5</em></small><br />
    <strong><big>Display Name: Alan</big></strong><br />
    User Login: AlanHarris (Database ID: 939    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #222; background: #aaa">
    <small><em>This is row number 6</em></small><br />
    <strong><big>Display Name: Alan</big></strong><br />
    User Login: AlanKing (Database ID: 420    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #aaa; background: #222">
    <small><em>This is row number 7</em></small><br />
    <strong><big>Display Name: Alan</big></strong><br />
    User Login: AlanPeters (Database ID: 208    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #222; background: #aaa">
    <small><em>This is row number 8</em></small><br />
    <strong><big>Display Name: Albert</big></strong><br />
    User Login: AlbertArnold (Database ID: 145    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<!--// TINA_MVC VIEW FILE START: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->
        <div style="color: #aaa; background: #222">
    <small><em>This is row number 9</em></small><br />
    <strong><big>Display Name: Albert</big></strong><br />
    User Login: AlbertFrazier (Database ID: 469    </div>
<!--// TINA_MVC VIEW FILE END: /home/dev/domains/wp.dev.seeit.org/public_html/wp-content/plugins/tina-mvc/user_apps/default/pages/tina_mvc_for_wordpress/page_test_2_view.php //-->

<span class="inactive" href="#">&laquo; Previous</span> <span title="Go to page 1 of 106" class="current inactive" href="#">1</span> <a class="paginate" title="Go to page 2 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=2&#038;ipp=10">2</a> <a class="paginate" title="Go to page 3 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=3&#038;ipp=10">3</a> <a class="paginate" title="Go to page 4 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=4&#038;ipp=10">4</a> <a class="paginate" title="Go to page 5 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=5&#038;ipp=10">5</a> <a class="paginate" title="Go to page 6 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=6&#038;ipp=10">6</a> <a class="paginate" title="Go to page 7 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=7&#038;ipp=10">7</a> <a class="paginate" title="Go to page 8 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=8&#038;ipp=10">8</a> <a class="paginate" title="Go to page 9 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=9&#038;ipp=10">9</a>  &#8230; <a class="paginate" title="Go to page 106 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=106&#038;ipp=10">106</a> <a class="paginate" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=2&#038;ipp=10">Next &raquo;</a>
<a class="paginate" style="margin-left:10px" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-2/?ppage=1&#038;ipp=All">All</a> 

</div>

<h3>Using Custom HTML With a Table</h3>

<p>You can use custom HTML with sortable table headings by passing an array or object back to the paginator. The file
<code>samples/12_table_and_pagination_helpers/page_test_3_controller.php</code> demonstrates. In this case, the custom HTML is passed
back as an array of objects. This allows the use of sortable columns.</p>

<p>If you create a custom column on the fly (i.e. a column that is not straight from the database, then that coumn cannot be sorted. You
can suppress the use of a sortable column using the <code>suppress_sort()</code> function.</p>

<h4>page_test_3_controller.php</h4>

<div style="line-height: 1.1em; background: #eaeaea;"><?= $code125 ?></div>

<p>The output will look something like this (this is not a working example):</p>
<div id="my_paginator_pager">
<FORM ACTION="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/#my_paginator_pager" METHOD="post"><strong>Filter by User Login, Display Name, user_email: </strong><br /><INPUT TYPE="TEXT" NAME="my_paginator_filter_terms" VALUE="" ><INPUT TYPE="SUBMIT" NAME="my_paginator_filter_terms_filter" VALUE="Filter"><INPUT TYPE="SUBMIT" NAME="my_paginator_filter_terms_clear" VALUE="Clear"></FORM><span class="inactive" href="#">&laquo; Previous</span> <span title="Go to page 1 of 106" class="current inactive" href="#">1</span> <a class="paginate" title="Go to page 2 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=2&#038;ipp=10">2</a> <a class="paginate" title="Go to page 3 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=3&#038;ipp=10">3</a> <a class="paginate" title="Go to page 4 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=4&#038;ipp=10">4</a> <a class="paginate" title="Go to page 5 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=5&#038;ipp=10">5</a> <a class="paginate" title="Go to page 6 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=6&#038;ipp=10">6</a> <a class="paginate" title="Go to page 7 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=7&#038;ipp=10">7</a> <a class="paginate" title="Go to page 8 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=8&#038;ipp=10">8</a> <a class="paginate" title="Go to page 9 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=9&#038;ipp=10">9</a>  &#8230; <a class="paginate" title="Go to page 106 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=106&#038;ipp=10">106</a> <a class="paginate" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=2&#038;ipp=10">Next &raquo;</a>
<a class="paginate" style="margin-left:10px" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=1&#038;ipp=All">All</a> 
<table id="sample_table" class="tina_mvc_table"><thead><tr><th><a href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/1/0/sort-Database+ID-asc#">Database ID</a><span style="width:2em;">&nbsp;&nbsp;</span></th><th><a href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/1/0/sort-User+Login-asc#">User Login</a><span style="width:2em;">&nbsp;&nbsp;</span></th><th>A non-DB field (non-sortable)</th></tr></thead><tbody><tr><td><span style="color:#333;background:#ccc">1</span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Dean">admin</a></span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Dean">0</a></span></td></tr><tr><td><span style="color:#ccc;background:#333">697</span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">AlanBoyd</a></span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">1</a></span></td></tr><tr><td><span style="color:#333;background:#ccc">247</span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Alan">AlanBurton</a></span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Alan">2</a></span></td></tr><tr><td><span style="color:#ccc;background:#333">757</span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">AlanCruz</a></span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">3</a></span></td></tr><tr><td><span style="color:#333;background:#ccc">355</span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Alan">AlanGeorge</a></span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Alan">4</a></span></td></tr><tr><td><span style="color:#ccc;background:#333">939</span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">AlanHarris</a></span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">5</a></span></td></tr><tr><td><span style="color:#333;background:#ccc">420</span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Alan">AlanKing</a></span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Alan">6</a></span></td></tr><tr><td><span style="color:#ccc;background:#333">208</span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">AlanPeters</a></span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Alan">7</a></span></td></tr><tr><td><span style="color:#333;background:#ccc">145</span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Albert">AlbertArnold</a></span></td><td><span style="color:#333;background:#ccc"><a href="#" title="Albert">8</a></span></td></tr><tr><td><span style="color:#ccc;background:#333">469</span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Albert">AlbertFrazier</a></span></td><td><span style="color:#ccc;background:#333"><a href="#" title="Albert">9</a></span></td></tr></tbody></table><span class="inactive" href="#">&laquo; Previous</span> <span title="Go to page 1 of 106" class="current inactive" href="#">1</span> <a class="paginate" title="Go to page 2 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=2&#038;ipp=10">2</a> <a class="paginate" title="Go to page 3 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=3&#038;ipp=10">3</a> <a class="paginate" title="Go to page 4 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=4&#038;ipp=10">4</a> <a class="paginate" title="Go to page 5 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=5&#038;ipp=10">5</a> <a class="paginate" title="Go to page 6 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=6&#038;ipp=10">6</a> <a class="paginate" title="Go to page 7 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=7&#038;ipp=10">7</a> <a class="paginate" title="Go to page 8 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=8&#038;ipp=10">8</a> <a class="paginate" title="Go to page 9 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=9&#038;ipp=10">9</a>  &#8230; <a class="paginate" title="Go to page 106 of 106" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=106&#038;ipp=10">106</a> <a class="paginate" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=2&#038;ipp=10">Next &raquo;</a>
<a class="paginate" style="margin-left:10px" href="http://wp.dev.seeit.org/tina-mvc-for-wordpress/page-test-3/?ppage=1&#038;ipp=All">All</a> 
</div>