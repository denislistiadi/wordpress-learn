<?php
$current_status = KIB_Shortcodes::get_status_today($settings, $current_day, $current_time);
?>
<div class="info-operasional-widget">
  <h3>Jam Operasional</h3>
  <p>Status Hari Ini: <span
      class="<?php echo $current_status['class']; ?>"><?php echo $current_status['text']; ?></span></p>
  <table>
    <?php
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    foreach ($days as $day) {
      $schedule = $settings['jam'][$day] ?? null;
      $is_today = ($day === $current_day);
      $row_style = $is_today ? 'font-weight: bold; background: #f9f9f9;' : '';
      echo "<tr style='{$row_style}'>
                <td>{$day}</td>
                <td>" . (isset($schedule['Tutup']) ? 'Tutup' : ($schedule['buka1'] ?? '') . ' - ' . ($schedule['tutup1'] ?? '')) . "</td>
            </tr>";
    }
    ?>
  </table>
</div>