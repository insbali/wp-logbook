<?php

namespace Solvrtech\WPlogbook\Admin;

if (!defined("ABSPATH")) {
    exit;
}

use Exception;

class AdminPageSetting
{

    public function register()
    {
        add_filter("cron_schedules", array($this, "add_custom_cron_interval"));
        add_action("init", array($this, "log_schedule"));

        add_action('admin_enqueue_scripts', array($this, "enqueue_assets"));

        add_action("init", array($this, "init_session"));
        add_action("admin_menu", array($this, "register_admin_menu"));
        add_action("option_log_level", array($this, "log_level_options"));
        add_action("admin_post_wp_logbook_save_setting", array($this, "setting_save_handle"));
        add_action(
            "admin_post_nopriv_wp_logbook_save_setting",
            array($this, "setting_save_handle")
        );

        add_action("admin_post_wp_logbook_save_schedule", array($this, "schedule_save_handle"));
        add_action(
            "admin_post_nopriv_wp_logbook_save_schedule",
            array($this, "schedule_save_handle")
        );

        add_filter(
            "plugin_action_links_" . WP_LOGBOOK_BASENAME,
            array($this, "add_setting_link")
        );
    }

    public function init_session()
    {
        if (empty(session_id()) && !headers_sent()) {
            session_start();
        }
    }

    public function add_setting_link(array $links)
    {
        $url = admin_url() . "tools.php?page=wpl_setting";
        $link = "<a href='$url'>" . __("Settings", "wp-logbook") . "</a>";
        $links[] = $link;
        return $links;
    }

    /**
     * Register / Enqueue assets - Css/Js
     *
     * @return void
     */
    public function enqueue_assets()
    {
        wp_enqueue_style(
            'wp-logbook-style',
            WP_LOGBOOK_URL . "assets/css/wp-logbook-style.css"
        );

        wp_enqueue_script(
            'wp-logbook-script',
            WP_LOGBOOK_URL . "assets/js/wp-logbook-script.js"
        );
    }

    public function register_admin_menu()
    {
        add_submenu_page(
            "tools.php",
            __("WP Logbook", "wp-logbook"),
            __("WP Logbook", "wp-logbook"),
            "manage_options",
            "wpl_setting",
            array($this, "setting_page_render"),
        );
    }

    public function setting_nav_tab($current = "setting")
    {
        $tabs = array(
            'setting'   => __('Api Setting', 'wp-logbook'),
            'schedule'  => __('Log Schedule', 'wp-logbook')
        );
        $html = '<nav class="nav-tab-wrapper">';
        foreach ($tabs as $tab => $name) {
            $class = ($tab == $current) ? 'nav-tab-active' : '';
            $html .= '<a class="nav-tab ' . $class . '" href="?page=wpl_setting&tab=' . $tab . '">' . $name . '</a>';
        }
        $html .= '</nav>';
        echo $html;
    }

    public function setting_page_render()
    {
        if (!current_user_can('manage_options'))
            return;

        $tab = !empty($_GET["tab"]) ? esc_attr($_GET["tab"]) : "setting";
        echo "<div class='wrap'>
        <div class='wpl_header_page'>
            <h1 class='wp-heading-inline'>" . get_admin_page_title() . "</h1>
        </div>
        ";
        $this->setting_nav_tab($tab);

        if (isset($_GET['settings-updated']))
            add_settings_error(
                'wplb_message',
                'wplb_message',
                __('Changes have been saved', 'wplb'),
                'updated',
            );

        settings_errors('wplb_message');

        if ($tab == "setting") {
            include_once plugin_dir_path(__FILE__) . "../../include/tabs/wplogbook-setting.php";
        } else {
            include_once plugin_dir_path(__FILE__) . "../../include/tabs/wplogbook-schedule.php";
        }

        echo "</div>";
    }

    public function log_level_options()
    {
        $options = [
            [
                "option" => "DEBUG",
                "value" => 0
            ],
            [
                "option" => "INFO",
                "value" => 1
            ],
            [
                "option" => "NOTICE",
                "value" => 2
            ],
            [
                "option" => "WARNING",
                "value" => 3
            ],
            [
                "option" => "ERROR",
                "value" => 4
            ],
            [
                "option" => "CRITICAL",
                "value" => 5
            ],
            [
                "option" => "ALERT",
                "value" => 6
            ],
            [
                "option" => "EMERGENCY",
                "value" => 7
            ],
        ];
        $html = "";
        foreach ($options as $option) {
            $s_option   = "<option ";
            $selected   = get_option("wp_logbook_config")["log_level"] == $option["value"] ? "selected" : "";
            $value      = " value='{$option['value']}'";
            $e_option   = ">{$option['option']}</option>";
            $html .= $s_option . $selected . $value . $e_option;
        }

        echo $html;
    }

    public function setting_save_handle()
    {
        $errors = array();
        $values = array();

        if (isset($_POST['action']) && $_POST['action'] === "wp_logbook_save_setting") {
            $debug_status   = $_POST['debug_status'];
            $api_key        = sanitize_text_field($_POST['api_key']);
            $api_url        = sanitize_text_field($_POST['api_url']);
            $log_level      = sanitize_text_field($_POST['log_level']);
            $instance_id    = sanitize_text_field($_POST['instance_id']);

            $values['api_key'] = $api_key;
            $values['api_url'] = $api_url;
            $values['log_level'] = $log_level;
            $values['instance_id'] = $instance_id;

            if (empty($api_key))
                $errors['api_key'] = "Api key is required!";

            if (empty($api_url))
                $errors['api_url'] = "Api url is required!";

            if (empty($log_level) && $log_level < 0)
                $errors['log_level'] = "Log level is required!";

            if (!empty($instance_id)) {
                if (strlen($instance_id) > 20)
                    $errors['instance_id'] = "Instance ID value must be less than or equal to 20";
            } else {
                $errors['instance_id'] = "Insatnce ID is required!";
            }

            if ($debug_status == "enable") (new ConfigSetting)->config_enable_debug();
            else (new ConfigSetting)->config_disable_debug();

            if (!empty($errors)) {
                $_SESSION['values'] = $values;
                $_SESSION['errors'] = $errors;
                wp_redirect(admin_url("tools.php?page=wpl_setting"));
                exit;
            }

            try {

                update_option("wp_logbook_status", sanitize_text_field($debug_status));
                update_option("wp_logbook_config", $values);
                wp_redirect(admin_url("tools.php?page=wpl_setting&settings-updated"));
                exit;
            } catch (Exception $e) {
                //throw $th;
            }
        }
    }

    public function schedule_save_handle()
    {
        $errors = array();
        $values = array();

        if (isset($_POST['action']) && $_POST['action'] === "wp_logbook_save_schedule") {
            $log_schedule = sanitize_text_field($_POST['log_schedule']);

            $values['log_schedule'] = $log_schedule;

            if (!empty($log_schedule) || $log_schedule == 0) {
                if ($log_schedule < 1)
                    $errors['log_schedule'] = "The value must is grather tahan 1";
            } else {
                $errors['log_schedule'] = "Log schedule is required!.";
            }

            if (!empty($errors)) {
                $_SESSION['values'] = $values;
                $_SESSION['errors'] = $errors;
                wp_redirect(wp_get_referer());
                exit;
            }

            try {
                update_option("wp_logbook_log_schedule", $log_schedule);
                wp_redirect(admin_url("tools.php?page=wpl_setting&tab=schedule&settings-updated"));
                exit;
            } catch (Exception $e) {
                //throw $th;
            }
        }
    }

    function add_custom_cron_interval($schedules)
    {
        $get_interval = get_option("wp_logbook_log_schedule");
        $interval = !empty($get_interval) ? absint($get_interval) : 900;

        $schedules['wpl_custom_interval'] = array(
            'interval' => $interval,
            'display'  => esc_html__('WP Logbook Schedule'),
        );
        return $schedules;
    }

    function log_schedule()
    {
        if (!wp_next_scheduled("schedule_log_check_hook"))
            wp_schedule_event(
                time(),
                "wpl_custom_interval",
                "schedule_log_check_hook"
            );
    }
}
