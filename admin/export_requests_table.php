<?php
load_bootstrap_assets();
//echo "here==='";
$api_url = trim(get_option('scjpc_es_host'), '/') . "/export-requests?" . http_build_query($_GET);
$export_requests = make_search_api_call($api_url);

$record_keys = count($export_requests) > 0 ? array_keys($export_requests['0']) : [];

$sort_order = ["id", "s3_path", "last_update", "status", "updated_at", "total_pages", "pages_processed", "created_at"];
$orderMap = array_flip($sort_order);
usort($record_keys, function($a, $b) use ($orderMap) {
    return ($orderMap[$a] ?? PHP_INT_MAX) - ($orderMap[$b] ?? PHP_INT_MAX);
});

?>
<div class="export-container overflow-auto">
    <table class="table w-100 table-striped">
        <thead>
        <tr>
            <?php foreach ($record_keys as $value) { ?>
                <th class="text-capitalize" style="font-size: 16px"
                    scope="col"><?php echo str_replace("_", " ", $value); ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($export_requests as $value) { ?>
            <tr>
                <?php
                foreach($record_keys as $key):
                    $scope = "";
                    $class = "";
                    $style = "";
                    if ($key === "id") {
                        $scope = "fow";
                    }
                    else if ($key === "export_query") {
                        $class = "text-truncate";
                        $style = "width: 300px;;";
                    }
                    ?>
                    <td scope="<?php echo $scope; ?>" class="<?php echo $class; ?>" style="<?php echo $style; ?>">
                        <?php echo $value[$key]; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>