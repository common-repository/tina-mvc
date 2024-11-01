<?php
/**
* Template File: View/Edit the users personal account data
*
* This view file is used for the view and edit actions of the my_profile controller.
*
* @package    Tina-MVC
* @subpackage Tina-Core
*/

/**
 * You should include this check in every view file you write. The constant is defined in
 * tina_mvc_base_page->load_view() 
 */
if( ! defined('TINA_MVC_LOAD_VIEW') ) exit();
?>
<?php if( empty( $edit_data_form ) ): ?>

<?php if( ! empty( $message ) ): ?>
<div style="color:red"><?= $message ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Key</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ID</td>
            <td><?= $current_user->ID ?></td>
        </tr>
        <tr>
            <td>Username</td>
            <td><?= $current_user->user_login ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $current_user->user_email ?></td>
        </tr>
        <tr>
            <td>First Name</td>
            <td><?= !empty($current_user->user_firstname) ? $current_user->user_firstname : '&nbsp;' ?></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><?= !empty($current_user->user_lastname) ? $current_user->user_lastname : '&nbsp;' ?></td>
        </tr>
        <tr>
            <td>Display Name</td>
            <td><?= !empty($current_user->display_name) ? $current_user->display_name : '&nbsp;' ?></td>
        </tr>
    </tbody>
</table>

<p><?php echo tina_mvc\get_controller_link('my-profile/edit','Edit profile') ?>.</p>

<?php else: ?>

    <?= $edit_data_form ?>

<?php endif; ?>
