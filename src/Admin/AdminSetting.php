<?php

namespace Solvrtech\WPlogbook\Admin;

if (!defined("ABSPATH")) {
    exit;
}

class AdminSetting
{

    public function register()
    {
        add_action("admin_menu", array($this, "register_admin_menu"));
        add_action('admin_enqueue_scripts', array($this, "enqueue_styles"));
        add_action('admin_init', array($this, "admin_setting_fields"));
    }

    public function enqueue_styles()
    {
        wp_enqueue_style(
            'wp-logbook-style',
            WP_LOGBOOK_URL . "assets/css/wp-logbook-style.css"
        );
    }

    public function register_admin_menu()
    {
        add_submenu_page(
            "tools.php",
            __("WP Logbook Settings", "wp-logbook"),
            __("WP Logbook", "wp-logbook"),
            "manage_options",
            "wp-logbook",
            array($this, "admin_page_setting")
        );
    }

    public function admin_page_setting()
    {
        if (!current_user_can('manage_options'))
            return;


        if (isset($_GET['settings-updated']))
            add_settings_error(
                'wp_logbook_message',
                'wp_logbook_message',
                __('Changes have been saved', 'wplb'),
                'updated',
            );

        settings_errors('wp_logbook_message');

        include_once plugin_dir_path(__FILE__) . "../../include/wplogbook-setting.php";
    }

    public function admin_setting_fields()
    {
        $fields = array(
            array(
                "option_group"  => "wp_logbook_fields",
                "option_name"   => "wp_logbook_api_key",
            ),
            array(
                "option_group"  => "wp_logbook_fields",
                "option_name"   => "wp_logbook_api_url",
            ),
            array(
                "option_group"  => "wp_logbook_fields",
                "option_name"   => "wp_logbook_log_level",
            ),
        );

        foreach ($fields as $field) {

            register_setting(
                $field["option_group"],
                $field["option_name"]
            );
        }
    }
}
