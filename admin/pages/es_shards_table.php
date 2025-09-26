<?php
load_bootstrap_assets();
load_admin_assets();
$api_url = trim(get_option('scjpc_es_host'), '/') . "/" . API_NAMESPACE . "/es-shards";

$export_requests = make_search_api_call($api_url);
if (!empty($export_requests)) {
    ?>
    <div class="alert custom-alert-wrapper d-none my-3 alert-success " role="alert">
        <h4 class="alert-heading custom-alert m-2 fs-5 "></h4>
    </div>
    <div class="export-container overflow-auto">
        <div class=" my-2 float-end" role="group" aria-label="Basic example">
            <button data-method="PUT" data-api-action = "update-es-settings" id="es_settings" type="button" class="btn btn-primary">Update ES Settings</button>
        </div>
        <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
            <thead>
            <tr>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Index
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Shard
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Prirep
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    State
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Docs
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Store
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    IP
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Node
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($export_requests as $value) {
                $statusStyles = getStateStyles($value['state']);
                ?>
                <tr>
                    <td class="align-middle">
                        <?php echo $value['index']; ?>
                    </td>
                    <td class="align-middle">
                        <?php echo $value['shard']; ?>
                    </td>
                    <td class="align-middle">
                        <?php echo $value['prirep']; ?>
                    </td>
                    <td class="align-middle">
                        <p class="m-0 <?php echo $statusStyles['class']; ?>" style="<?php echo $statusStyles['style']; ?>">
                            <?php echo $value['state']; ?>
                        </p>
                    </td>
                    <td class="align-middle">
                        <?php echo $value['docs']; ?>
                    </td>
                    <td class="align-middle">
                        <?php echo $value['store']; ?>
                    </td>
                    <td class="align-middle">
                        <?php echo $value['ip']; ?>
                    </td>
                    <td class="align-middle">
                        <?php echo $value['node']; ?>
                    </td>
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
