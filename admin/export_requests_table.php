<?php
load_bootstrap_assets();
//echo "here==='";
$api_url = trim(get_option('scjpc_es_host'), '/') . "/export-requests?" . http_build_query($_GET);
$export_requests = make_search_api_call($api_url);
$record_keys = array_keys($export_requests['0']); ?>
<div class="export-container">
  <table class="table w-100 table-striped">
    <thead>
    <tr>
      <?php foreach ($record_keys as $value) { ?>
        <th class="text-capitalize" style="font-size: 16px"
            scope="col"><?php echo str_replace("_", " ", $value); ?></th>
      <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($export_requests as $value) { ?>
      <tr>
        <th scope="row"><?php echo $value['id']; ?></th>
        <td><?php echo $value['export_query']; ?></td>
        <td><?php echo $value['file_path']; ?></td>
        <td><?php echo $value['s3_path']; ?></td>
        <td><?php echo $value['status']; ?></td>
        <td><?php echo $value['error_exception']; ?></td>
        <td><?php echo $value['total_pages']; ?></td>
        <td><?php echo $value['pages_processed']; ?></td>
        <td><?php echo $value['last_update']; ?></td>
        <td><?php echo $value['created_at']; ?></td>
        <td><?php echo $value['updated_at']; ?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>

</div>