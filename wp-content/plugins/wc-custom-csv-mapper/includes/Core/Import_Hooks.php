<?php

namespace WCCM\Core;

class Import_Hooks
{
  public function __construct()
  {
    // Handle form actions
    add_action('admin_post_wccm_save_mapping', [$this, 'save_mapping']);
    add_action('admin_post_wccm_delete_template', [$this, 'delete_template']);

    // Render dropdown template selector di step importer
    add_action('woocommerce_product_csv_importer_steps', [$this, 'render_template_selector']);

    // Apply mapping template ke default mapping WooCommerce
    add_filter(
      'woocommerce_csv_product_import_mapping_default_columns',
      [$this, 'apply_template_mapping'],
      10,
      2
    );
  }

  /**
   * Save template mapping
   */
  public static function save_mapping()
  {
    if (
      !current_user_can('manage_woocommerce') ||
      !isset($_POST['_wpnonce']) ||
      !wp_verify_nonce($_POST['_wpnonce'], 'wccm_save_mapping')
    ) {
      wp_die('Security check failed');
    }

    $name = isset($_POST['template_name']) ? sanitize_text_field($_POST['template_name']) : '';
    $mapping = isset($_POST['mapping']) ? (array) $_POST['mapping'] : [];

    $clean = [];
    if (!empty($mapping['user']) && !empty($mapping['wc'])) {
      foreach ($mapping['user'] as $i => $user_header) {
        $user = sanitize_text_field($user_header);
        $wc = isset($mapping['wc'][$i]) ? sanitize_text_field($mapping['wc'][$i]) : '';

        if ($user && $wc) {
          $clean[$user] = $wc;
        }
      }
    }

    if (!$name || empty($clean)) {
      wp_redirect(
        add_query_arg(['page' => 'wc-custom-csv-mapper', 'error' => 1], admin_url('admin.php'))
      );
      exit;
    }

    $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
    if ($id) {
      Template_Manager::remove($id);
    }

    Template_Manager::save($name, $clean);

    wp_redirect(
      add_query_arg(['page' => 'wc-custom-csv-mapper', 'saved' => 1], admin_url('admin.php'))
    );
    exit;
  }

  /**
   * Delete template mapping
   */
  public static function delete_template()
  {
    if (
      !current_user_can('manage_woocommerce') ||
      !isset($_GET['_wpnonce']) ||
      !wp_verify_nonce($_GET['_wpnonce'], 'wccm_delete_template')
    ) {
      wp_die('Security check failed');
    }

    $id = isset($_GET['id']) ? absint($_GET['id']) : 0;
    if ($id) {
      Template_Manager::remove($id);
    }

    wp_redirect(add_query_arg(['page' => 'wc-custom-csv-mapper'], admin_url('admin.php')));
    exit;
  }

  /**
   * Render template selector di halaman importer
   */
  public function render_template_selector()
  {
    $templates = Template_Manager::list();
    if (!$templates) {
      return;
    }
    ?>
    <div class="notice notice-info" style="margin:15px 0; padding:10px;">
      <label for="wccm_template_id"><strong>Pilih Template Mapping:</strong></label>
      <select name="wccm_template_id" id="wccm_template_id">
        <option value="">-- Tidak ada --</option>
        <?php foreach ($templates as $t): ?>
          <option value="<?php echo esc_attr($t->id); ?>">
            <?php echo esc_html($t->name); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php
  }

  /**
   * Apply template mapping ke importer
   *
   * @param array $columns Mapping default WooCommerce
   * @param array $raw_headers Header CSV
   * @return array
   */
  public function apply_template_mapping($columns, $raw_headers)
  {
    // === DEBUG: tulis semua nilai ===
    error_log('RAW HEADERS: ' . print_r($raw_headers, true));
    error_log('COLUMNS SEBELUM: ' . print_r($columns, true));
    error_log('POST wccm_template_id: ' . ($_POST['wccm_template_id'] ?? 'kosong'));
    // ===

    // 1. Pastikan $columns **array**
    if (!is_array($columns)) {
      $columns = [];
    }

    // 2. Kalau tidak pilih template → return array kosong / default
    if (empty($_POST['wccm_template_id'])) {
      error_log('Tidak ada template dipilih → return array kosong');
      return $columns;
    }

    $template_id = absint($_POST['wccm_template_id']);
    $template = Template_Manager::single($template_id);

    if (!$template || empty($template->mapping)) {
      error_log('Template tidak ditemukan / mapping kosong → return array kosong');
      return $columns;
    }

    // 3. Isi mapping
    $saved_map = $template->mapping;
    foreach ($raw_headers as $header) {
      $normalized = strtolower(trim($header));
      foreach ($saved_map as $user_header => $wc_field) {
        if ($normalized === strtolower(trim($user_header))) {
          $columns[$header] = $wc_field;
        }
      }
    }

    error_log('COLUMNS SESUDAH: ' . print_r($columns, true));
    return $columns; // **pasti array**
  }
}
