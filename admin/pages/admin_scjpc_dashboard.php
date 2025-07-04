<?php
load_bootstrap_assets();
wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');
load_admin_assets();
wp_enqueue_script('admin_dashboard', SCJPC_ASSETS_URL . 'js/admin-dashboard.js', false, '3.6', true);

$api_url_es_db_counts = trim(get_option('scjpc_es_host'), '/') . "/" . API_NAMESPACE . "/es-db-counts";
$api_url_deleted_poles = trim(get_option('scjpc_es_host'), '/') . "/" . API_NAMESPACE . "/deleted-records?table=deleted_poles&order=desc";
$api_url_deleted_jpas = trim(get_option('scjpc_es_host'), '/') . "/" . API_NAMESPACE . "/deleted-records?table=deleted_jpas&order=desc";

$db_db_counts = make_search_api_call($api_url_es_db_counts);
$deleted_poles = make_search_api_call($api_url_deleted_poles);
$deleted_jpas = make_search_api_call($api_url_deleted_jpas);
if (!empty($db_db_counts)) {
    ?>
    <div class="alert custom-alert-wrapper d-none my-3 alert-success " role="alert">
        <h4 class="alert-heading custom-alert m-2 fs-5 ">CDN cache has been successfully cleared</h4>
    </div>
    <div class="export-container overflow-auto">

        <?php include_once(SCJPC_PLUGIN_ADMIN_BASE."partials/_dashboard_buttons.php"); ?>
        <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
            <thead>
            <tr>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    Entity
                </th>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    DB Count
                </th>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    ES Count
                </th>
                <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    Difference
                </th>
                <th title="Deleted Difference" scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
                    Difference From Client DB
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="align-middle" scope="row">
                    Poles
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['poles_db_count']; ?>
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['poles_es_count']; ?>
                </td>
                <td class="align-middle <?php echo $db_db_counts['poles_db_es_diff'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $db_db_counts['poles_db_es_diff']; ?>
                </td>
                <td class="align-middle <?php echo $deleted_poles['total'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $deleted_poles['total']; ?>
                </td>
            </tr>
            <tr>
                <td class="align-middle" scope="row">
                    JPAs
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['jpas_db_count']; ?>
                </td>
                <td class="align-middle" scope="row">
                    <?php echo $db_db_counts['jpas_es_count']; ?>
                </td>
                <td class="align-middle <?php echo $db_db_counts['jpas_db_es_diff'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $db_db_counts['jpas_db_es_diff']; ?>
                </td>
                <td class="align-middle <?php echo $deleted_jpas['total'] != 0 ? "bold-red": "";  ?>" scope="row">
                    <?php echo $deleted_jpas['total']; ?>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-between">
            <label for="file">Indexing progress:</label>
        </div>
        <div class="d-flex justify-content-between">
            <progress style="width: 80%" id="file" class="export_progress_bar" value="<?php echo $response_array['export_progress']; ?>" max="100"></progress>
            <p><span class="export_progress_text"><?php echo $response_array['export_progress']; ?> </span> %</p>
        </div>
    </div>
<?php } else { ?>
    <div class="card p-4"><p> No Requests found!</p></div>
<?php } ?>
<?php
$deleted_ids = ["Poles" => $deleted_poles["deleted_ids"], "Jpas" => $deleted_jpas["deleted_ids"]];
foreach($deleted_ids as $heading => $ids): ?>
    <h6> Deleted <?php echo $heading; ?></h6>
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">

    <?php
        foreach($ids as $id):  ?>
            <tr>
                <td class="align-middle" scope="row">
                    <?php echo $id; ?>
                </td>
            </tr>
    <?php endforeach; ?>
    </table>
<?php endforeach; ?>

<?php include_once(SCJPC_PLUGIN_ADMIN_BASE."partials/_settings/_index.php"); ?>
