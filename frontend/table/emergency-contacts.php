<?php [$fields, $jpa_contacts] = scjpc_get_emergency_claim_contacts();
[$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'emergency');
$response = scjpc_transpose_contacts_data($response, $field_labels); ?>
<div class="excel-table-container excel-table-container-auto-height">
  <table class="excel-table">
    <thead>
    <?php foreach ($response as $key => $row) { ?>
      <tr>
        <?php if ($key == 'member_code') {
          foreach ($row as $field => $value) { ?>
            <th><?php echo $value; ?></th>
          <?php }
        } else {
          continue;
        } ?>
      </tr>
    <?php } ?>
    </thead>
    <tbody>
    <?php foreach ($response as $key => $row) { ?>
      <tr>
        <?php if ($key == 'member_code') {
          continue;
        } else {
          foreach ($row as $field => $value) { ?>
            <td><?php echo $value; ?></td>
          <?php }
        } ?>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
