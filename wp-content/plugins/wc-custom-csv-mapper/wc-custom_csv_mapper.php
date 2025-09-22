<?php

/**
 * Plugin Name: WC Custom CSV Mapper
 * Plugin URI:  https://example.com
 * Description: Mapping kolom CSV kustom ↔ WooCommerce
 * Version:     1.0.0
 * Author:      Test Team
 *Text Domain: wc-custom-csv-mapper
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

if (
  !in_array(
    'woocommerce/woocommerce.php',
    apply_filters(
      'active_plugins',
      get_option('active_plugins')
    ),
    true
  )
) {
  add_action('admin_notices', function () {
    echo '<div class="error"><p>WC Custom CSV Mapper membutuhkan WooCommerce aktif.</p></div>';
  });
  return;
}

define("WCCM_VERSION", "1.0.0");
define("WCCM_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("WCCM_PLUGIN_URL", plugin_dir_url(__FILE__));

require_once WCCM_PLUGIN_DIR . "includes/autoload.php";

register_activation_hook(__FILE__, ["WCCM\Plugin", "activate"]);
register_deactivation_hook(__FILE__, ["WCCM\Plugin", "deactivate"]);

WCCM\Loader::init();
