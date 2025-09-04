<?php
class KIB_Shortcodes
{

  public static function init()
  {
    add_shortcode('info_jam_operasional', [__CLASS__, 'jam_operasional']);
    add_action('wp_footer', [__CLASS__, 'pengumuman_bar']);
  }

  public static function jam_operasional()
  {
    $settings = get_option('kib_settings');
    $current_day = self::get_current_day();
    $current_time = current_time('H:i');

    ob_start();
    include KIB_PLUGIN_DIR . 'templates/shortcode-jam-operasional.php';
    return ob_get_clean();
  }

  public static function pengumuman_bar()
  {
    $settings = get_option('kib_settings');
    if (empty($settings['pengumuman_aktif']) || empty($settings['pengumuman_teks']))
      return;

    $text = wp_kses_post($settings['pengumuman_teks']);
    include KIB_PLUGIN_DIR . 'templates/announcement-bar.php';
  }

  private static function get_current_day()
  {
    $days = [
      'monday' => 'Senin',
      'tuesday' => 'Selasa',
      'wednesday' => 'Rabu',
      'thursday' => 'Kamis',
      'friday' => 'Jumat',
      'saturday' => 'Sabtu',
      'sunday' => 'Minggu'
    ];
    return $days[strtolower(current_time('l'))] ?? '';
  }

  public static function get_status_today($settings, $day, $time)
  {
    $schedule = $settings['jam'][$day] ?? null;
    if ($schedule['Tutup'] ?? false)
      return ['status' => 'closed', 'text' => 'Tutup Sekarang', 'class' => 'closed'];
    $open = $schedule['buka1'] ?? '';
    $close = $schedule['tutup1'] ?? '';
    if ($open && $close && $time >= $open && $time <= $close) {
      return ['status' => 'open', 'text' => 'Buka Sekarang', 'class' => 'open'];
    }
    return ['status' => 'closed', 'text' => 'Tutup Sekarang', 'class' => 'closed'];
  }
}