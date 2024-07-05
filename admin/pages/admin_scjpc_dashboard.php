<?php
load_bootstrap_assets();
$api_url = trim(get_option('scjpc_es_host'), '/') . "/es-db-counts";

$db_db_counts = make_search_api_call($api_url);
if (!empty($db_db_counts)) {
    ?>
    <div class="export-container overflow-auto">
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
                <td class="align-middle <?php echo $db_db_counts['poles_db_es_diff'] != 0 ? "bold-red": "";  ?>"" scope="row">
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
                <td class="align-middle <?php echo $db_db_counts['jpas_db_es_diff'] != 0 ? "bold-red": "";  ?>" scope="row"
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
        width: 3.5em
    }
    .bold-red {
        color:red;
        font-weight: bold;
    }
</style>
