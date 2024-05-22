<?php load_bootstrap_assets();
$user = wp_get_current_user();
$response = get_export_status($_GET);
//echo "<pre>" . print_r($response, true) . "</pre>";
$status = $response['status'];
$download_url = $status == 'Processed' ? "/?download_scjpc={$response['s3_path']}" : '';
$btn_disabled = $response['status'] == 'Processed' ? '' : 'disabled';
$btn_text = $response['status'] == 'Processed' ? "Download Export" : "Export In Progress";
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
      <button class="btn btn-primary" <?php echo $btn_disabled ?>
              id="download_export_file">
        <?php echo $btn_text; ?>
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
    } else {
      jQuery('button#download_export_file').on('click', () => {
        window.location.href = jQuery('input#download_url').val();
      })
    }
  </script>
</div>

