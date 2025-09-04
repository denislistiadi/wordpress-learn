<?php
/*
Plugin Name: Kafe Info Bisnis
Description: Plugin untuk menampilkan jam operasional & pengumuman kafe
Version: 2.0.0
Author: Denis Listiadi
*/

defined('ABSPATH') || exit;

define('KIB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('KIB_PLUGIN_URL', plugin_dir_url(__FILE__));

// Load classes
require_once KIB_PLUGIN_DIR . 'includes/class-plugin.php';
require_once KIB_PLUGIN_DIR . 'includes/class-admin-settings.php';
require_once KIB_PLUGIN_DIR . 'includes/class-shortcode.php';

// Hooks
register_activation_hook(__FILE__, ['KIB_Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['KIB_Plugin', 'deactivate']);

// Initialize
KIB_Plugin::get_instance();