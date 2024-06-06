<?php load_bootstrap_assets();
$user = wp_get_current_user();
$response = get_export_status($_GET);
//echo "<pre>" . print_r($response, true) . "</pre>";

if(!empty($response) && !empty($response['status'])):

    $status = $response['status'];
    $base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
    $base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";
    $download_url = $status == 'Processed' ? "$base_cdn_url/{$response['s3_path']}" : '';
    $btn_disabled = $response['status'] == 'Processed' ? '' : 'disabled';
    $btn_text = $response['status'] == 'Processed' ? "Download Export" : "Export In Progress";
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
                <button class="btn btn-primary" <?php echo $btn_disabled ?> id="download_export_file">
                    <?php echo $btn_text; ?>
                </button>
                <p class="mb-0 mt-2"><?php echo $response['file_name'] ?? ''; ?></p>
            </div>
            <?php if ($status !== 'Processed'):  ?>g
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

