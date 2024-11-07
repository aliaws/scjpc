<?php
$contains_headers = 'checked';
if (isset($_REQUEST['go_back'])) {
  $contains_headers = isset($_REQUEST['contains_header']) ? 'checked' : '';
}
?>
<div class="card p-2 p-sm-4">
  <p class="text  mb-0 fw-light ">
    <strong class="me-2">
      Search Option 1:
    </strong>
    <small>Search multiple poles by pole numbers listed in an Excel file.</small>
    <small class="my-3 d-block">
      <a href="<?php echo SCJPC_URL . "frontend/ExamplePoleNumbers.xlsx"; ?>" download>
        Example of Pole Numbers Excel file - (.xlsx)
      </a>
    </small>
    <small class="my-3 d-block">
      Click "Choose File" button, select an Excel file, and click "Search multiple poles" button (one time only).
    </small>
  </p>
  <!--  <form class="needs-validation" id="multiple_pole_search" method="post" enctype="multipart/form-data" novalidate>-->
  <form class="needs-validation" id="multiple_pole_search" method="post" novalidate>
    <div class="row custom-border rounded-1 p-0 p-md-2 p-lg-4">
      <div class="col-12 col-lg-6 mb-3 mb-lg-0 d-flex flex-column">
        <div class="mb-3">
          <label for="excel_contains_header" class="form-label d-block">Does Excel contains Header?</label>
          <input type="checkbox" name="contains_headers" id="excel_contains_header" <?php echo $contains_headers; ?> />
        </div>
        <div class="mb-3">
          <label for="id_search_file" class="form-label">Select File</label>
          <input class="form-control" name="uploaded_file" type="file" id="id_search_file" accept=".xlsx" autofocus
                 required/>

        </div>
        <p class="text  mb-3 fw-light ">
          <strong class="me-2">
            Search Option 2:
          </strong>
          <small>Search poles by owner code</small>
          <small class="my-1 d-block">
            Select an owner code and click "Search multiple poles" button.
          </small>
        </p>
        <div class="mb-3">
          <label for="base_owner" class="form-label">OR, Select Code</label>
          <select class="form-select" id="base_owner" name="base_owner" aria-label="Default select example" required>
            <option value=""></option>
            <?php foreach (scjpc_get_base_owners(true, true) as $key => $value) {
              $is_selected = isset($_REQUEST['base_owner']) && $_REQUEST['base_owner'] == $key ? 'selected' : ''; ?>
              <option <?php echo $is_selected; ?> value="<?php echo $key; ?>">
                <?php echo $value == $key ? $value : "$key ($value)"; ?>
              </option>
            <?php } ?>
          </select>
          <div id="invalid-feedback" class="invalid-feedback ">Please Upload Excel/Csv file or Select Owner Code.</div>
        </div>
        <input type="hidden" id="action" name="action" value="multiple_pole_search"/>
        <input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
        <input type="hidden" id="page_number" name="page_number" value="<?php echo $num_results_on_page ?? '1'; ?>"/>
        <input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>
        <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_REQUEST['sort_key'] ?? 'unique_id'; ?>"/>
        <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_REQUEST['sort_order'] ?? 'asc'; ?>"/>
        <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
        <input type="hidden" id="s3_key" name="s3_key" value="<?php echo $_REQUEST['s3_key'] ?? ''; ?>"/>
        <input type="hidden" id="page_slug" name="page_slug"
               value="<?php echo get_post_field('post_name', get_the_ID()); ?>"/>
        <div class="d-none d-lg-flex mt-auto justify-content-between">
          <button type="button" class="clearBtn btn btn-secondary">Clear</button>
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </div>
      <div class="col-12 col-lg-6 mb-3 mb-lg-0">
        <?php include_once SCJPC_PLUGIN_FRONTEND_BASE . 'forms/multiple_pole_search/_columns_form.php' ?>
      </div>
      <div class="col-12">
        <div class="d-flex d-lg-none mb-3 justify-content-between">
          <button type="button" class="clearBtn btn btn-secondary">Clear</button>
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php"; ?>
<div class="response-table">
  <?php if (!empty($_REQUEST) && (!empty($_REQUEST['s3_key']) || !empty($_REQUEST['base_owner']))) {
    $_REQUEST['action'] = 'multiple_pole_search';
    include_once SCJPC_PLUGIN_FRONTEND_BASE . "results/multiple_pole_results.php";
  } ?>
</div>
<div class="database-update-information alert alert-primary mt-4">
  <?php echo scjpc_database_update_information(); ?>
</div>
