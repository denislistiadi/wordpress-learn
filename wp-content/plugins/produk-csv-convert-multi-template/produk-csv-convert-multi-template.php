<?php
/**
 * Plugin Name: Produk CSV Converter Templates for WooCommerce
 * Description: Memungkinkan konversi format CSV kustom menjadi format standar WooCommerce dengan manajemen multi-template dan pilihan mapping kolom yang lengkap.
 * Version: 1.0.0
 * Author: Test Name
 */

if (!defined('ABSPATH')) {
  exit;
}


add_action('admin_menu', 'produk_csv_converter_menu');
function produk_csv_converter_menu()
{
  add_options_page(
    'Produk CSV Converter Settings',
    'Produk CSV',
    'manage_options',
    'produk-csv-converter',
    'produk_csv_converter_options_page'
  );
}


function produk_csv_converter_get_wc_fields()
{
  return [
    '' => '-- Abaikan Kolom Ini --',
    'sku' => 'SKU',
    'name' => 'Nama',
    'description' => 'Deskripsi',
    'short_description' => 'Deskripsi Singkat',
    'stock_quantity' => 'Jumlah Stok',
    'regular_price' => 'Harga Normal',
    'sale_price' => 'Harga Diskon',
    'images' => 'URL Gambar Utama',
    'gallery_images' => 'URL Gambar Galeri',
    'weight' => 'Berat',
    'length' => 'Panjang',
    'width' => 'Lebar',
    'height' => 'Tinggi',
    'shipping_class' => 'Kelas Pengiriman',
    'attribute:pa_color' => 'Atribut: Warna',
    'attribute:pa_size' => 'Atribut: Ukuran',
    'KATEGORI' => 'Kategori',
  ];
}

function produk_csv_converter_options_page()
{
  $wc_fields = produk_csv_converter_get_wc_fields();
  $templates = get_option('produk_csv_templates', []);
  $active_template_name = get_option('produk_csv_active_template', null);
  $csv_columns_to_map = get_transient('produk_csv_columns_to_map');

  ?>
  <div class="wrap">
    <h1>Pengaturan Produk CSV Converter</h1>

    <?php
    if ($csv_columns_to_map) {
      echo '<h2>Atur dan Simpan Template Baru</h2>';
      echo '<p>Cocokkan kolom dari file CSV Anda dengan kolom standar WooCommerce, lalu beri nama template ini.</p>';
      echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
      echo '<input type="hidden" name="action" value="produk_csv_converter_save_template">';
      wp_nonce_field('produk_csv_converter_save_template_nonce');

      echo '<table class="form-table">';
      echo '<tr valign="top">';
      echo '<th scope="row"><label for="template_name">Nama Template</label></th>';
      echo '<td><input type="text" id="template_name" name="template_name" required /></td>';
      echo '</tr>';
      echo '</table>';

      echo '<table class="wp-list-table widefat fixed striped">';
      echo '<thead><tr><th>Kolom di CSV Anda</th><th>Cocokkan dengan Kolom WooCommerce</th></tr></thead>';
      echo '<tbody>';
      foreach ($csv_columns_to_map as $column) {
        echo '<tr>';
        echo '<td><strong>' . esc_html($column) . '</strong></td>';
        echo '<td>';
        echo '<select name="csv_mapping[' . esc_attr($column) . ']">';
        foreach ($wc_fields as $wc_slug => $wc_name) {
          $selected = '';
          if (isset($active_template_name) && isset($templates[$active_template_name]) && isset($templates[$active_template_name][$wc_slug]) && $templates[$active_template_name][$wc_slug] === $column) {
            $selected = 'selected';
          }
          echo '<option value="' . esc_attr($wc_slug) . '" ' . $selected . '>' . esc_html($wc_name) . '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';
      }
      echo '</tbody>';
      echo '</table>';
      submit_button('Simpan Template');
      echo '</form>';
    } else {
      echo '<h2>Unggah File Sampel</h2>';
      echo '<p>Unggah file CSV sampel Anda untuk membuat mapping baru.</p>';
      echo '<form method="post" enctype="multipart/form-data" action="' . esc_url(admin_url('admin-post.php')) . '">';
      echo '<input type="hidden" name="action" value="produk_csv_converter_upload_sample">';
      wp_nonce_field('produk_csv_converter_upload_sample_nonce');
      echo '<table class="form-table">';
      echo '<tr valign="top">';
      echo '<th scope="row">Unggah File Sampel CSV</th>';
      echo '<td><input type="file" name="sample_csv_file" required /></td>';
      echo '</tr>';
      echo '</table>';
      submit_button('Unggah & Lanjutkan');
      echo '</form>';
    }
    ?>

    <hr>

    <h2>Template Tersimpan</h2>
    <?php if (empty($templates)): ?>
      <p>Belum ada template yang disimpan. Unggah file sampel di atas untuk membuat template pertama Anda.</p>
    <?php else: ?>
      <p>Pilih template yang ingin Anda gunakan untuk impor produk selanjutnya.</p>
      <table class="wp-list-table widefat fixed striped">
        <thead>
          <tr>
            <th scope="col">Nama Template</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($templates as $name => $mapping): ?>
            <tr>
              <td><?php echo esc_html($name); ?></td>
              <td><?php echo ($name === $active_template_name) ? '<strong>Aktif</strong>' : ''; ?></td>
              <td>
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=produk_csv_converter_activate_template&template_name=' . urlencode($name)), 'produk_csv_converter_activate_nonce')); ?>"
                  class="button button-primary">Aktifkan</a>
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=produk_csv_converter_delete_template&template_name=' . urlencode($name)), 'produk_csv_converter_delete_nonce')); ?>"
                  class="button button-secondary">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  <?php
}


add_action('admin_post_produk_csv_converter_upload_sample', 'produk_csv_converter_upload_sample');
function produk_csv_converter_upload_sample()
{
  if (!isset($_FILES['sample_csv_file']) || !wp_verify_nonce($_POST['_wpnonce'], 'produk_csv_converter_upload_sample_nonce')) {
    wp_die('Permintaan tidak valid.');
  }

  require_once(ABSPATH . 'wp-admin/includes/file.php');
  $file_info = wp_handle_upload($_FILES['sample_csv_file'], ['test_form' => false, 'mimes' => ['csv' => 'text/csv']]);

  if (isset($file_info['file'])) {
    if (($handle = fopen($file_info['file'], 'r')) !== FALSE) {
      $header_columns = fgetcsv($handle, 1000, ',');
      fclose($handle);
      unlink($file_info['file']);

      set_transient('produk_csv_columns_to_map', $header_columns, 60 * 5);
      wp_redirect(admin_url('options-general.php?page=produk-csv-converter'));
      exit;
    }
  }
  wp_die('Gagal membaca file CSV. Pastikan formatnya benar.');
}


add_action('admin_post_produk_csv_converter_save_template', 'produk_csv_converter_save_template');
function produk_csv_converter_save_template()
{
  if (!isset($_POST['csv_mapping']) || !isset($_POST['template_name']) || !wp_verify_nonce($_POST['_wpnonce'], 'produk_csv_converter_save_template_nonce')) {
    wp_die('Permintaan tidak valid.');
  }

  $template_name = sanitize_text_field($_POST['template_name']);
  $mapping_data = array_map('sanitize_text_field', $_POST['csv_mapping']);

  $final_mapping = [];
  foreach ($mapping_data as $csv_column => $wc_field) {
    if (!empty($wc_field)) {
      $final_mapping[$wc_field] = $csv_column;
    }
  }

  $templates = get_option('produk_csv_templates', []);
  $templates[$template_name] = $final_mapping;
  update_option('produk_csv_templates', $templates);
  update_option('produk_csv_active_template', $template_name);

  delete_transient('produk_csv_columns_to_map');

  wp_redirect(add_query_arg('template_status', 'saved', admin_url('options-general.php?page=produk-csv-converter')));
  exit;
}


add_action('admin_post_produk_csv_converter_activate_template', 'produk_csv_converter_activate_template');
function produk_csv_converter_activate_template()
{
  if (!isset($_GET['template_name']) || !wp_verify_nonce($_GET['_wpnonce'], 'produk_csv_converter_activate_nonce')) {
    wp_die('Permintaan tidak valid.');
  }
  $template_name = sanitize_text_field($_GET['template_name']);
  update_option('produk_csv_active_template', $template_name);
  wp_redirect(add_query_arg(['template_status' => 'activated'], admin_url('options-general.php?page=produk-csv-converter')));
  exit;
}


add_action('admin_post_produk_csv_converter_delete_template', 'produk_csv_converter_delete_template');
function produk_csv_converter_delete_template()
{
  if (!isset($_GET['template_name']) || !wp_verify_nonce($_GET['_wpnonce'], 'produk_csv_converter_delete_nonce')) {
    wp_die('Permintaan tidak valid.');
  }
  $template_name = sanitize_text_field($_GET['template_name']);
  $templates = get_option('produk_csv_templates', []);
  unset($templates[$template_name]);
  update_option('produk_csv_templates', $templates);

  if (get_option('produk_csv_active_template') === $template_name) {
    delete_option('produk_csv_active_template');
  }
  wp_redirect(add_query_arg(['template_status' => 'deleted'], admin_url('options-general.php?page=produk-csv-converter')));
  exit;
}


add_filter('woocommerce_csv_product_import_mapping_options', function ($options) {
  $active_template_name = get_option('produk_csv_active_template', null);
  $templates = get_option('produk_csv_templates', []);
  $active_mapping = isset($templates[$active_template_name]) ? $templates[$active_template_name] : [];

  if (!empty($active_mapping)) {
    foreach ($active_mapping as $wc_field => $csv_column) {
      if (!empty($csv_column)) {
        $options[$csv_column] = $csv_column;
      }
    }
  }
  return $options;
});


add_filter('woocommerce_csv_product_import_mapping_default_columns', function ($columns) {
  $active_template_name = get_option('produk_csv_active_template', null);
  $templates = get_option('produk_csv_templates', []);
  $active_mapping = isset($templates[$active_template_name]) ? $templates[$active_template_name] : [];

  if (!empty($active_mapping)) {
    foreach ($active_mapping as $wc_field => $csv_column) {
      if (!empty($csv_column)) {
        $columns[$csv_column] = $wc_field;
      }
    }
  }
  return $columns;
});


add_filter('woocommerce_product_import_pre_insert_product_object', 'proses_data_produk_custom', 10, 2);

function proses_data_produk_custom($product, $data)
{
  $active_template_name = get_option('produk_csv_active_template', null);
  $templates = get_option('produk_csv_templates', []);
  $active_mapping = isset($templates[$active_template_name]) ? $templates[$active_template_name] : [];

  if (isset($active_mapping['KATEGORI']) && !empty($data[$active_mapping['KATEGORI']])) {
    $category_names = explode(',', $data[$active_mapping['KATEGORI']]);
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

add_action('admin_notices', 'produk_csv_converter_admin_notices');

function produk_csv_converter_admin_notices()
{
  if (isset($_GET['template_status'])) {
    switch ($_GET['template_status']) {
      case 'saved':
        echo '<div class="notice notice-success is-dismissible"><p>Template berhasil disimpan dan diaktifkan!</p></div>';
        break;
      case 'activated':
        echo '<div class="notice notice-success is-dismissible"><p>Template berhasil diaktifkan!</p></div>';
        break;
      case 'deleted':
        echo '<div class="notice notice-success is-dismissible"><p>Template berhasil dihapus.</p></div>';
        break;
      case 'failed':
        echo '<div class="notice notice-error is-dismissible"><p>Gagal memproses file CSV. Pastikan formatnya benar.</p></div>';
        break;
    }
  }
}


