<?php
if (!empty($_GET)) {
  $search_result = search($_GET);
}
if (!empty($_POST)) {
  $search_result = search($_POST);
}
if (!empty($_GET) || !empty($_POST)) {

  $record_keys = array_keys($search_result['results'][0] ?? []);
  $total_pages = isset($search_result["total_pages"]) ? (int)$search_result["total_pages"] : 0;
  $page = (int)$search_result["page_number"];
  $total_records = $search_result["total_records"] ?? 0;
  $result_per_page = $search_result['result_per_page'];
  $num_results_on_page = $search_result['per_page'];
  $search_results = $search_result['results'] ?? [];
  if ($total_records == 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/not_found.php';
    return;
  }
  ?>
  <div class="mw-100 mt-5">
    <div class="d-flex justify-content-between">
      <p class="text-secondary">
        <?php echo "Found $total_records results. (May Contain Duplicates)"; ?>
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
            foreach (EXTRA_COLUMNS_LABELS[$column] as $extra) { ?>
              <th><?php echo $extra; ?></th>
            <?php }
          } else { ?>
            <th><?php echo $value['label']; ?></th>
          <?php }
        } ?>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($search_results as $get_columns_value_data) { ?>
        <tr>
          <?php foreach (CHOICES as $get_columns_keys) {
            if (!empty(EXTRA_COLUMNS_LABELS[$get_columns_keys])) {
              foreach (EXTRA_COLUMNS_LABELS[$get_columns_keys] as $extra_column => $label) { ?>
                <td> <?php echo $get_columns_value_data[$extra_column]; ?></td>
              <?php }
            } else { ?>
              <td> <?php echo $get_columns_value_data[$get_columns_keys]; ?></td>
            <?php } ?>
          <?php } ?>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
<?php }