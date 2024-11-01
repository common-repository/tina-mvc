<?php
/**
* Template File: tutorial 12 - using the pagination helper with custom html.
*
* This is not a complete view file. It is only used to style one row of data and the table heading
* (based on a variable passed to this view file).
*
* @package  Tina-MVC
* @subpackage Samples
*/
/**
 * Security check - make sure we were included by the main plugin file
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>
<?php if( $view_file_part == 'headings' ): ?>
    <h2>My Custom Table Heading</h2>
<?php else: ?>
    <?php
        if( $i % 2 ) {
            $bg = '#222';
            $fg = '#aaa';
        }
        else {
            $bg = '#aaa';
            $fg = '#222';
        }
    ?>
    <div style="color: <?= $fg ?>; background: <?= $bg ?>">
    <small><em>This is row number <?= $i ?></em></small><br />
    <strong><big>Display Name: <?= $r->{'Display Name'} ?></big></strong><br />
    User Login: <?= $r->{'User Login'} ?> (Database ID: <?= $r->{'Database ID'} ?>)
    </div>
<?php endif; ?>