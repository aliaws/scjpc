<div class="card-wrapper d-flex justify-content-center">
  <div class="card custom-card p-4">
    <form class="needs-validation" id="quick_pole_search" method="get" novalidate>
      <div class="mb-3">
        <label for="pole_number" class="form-label">Enter Pole Number</label>
        <input type="text" name="pole_number" class="form-control" id="jpa_number"
               value="<?php echo $_GET['pole_number'] ?? ''; ?>" required/>
        <div id="jpa_number_feedback" class="invalid-feedback"> Please choose a Pole number.</div>
      </div>
      <input type="hidden" id="action" name="action" value="quick_pole_search"/>
      <input type="hidden" id="per_page" name="per_page" value="<?php echo $_GET['per_page'] ?? '50'; ?>"/>
      <input type="hidden" id="page_number" name="page_number" value="<?php echo $_GET['page_number'] ?? '1'; ?>"/>
      <input type="hidden" id="last_id" name="last_id" value="<?php echo $_GET['last_id'] ?? ''; ?>"/>
      <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_POST['sort_key'] ?? 'unique_id'; ?>"/>
      <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_POST['sort_order'] ?? 'asc'; ?>"/>
      <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
      <div class="d-flex justify-content-between">
        <button type="button" class="clearBtn btn btn-secondary">Clear</button>
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
    <p class="text mt-4 fw-light ">
      <small>
        To search: omit space, slash, hyphen, and other special characters.
        <br>
        Example: Pole: 123456E (instead of 123456-E).
      </small>
    </p>
  </div>
</div>
<?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php"; ?>
<div class="response-table"></div>
<div class="database-update-information"><?php echo scjpc_database_update_information(); ?></div>
