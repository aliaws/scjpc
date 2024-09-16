<?php $html_fields = []; ?>
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
          <td data-post-id="<?php echo $key; ?>">
            <?php if (!scjpc_string_contains_html_table($value)) {
              echo $value;
            } else {
              $html_fields[] = [
                'id' => "$field-$key",
                'member_code' => $row['member_code'],
                'field_label' => $field_labels[$field],
                'value' => $value]; ?>
              <button class="btn btn-primary" onclick="scrollToElement(event, '<?php echo "$field-$key"; ?>')">
                Click to view <?php echo $field_labels[$field]; ?> below.
              </button>
            <?php } ?>
          </td>
        <?php } ?>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>


<?php foreach ($html_fields as $field) { ?>
  <div class="margin-top-5pc" id="<?php echo $field['id']; ?>">
    <span>
      <i>Member Code: </i><strong><?php echo $field['member_code']; ?></strong><br>
      <i>Table Field: </i><strong><?php echo $field['field_label']; ?></strong>
    </span>
    <div class="excel-table-container margin-top-10px">
      <?php echo $field['value']; ?>
    </div>
  </div>
<?php } ?>
