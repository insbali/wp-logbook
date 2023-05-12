<?php
$error = isset($_SESSION['errors']) ? $_SESSION['errors'] : array();
$value = isset($_SESSION['values']) ? $_SESSION['values'] : array();
unset($_SESSION['errors']);
unset($_SESSION['values']);
?>
<form action="<?= esc_url(admin_url('admin-post.php')); ?>" method="POST">
    <input type="hidden" name="action" value="wp_logbook_save_setting">
    <input type="hidden" name="debug_status" value="disable">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="debug_status"><?= __("Debug status", "wp-logbook") ?></label>
                </th>
                <td>
                    <input class="wpl_switch" id="debug_status" type="checkbox" <?= get_option("wp_logbook_status") == "enable" ? "checked" : "" ?> name="debug_status" value="enable">
                    <label class="wpl_switch_label" for="debug_status"></label>
                    <span class="wpl_debug_status"><?= strtoupper(get_option("wp_logbook_status")) ?></span>
                </td>
            </tr>
            <tr class="setting_field_none">
                <th scope="row">
                    <label for="api_key"><?= __("Api key*", "wp-logbook") ?></label>
                </th>
                <td>
                    <?php if (isset($error['api_key'])) : ?>
                        <p>
                            <small class="wpl_field_error">
                                <?= $error['api_key'] ?>
                            </small>
                        </p>
                    <?php endif; ?>
                    <input type="text" name="api_key" id="api_key" class="large-text" value="<?php if (!empty($value)) : ?><?= isset($value['api_key']) ? $value['api_key'] : '' ?><?php else : ?><?= get_option('wp_logbook_config')['api_key'] !== null ? get_option('wp_logbook_config')['api_key'] : '' ?><?php endif; ?>">
                    <p>
                        <?= __('Used for authenticate your website if sending log', 'wp-logbook') ?>
                    </p>
                </td>
            </tr>
            <tr class="setting_field_none">
                <th scope="row">
                    <label for="api_url"><?= __("Api url*", "wp-logbook") ?></label>
                </th>
                <td>
                    <?php if (isset($error['api_url'])) : ?>
                        <p>
                            <small class="wpl_field_error">
                                <?= $error['api_url'] ?>
                            </small>
                        </p>
                    <?php endif; ?>
                    <input type="text" name="api_url" id="api_url" class="regular-text" value="<?php if (!empty($value)) : ?><?= isset($value['api_url']) ? $value['api_url'] : '' ?><?php else : ?><?= get_option('wp_logbook_config')['api_url'] !== null ? get_option('wp_logbook_config')['api_url'] : '' ?><?php endif; ?>">
                    <p>
                        <?= __('Write your logbook base URL, for example "http://logbook.com" without the slash character at the end.', 'wp-logbook') ?>
                    </p>
                </td>
            </tr>
            <tr class="setting_field_none">
                <th scope="row">
                    <label for="log_level"><?= __("Log level*", "wp-logbook") ?></label>
                </th>
                <td>
                    <?php if (isset($error['log_level'])) : ?>
                        <p>
                            <small class="wpl_field_error">
                                <?= $error['log_level'] ?>
                            </small>
                        </p>
                    <?php endif; ?>
                    <select name="log_level" id="log_level">
                        <?= do_action("option_log_level") ?>
                    </select>
                    <p>
                        <?= __('Minimum log level', 'wp-logbook') ?>
                    </p>
                    <?php if (get_option("wp_logbook_log_path") !== null) : ?>
                        <p class="wpl_log_file_location">
                            <small>
                                <?= __("Log file location :", "wp-logbook") .
                                    get_option("wp_logbook_log_path") ?>
                            </small>
                        </p>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
    <hr class="wpl_hr_line">
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">
                    <label for="instance_id"><?= __("Instance ID*", "wp-logbook") ?></label>
                </th>
                <td>
                    <?php if (isset($error['instance_id'])) : ?>
                        <p>
                            <small class="wpl_field_error">
                                <?= $error['instance_id'] ?>
                            </small>
                        </p>
                    <?php endif; ?>
                    <input type="text" name="instance_id" id="instance_id" maxlength="20" class="regular-text" value="<?php if (!empty($value)) : ?><?= isset($value['instance_id']) ? $value['instance_id'] : '' ?><?php else : ?><?= get_option('wp_logbook_config')['instance_id'] !== null ? get_option('wp_logbook_config')['instance_id'] : '' ?><?php endif; ?>">
                    <p>
                        <?= __('Instance ID is a unique identifier per instance of your apps. Please use only alphabetic characters, dash, or underscore.', 'wp-logbook') ?>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    <?= submit_button() ?>
</form>