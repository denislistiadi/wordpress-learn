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
    "Pengaturan Bar Pengumuman",
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
  $options = get_option('kib_settings');
  $is_checked = isset($options['pengumuman_aktif']) && $options['pengumuman_aktif'] == '1';
  $checked_attr = $is_checked ? 'checked ="checked"' : '';

  echo "<input type='checkbox' name='kib_settings[pengumuman_aktif]' value='1' {$checked_attr}>";
}

function kib_field_pengumuman_teks_render()
{
  $options = get_option('kib_settings');
  $value = isset($options['pengumuman_teks']) ? $options['pengumuman_teks'] : '';
  echo "<textarea name='kib_settings[pengumuman_teks]' rows='3' cols='50'>" . esc_textarea($value) . "</textarea>";
}

//  shortcode [kib_jam_operasional]
function kib_jam_operasional_shortcode()
{
  $options = get_option("kib_settings");

  if (empty($options['jam'])) {
    return "<p>Jam operasional belum diatur.</p>";
  }

  date_default_timezone_set('Asia/Jakarta');
  $current_day_en = strtolower(date('l'));
  $current_time = date('H:i');

  $days_map = [
    'monday' => 'Senin',
    'tuesday' => 'Selasa',
    'wednesday' => 'Rabu',
    'thursday' => 'Kamis',
    'friday' => 'Jumat',
    'saturday' => 'Sabtu',
    'sunday' => 'Minggu'
  ];

  $current_day = $days_map[$current_day_en] ?? '';

  $status = "<span style='color: red; font-weight: bold;'>Tutup Sekarang</span>";
  $current_schedule = $options['jam'][$current_day] ?? null;

  if ($current_schedule) {
    $is_closed = isset($current_schedule['Tutup']);

    if (!$is_closed) {
      $open1 = $current_schedule['buka1'] ?? null;
      $close1 = $current_schedule['tutup1'] ?? null;

      if (!empty($open1) && !empty($close1) && $current_time >= $open1 && $current_time <= $close1) {
        if ($current_time >= $open1 && $current_time <= $close1) {
          $status = "<span style='color: green; font-weight: bold;'>Buka Sekarang</span>";
        }
      }
    }
  }

  // Output jadwal html shortcode
  $output = "<div class='info-operasional-widget'>";
  $output .= "<h3>Jam Operasional</h3>";
  $output .= "<p>Status Hari Ini: {$status}</p>";
  $output .= "<table style='width: 100%; text-align: left;'>";

  $display_days = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

  foreach ($display_days as $day) {
    $style_active = ($day === $current_day) ? "font-weight: bold; background-color: #f0f0f0;" : "";
    $output .= "<tr style='{$style_active}'>";
    $output .= "<td style='width: 100px;'>" . ucfirst($day) . "</td>";

    $schedule = $options['jam'][$day] ?? null;

    if (isset($schedule['Tutup'])) {
      $output .= "<td>Tutup</td>";
    } elseif (!empty($schedule['buka1'])) {
      $output .= "<td>{$schedule['buka1']} - {$schedule['tutup1']}</td>";
    } else {
      $output .= "<td>Tutup</td>";
    }
    $output .= "</tr>";
  }

  $output .= "</table>";
  $output .= "</div>";

  return $output;
}
add_shortcode("info_jam_operasional", "kib_jam_operasional_shortcode");

// shortcode [kib_pengumuman]
function kib_pengumuman_shortcode()
{
  $options = get_option("kib_settings");

  if (isset($options['pengumuman_aktif']) && !empty($options['pengumuman_teks'])) {
    $teks = esc_html($options['pengumuman_teks']);
    ?>
    <div id="kib-announcement-bar">
      <p><?php echo $teks; ?></p>
    </div>
    <style>
            #kib-announcement-bar {
                background-color: #23282d; /* Warna default yang kontras */
                color: #ffffff;
                text-align: center;
                padding: 12px 10px;
                position: fixed; /* 'fixed' agar selalu di atas, bukan 'sticky' */
                top: 0;
                left: 0;
                width: 100%;
                z-index: 99998; /* Di bawah admin bar (99999) */
                box-sizing: border-box;
            }
            #kib-announcement-bar p {
                margin: 0;
                padding: 0;
                font-size: 14px;
            }
            
            /* Jika admin bar tampil, geser bar pengumuman ke bawah */
            body.admin-bar #kib-announcement-bar {
                top: 32px;
            }

            /* Beri jarak pada body agar konten utama tidak tertutup bar */
            body {
                padding-top: 46px !important; /* Sesuaikan dengan tinggi bar */
            }

            /* Jika admin bar tampil, beri jarak lebih banyak */
            body.admin-bar {
                padding-top: 78px !important; /* 46px (bar) + 32px (admin-bar) */
            }
        </style>
    <?php
  }
}
add_action("wp_footer", "kib_pengumuman_shortcode");