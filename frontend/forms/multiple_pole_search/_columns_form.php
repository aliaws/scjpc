<?php $columns_chunked = array_chunk(CHECK_BOXES_LABELS, ceil(count(CHECK_BOXES_LABELS) / 2), true);
$active_only_status = empty($_REQUEST) || isset($_REQUEST['active_only']) ? 'checked' : ''; ?>
<div class="well m-0">
  <div class="mb-2"><label for="id_choices">Fields</label></div>
  <div class="row">
    <?php foreach ($columns_chunked as $column_group) {
      print_checkboxes($column_group);
    } ?>
  </div>
  <div class="mt-3 form-group">
    <div><label for="active_only">Show Active only?</label></div>
    <input id="active_only" name="active_only" type="checkbox" <?php echo $active_only_status; ?> />
  </div>
</div>

<input type="hidden" id="action" name="action" value="multiple_pole_search"/>
<input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
<input type="hidden" id="page_number" name="page_number" value="<?php echo $_REQUEST['page_number'] ?? '1'; ?>"/>
<input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>