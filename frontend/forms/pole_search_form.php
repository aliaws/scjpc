<form class="needs-validation" id="jpa_detail_search" method="post" novalidate>
  <div class="mb-3">
    <!--      <label for="pole_number" class="form-label">Enter Pole Number</label>-->
    <input type="hidden" name="jpa_number" class="form-control" id="jpa_number"
           value="<?php echo $_REQUEST['jpa_number'] ?? ''; ?>" required/>
    <div id="jpa_number_feedback" class="invalid-feedback"> Please choose a Pole number.</div>
  </div>
  <input type="hidden" id="action" name="action" value="jpa_detail_search"/>
  <input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
  <input type="hidden" id="page_number" name="page_number" value="<?php echo $_REQUEST['page_number'] ?? '1'; ?>"/>
  <input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>
  <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
  <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_POST['sort_key'] ?? 'unique_id'; ?>"/>
  <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_POST['sort_order'] ?? 'asc'; ?>"/>

</form>
<?php
include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php";
?>
<div class="response-table"></div>
