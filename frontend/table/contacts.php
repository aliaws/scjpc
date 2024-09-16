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
              $html_fields[] = ['id' => "$field-$key", 'field_label' => $field_labels[$field], 'value' => $value]; ?>
              <button class="btn btn-primary" onclick="toggleTableVisibility(event, '<?php echo "$field-$key"; ?>')">
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
  <div class="margin-top-5pc display-none" id="<?php echo $field['id']; ?>">
    <strong><span><?php echo $field['field_label']; ?></span></strong>
    <button class="btn btn-primary" onclick="toggleTableVisibility(event, '<?php echo $field['id']; ?>')">
      Hide Table
    </button>
    <div class="excel-table-container margin-top-10px">
      <?php echo $field['value']; ?>
    </div>
  </div>
<?php } ?>
