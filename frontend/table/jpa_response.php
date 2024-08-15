<?php $search_key = $search_key ?? ''; ?>
<div id="response-overlay"></div>
<div class="mw-100 mt-1">
  <div class="remove-print d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
      <?php if ($search_key != '') { ?>
        <span>Search Key: <strong><?php echo $search_key; ?></strong></span>
      <?php } ?>
      <p class="text-secondary order-sm-0 order-1 result-text mb-2 mb-sm-0">
        Found <?php echo $search_result['total_records'] ?> results.</p>
    </div>
    <div class="d-flex b justify-content-end mb-2" role="group" aria-label="Basic outlined example">
      <button type="button" id="export_as_excel" data-query="<?php echo $search_result['search_query']; ?>"
              data-format="xlsx" title="Export as Excel" data-user_id="<?php echo $user_id; ?>"
              data-user_email="<?php echo $user_email; ?>" data-endpoint="<?php echo $export_endpoint; ?>"
              class="btn-icon-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
             class="btn-icon bi bi-file-earmark-excel" viewBox="0 0 16 16">
          <path
            d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
          <path
            d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
        </svg>
      </button>
      <button type="button" id="export_as_csv" data-query="<?php echo $search_result['search_query']; ?>"
              data-format="csv" data-user_id="<?php echo $user_id; ?>" data-user_email="<?php echo $user_email; ?>"
              data-endpoint="<?php echo $export_endpoint; ?>" class="btn-icon-wrapper" title="Export as CSV">

        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
             class="btn-icon bi bi-filetype-csv" viewBox="0 0 16 16">
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
    <table class="table  w-100 table-striped table-sortable">
      <thead>
      <tr>
        <?php foreach ($record_keys as $key => $label) { ?>
          <?php [$css_classes, $data_sort_order] = getSortingAttributes($key, $sort_keys, $response_sort_key, $response_sort_order); ?>

          <th key="<?php echo $key; ?>" class='text-capitalize <?php echo $css_classes; ?>'
              data-sort-key=<?php echo $key; ?> data-sort-order="<?php echo $data_sort_order; ?>" scope='col'>
            <?php echo $label; ?>
          </th>
        <?php } ?>
      </tr>
      </thead>
      <tbody>
      <?php $base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
      $base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";
      foreach ($search_results as $result) {
        $jpa_pdf_url = "$base_cdn_url/{$result['pdf_s3_key']}";
        $jpa_number = $result['jpa_number_2'];
        $jpa_detail_url = "/pole-search/?jpa_number=$jpa_number&action=jpa_detail_search&per_page=50&page_number=1&last_id=&search_query=$search_query"; ?>
        <tr>
          <th scope="row"><?php echo $result['jpa_unique_id']; ?></th>
          <td><a href="<?php echo $jpa_detail_url; ?>" target="_self"><?php echo $jpa_number; ?></a></td>
          <td>
            <?php if ($result['pdf_s3_key'] !== null) { ?>
              <a class="text-decoration-none pdf-icon-wrapper" href="<?php echo $jpa_pdf_url; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                     class="bi bi-file-pdf text-danger" viewBox="0 0 16 16">
                  <path
                    d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
                  <path
                    d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                </svg>
              </a>
            <?php } ?>
            <a class="text-decoration-none edit-icon d-none" id="edit-icon-<?php echo $result['jpa_unique_id']; ?>"
               data-edit-id="<?php echo isset($result['jpa_unique_id']) ? $result['jpa_unique_id'] : ''; ?>"
               data-edit-jpa-number="<?php echo isset($jpa_number) ? $jpa_number : ''; ?>"
               data-edit-pdf="<?php echo isset($jpa_pdf_url) ? $jpa_pdf_url : ''; ?>"
               data-aws-cdn="<?php echo $base_cdn_url; ?>"
               data-bs-toggle="modal"
               data-bs-target="#jpaModal"

            >
              <?php echo EDIT_ICON; ?>
            </a>
          </td>
          <td title="<?php echo $result['date_received']; ?>"><?php echo $result['date_received']; ?></td>
          <td title="<?php echo $result['billed_date']; ?>"><?php echo $result['billed_date']; ?></td>
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
