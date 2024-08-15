<?php
load_bootstrap_assets();
$api_url = trim(get_option('scjpc_es_host'), '/') . "/export-requests?" . http_build_query($_GET);
$export_requests = make_search_api_call($api_url);

if (!empty($export_requests)) {
  $record_keys = count($export_requests) > 0 ? array_keys($export_requests['0']) : [];
  $sort_order = ["id", "s3_path", "last_update", "status", "updated_at", "total_pages", "pages_processed", "created_at"];
  $orderMap = array_flip($sort_order);
  usort($record_keys, function ($a, $b) use ($orderMap) {
    return ($orderMap[$a] ?? PHP_INT_MAX) - ($orderMap[$b] ?? PHP_INT_MAX);
  });

  $base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
  $base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";
  $site_url = get_site_url(); ?>
  <div class="export-container overflow-auto">
    <div class=" my-2 float-end" role="group" aria-label="Basic example">
       <button data-api-action = "remove-all-processed-exports"
               id="remove_all_processed_exports"
               type="button" class="btn btn-danger"
       >
           Remove All Processed Requests
       </button>
    </div>
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
      <thead>
      <tr>
        <?php foreach ($record_keys as $value) { ?>
          <?php $class = $value == "id" ? "small-width-column" : ""; ?>
          <th scope="col" class="text-capitalize manage-column <?php echo $class; ?>" style="font-size: 16px;"
              scope="col"><?php echo str_replace("_", " ", $value); ?></th>
        <?php } ?>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($export_requests as $value) { ?>
        <tr>
          <?php foreach ($record_keys as $key) {
            $scope = "";
            $class = "";
            $style = "";
            $key_value = $value[$key] ?? "";
            if ($key === "id") {
              $scope = "row";
            } else if ($key === "export_query" || $key=== "original_query") {
              $class = "text-truncate";
              $style = "width: 300px;";
            } else if ($key === "status") {
              if ($key_value == "Processed") {
                $style = "font-weight: bold";
                $class = "badge bg-success";
              } else if ($key_value == "Pending") {
                $style = "font-style:italic";
                $class = "badge bg-info";
              } else if ($key_value == "Processing") {
                $style = "font-style:italic";
                $class = "badge bg-primary";
              }
            } else if ($key == "s3_path") {
              if ($value["status"] == "Processed") {
                $url = $base_cdn_url . "/" . $key_value;
                $key_value = "<a target='_blank' href='$url'>{$key_value}</a>";
              } else {
                $url = $site_url . "/download-export/?file_path=" . $key_value;
                $key_value = "<a target='_blank' href='$url'>{$key_value}</a>";
              }
            } ?>
            <td title="<?php echo $key_value; ?>" scope="<?php echo $scope; ?>"
                class="<?php echo $class; ?>" style="<?php echo $style; ?>">

              <?php if ($key === "export_query" || $key=== "original_query") : ?>
                <p style="width:90%"><?php echo $key_value; ?></p>
                <button type="button" class="btn btn-secondary copy-text" data-value="<?php echo $key_value; ?>" >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"></path>
                    </svg>
                </button>
              <?php else: ?>
                  <?php echo $key_value; ?>
              <?php endif; ?>
            </td>
          <?php } ?>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
<?php } else { ?>
  <div class="card p-4"><p> No Requests found!</p></div>
<?php } ?>
<style>
  .small-width-column {
    width: 3.5em
  }
</style>
<script>
    jQuery("button.copy-text").click(function() {
        console.log(jQuery(this).data('value'));

        const dummy = document.createElement("textarea");
        document.body.appendChild(dummy);
        dummy.value = jQuery(this).data('value');
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
    })
</script>