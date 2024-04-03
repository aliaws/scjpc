<?php
load_bootstrap_assets();
$get_migration_logs = get_migration_logs() ? get_migration_logs() : "";
$recordKeys = array_keys($get_migration_logs['0']);
?>
<div class="container">
  <table class="table w-100 table-striped">
    <thead>
    <tr>
      <?php
      foreach ($recordKeys as $value) {
        echo "<th class='text-capitalize' style='font-size: 16px' scope='col'>" . str_replace("_", " ", $value) . "</th>";
      }
        ?>
    </tr>
    </thead>
    <tbody>
    <?php
      foreach (MIGRATION_LOGS as $value){
    ?>
    <tr>
      <th scope="row"><?php echo $value['id']; ?></th>
      <td><?php echo $value['status']; ?></td>
      <td><?php echo $value['jpas_s3_key']; ?></td>
      <td><?php echo $value['no_jpas_created']; ?></td>
      <td><?php echo $value['no_jpas_updated']; ?></td>
      <td><?php echo $value['poles_s3_key']; ?></td>
      <td><?php echo $value['no_poles_created']; ?></td>
      <td><?php echo $value['no_poles_updated']; ?></td>
      <td><?php echo $value['error']; ?></td>
      <td><?php echo $value['created_at']; ?></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>

</div>