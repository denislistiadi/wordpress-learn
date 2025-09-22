<?php

namespace WCCM\Admin;

use WCCM\Core\Template_Manager;

if (!class_exists('WP_List_Table')) {
  require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Templates_List_Table extends \WP_List_Table
{
  public function get_columns()
  {
    return [
      'cb' => '<input type="checkbox" />',
      'name' => __('Nama Template', 'wc-custom-csv-mapper'),
      'count' => __('Jumlah Mapping', 'wc-custom-csv-mapper'),
      'date' => __('Dibuat', 'wc-custom-csv-mapper'),
    ];
  }

  public function prepare_items()
  {
    $templates = Template_Manager::list();
    $data = [];
    foreach ($templates as $t) {
      $mapping = json_decode($t->mapping, true);
      $data[] = [
        'id' => $t->id,
        'name' => $t->name,
        'count' => count($mapping),
        'date' => $t->created_at
      ];
    }
    $this->_column_headers = [$this->get_columns(), [], []];
    $this->items = $data;
  }

  public function column_name($item)
  {
    $actions = [
      'edit' => sprintf(
        '<a href="%s">Edit</a>',
        add_query_arg(
          [
            'page' => 'wc-custom-csv-mapper',
            'action' => 'edit',
            'id' => $item['id']
          ],
          admin_url('admin.php')
        )
      ),
      'delete' => sprintf(
        '<a href="%s" onclick="return confirm(\'Yakin?\')">Hapus</a>',
        wp_nonce_url(
          add_query_arg(
            [
              'page' => 'wc-custom-csv-mapper',
              'action' => 'delete',
              'id' => $item['id']
            ],
            admin_url('admin-post.php')
          ),
          'wccm_delete_template'
        )
      ),
    ];

    return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions));
  }

  public function column_default($item, $column_name)
  {
    return isset($item[$column_name]) ? $item[$column_name] : '';
  }
}