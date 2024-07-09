<?php
load_bootstrap_assets();
$api_url = trim(get_option('scjpc_es_host'), '/') . "/es-indices-health";

$export_requests = make_search_api_call($api_url);
// echo "<pre>";
// print_r($export_requests);
// die;
if (!empty($export_requests)) {
?>
  <div class="export-container overflow-auto">
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
            States
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
      <?php foreach ($export_requests as $key=>$data) { 
        // $statusStyles = getStateStyles($value['state']
        // echo "<pre>";
        // print_r($key);
        // die;
        if($key == "health"){
            foreach($data as $value){

        ?>
            <tr>
            <td class="align-middle">
                <?php echo $value['epoch']; ?>
            </td>
            <td class="align-middle">
                <?php echo $value['timestamp']; ?>
            </td>
            <td class="align-middle">
                <?php echo $value['cluster']; ?>
            </td>
            <td class="align-middle">
                <p class="m-0 <?php echo $statusStyles['status']; ?>" style="<?php echo $statusStyles['style']; ?>">
                    <?php echo $value['status']; ?>
                </p>
            </td>
            <td class="align-middle">
                <?php echo $value['node.total']; ?>
            </td>
            <td class="align-middle">
                <?php echo $value['node.data']; ?>
            </td>
            <td class="align-middle">
                <?php echo $value['shards']; ?>
            </td>
            <td class="align-middle">
                <?php echo $value['pri']; ?>
            </td>
            <td class="align-middle">
                <?php echo $value['active_shards_percent']; ?>
            </td>
            </tr>
      <?php } } }?>
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
