<?php

use WCCM\Helpers\Helper;

$wc_headers = Helper::get_wc_csv_headers();
$template = isset($template) ? $template : null;
$is_edit = $template;
?>

<div class="wrap">
  <h1><?php echo $is_edit ? "Edit Template" : "Tambah Template"; ?></h1>
  <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field("wccm_save_mapping"); ?>
    <input type="hidden" name="action" value="wccm_save_mapping">
    <?php if ($is_edit): ?>
      <input type="hidden" name="id" value="<?php echo $template->id; ?>">
    <?php endif; ?>
    <table class="form-table">
      <tr>
        <th scope="row"><label>Nama Template</label></th>
        <td>
          <input name="template_name" type="text" value="<?php echo $is_edit ? esc_attr($template->name) : ''; ?>"
            class="regular-text" required>
        </td>
      </tr>
    </table>

    <h2>Pemtaan Kolom</h2>
    <table id="mapping-table" class="widefat fixed striped">
      <thead>
        <tr>
          <th width="45%"> Header di File Anda </th>
          <th width="45%"> Header WooCommerce </th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $mapping = $is_edit ? $template->mapping : ['' => ''];
        foreach ($mapping as $user_header => $wc_header):
          ?>
          <tr>
            <td><input type="text" name="mapping[user][]" value="<?php echo esc_attr($user_header); ?>"
                placeholder="KODE_PRODUK" required></td>
            <td>
              <select name="mapping[wc][]" required>
                <option value="">-- Pilih --</option>
                <?php foreach ($wc_headers as $key => $label): ?>
                  <option value="<?php echo esc_attr($key); ?>" <?php selected($wc_header, $key); ?>>
                    <?php echo esc_html($label) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </td>
            <td><button class="button remove-row" type="button">Hapus</button></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <button class="button" id="add-row" type="button"> + Tambah Baris</button>
    <?php submit_button(); ?>
    <script id="wccm-wc-options" type="text/template">
  <option value="">-- Pilih --</option>
  <?php foreach ($wc_headers as $key => $label): ?>
          <option value="<?= esc_attr($key) ?>"><?= esc_html($label) ?></option>
  <?php endforeach; ?>
</script>
  </form>
</div>

<script>
  jQuery(function ($) {
    $('#add-row').on('click', function () {
      var row =
        '<tr>' +
        '<td><input type="text" name="mapping[user][]" placeholder="KODE_PRODUK" required></td>' +
        '<td><select name="mapping[wc][]" required>' +
        $('#wccm-wc-options').html() +
        '</select></td>' +
        '<td><button class="button remove-row" type="button">Hapus</button></td>' +
        '</tr>';
      $('#mapping-table tbody').append(row);
    });

    $(document).on('click', '.remove-row', function () {
      $(this).closest('tr').remove();
    });
  });
</script>