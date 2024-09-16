<?php [$fields, $jpa_contacts] = scjpc_get_emergency_claim_contacts();
[$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'emergency');
//echo "<pre>==response==" . print_r($response, true) . "</pre>";
//$response = scjpc_transpose_contacts_data($response, $field_labels); ?>
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

<!--<div class="excel-table-container excel-table-container-auto-height emergency-contacts-table">-->
<!--  <table class="excel-table">-->
<!--    <thead>-->
<!--    --><?php //foreach ($response as $key => $row) {
//      if ($key == 'member_code') { ?>
<!--        <tr>-->
<!--          --><?php //foreach ($row as $field => $value) { ?>
<!--            <th><p>--><?php //echo $value; ?><!--</p></th>-->
<!--          --><?php //} ?>
<!--        </tr>-->
<!--      --><?php //}
//    } ?>
<!--    </thead>-->
<!--    <tbody>-->
<!--    --><?php //foreach ($response as $key => $row) {
//      if ($key != 'member_code') { ?>
<!--        <tr>-->
<!--          --><?php //foreach ($row as $field => $value) { ?>
<!--            <td><p>--><?php //echo $value; ?><!--</p></td>-->
<!--          --><?php //} ?>
<!--        </tr>-->
<!--      --><?php //}
//    } ?>
<!--    </tbody>-->
<!--  </table>-->
<!--</div>-->
<!---->
