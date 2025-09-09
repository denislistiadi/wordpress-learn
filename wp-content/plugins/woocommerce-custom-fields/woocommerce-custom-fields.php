<?php

/*
Plugin Name: WooCommerce Custom Fields
Description: Add custom fields to Woocommerce products and checkout page
Version: 1.0.0
Author: Digdaya Team
*/

if ( !defined( constant_name: "ABSPATH")) {
  exit;
}

define("WCCF_PLUGIN_PATH", plugin_dir_path(__FILE__));

function wccf_init() {
  if (! class_exists("WooCommerce")) {
    return;
  }

  require_once WCCF_PLUGIN_PATH . "includes/class-wccf-admin.php";
  require_once WCCF_PLUGIN_PATH . "includes/class-wccf-frontend.php";

  new WCCF_Admin();
  new WCCF_Frontend();
}

add_action("plugin_loaded", "wccf_init");