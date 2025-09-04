<?php
class KIB_Plugin
{

  private static $instance = null;

  public static function get_instance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    KIB_Admin_Settings::get_instance();
    KIB_Shortcodes::init();
  }

  public static function activate()
  {
    $default = [
      'jam' => [
        'Senin' => ['buka1' => '08:00', 'tutup1' => '22:00', 'Tutup' => false],
        'Selasa' => ['buka1' => '08:00', 'tutup1' => '22:00', 'Tutup' => false],
        'Rabu' => ['buka1' => '08:00', 'tutup1' => '22:00', 'Tutup' => false],
        'Kamis' => ['buka1' => '08:00', 'tutup1' => '22:00', 'Tutup' => false],
        'Jumat' => ['buka1' => '08:00', 'tutup1' => '22:00', 'Tutup' => false],
        'Sabtu' => ['buka1' => '09:00', 'tutup1' => '23:00', 'Tutup' => false],
        'Minggu' => ['buka1' => '', 'tutup1' => '', 'Tutup' => true],
      ],
      'pengumuman_aktif' => false,
      'pengumuman_teks' => '',
    ];
    add_option('kib_settings', $default);
  }

  public static function deactivate()
  {
    delete_option('kib_settings');
  }
}