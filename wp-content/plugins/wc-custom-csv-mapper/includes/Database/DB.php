<?php

namespace WCCM\Database;

class DB
{
  const TABLE_TEMPLATES = "wccm_templates";

  public static function create_tables()
  {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    $table = $wpdb->prefix . self::TABLE_TEMPLATES;

    $sql = "CREATE TABLE IF NOT EXISTS $table (
      id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
      mapping longtext NOT NULL,
      created_at datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY  (id)
    ) $charset;";

    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($sql);
  }

  public static function get_templates()
  {
    global $wpdb;
    return $wpdb->get_results(
      "SELECT * FROM {$wpdb->prefix}" . self::TABLE_TEMPLATES
    );
  }

  public static function get_template($id)
  {
    global $wpdb;
    return $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}" . self::TABLE_TEMPLATES . " WHERE id = %d",
        $id
      )
    );
  }

  public static function insert_template($name, $mapping)
  {
    global $wpdb;
    $wpdb->insert(
      $wpdb->prefix . self::TABLE_TEMPLATES,
      [
        'name' => sanitize_text_field($name),
        'mapping' => wp_json_encode($mapping)
      ],
      ['%s', '%s']
    );
    return $wpdb->insert_id;
  }

  public static function delete_template($id)
  {
    global $wpdb;
    return $wpdb->delete(
      $wpdb->prefix . self::TABLE_TEMPLATES,
      ["id" => $id],
      ["%d"]
    );
  }
}