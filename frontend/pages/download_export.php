<?php load_bootstrap_assets();
$user = wp_get_current_user();
$response = get_export_status($_GET);
$status = $response['status'];
$download_url = $status == 'Processed' ? "/?download_scjpc={$response['s3_path']}" : '';
?>

<div class="card p-4">
  <form class="needs-validation" id="data_export" action="<?php echo get_permalink(get_the_ID()); ?>" method="get"
        novalidate>
    <div class="mb-3">
      <input type="hidden" id="action" name="action" value="data_export"/>
      <input type="hidden" name="file_path" class="form-control" id="file_path"
             value="<?php echo $_GET['file_path'] ?? ''; ?>" required/>
      <input type="hidden" id="format" name="per_page" value="<?php echo $_GET['format'] ?? 'csv'; ?>"/>
      <input type="hidden" id="user_id" name="page_number" value="<?php echo $user->ID ?? ''; ?>"/>
      <input type="hidden" id="user_email" name="user_email" value="<?php echo $user->user_email ?? ''; ?>"/>
      <input type="hidden" id="status" name="status" value="<?php echo $status ?? ''; ?>"/>
      <input type="hidden" id="download_url" name="status" value="<?php echo $download_url ?? ''; ?>"/>

    </div>
  </form>
  <div class="d-flex justify-content-between columns-2">
    <p>
      <button class="btn btn-primary" <?php echo $response['status'] == 'Processed' ? '' : 'disabled'; ?>
              id="download_export_file">
        Download Export
      </button>
      <span><?php echo $response['file_name'] ?? ''; ?></span>
    </p>
    <p>Window will auto reload in 10s until export is not ready.</p>
  </div>
  <script type="application/javascript">
    const status = jQuery('input#status').val()
    if (status !== 'Processed') {
      setTimeout(() => {
          window.location.reload(true)
        }, 10000
      )
    }
  </script>
</div>
