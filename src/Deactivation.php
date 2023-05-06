<?php

namespace Solvrtech\WPlogbook;

if (!defined("ABSPATH")) {
    exit;
}

class Deactivation
{

    public static function plugin_deactivate()
    {
        flush_rewrite_rules();
    }
}
