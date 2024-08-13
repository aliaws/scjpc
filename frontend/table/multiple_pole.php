<?php $search_key = $search_key ?? '';
$prev_search_query = $_REQUEST['search_query'] ?? ''; ?>
<div id="response-overlay"></div>
<div class="mw-100 mt-5">
  <div class="remove-print d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
      <?php if ($prev_search_query != '') { ?>
        <a class="btn" href="/jpa-search?<?php echo urldecode($prev_search_query); ?>">Go Back To Search Results</a>
      <?php } ?>
      <?php if ($search_key != '') { ?>
        <span>Search Key: <strong><?php echo $search_key; ?></strong></span>
      <?php } ?>
      <p class="text-secondary order-sm-0 order-1 result-text mb-2 mb-sm-0">
        Found <?php echo $search_result['total_records'] ?> results.</p>
    </div>
    <div class="d-flex justify-content-end mb-2" role="group" aria-label="Basic outlined example">
      <button type="button" id="export_as_excel" title="Export as Excel"
              data-query="<?php echo $search_result['search_query']; ?>" data-format="xlsx"
              data-user_id="<?php echo $user_id; ?>" data-user_email="<?php echo $user_email; ?>"
              data-endpoint="<?php echo $export_endpoint; ?>" class="btn-icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
             class="btn-icon bi bi-file-earmark-excel" viewBox="0 0 16 16">
          <path
            d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
          <path
            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
        </svg>
      </button>
      <button type="button" title="Export as CSV" id="export_as_csv"
              data-query="<?php echo $search_result['search_query']; ?>" data-format="csv"
              data-user_id="<?php echo $user_id; ?>" data-user_email="<?php echo $user_email; ?>"
              data-endpoint="<?php echo $export_endpoint; ?>" class="btn-icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" viewBox="0 0 16 16"
             class="btn-icon bi bi-filetype-csv">
          <path fill-rule="evenodd"
                d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM3.517 14.841a1.13 1.13 0 0 0 .401.823q.195.162.478.252.284.091.665.091.507 0 .859-.158.354-.158.539-.44.187-.284.187-.656 0-.336-.134-.56a1 1 0 0 0-.375-.357 2 2 0 0 0-.566-.21l-.621-.144a1 1 0 0 1-.404-.176.37.37 0 0 1-.144-.299q0-.234.185-.384.188-.152.512-.152.214 0 .37.068a.6.6 0 0 1 .246.181.56.56 0 0 1 .12.258h.75a1.1 1.1 0 0 0-.2-.566 1.2 1.2 0 0 0-.5-.41 1.8 1.8 0 0 0-.78-.152q-.439 0-.776.15-.337.149-.527.421-.19.273-.19.639 0 .302.122.524.124.223.352.367.228.143.539.213l.618.144q.31.073.463.193a.39.39 0 0 1 .152.326.5.5 0 0 1-.085.29.56.56 0 0 1-.255.193q-.167.07-.413.07-.175 0-.32-.04a.8.8 0 0 1-.248-.115.58.58 0 0 1-.255-.384zM.806 13.693q0-.373.102-.633a.87.87 0 0 1 .302-.399.8.8 0 0 1 .475-.137q.225 0 .398.097a.7.7 0 0 1 .272.26.85.85 0 0 1 .12.381h.765v-.072a1.33 1.33 0 0 0-.466-.964 1.4 1.4 0 0 0-.489-.272 1.8 1.8 0 0 0-.606-.097q-.534 0-.911.223-.375.222-.572.632-.195.41-.196.979v.498q0 .568.193.976.197.407.572.626.375.217.914.217.439 0 .785-.164t.55-.454a1.27 1.27 0 0 0 .226-.674v-.076h-.764a.8.8 0 0 1-.118.363.7.7 0 0 1-.272.25.9.9 0 0 1-.401.087.85.85 0 0 1-.478-.132.83.83 0 0 1-.299-.392 1.7 1.7 0 0 1-.102-.627zm8.239 2.238h-.953l-1.338-3.999h.917l.896 3.138h.038l.888-3.138h.879z"/>
        </svg>
      </button>
      <button type="button" id="print_window" class="btn-icon-wrapper" title="Print">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
             class="btn-icon bi bi-printer" viewBox="0 0 16 16">
          <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
          <path
            d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
        </svg>
      </button>
    </div>
    <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
  </div>
  <div class="overflow-auto">
    <table class="table w-100 table-striped table-sortable">
      <thead>
      <tr>
        <?php foreach (POLES_KEYS as $key => $label) {
          [$css_classes, $data_sort_order] = getSortingAttributes($key, $sort_keys, $response_sort_key, $response_sort_order);
          echo "<th class='text-capitalize $css_classes' scope='col' data-sort-key='$key' data-sort-order='$data_sort_order' scope='col'  >" . str_replace("_", " ", $label) . "</th>";
        } ?>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($search_result['results'] as $record) { ?>
        <tr>
          <?php foreach (POLES_KEYS as $key => $label) {
            if ($key == 'unique_id' || $key == 'pole_number') {
              $value = $record['unique_id'];
              $url = "/pole-detail/?unique_id=$value&action=pole_detail"; ?>
              <td><a href="<?php echo $url; ?>"><?php echo $record[$key]; ?></a></td>
            <?php } elseif ($key == 'jpa_number_2' || $key == 'jpa_number') {
              $value = $record['jpa_number_2'];
              $url = "/pole-search/?jpa_number=$value&action=jpa_detail_search&per_page=50&page_number=1&last_id="; ?>
              <td><a href="<?php echo $url; ?>"><?php echo $record[$key]; ?></a></td>
            <?php } else { ?>
              <td><?php echo $record[$key]; ?></td>
            <?php } ?>
          <?php } ?>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
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
</div>