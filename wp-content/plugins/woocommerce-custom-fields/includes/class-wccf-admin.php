<?php

class WCCF_Admin
{
  public function __construct()
  {
    add_action(
      'woocommerce_product_options_general_product_data',
      [$this, 'add_product_custom_fields']
    );
    add_action(
      'woocommerce_process_product_meta',
      [$this, 'save_product_custom_fields']
    );
  }

  public function add_product_custom_fields()
  {
    echo '<div class="options_group">';

    woocommerce_wp_textarea_input([
      'id' => '_special_instructions',
      'label' => 'Special Instructions',
      'desc_tip' => true,
      'description' => 'Add any special instructions for this product to be displayed on the frontend.',
    ]);

    woocommerce_wp_checkbox([
      'id' => '_bubble_wrap_option',
      'label' => 'Enable Bubble Wrap Option',
      'description' => 'Check this box to enable a bubble wrap option for this product.',
    ]);

    echo '</div>';
  }

  public function save_product_custom_fields($post_id)
  {
    $special_instructions = isset($_POST['_special_instructions']) ? sanitize_textarea_field($_POST['_special_instructions']) : '';
    update_post_meta(
      $post_id,
      '_special_instructions',
      $special_instructions
    );

    $bubble_wrap_option = isset($_POST['_bubble_wrap_option']) ? 'yes' : 'no';
    update_post_meta(
      $post_id,
      '_bubble_wrap_option',
      $bubble_wrap_option
    );
  }
}