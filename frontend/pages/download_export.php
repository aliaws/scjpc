<?php load_bootstrap_assets();
$user = wp_get_current_user();
$response = get_export_status($_GET);
$status = $response['status']; ?>

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

    </div>
  </form>
  <div class="d-flex justify-content-between">
    <?php if ($status == 'Processed') {
      $download_url = "/?download_scjpc={$response['s3_path']}"; ?>
      <p>
        <a href="<?php echo $download_url; ?>" class="btn btn-primary">Download Export</a>
        <span><?php echo $response['file_name'] ?? ''; ?></span>
      </p>
    <?php } else { ?>
      <p>
        <button class="btn btn-primary" disabled>Download Export</button>
        <span><?php echo $response['file_name'] ?? ''; ?></span>
      </p>
    <?php } ?>

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

