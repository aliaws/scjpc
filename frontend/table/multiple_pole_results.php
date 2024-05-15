<?php
if (!empty($_GET)) {
  $search_results = search(($_GET));
  $record_keys = array_keys($search_results['results'][0] ?? []);
  $total_pages = (int)$search_results["total_pages"];
  $page = (int)$search_results["page_number"];
  $total_records = $search_results["total_records"];
  $result_per_page = $search_results['result_per_page'];
  $num_results_on_page = $search_results['per_page']; ?>
  <div class="mw-100 mt-5">
    <div class="d-flex justify-content-between">
      <p class="text-secondary">
        <?php echo "Found $total_records results. ($total_records records with duplicates)"; ?>
      </p>
      <div class="btn-group  btn-group-sm mb-4" role="group" aria-label="Basic outlined example">
        <button type="button" class="btn btn-outline-primary text-uppercase">Export as Excel</button>
        <button type="button" class="btn btn-outline-primary text-uppercase">Export as CSV</button>
        <button type="button" class="btn btn-outline-primary text-uppercase">Print</button>
      </div>
    </div>
  </div>

  <div class="overflow-auto mw-100">.
    <table class="table">
      <thead>
      <tr>
        <?php foreach (CHOICES as $column) {
          $value = CHECK_BOXES_LABELS[$column];
          if (!empty(EXTRA_COLUMNS_LABELS[$column])) {
            foreach (EXTRA_COLUMNS_LABELS[$column] as $extra) {
              echo "<th> $extra </th>";
            }
          } else {
            echo "<th> {$value['label']} </th>";
          }
        } ?>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($search_results['results'] as $get_columns_value_data) { ?>
        <tr>
          <?php foreach (CHOICES as $get_columns_keys) { ?>
            <td> <?php echo $get_columns_value_data[$get_columns_keys]; ?></td>
            <!--            if (is_string($get_columns_value_data[$get_columns_keys])) {-->
            <!--              echo "<td> $get_columns_value_data[$get_columns_keys] </td>";-->
            <!--            } else {-->
            <!--              foreach ($get_columns_value_data[$get_columns_keys] as $get_extra_value) {-->
            <!--                echo "<td> $get_extra_value </td>";-->
            <!--              }-->
            <!--            }-->
          <?php } ?>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
<?php }