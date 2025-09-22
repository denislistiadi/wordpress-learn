<?php

namespace WCCM\Helpers;

class Helper
{
  public static function get_wc_csv_headers()
  {
    return apply_filters(
      "wccm_wc_headers",
      [
        "sku" => "SKU",
        'name' => "Name",
        "regular_price" => "Regular Price",
        "sale_price" => "Sale Price",
        "stock" => "Stock",
        "categories" => "Categories",
        "description" => "Description",
        "short_description" => "Short Description",
        "images" => "Images",
      ]
    );
  }

  public static function wc_headers_select_html($name) {
    $html = '<select name="' . esc_attr($name) . '"required>';
    $html .='<option value=""> -- Pilih --</option>';
    foreach (self::get_wc_csv_headers() as $key => $label) {
      $html .= '<option value="' . esc_attr($key) . ':>' . esc_html($label) . '</option';
    } 
    $html .= '</select>';
    return $html;
  }
}