<?php
/**
 * Plugin Name: Produk CSV Converter for Produk example 1
 * Description: Convert format produk.csv custom menjadi format standar WooCommerce ketika import.
 * Version: 1.0
 * Author: Test Name
 */

if (!defined('ABSPATH')) {
  exit;
}


add_filter('woocommerce_csv_product_import_mapping_options', function ($options) {
  $options['KODE_PRODUK'] = 'KODE_PRODUK';
  $options['NAMA_BARANG'] = 'NAMA_BARANG';
  $options['DESKRIPSI'] = 'DESKRIPSI';
  $options['HARGA_NORMAL'] = 'HARGA_NORMAL';
  $options['HARGA_DISKON'] = 'HARGA_DISKON';
  $options['JUMLAH_STOK'] = 'JUMLAH_STOK';
  $options['KATEGORI'] = 'KATEGORI';
  $options['GAMBAR'] = 'GAMBAR';
  return $options;
});


add_filter('woocommerce_csv_product_import_mapping_default_columns', function ($columns) {
  $columns['KODE_PRODUK'] = 'sku';
  $columns['NAMA_BARANG'] = 'name';
  $columns['DESKRIPSI'] = 'description';
  $columns['HARGA_NORMAL'] = 'regular_price';
  $columns['HARGA_DISKON'] = 'sale_price';
  $columns['JUMLAH_STOK'] = 'stock_quantity';
  $columns['GAMBAR'] = 'images';
  $columns['KATEGORI'] = 'KATEGORI';

  return $columns;
});


add_filter('woocommerce_product_import_pre_insert_product_object', 'proses_data_produk_custom', 9, 2);
function proses_data_produk_custom($product, $data)
{

  if (isset($data['stock_quantity'])) {
    $jumlah_stok = (int) $data['stock_quantity'];
    $product->set_manage_stock(true);
    $product->set_stock_quantity($jumlah_stok);
    if ($jumlah_stok > 0) {
      $product->set_stock_status('instock');
    } else {
      $product->set_stock_status('outofstock');
    }
  }

  if (!empty($data['KATEGORI'])) {
    $category_names = explode(',', $data['KATEGORI']);
    $category_ids = [];

    foreach ($category_names as $cat_name) {
      $cat_name = trim($cat_name);
      if (empty($cat_name))
        continue;

      $term = get_term_by('name', $cat_name, 'product_cat');

      if ($term) {
        $category_ids[] = $term->term_id;
      } else {
        $new_term = wp_insert_term($cat_name, 'product_cat');
        if (!is_wp_error($new_term)) {
          $category_ids[] = $new_term['term_id'];
        }
      }
    }

    if (!empty($category_ids)) {
      $product->set_category_ids($category_ids);
    }
  }

  return $product;
}
