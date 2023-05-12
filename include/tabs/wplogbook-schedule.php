<?php
$error = isset($_SESSION['errors']) ? $_SESSION['errors'] : array();
$value = isset($_SESSION['values']) ? $_SESSION['values'] : array();
unset($_SESSION['errors']);
unset($_SESSION['values']);
?>
<form action="<?= esc_url(admin_url('admin-post.php')); ?>" method="POST">
    <input type="hidden" name="action" value="wp_logbook_save_schedule">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label for=""><?= __("Log schedule*", "wp-logbook") ?></label>
                </th>
                <td>
                    <?php if (isset($error['log_schedule'])) : ?>
                        <p>
                            <small class="wpl_field_error">
                                <?= $error['log_schedule'] ?>
                            </small>
                        </p>
                    <?php endif; ?>
                    <input type="number" name="log_schedule" id="log_schedule" value="<?php if (!empty($value)) : ?><?= isset($value['log_schedule']) ? $value['log_schedule'] : '' ?><?php else : ?><?= get_option('wp_logbook_log_schedule') !== null ? get_option('wp_logbook_log_schedule') : '' ?><?php endif; ?>">
                    <span><?= __(" Minute", "wp-logbook") ?></span>
                </td>
            </tr>
        </tbody>
    </table>
    <?= submit_button() ?>
</form>