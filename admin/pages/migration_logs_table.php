<?php
load_bootstrap_assets();
$api_url_migration_logs = trim(get_option('scjpc_es_host'), '/') . "/migration_logs";
$api_url_deleted_poles = trim(get_option('scjpc_es_host'), '/') . "/deleted-records?table=deleted_poles&order=desc";
$api_url_deleted_jpas = trim(get_option('scjpc_es_host'), '/') . "/deleted-records?table=deleted_jpas&order=desc";

$export_requests = make_search_api_call($api_url_migration_logs);
$deleted_poles = make_search_api_call($api_url_deleted_poles);
$deleted_jpas = make_search_api_call($api_url_deleted_jpas);

if (!empty($export_requests)) {
?>
  <div class="export-container overflow-auto">
      <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
          <tr>
              <th>Deleted Poles Counts</th>
              <td class="align-middle <?php echo $deleted_poles['total'] != 0 ? "bold-red": "";  ?>" scope="row">
                  <?php echo $deleted_poles['total']; ?>
              </td>
          </tr>
          <tr>
              <th>Deleted Jpas Counts</th>
              <td class="align-middle <?php echo $deleted_jpas['total'] != 0 ? "bold-red": "";  ?>" scope="row">
                  <?php echo $deleted_jpas['total']; ?>
              </td>
          </tr>
      </table>  
    <table class="table w-100 table-striped wp-list-table widefat fixed striped table-view-list posts">
      <thead>
      <tr>
        <th scope="col" style="font-size: 16px;" class="small-width-column text-capitalize manage-column">
          ID
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
          Status
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
          Request Host
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
          Export Start Time
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
          Export Duration
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
          Import Start Time
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
          Import Duration
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
          Import Finished Time
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
           Deleted JPA's
        </th>
        <th scope="col" style="font-size: 16px;" class="text-capitalize manage-column">
           Deleted Pole's
        </th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($export_requests as $value) { 
        $job_datetime =  convert_date_time_format($value['job_datetime']);
        $statusStyles = getStatusStyles($value['status']);
      ?>
        <tr>
          <td class="align-middle" scope="row">
            <?php echo $value['id']; ?>
          </td>
          <td class="align-middle">
              <p class="m-0 <?php echo $statusStyles['class']; ?>" style="<?php echo $statusStyles['style']; ?>">
                <?php echo $value['status']; ?>
              </p>
          </td>
          <td class="align-middle">
            <?php echo $value['request_host']; ?>
          </td>
          <td class="align-middle">
            <?php echo $job_datetime; ?>
          </td>
          <td class="align-middle">
            <?php echo time_ago(strtotime($value['created_at']), strtotime($job_datetime)); ?>
          </td>
          <td class="align-middle">
            <?php echo $value['created_at']; ?>
          </td>
          <td class="align-middle">
            <?php echo time_ago(strtotime($value['created_at']), strtotime($value['updated_at'])); ?>
          </td>
          <td class="align-middle">
            <?php echo $value['updated_at']; ?>
          </td>
          <td class="align-middle">
              <?php echo $value['deleted_jpas']; ?>
          </td>
          <td class="align-middle">
              <?php echo $value['deleted_poles']; ?>
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
    .bold-red {
        color:red !important;
        font-weight: bold;
    }
</style>
