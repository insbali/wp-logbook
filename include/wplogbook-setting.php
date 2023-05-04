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
                    <label for="wp_logbook_api_key"><?= __("Min log level", 'wp-logbook') ?></label>
                    <input type="text" id="wp_logbook_log_level" name="wp_logbook_log_level" value="<?= get_option('wp_logbook_log_level') !== null ? get_option('wp_logbook_log_level') : '' ?>">
                </div>

                <?= submit_button(__("Save Change", "wp-logbook")) ?>

            </form>
        </div>
    </div>
</section>