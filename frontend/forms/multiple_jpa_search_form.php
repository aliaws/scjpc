<div class="card p-4">
  <p class="text  mb-0 fw-light ">
    <small>
      Click "Chose File" button, select an Excel file, and click "Search multiple JPAs" button (one time only)
    </small>
    <small class="my-3 d-block">
      <a href="<?php echo home_url(SCJPC_PLUGIN_FRONTEND_BASE . "multiple_jpa_search_sample_file.xlsx"); ?>" download>
        Example of JPA Numbers (Excel file)
      </a>
    </small>
  </p>
  <form class="needs-validation" id="multiple_jpa_search" action="<?php echo get_permalink(get_the_ID()); ?>"
        method="get" novalidate>
    <div class="mb-3">
      <label for="check_excel_csv" class="form-label d-block">Does Excel/CSV contains Header?</label>
      <input type="checkbox" name="jpa_number" class="form-check-input" id="check_excel_csv"/>
    </div>
    <div class="mb-3">
      <label for="formFile" class="form-label">Select File</label>
      <input class="form-control" type="file" id="formFile"/>
    </div>
    <div class="d-flex justify-content-between">
      <button type="button" class="clearBtn btn btn-secondary">Clear</button>
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>
</div>
<?php
