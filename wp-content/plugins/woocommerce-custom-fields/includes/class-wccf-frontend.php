<?php

class WCCF_Frontend
{
  public function __construct()
  {
    add_action(
      'woocommerce_before_add_to_cart_button',
      [$this, 'display_product_custom_fields']
    );
    add_filter(
      'woocommerce_add_cart_item_data',
      [$this, 'add_cart_item_data']
    );
    add_filter(
      'woocommerce_get_item_data',
      [$this, 'display_cart_item_data'],
      10,
      2
    );
    add_action(
      'woocommerce_cart_calculate_fees',
      [$this, 'add_bubble_wrap_fee_to_cart']
    );
  }

  public function display_product_custom_fields()
  {
    global $product;
    $special_instructions = get_post_meta(
      $product->get_id(),
      '_special_instructions',
      true
    );
    $bubble_wrap_option = get_post_meta(
      $product->get_id(),
      '_bubble_wrap_option',
      true
    );

    // Tampilkan instruksi khusus dari admin
    if (!empty($special_instructions)) {
      echo '<p>' . esc_html($special_instructions) . '</p>';
    }

    // Tampilkan opsi bubble wrap jika diaktifkan di admin
    if ($bubble_wrap_option === 'yes') {
      ?>
      <div>
        <input type="checkbox" name="wccf_bubble_wrap_option_frontend" value="1" />
        <label for="wccf_bubble_wrap_option_frontend">
          Add Bubble Wrap (+ Rp.2500)
        </label>
      </div>
      <?php
    }

  }

  public function add_cart_item_data($cart_item_data)
  {
    if (isset($_POST['wccf_bubble_wrap_option_frontend'])) {
      $cart_item_data['wccf_bubble_wrap_option'] = 'yes';
    }
    return $cart_item_data;
  }

  public function display_cart_item_data($item_data, $cart_item)
  {
    if (isset($cart_item['wccf_bubble_wrap_option'])) {
      $item_data[] = [
        'key' => 'Bubble Wrap',
        'value' => 'Yes',
      ];
    }
    return $item_data;
  }

  public function add_bubble_wrap_fee_to_cart()
  {
    if (is_admin() && !defined('DOING_AJAX'))
      return;

    $fee = 0;
    foreach (WC()->cart->get_cart() as $cart_item) {
      if (isset($cart_item['wccf_bubble_wrap_option']) &&  $cart_item['wccf_bubble_wrap_option'] === 'yes') {
        $fee += 2500;
      }
    }

    if ($fee > 0) {
      WC()->cart->add_fee('Bubble Wrap Fee', $fee);
    }
  }
}
