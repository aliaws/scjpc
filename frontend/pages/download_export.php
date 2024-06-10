<?php load_bootstrap_assets();
$user       = wp_get_current_user();
$response   = get_export_status($_GET);


if(!empty($response) && !empty($response['status'])):
  $response_array =  download_export_array($response);
   ?>

  <div class="card p-4">
    <form id="data_export" action="<?php echo get_permalink(get_the_ID()); ?>" method="get"
          novalidate>
      <div class="mb-3">
        <input type="hidden" id="action" name="action" value="data_export"/>
        <input type="hidden" name="file_path" class="form-control" id="file_path"
               value="<?php echo $_GET['file_path'] ?? ''; ?>" required/>
        <input type="hidden" id="format" name="format" value="<?php echo $_GET['format'] ?? 'csv'; ?>"/>
        <input type="hidden" id="user_id" name="user_id" value="<?php echo $user->ID ?? ''; ?>"/>
        <input type="hidden" id="user_email" name="user_email" value="<?php echo $user->user_email ?? ''; ?>"/>
        <input type="hidden" id="status" name="status" value="<?php echo $response_array['status'] ?? ''; ?>"/>
        <input type="hidden" id="no_of_seconds_interval" name="no_of_seconds_interval" value="<?php echo $response_array['no_of_seconds_interval'] ?? ''; ?>"/>
        <input type="hidden" id="download_url" name="download_url" value="<?php echo $download_url ?? ''; ?>"/>
        <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
      </div>
    </form>
    <div class="row justify-content-between mb-4">
      <div class="col-12 col-sm-6 order-1 order-sm-0">
        <button style="border-radius: 5px;" title="<?php echo $response_array['btn_text']; ?>" class="btn btn-primary download_btn" <?php echo $response_array['btn_disabled'] ?> id="download_export_file">
          <?php echo $response_array['btn_icon']; ?>
        </button>
        <p class="mb-0 mt-2"><?php echo $response['file_name'] ?? ''; ?></p>
      </div>
    </div>
    <div class="d-flex justify-content-between">
      <label for="file">Downloading progress:</label>
    </div>
    <div class="d-flex justify-content-between">
      <progress style="width: 80%" id="file" class="export_progress_bar" value="<?php echo $response_array['export_progress']; ?>" max="100"></progress>
      <p><span class="export_progress_text"><?php echo $response_array['export_progress']; ?> </span> %</p>
    </div>
    <script type="application/javascript">
        jQuery(document).ready(function () {
            fetch_export_status();
            jQuery('button#download_export_file').on('click', () => {
                window.location.href = jQuery('input#download_url').val();
            })
        })
    </script>
  </div>
  <div class="response-table"></div>
<?php
else:
  echo '<div class="card p-4"><p> No Record found!</p></div>';
endif;