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
      <?php foreach (POLES_KEYS as $key => $label) {
        echo "<th class='text-capitalize' scope='col'>" . str_replace("_", " ", $label) . "</th>";
      } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($search_result['results'] as $record) { ?>
      <tr>
        <?php foreach (POLES_KEYS as $key => $label) {
          if ($key == 'unique_id') {
            $value = $record[$key];
            $url = "/pole-detail/?unique_id=$value&action=pole_detail"; ?>
            <td><a href="<?php echo $url; ?>"><?php echo $value; ?></a></td>
          <?php } elseif ($key == 'jpa_number_2') {
            $value = $record[$key];
            $url = "/pole-search/?jpa_number=$value&action=jpa_detail_search&per_page=50&page_number=1&last_id="; ?>
            <td><a href="<?php echo $url; ?>"><?php echo $value; ?></a></td>
          <?php } else { ?>
            <td><?php echo $record[$key]; ?></td>
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