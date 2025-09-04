<?php
if (!defined('ABSPATH'))
  exit;

class KIB_Admin_Settings
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
    add_action('admin_menu', [$this, 'add_menu']);
    add_action('admin_init', [$this, 'register_settings']);
  }

  public function add_menu()
  {
    add_menu_page(
      'Kafe Info Bisnis',
      'Info Bisnis',
      'manage_options',
      'kafe-info-bisnis',
      [$this, 'render_page'],
      'dashicons-store',
      200
    );
  }

  public function register_settings()
  {
    register_setting('kib_settings_group', 'kib_settings', [
      'sanitize_callback' => [$this, 'sanitize'],
      'default' => []
    ]);

    add_settings_section('kib_section_jam', 'Jam Operasional', '__return_false', 'kafe-info-bisnis');
    add_settings_field('kib_field_jam', 'Jadwal Mingguan', [$this, 'render_jam_field'], 'kafe-info-bisnis', 'kib_section_jam');

    add_settings_section('kib_section_pengumuman', 'Bar Pengumuman', '__return_false', 'kafe-info-bisnis');
    add_settings_field('kib_field_pengumuman_aktif', 'Aktifkan Bar', [$this, 'render_checkbox'], 'kafe-info-bisnis', 'kib_section_pengumuman');
    add_settings_field('kib_field_pengumuman_teks', 'Teks Pengumuman', [$this, 'render_textarea'], 'kafe-info-bisnis', 'kib_section_pengumuman');
  }

  public function sanitize($input)
  {
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    $output = ['jam' => []];

    // Jam operasional per hari
    foreach ($days as $day) {
      $val = $input['jam'][$day] ?? [];

      $buka1 = isset($val['buka1']) ? sanitize_text_field($val['buka1']) : '';
      $tutup1 = isset($val['tutup1']) ? sanitize_text_field($val['tutup1']) : '';

      $output['jam'][$day] = [
        'buka1' => $buka1,
        'tutup1' => $tutup1,
      ];

      // Simpan key "Tutup" hanya jika dicentang (truthy)
      if (!empty($val['Tutup'])) {
        $output['jam'][$day]['Tutup'] = true;
      }
    }

    // Pengumuman
    $output['pengumuman_aktif'] = !empty($input['pengumuman_aktif']);
    $output['pengumuman_teks'] = sanitize_textarea_field($input['pengumuman_teks'] ?? '');

    return $output;
  }

  public function render_page()
  {
    ?>
    <div class="wrap">
      <h1>Pengaturan Info Bisnis</h1>
      <?php settings_errors(); ?>
      <form method="post" action="options.php">
        <?php
        settings_fields('kib_settings_group');
        do_settings_sections('kafe-info-bisnis');
        submit_button();
        ?>
      </form>
    </div>
    <?php
  }

  public function render_jam_field() {
    $settings = get_option('kib_settings', []);
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    ?>

    <table class="widefat striped kib-schedule-table">
        <thead>
            <tr>
                <th style="width:100px"><?php esc_html_e('Hari', 'kafe-info-bisnis'); ?></th>
                <th style="width:120px"><?php esc_html_e('Status', 'kafe-info-bisnis'); ?></th>
                <th><?php esc_html_e('Jam Buka - Tutup', 'kafe-info-bisnis'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($days as $day): ?>
                <?php
                $schedule  = $settings['jam'][$day] ?? [];
                $is_closed = ! empty($schedule['Tutup']);
                $open_time = esc_attr($schedule['buka1'] ?? '');
                $close_time = esc_attr($schedule['tutup1'] ?? '');
                ?>
                <tr>
                    <td><strong><?php echo esc_html($day); ?></strong></td>
                    <td>
                        <label>
                            <input type="checkbox"
                                   name="kib_settings[jam][<?php echo esc_attr($day); ?>][Tutup]"
                                   value="1"
                                   <?php checked($is_closed); ?>
                                   onchange="toggleJam(this, '<?php echo esc_attr($day); ?>')">
                            <?php esc_html_e('Tutup', 'kafe-info-bisnis'); ?>
                        </label>
                    </td>
                    <td>
                        <input type="time"
                               id="buka-<?php echo esc_attr($day); ?>"
                               name="kib_settings[jam][<?php echo esc_attr($day); ?>][buka1]"
                               value="<?php echo $open_time; ?>"
                               <?php disabled($is_closed); ?>>
                        &ndash;
                        <input type="time"
                               id="tutup-<?php echo esc_attr($day); ?>"
                               name="kib_settings[jam][<?php echo esc_attr($day); ?>][tutup1]"
                               value="<?php echo $close_time; ?>"
                               <?php disabled($is_closed); ?>>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <style>
        .kib-schedule-table input[type="time"] { width: 110px; }
        .kib-schedule-table label { cursor: pointer; }
    </style>

    <script>
        // Fungsi native JS untuk enable/disable input jam
        function toggleJam(checkbox, day) {
            var buka  = document.getElementById('buka-' + day);
            var tutup = document.getElementById('tutup-' + day);
            buka.disabled = checkbox.checked;
            tutup.disabled = checkbox.checked;
        }
    </script>

    <p class="description">
        <?php esc_html_e('Centang “Tutup” jika kafe tidak beroperasi pada hari tersebut.', 'kafe-info-bisnis'); ?>
    </p>
    <?php
}

  public function render_checkbox()
  {
    $settings = get_option('kib_settings', []);
    $is_active = !empty($settings['pengumuman_aktif']);
    $checked_attr = checked(true, $is_active, false);
    echo "<label><input type='checkbox' name='kib_settings[pengumuman_aktif]' value='1' {$checked_attr}> Aktif</label>";
  }

  public function render_textarea()
  {
    $settings = get_option('kib_settings', []);
    $text = esc_textarea($settings['pengumuman_teks'] ?? '');
    echo "<textarea name='kib_settings[pengumuman_teks]' rows='3' cols='50' style='max-width:720px'>{$text}</textarea>";
  }
}
