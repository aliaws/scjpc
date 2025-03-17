<?php load_bootstrap_assets();
$user       = wp_get_current_user();
$response   = get_export_status($_GET);


if ( ! empty( $response ) && ! empty( $response[ 'status' ] ) ):
  $response_array   = download_export_array( $response );
  $export_endpoint  = trim( get_option( 'scjpc_es_host' ), '/' ) . "/data-export";
  $current_user     = wp_get_current_user();
  $user_id          = $current_user->ID;
  $user_email       = $current_user->user_email;

  $redirect_url     = ! empty ( $_REQUEST[ 'query_id' ] ) ? scjpc_get_last_search_query( $_REQUEST['query_id'] ) : '';

  wp_enqueue_script( 'download_export', SCJPC_ASSETS_URL . 'js/download_export.js', false, '1.1', true );

  ?>

  <div class="card p-4">

    <div class="remove-print d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
      <?php if ( ! empty( $redirect_url ) ) { ?>
        <a class="btn" href="<?php echo $redirect_url . "&go_back=1"; ?>" style="color: black;">Go Back</a><br />
      <?php } ?>
    </div>

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
            <?php if ($response_array['status']  === "Error"): ?>
                <div class="col-12 col-sm-6 order-1 order-sm-0">
                    <button  style="border-radius: 5px;"
                             data-query="<?php echo $response_array['search_query']; ?>"
                             data-format="<?php echo $response_array['export_format']; ?>"
                             data-endpoint="<?php echo $export_endpoint; ?>"
                             data-user_email="<?php echo $user_email; ?>"
                             class="btn btn-outline-danger restart"
                             title="Restart" id="restart">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bootstrap-reboot" viewBox="0 0 16 16">
                            <path d="M1.161 8a6.84 6.84 0 1 0 6.842-6.84.58.58 0 1 1 0-1.16 8 8 0 1 1-6.556 3.412l-.663-.577a.58.58 0 0 1 .227-.997l2.52-.69a.58.58 0 0 1 .728.633l-.332 2.592a.58.58 0 0 1-.956.364l-.643-.56A6.8 6.8 0 0 0 1.16 8z"/>
                            <path d="M6.641 11.671V8.843h1.57l1.498 2.828h1.314L9.377 8.665c.897-.3 1.427-1.106 1.427-2.1 0-1.37-.943-2.246-2.456-2.246H5.5v7.352zm0-3.75V5.277h1.57c.881 0 1.416.499 1.416 1.32 0 .84-.504 1.324-1.386 1.324z"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-between">
            <label for="file">Downloading progress:</label>
        </div>
        <div class="d-flex justify-content-between">
            <progress style="width: 80%" id="file" class="export_progress_bar" value="<?php echo $response_array['export_progress']; ?>" max="100"></progress>
            <p><span class="export_progress_text"><?php echo $response_array['export_progress']; ?> </span> %</p>
        </div>
    </div>
    <div class="response-table"></div>
<?php
else:
    echo '<div class="card p-4"><p> No Record found!</p></div>';
endif;
