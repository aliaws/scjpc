<?php [$fields, $jpa_contacts] = scjpc_get_buddy_pole_contacts();
[$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'buddy-pole'); ?>
<div class="excel-table-container excel-table-container-auto-height">
  <table class="excel-table">
    <thead>
    <tr>
      <?php foreach ($response[array_key_first($response)] as $field => $value) { ?>
        <th><p><?php echo $field_labels[$field]; ?></p></th>
      <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($response as $key => $row) { ?>
      <tr>
        <?php foreach ($row as $field => $value) { ?>
          <td><p><?php echo $value; ?></p></td>
        <?php } ?>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
