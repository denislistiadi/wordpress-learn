<?php

/*
Plugin Name: Kafe Info Bisnis
Description: Plugin untuk menampilkan informasi bisnis kafe
Version: 1.0.0
Author: Denis Listiadi
*/

if (!defined("ABSPATH")) {
  exit;
}

// menu admin dashboard setting
function kib_admin_menu()
{
  add_menu_page(
    "Kafe Info Bisnis",
    "Info Bisnis",
    "manage_options",
    "kafe-info-bisnis",
    "kib_settings_page",
    "dashicons-store", // https://developer.wordpress.org/resource/dashicons
    20
  );
}
add_action("admin_menu", "kib_admin_menu");

// halaman setting
function kib_settings_page()
{
  ?>
  <div class="wrap">
    <h1>Pengaturan Info Bisnis</h1>
    <form method="post" action="options.php">
      <?php
      // fungsi standar field wordpress
      settings_fields("kib_settings_group");
      do_settings_sections("kafe-info-bisnis");
      submit_button("Simpan Pengaturan");
      ?>
    </form>
  </div>
  <?php
}

// daftar semua setting
function kib_settings_init()
{
  register_setting("kib_settings_group", "kib_settings");

  // SECIONS
  add_settings_section(
    "kib_section_jam_operasional",
    "Pengaturan Jam Operasional",
    function () {},
    'kafe-info-bisnis'
  );

  add_settings_section(
    "kib_section_pengumuman",
    "pengaturan Bar Pengumuman",
    function () {},
    "kafe-info-bisnis"
  );

  // FIELDS
  add_settings_field(
    "kib_field_jam_operasional",
    "Jadwal Mingguan",
    "kib_field_jam_operasional_render",
    "kafe-info-bisnis",
    "kib_section_jam_operasional"
  );

  add_settings_field(
    "kib_field_pengumuman_aktif",
    "Aktifkan Bar Pengumuman",
    "kib_field_pengumuman_aktif_render",
    "kafe-info-bisnis",
    "kib_section_pengumuman"
  );

  add_settings_field(
    "kib_field_pengumuman_teks",
    "Teks Pengumuman",
    "kib_field_pengumuman_teks_render",
    "kafe-info-bisnis",
    "kib_section_pengumuman"
  );
}
add_action("admin_init", "kib_settings_init");

// get data form field
$options = get_option("kib_settings");

//  render all field
function kib_field_jam_operasional_render()
{
  global $options;
  $days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

  echo "<table>";

  foreach ($days as $day) {
    // get saved value or default
    $is_closed = isset($options['jam'][$day]["Tutup"]) ? 'checked' : '';
    $open1 = $options['jam'][$day]['buka1'] ?? '';
    $close1 = $options['jam'][$day]['tutup1'] ?? '';

    echo "<tr>";
    echo "<td style='width: 100px;'>" . ucfirst($day) . "</td>";
    echo "<td>";
    echo "<input type='checkbox' name='kib_settings[jam][{$day}][Tutup]' {$is_closed}> Tutup";
    echo "</td>";
    echo "<td>";
    echo "Sesi 1: <input type='time' name='kib_settings[jam][{$day}][buka1]' value='" . esc_attr($open1) . "'> - ";
    echo "<input type='time' name='kib_settings[jam][{$day}][tutup1]' value='" . esc_attr($close1) . "'>";
    echo "</td>";
    echo "</tr>";
  }
  echo "</table>";
  echo "<p class='description'>Kosongkan jam jika tidak ada sesi kedua</p>";
}

function kib_field_pengumuman_aktif_render()
{
  global $options;
  $value = isset($options['pengumuman_aktif']) ? 'checked' : '';
  echo "<input type='checkbox' name='kib_settings[pengumuman_aktif]' value='1' {$value}>";
}

function kib_field_pengumuman_teks_render()
{
  global $options;
  $value = isset($options['pengumuman_teks']) ?? '';
  echo "<textarea name='kib_settings[pengumuman_teks]' rows='3' cols='50'>" . esc_attr($value) . "</textarea>";
}