<?php

namespace WCCM;

class Plugin
{
  private static $instance = null;

  public static function get_instance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    $this->includes();
    $this->hooks();
  }

  private function includes()
  {
    if (is_admin()) {
      new Admin\Admin_Menu();
    }

    new Core\Import_Hooks();
    new Core\Export_Hooks();
  }

  private function hooks()
  {
    add_action("init", [$this, "load_textdomain"]);
  }

  public static function activate()
  {
    Database\DB::create_tables();
    flush_rewrite_rules();
  }

  public static function deactivate()
  {
    flush_rewrite_rules();
  }

  public function load_textdomain()
  {
    load_plugin_textdomain(
      'wc-custom-csv-mapper',
      false,
      dirname(plugin_basename(__FILE__), 2) . '/languages/'
    );
  }
}