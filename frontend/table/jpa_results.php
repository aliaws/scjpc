<?php if (!empty($_GET)) {
  $search_result = search(($_GET));
  $recordKeys = array_keys($search_result['results'][0] ?? []);
  $total_pages = (int)$search_result["total_pages"];
  $page = (int)$search_result["page_number"];
  $result_per_page = $search_result['result_per_page'];
  $num_results_on_page = $search_result['per_page']; ?>
  <div class="mw-100 mt-5">
    <div class="d-flex justify-content-between">
      <p class="text-secondary">Found <?php echo $search_result['total_records'] ?> results.</p>
      <div class="btn-group  btn-group-sm mb-4" role="group" aria-label="Basic outlined example">
        <button type="button" class="btn btn-outline-primary text-uppercase">Export as Excel</button>
        <button type="button" class="btn btn-outline-primary text-uppercase">Export as CSV</button>
        <button type="button" class="btn btn-outline-primary text-uppercase">Print</button>
      </div>
    </div>
    <table class="table w-100 table-striped">
      <thead>
      <tr>
        <?php foreach ($recordKeys as $key) {
          echo "<th class='text-capitalize' scope='col'>" . str_replace("_", " ", $key) . "</th>";
        } ?>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($search_result['results'] as $record) { ?>
        <tr>
          <th scope="row"><?php echo $record['jpa_unique_id']; ?></th>
          <td><?php echo $record['jpa_number_2']; ?></td>
          <td>
            <?php if ($record['pdf_s3_key'] !== null) { ?>
              <a href="<?php echo $record['pdf_s3_key']; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                     class="bi bi-file-pdf text-danger" viewBox="0 0 16 16">
                  <path
                    d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
                  <path
                    d="M4.603 12.087a.8.8 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.7 7.7 0 0 1 1.482-.645 20 20 0 0 0 1.062-2.227 7.3 7.3 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a11 11 0 0 0 .98 1.686 5.8 5.8 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.86.86 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.7 5.7 0 0 1-.911-.95 11.6 11.6 0 0 0-1.997.406 11.3 11.3 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.8.8 0 0 1-.58.029m1.379-1.901q-.25.115-.459.238c-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361q.016.032.026.044l.035-.012c.137-.056.355-.235.635-.572a8 8 0 0 0 .45-.606m1.64-1.33a13 13 0 0 1 1.01-.193 12 12 0 0 1-.51-.858 21 21 0 0 1-.5 1.05zm2.446.45q.226.244.435.41c.24.19.407.253.498.256a.1.1 0 0 0 .07-.015.3.3 0 0 0 .094-.125.44.44 0 0 0 .059-.2.1.1 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a4 4 0 0 0-.612-.053zM8.078 5.8a7 7 0 0 0 .2-.828q.046-.282.038-.465a.6.6 0 0 0-.032-.198.5.5 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822q.036.167.09.346z"/>
                </svg>
              </a>
            <?php } ?>
          </td>
          <td title="<?php echo $record['date_received']; ?>"><?php echo $record['date_received']; ?></td>
          <td title="<?php echo $record['billed_date']; ?>"><?php echo $record['billed_date']; ?></td>
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