<?php
if (!empty(CHOICES)) {
  ?>
  <div class="overflow-auto mw-100">.
    <table class="table">
      <thead>
        <tr>
          <?php
          foreach (CHOICES as $column) {
            $value = CHECK_BOXES_LABELS[$column];
            if (!empty(EXTRA_COLUMNS_LABELS[$column])) {
              foreach (EXTRA_COLUMNS_LABELS[$column] as $extra) {
                echo '<th>' . $extra . '</th>';
              }
            } else {
              echo '<th>' . $value['label'] . '</th>';
            }
          }
          ?>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach (COLUMNS_VALUES as $get_columns_value_data){
      ?>
      <tr>
        <?php
        foreach (CHOICES as $get_columns_keys) {
          if (is_string($get_columns_value_data[$get_columns_keys])) {
            echo "<td> $get_columns_value_data[$get_columns_keys] </td>";
          } else {
            foreach ($get_columns_value_data[$get_columns_keys] as $get_extra_value) {
              echo "<td> $get_extra_value </td>";
            }
          }
        }
        } ?>
      </tr>
      </tbody>
    </table>

  </div>
  <?php
}