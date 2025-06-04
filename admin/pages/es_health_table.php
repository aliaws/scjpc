<?php
load_bootstrap_assets();
$api_url = trim(get_option("scjpc_es_host"), "/") . "/" . API_NAMESPACE . "/es-indices-health";

$export_requests = make_search_api_call($api_url);
if (!empty($export_requests)) { ?>

<div class="export-container overflow-auto">
    <?php foreach ($export_requests as $key => $data) {
        if ($key == "health") { ?>
    <h4 class="my-4 text-secondary">Health</h4>
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    epoch
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Timestamp
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Cluster
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Status
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Node Total
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Node Data
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Shards
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Pri
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Active Shards Percentage
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $value) {
                $statusStyles = getStyles($value["status"]); ?>
            <tr>
                <td class="align-middle">
                    <?php echo $value["epoch"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["timestamp"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["cluster"]; ?>
                </td>
                <td class="align-middle">
                    <p class="m-0 <?php echo $statusStyles["class"]; ?>"
                        style="<?php print_r($statusStyles["style"]); ?>">
                        <?php echo $value["status"]; ?>
                    </p>
                </td>
                <td class="align-middle">
                    <?php echo $value["node.total"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["node.data"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["shards"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["pri"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["active_shards_percent"]; ?>
                </td>
            </tr>
            <?php
            } ?>
        </tbody>
    </table>
    <?php }
        if ($key == "indexes") { ?>
    <h4 class="my-4 text-secondary">Indexes</h4>
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Health
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Status
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Index
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Docs Count
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Docs Deleted
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Store Size
                </th>
                <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
                    Pri Store Size
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $value) {
                $statusStyles = getStyles($value["health"]); ?>
            <tr>
                <td class="align-middle">
                    <p class="m-0 <?php echo $statusStyles["class"]; ?>"
                        style="<?php print_r($statusStyles["style"]); ?>">
                        <?php echo $value["health"]; ?>
                    </p>
                </td>
                <td class="align-middle fw-bold">
                    <?php echo $value["status"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["index"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["docs.count"]; ?>

                </td>
                <td class="align-middle">
                    <?php echo $value["docs.deleted"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["store.size"]; ?>
                </td>
                <td class="align-middle">
                    <?php echo $value["pri.store.size"]; ?>
                </td>
            </tr>
            <?php
            } ?>
        </tbody>
    </table>
    <?php }
    if ($key == "cluster_allocation") { ?>
        <h4 class="my-4 text-secondary">Cluster Allocation</h4>
        <code>
            <pre style='word-wrap: break-word; white-space: pre-wrap;'>
                <?php echo print_r($data); ?>
            </pre>
        </code>
    <?php }
    } ?>
</div>
<?php } else { ?>
<div class="card p-4">
    <p> No Requests found!</p>
</div>
<?php }
?>
<style>
.small-width-column {
    width: 3.5em
}
</style>
