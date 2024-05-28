<?php load_bootstrap_assets();
$user = wp_get_current_user();
$response = get_export_status($_GET);
//echo "<pre>" . print_r($response, true) . "</pre>";
$status = $response['status'];
$base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
$base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";
$download_url = $status == 'Processed' ? "$base_cdn_url/{$response['s3_path']}" : '';
$btn_disabled = $response['status'] == 'Processed' ? '' : 'disabled';
$btn_text = $response['status'] == 'Processed' ? "Download Export" : "Export In Progress";
$export_progress = intval($response['pages_processed']) / intval($response['total_pages']);
$export_progress = number_format($export_progress, 2) * 100;
if ($status !== 'Processed') {
  $export_progress -= 2;
}
?>

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
  <div class="d-flex justify-content-between columns-2">
    <p>
      <button class="btn btn-primary" <?php echo $btn_disabled ?> id="download_export_file">
        <?php echo $btn_text; ?>
      </button>
      <span><?php echo $response['file_name'] ?? ''; ?></span>
    </p>
    <p>Window will auto reload in 30s until export is not ready.</p>
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
        }, 30000
      )
    } else {
      jQuery('button#download_export_file').on('click', () => {
        window.location.href = jQuery('input#download_url').val();
      })
    }
  </script>
</div>
<div class="response-table"></div>


