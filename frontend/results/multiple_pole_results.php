<!--is using for advance search results-->
<?php if (!empty($_REQUEST)) {
  $search_result = search_scjpc($_REQUEST);
  $search_key = $_REQUEST['pole_number'] ?? '';
  if (!empty($search_result['s3_key'])) {
    $_REQUEST['s3_key'] = $search_result['s3_key'];
  }
  $search_query = urlencode(http_build_query($_REQUEST));
  $record_keys = array_keys($search_result['results'][0] ?? []);
  $total_pages = isset($search_result["total_pages"]) ? (int)$search_result["total_pages"] : 0;
  $page = (int)$search_result["page_number"];
  $total_records = $search_result["total_records"] ?? 0;
  $result_per_page = $search_result['result_per_page'];
  $num_results_on_page = $search_result['per_page'];
  $sort_keys = POLE_SORT_KEYS;
  $response_sort_key = !empty($search_result['sort_key']) ? $search_result['sort_key'] : 'unique_id';
  $response_sort_order = !empty($search_result['sort_order']) ? $search_result['sort_order'] : 'asc';
  $search_results = $search_result['results'] ?? [];
  $current_user = wp_get_current_user();
  $user_id = $current_user->ID;
  $user_email = $current_user->user_email;
  $export_endpoint = trim(get_option('scjpc_es_host'), '/') . "/data-export";
  if ($total_records == 0) {
    if (!empty($search_result['errors_file_path']) && $search_result['errors_file_path'] != '') { ?>
      <div class="d-flex b justify-content-end mb-2 align-items-center" role="group">
        <div>
          <a class="btn" href="<?php echo $base_cdn_url . "/" . $search_result['errors_file_path']; ?>">
            Errors List
          </a>
        </div>
      </div>
    <?php }
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/partials/_not_found.php';
    return;
  } else {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/poles/_multi_column_table.php";
  }
  ?>


  <div class="remove-print d-flex flex-column flex-md-row mt-3 justify-content-md-between align-items-baseline">
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
<?php }