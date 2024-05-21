<div class="card-wrapper d-flex justify-content-center">
  <div class="card custom-card p-4">
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
    <form class="needs-validation" id="multiple_jpa_search" action="<?php echo get_permalink(get_the_ID()); ?>"
      method="post" enctype="multipart/form-data" novalidate>
      <div class="mb-3">
        <label for="excel_contains_header" class="form-label d-block">Does Excel/CSV contains Header?</label>
        <input type="checkbox" name="contains_header" class="form-check-input" id="excel_contains_header" <?php echo isset($_POST['contains_header']) && $_POST['contains_header'] ? 'checked' : ''; ?> />
      </div>
      <div class="mb-3">
        <label for="formFile" class="form-label">Select File</label>
        <input class="form-control" name="uploaded_file" type="file" id="formFile" accept=".xlsx" />
      </div>
      <input type="hidden" id="action" name="action" value="multiple_jpa_search" />
      <input type="hidden" id="per_page" name="per_page" value="<?php echo $_POST['per_page'] ?? '50'; ?>" />
      <input type="hidden" id="page_number" name="page_number" value="<?php echo $_POST['page_number'] ?? '1'; ?>" />
      <input type="hidden" id="last_id" name="last_id" value="<?php echo $_POST['last_id'] ?? ''; ?>" />
      <div class="d-flex justify-content-between">
        <button type="button" class="clearBtn btn btn-secondary">Clear</button>
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
</div>
<?php
