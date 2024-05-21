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
  $current_user = wp_get_current_user();
  $user_id = $current_user->ID;
  $user_email = $current_user->user_email;
  $export_endpoint = trim(get_option('scjpc_es_host'), '/') . "/data-export";
  if ($total_records == 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/not_found.php';
    return;
  } ?>
  <div class="mw-100 mt-5">
    <div class="d-flex justify-content-between">
      <p class="text-secondary">
        <?php echo "Found $total_records results. (May Contain Duplicates)"; ?>
      </p>
      <div class="btn-group  btn-group-sm mb-4" role="group" aria-label="Basic outlined example">
        <button type="button" id="export_as_excel" data-query="<?php echo $search_result['search_query']; ?>"
                data-format="xlsx" data-user_id="<?php echo $user_id; ?>" data-user_email="<?php echo $user_email; ?>"
                data-endpoint="<?php echo $export_endpoint; ?>" class="btn btn-outline-primary text-uppercase">
          Export as Excel
        </button>
        <button type="button" id="export_as_csv" data-query="<?php echo $search_result['search_query']; ?>"
                data-format="csv" data-user_id="<?php echo $user_id; ?>" data-user_email="<?php echo $user_email; ?>"
                data-endpoint="<?php echo $export_endpoint; ?>" class="btn btn-outline-primary text-uppercase">Export as
          CSV
        </button>
        <button type="button" id="print_window" class="btn btn-outline-primary text-uppercase">Print</button>
      </div>
      <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
    </div>
  </div>

  <div class="overflow-auto mw-100">
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
            } else {
              if ($get_columns_keys == 'unique_id' || $get_columns_keys == 'pole_number') {
                $value = $get_columns_value_data['unique_id'];
                $url = "/pole-detail/?unique_id=$value&action=pole_detail"; ?>
                <td><a href="<?php echo $url; ?>"><?php echo $get_columns_value_data[$get_columns_keys]; ?></a></td>
              <?php } elseif ($get_columns_keys == 'jpa_number' || $get_columns_keys == 'jpa_number_2') {
                $value = $get_columns_value_data[$get_columns_keys];
                $url = "/pole-search/?jpa_number=$value&action=jpa_detail_search&per_page=50&page_number=1&last_id="; ?>
                <td><a href="<?php echo $url; ?>"><?php echo $value; ?></a></td>
              <?php } else { ?>
                <td> <?php echo $get_columns_value_data[$get_columns_keys]; ?></td>
              <?php } ?>
            <?php } ?>
          <?php } ?>
        </tr>
      <?php } ?>
      </tbody>
    </table>
    <div class="d-flex justify-content-between">
      <nav aria-label="Page navigation example">
        <?php if ($total_pages > 0) { ?>
          <ul class="pagination pagination-bar">
            <?php if ($page > 1) { ?>
              <li class="prev page-item">
                <a class="page-link" data-page="<?php echo $page - 1 ?>">Prev</a>
              </li>
            <?php } ?>

            <?php if ($page > 3) { ?>
              <li class="start page-item">
                <a class="page-link" data-page="1">1</a>
              </li>
              <li class="dots page-item">...</li>
            <?php } ?>

            <?php if ($page - 2 > 0) { ?>
              <li class="page page-item">
                <a class="page-link" data-page="<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a>
              </li>
            <?php } ?>
            <?php if ($page - 1 > 0) { ?>
              <li class="page page-item">
                <a class="page-link" data-page="<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a>
              </li>
            <?php } ?>
            <li class="currentpage active page-item">
              <a class="page-link" data-page="<?php echo $page ?>"><?php echo $page ?></a>
            </li>
            <?php if ($page + 1 < $total_pages) { ?>
              <li class="page page-item">
                <a class="page-link" data-page="<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a>
              </li>
            <?php } ?>
            <?php if ($page + 2 < $total_pages + 1) { ?>
              <li class="page page-item">
                <a class="page-link" data-page="<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a>
              </li>
            <?php } ?>

            <?php if ($page < $total_pages - 2) { ?>
              <li class="dots page-item">...</li>
              <li class="end page-item">
                <a class="page-link" data-page="<?php echo $total_pages ?>"><?php echo $total_pages ?></a>
              </li>
            <?php } ?>

            <?php if ($page < $total_pages) { ?>
              <li class="next page-item"><a class="page-link" data-page="<?php echo $page + 1 ?>">Next</a></li>
            <?php } ?>
          </ul>
        <?php } ?>
      </nav>

      <nav aria-label="Page navigation example">
        <ul class="pagination page-list justify-content-end">
          <li class="page-item disabled">
            <a class="page-link">Result per page</a>
          </li>
          <?php foreach ($result_per_page as $result_page) {
            $active = $result_page == $num_results_on_page ? "active" : ""; ?>
            <li class="page-item">
              <a class="page-link <?php echo $active; ?>"
                 data-page="<?php echo $result_page; ?>"><?php echo $result_page; ?></a>
            </li>
          <?php } ?>
        </ul>
      </nav>
    </div>
  </div>
<?php }