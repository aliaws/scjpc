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

$base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
$base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";

?>
<div class="export-container overflow-auto">
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
        <thead>
            <tr>
                <?php foreach ($record_keys as $value) { ?>
                    <?php
                        $class = $value == "id" ? "column-cb check-column": "";
                    ?>
                    <th scope="col" class="text-capitalize manage-column <?php echo $class; ?>" style="font-size: 16px"
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
                    $key_value = $value[$key];
                    if ($key === "id") {
                        $scope = "fow";
                    }
                    else if ($key === "export_query") {
                        $class = "text-truncate";
                        $style = "width: 300px;;";
                    }

                    else if ($key === "status") {
                        if($key_value == "Processed") {
                            $style = "font-weight: bold";
                            $class = "badge bg-success";
                        }
                        else if($key_value == "Pending") {
                            $style = "font-style:italic";
                            $class = "badge bg-info";
                        }
                        else if($key_value == "Processing") {
                            $style = "font-style:italic";
                            $class = "badge bg-primary";
                        }
                    }
                    else if($key == "s3_path") {
                        if ($value["status"] == "Processed") {
                            $url = $base_cdn_url."/".$key_value;
                            $key_value = "<a target='_blank' href='$url'>{$key_value}</a>";
                        }
                    }

                    ?>
                    <td  title="<?php  echo $key_value; ?>" data-value="<?php echo $key_value ; ?>" scope="<?php echo $scope; ?>" class="<?php echo $class; ?>" style="<?php echo $style; ?>">
                        <?php echo $key_value; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>

</div>