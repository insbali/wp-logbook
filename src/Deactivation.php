<?php

namespace Solvrtech\WPlogbook;

class Deactivation
{

    public static function plugin_deactivate()
    {
        flush_rewrite_rules();
    }
}
