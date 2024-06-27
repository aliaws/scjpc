<?php [$fields, $pole_contacts] = scjpc_get_pole_inspection_contacts();
[$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $pole_contacts, 'pole');
?>
<div class="excel-table-container">
  <table class="excel-table">
    <thead>
    <tr>
      <?php foreach ($response[array_key_first($response)] as $field => $value) { ?>
        <th><?php echo $field_labels[$field]; ?></th>
      <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($response as $key => $row) { ?>
      <tr>
        <?php foreach ($row as $field => $value) { ?>
          <td data-post-id="<?php echo $key; ?>"><?php echo $value; ?></td>
        <?php } ?>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
