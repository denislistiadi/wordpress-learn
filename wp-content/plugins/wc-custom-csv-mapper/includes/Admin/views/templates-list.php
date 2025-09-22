<?php

use WCCM\Admin\Templates_List_Table;
use WCCM\Core\Template_Manager;

// buat instance table
$table = new Templates_List_Table();
$table->prepare_items();
?>
<div class="wrap">
  <h1 class="wp-heading-inline">CSV Mapper Templates</h1>
  <a href="<?= esc_url(add_query_arg(['page' => 'wc-custom-csv-mapper', 'action' => 'add'], admin_url('admin.php'))) ?>"
    class="page-title-action">Tambah Baru</a>

  <?php if (isset($_GET['saved'])): ?>
    <div id="message" class="updated notice is-dismissible">
      <p>Template berhasil disimpan.</p>
    </div>
  <?php endif; ?>

  <form method="post">
    <?php $table->display(); ?>
  </form>
</div>