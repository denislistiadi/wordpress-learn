<?php

namespace WCCM;

final class Loader
{
    private static $instance = null;

    /**
     * Initialize the loader.
     * If plugins_loaded already fired, load core immediately,
     * otherwise attach to plugins_loaded.
     *
     * @return self
     */
    public static function init()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        if (function_exists('did_action') && did_action('plugins_loaded')) {
            // already fired - load now
            self::$instance->load_core();
        } else {
            // attach for later
            add_action('plugins_loaded', [self::$instance, 'load_core']);
        }

        return self::$instance;
    }

    private function __construct()
    {
        // intentionally empty
    }

    public function load_core(): void
    {
        Plugin::get_instance();
    }
}
