<section id="wp-logbook">
    <div class="wp-logbook wrapper">
        <div class="logo">
            <img src="<?= WP_LOGBOOK_URL . "assets/images/solvrtech-logo.svg" ?>" alt="solvrtech logo">
            <span class="wp-logbook-title">
                <h2>WP Logbook</h2>
                <p>by <a href="https://solvrtech.id">Solvrtech Indonesia</a></p>
            </span>
        </div>
        <div class="wp-logbook-card">
            <form action="options.php" method="POST">

                <?= settings_fields("wp_logbook_fields") ?>

                <div class="wp-logbook-input-box">
                    <label for="wp_logbook_api_key"><?= __("Api key", 'wp-logbook') ?></label>
                    <input type="text" id="wp_logbook_api_key" name="wp_logbook_api_key" value="<?= get_option('wp_logbook_api_key') !== null ? get_option('wp_logbook_api_key') : '' ?>">
                </div>

                <div class="wp-logbook-input-box">
                    <label for="wp_logbook_api_key"><?= __("Api url", 'wp-logbook') ?></label>
                    <input type="text" id="wp_logbook_api_url" name="wp_logbook_api_url" value="<?= get_option('wp_logbook_api_url') !== null ? get_option('wp_logbook_api_url') : '' ?>">
                </div>

                <div class="wp-logbook-input-box">
                    <label for="wp_logbook_log_level"><?= __("Min log level", 'wp-logbook') ?></label>
                    <select name="wp_logbook_log_level" id="wp_logbook_log_level">
                        <?php
                        $levels = [
                            [
                                "level" => "DEBUG",
                                "value" => 0
                            ],
                            [
                                "level" => "INFO",
                                "value" => 1
                            ],
                            [
                                "level" => "NOTICE",
                                "value" => 2
                            ],
                            [
                                "level" => "WARNING",
                                "value" => 3
                            ],
                            [
                                "level" => "ERROR",
                                "value" => 4
                            ],
                            [
                                "level" => "CRITICAL",
                                "value" => 5
                            ],
                            [
                                "level" => "ALERT",
                                "value" => 6
                            ],
                            [
                                "level" => "EMERGENCY",
                                "value" => 7
                            ],
                        ];
                        foreach ($levels as $level) :
                        ?>
                            <option value="<?= $level['value'] ?>" <?= get_option("wp_logbook_log_level") == $level["value"] ? "selected" : "" ?> class="">
                                <?= $level['level'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?= submit_button(__("Save Change", "wp-logbook")) ?>

            </form>
        </div>
    </div>
</section>