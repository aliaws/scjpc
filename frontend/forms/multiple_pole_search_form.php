<div class="card p-4">
  <p class="text  mb-0 fw-light ">
    <strong class="me-2">
      Search Option 1:
    </strong>
    <small>Search multiple poles by pole numbers listed in an excel file.</small>
    <small class="my-3 d-block">
      <a href="<?php echo home_url(SCJPC_PLUGIN_FRONTEND_BASE."multiple_jpa_search_sample_file.xlsx"); ?>">Example of pole numbers (excel file) (50.0 KB)</a>
    </small>
    <small  class="my-3 d-block" >
      Click "Choose File" button, select an excel file, and click "Search multiple poles" button (one time only).
    </small>
  </p>
  <form class="">
    <div class="mb-3"">
      <label for="id_header" class="form-label d-block">Does Excel/CSV contains Header?</label>
      <input type="checkbox" name="header" class="form-check-input" id="id_header" />
    </div>
    <div class="mb-3">
      <label for="id_search_file" class="form-label">Select File</label>
      <input class="form-control" name="search_file" type="file" id="id_search_file" />
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
      <label for="id_code" class="form-label">OR, Select Code</label>
      <select class="form-select" id="id_code" name="code" aria-label="Default select example">
        <?php
        foreach (FEET as $key => $value) {
          echo '<option value="'.$key.'">'.$value.'</option>';
        }
        ?>
      </select>
    </div>
    <div class="mb-3"">
      <label for="id_active" class="form-label d-block">Show Active only?</label>
      <input type="checkbox" name="active" class="form-check-input" id="id_active" />
    </div>
    <div class="d-flex justify-content-between">
      <button type="button" class="clearBtn btn btn-secondary">Clear</button>
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>
</div>
<?php
