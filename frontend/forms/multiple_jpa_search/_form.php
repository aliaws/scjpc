<?php
$contains_headers = 'checked';
if (isset($_REQUEST['go_back'])) {
  $contains_headers = isset($_REQUEST['contains_header']) ? 'checked' : '';
} ?>
<div class="card-wrapper d-flex justify-content-center">
  <div class="card custom-card p-2">
    <p class="text  mb-0 fw-light ">
      <small>
        Click "Chose File" button, select an Excel file, and click "Search multiple JPAs" button (one time only)
      </small>
      <small class="my-3 d-block">
        <a href="<?php echo SCJPC_URL . "frontend/ExampleJPANumbers.xlsx"; ?>" download>
          Example of JPA Numbers Excel file (.xlsx)
        </a>
      </small>
    </p>
    <form class="needs-validation" id="multiple_jpa_search" method="post" enctype="multipart/form-data" novalidate>
      <div class="mb-3">
        <label for="excel_contains_header" class="form-label d-block">Does Excel contains Header?</label>
        <input type="checkbox" name="contains_headers" class=""
               id="excel_contains_header" <?php echo $contains_headers; ?> />
      </div>
      <div class="mb-3">
        <label for="formFile" class="form-label">Select File</label>
        <input class="form-control" name="uploaded_file" type="file" id="formFile" accept=".xlsx" autofocus required/>
      </div>
      <input type="hidden" id="action" name="action" value="multiple_jpa_search"/>
      <input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
      <input type="hidden" id="page_number" name="page_number" value="<?php echo $_REQUEST['page_number'] ?? '1'; ?>"/>
      <input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>
      <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_REQUEST['sort_key'] ?? 'jpa_unique_id'; ?>"/>
      <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_REQUEST['sort_order'] ?? 'asc'; ?>"/>
      <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
      <input type="hidden" id="s3_key" name="s3_key" value="<?php echo $_REQUEST['s3_key'] ?? ''; ?>"/>
      <input type="hidden" id="page_slug" name="page_slug" value="<?php echo get_post_field('post_name', get_the_ID()); ?>"/>

      <?php $query_id = empty ( $_REQUEST['query_id'] ) ? time() : $_REQUEST['query_id']; ?>
      <input type="hidden" id="query_id" name="query_id" value="<?php echo $query_id; ?>"/>
      <?php if ( ! empty ( $_REQUEST['go_back'] ) ) { ?>
        <input type="hidden" id="go_back" name="go_back" value="<?php echo $_REQUEST['go_back']; ?>"/>
      <?php } ?>

      <div class="d-flex justify-content-between">
        <button type="button" class="clearBtn btn btn-secondary">Clear</button>
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
</div>
<?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php"; ?>
<div class="response-table">
  <?php if (!empty($_REQUEST) && !empty($_REQUEST['s3_key'])) {
    $_REQUEST['action'] = 'multiple_jpa_search';
    include_once SCJPC_PLUGIN_FRONTEND_BASE . "results/jpa_results.php";
  } ?>
</div>
<div class="database-update-information alert alert-primary mt-4">
  <?php echo scjpc_database_update_information(); ?>
</div>