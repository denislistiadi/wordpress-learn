<?php

namespace WCCM\Admin;

class Admin_Menu
{
  public function __construct()
  {
    add_action("admin_menu", [$this, "add_menu"], 99);
    add_action("admin_enqueue_scripts", [$this, "assets"]);

  }

  public function add_menu()
  {
    add_submenu_page(
      "woocommerce",
      __("CSV Mapper", "wc-custom-csv-mapper"),
      __("CSV Mapper", "wc-custom-csv-mapper"),
      "manage_options",
      "wc-custom-csv-mapper",
      [$this, "render_page"]
    );
  }

  public function assets($hook)
  {
    if ($hook !== "woocommerce_page_wc-custom-csv-mapper") {
      return;
    }

    wp_enqueue_style(
      "wccm-admin",
      WCCM_PLUGIN_URL . "assets/css/admin.css",
      [],
      WCCM_VERSION
    );
    wp_enqueue_script(
      "wccm-admin",
      WCCM_PLUGIN_URL . "assets/js/admin.js",
      ["jquery"],
      WCCM_VERSION,
      true
    );
  }

  public function render_page()
  {
    $action = isset($_GET["action"]) ? sanitize_key($_GET["action"]) : "list";

    switch ($action) {
      case "add":
        require_once WCCM_PLUGIN_DIR . "includes/admin/views/mapping-form.php";
        break;
      case "edit":
        $id = isset($_GET["id"]) ? absint($_GET["id"]) : 0;
        $template = \WCCM\Core\Template_Manager::single($id);

        if (!$template) {
          wp_die("Template tidak ditemukan");
        }

        require_once WCCM_PLUGIN_DIR . "includes/admin/views/mapping-form.php";
        break;
      default:
        require_once WCCM_PLUGIN_DIR . "includes/admin/views/templates-list.php";
    }
  }
}