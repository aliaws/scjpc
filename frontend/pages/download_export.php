<?php load_bootstrap_assets();
$user = wp_get_current_user();
$response = get_export_status($_GET);
//echo "<pre>" . print_r($response, true) . "</pre>";

if(!empty($response) && !empty($response['status'])):
  $download_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                  </svg>';
  $processed_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                      <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/>
                      <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/>
                      <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/>
                    </svg>';
  $status = $response['status'];
  $base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
  $base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";
  $download_url = $status == 'Processed' ? "$base_cdn_url/{$response['s3_path']}" : '';
  $btn_disabled = $response['status'] == 'Processed' ? '' : 'disabled';
  $btn_text = $response['status'] == 'Processed' ? "Download Export" : "Export In Progress";
  $btn_icon = $response['status'] == 'Processed' ? $download_icon : $processed_icon;


  $total_pages = intval($response['total_pages']);
  $processed_pages = intval($response['pages_processed']);
  $export_progress =  $processed_pages/ $total_pages;

  // Calculate remaining pages
  $remaining_pages = $total_pages - $processed_pages;

  // Each page takes 1 second to process, so remaining time in seconds
  $remaining_time = $remaining_pages * 1;

  // Setting the refresh interval
  // Let's say we want to refresh every 1/10th of the remaining time
  $no_of_seconds_interval = max(1, round($remaining_time / 10));

  $export_progress = number_format($export_progress, 2) * 100;
  if ($status !== 'Processed' && $export_progress >= 90) {
    $export_progress -= 2;
  } ?>

  <div class="card p-4">
    <form id="data_export" action="<?php echo get_permalink(get_the_ID()); ?>" method="get"
          novalidate>
      <div class="mb-3">
        <input type="hidden" id="action" name="action" value="data_export"/>
        <input type="hidden" name="file_path" class="form-control" id="file_path"
               value="<?php echo $_GET['file_path'] ?? ''; ?>" required/>
        <input type="hidden" id="format" name="per_page" value="<?php echo $_GET['format'] ?? 'csv'; ?>"/>
        <input type="hidden" id="user_id" name="user_id" value="<?php echo $user->ID ?? ''; ?>"/>
        <input type="hidden" id="user_email" name="user_email" value="<?php echo $user->user_email ?? ''; ?>"/>
        <input type="hidden" id="status" name="status" value="<?php echo $status ?? ''; ?>"/>
        <input type="hidden" id="download_url" name="download_url" value="<?php echo $download_url ?? ''; ?>"/>
        <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
      </div>
    </form>
    <div class="row justify-content-between mb-4">
      <div class="col-12 col-sm-6 order-1 order-sm-0">
        <button style="border-radius: 5px;" title="<?php echo $btn_text; ?>" class="btn btn-primary" <?php echo $btn_disabled ?> id="download_export_file">
          <?php echo $btn_icon; ?>
        </button>
        <p class="mb-0 mt-2"><?php echo $response['file_name'] ?? ''; ?></p>
      </div>
      <?php if ($status !== 'Processed'):  ?>
        <p class="col-12 col-sm-6 mb-4 fw-bold">Window will auto reload in <?php echo $no_of_seconds_interval; ?>s until export is not ready.</p>
      <?php endif; ?>
    </div>
    <div class="d-flex justify-content-between">
      <label for="file">Downloading progress:</label>
    </div>
    <div class="d-flex justify-content-between">
      <progress style="width: 80%" id="file" value="<?php echo $export_progress; ?>" max="100"></progress>
      <p><?php echo "$export_progress %"; ?></p>
    </div>
    <script type="application/javascript">
        const status = jQuery('input#status').val()
        // if (true) {
        if (status !== 'Processed') {
            setTimeout(() => {
                console.log('submitting...')
                window.location.reload()
            }, <?php echo $no_of_seconds_interval * 1000; ?>
            )
        } else {
            jQuery('button#download_export_file').on('click', () => {
                window.location.href = jQuery('input#download_url').val();
            })
        }
    </script>
  </div>
  <div class="response-table"></div>
<?php
else:
  echo '<div class="card p-4"><p> No Record found!</p></div>';
endif;