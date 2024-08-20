<?php
load_bootstrap_assets();
load_admin_assets();

$api_url = trim(get_option('scjpc_es_host'), '/') . "/es-db-counts";

$db_db_counts = make_search_api_call($api_url);
if (!empty($db_db_counts)) {
    ?>
    <div class="alert custom-alert-wrapper d-none my-3 alert-success " role="alert">
        <h4 class="alert-heading custom-alert m-2 fs-5 ">CDN cache has been successfully cleared</h4>
    </div>
    <div class="export-container overflow-auto">
        
        <div class=" my-2 float-end" role="group" aria-label="Basic example">
            <button data-api-action = "clear-cdn-cache"  data-key = "/exports/*" id="clear-export" type="button" class="btn btn-primary clear-cache">Clear Export Cache</button>
            <button data-api-action = "clear-cdn-cache" data-key = "/pdf/*" id="clear-pdf" type="button" class="btn btn-primary clear-cache">Clear PDF Cache</button>
            <button data-api-action = "flush-redis-cache" id="clear-redis" type="button" class="btn btn-primary clear-cache">Clear Redis Cache</button>
            <button data-method="POST" data-api-action = "elastic-search-re-index" id="re-index"  data-elastic_search_re_index="yes" type="button" class="btn btn-primary clear-cache">Re-Index Elastic Search</button>
        </div>
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
            </tr>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="card p-4"><p> No Requests found!</p></div>
<?php } ?>
<style>
    .small-width-column {
        width: 3.5em;
    }
    .bold-red {
        color:red !important;
        font-weight: bold;
    }
</style>
