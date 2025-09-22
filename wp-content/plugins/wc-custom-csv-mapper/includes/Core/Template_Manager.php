<?php

namespace WCCM\Core;

use WCCM\Database\DB;

class Template_Manager
{
  public static function list()
  {
    return DB::get_templates();
  }

  public static function single($id)
  {
    $row = DB::get_template($id);

    if (!$row) {
      return false;
    }

    $decoded = json_decode($row->mapping, true);
    $row->mapping = is_array($decoded) ? $decoded : [];

    return $row;
  }

  public static function save($name, $mapping)
  {
    return DB::insert_template($name, $mapping);
  }

  public static function remove($id)
  {
    return DB::delete_template($id);
  }
}