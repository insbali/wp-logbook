<?php

namespace Solvrtech\WPlogbook;

use Solvrtech\WPlogbook\Admin\ConfigSetting;

if (!defined("ABSPATH")) {
    exit;
}

class Deactivation
{

    public static function plugin_deactivate()
    {
        (new ConfigSetting)->config_disable_debug();
        flush_rewrite_rules();
    }
}
