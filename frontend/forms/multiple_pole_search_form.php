<div class="card p-2 p-sm-4">
    <p class="text  mb-0 fw-light ">
        <strong class="me-2">
            Search Option 1:
        </strong>
        <small>Search multiple poles by pole numbers listed in an excel file.</small>
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
                    <label for="excel_contains_header" class="form-label d-block">Does Excel/CSV contains
                        Header?</label>
                    <input type="checkbox" name="contains_header" id="excel_contains_header" <?php echo isset($_POST['contains_header']) && $_POST['contains_header'] ? 'checked' : ''; ?> />
                </div>
                <div class="mb-3">
                    <label for="id_search_file" class="form-label">Select File</label>
                    <input class="form-control" name="uploaded_file" type="file" id="id_search_file" accept=".xlsx,.csv" />
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
                    <select class="form-select" id="base_owner" name="base_owner" aria-label="Default select example">
                        <option value=""></option>
                        <?php foreach (BASE_OWNERS as $key => $value) { ?>
                            <option <?php echo isset($_POST['base_owner']) && $_POST['base_owner'] == $key ? 'selected' : '' ?>
                                    value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" id="action" name="action" value="multiple_pole_search" />
                <input type="hidden" id="per_page" name="per_page" value="<?php echo $_POST['per_page'] ?? '50'; ?>" />
                <input type="hidden" id="page_number" name="page_number"
                    value="<?php echo $num_results_on_page ?? '1'; ?>" />
                <input type="hidden" id="last_id" name="last_id" value="<?php echo $_POST['last_id'] ?? ''; ?>" />
                <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
                <div class="d-none d-lg-flex mt-auto justify-content-between">
                    <button type="button" class="clearBtn btn btn-secondary">Clear</button>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                <?php include_once SCJPC_PLUGIN_FRONTEND_BASE . 'forms/multiple_pole_search_columns_form.php' ?>
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
<?php
  include_once  SCJPC_PLUGIN_FRONTEND_BASE."table/spinner.php";
?>
<div class="response-table"></div>