<?php

namespace WCCM\Core;

class Export_Hooks
{
  public function __construct()
  {
    add_action('woocommerce_product_export_before_column_mapping', array($this, 'dropdown_export_template'));
    add_filter('woocommerce_product_export_row_data', array($this, 'remap_export'), 10, 2);
  }

  public function dropdown_export_template()
  {
    $templates = Template_Manager::list();
    if (!$templates) {
      return;
    }
    echo '<div class="wccm-export-template" style="margin:15px 0;">';
    echo '<label><strong>Ekspor dengan Template Pemetaan:</strong></label> ';
    echo '<select name="wccm_export_template"><option value="">-- Standar WooCommerce --</option>';
    foreach ($templates as $t) {
      echo '<option value="' . esc_attr($t->id) . '">' . esc_html($t->name) . '</option>';
    }
    echo '</select>';
    echo '</div>';
  }

  public function remap_export($row, $product)
  {
    if (!isset($_POST['wccm_export_template'])) { // phpcs:ignore
      return $row;
    }
    $template_id = absint($_POST['wccm_export_template']); // phpcs:ignore
    $template = Template_Manager::single($template_id);
    if (!$template) {
      return $row;
    }
    $map = $template->mapping; // [ 'KODE_PRODUK' => 'sku', ... ]
    // balikkan array agar mudah
    $flip = array_flip($map); // [ 'sku' => 'KODE_PRODUK', ... ]

    $new_row = array();
    foreach ($row as $wc_header => $value) {
      if (isset($flip[$wc_header])) {
        $new_row[$flip[$wc_header]] = $value;
      } else {
        // tetap sertakan kolom asli jika tidak dipetakan
        $new_row[$wc_header] = $value;
      }
    }
    return $new_row;
  }
}