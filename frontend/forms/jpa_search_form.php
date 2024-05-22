<div class="card-wrapper d-flex justify-content-center">
  <div class="card custom-card p-4">
    <form class="needs-validation" id="jpa_search" method="post" novalidate>
      <div class="mb-3">
        <label for="jpa_number" class="form-label">Enter JPA Number</label>
        <input type="text" name="jpa_number" class="form-control" id="jpa_number"
               value="<?php echo $_POST['jpa_number'] ?? ''; ?>" required/>
        <input type="hidden" id="action" name="action" value="jpa_search"/>
        <input type="hidden" id="per_page" name="per_page" value="<?php echo $_POST['per_page'] ?? '50'; ?>"/>
        <input type="hidden" id="page_number" name="page_number" value="<?php echo $_POST['page_number'] ?? '1'; ?>"/>
        <input type="hidden" id="last_id" name="last_id" value="<?php echo $_POST['last_id'] ?? ''; ?>"/>
        <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_POST['sort_key'] ?? 'jpa_unique_id'; ?>"/>
        <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_POST['sort_value'] ?? 'asc'; ?>"/>
        <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
        <div id="jpa_number_feedback" class="invalid-feedback">
          Please choose a JPA number.
        </div>
      </div>
      <div class="d-flex justify-content-between">
        <button type="button" class="clearBtn btn btn-secondary">Clear</button>
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
    <p class="text mt-4 fw-light ">
      <small>
        To search: omit space, slash, hyphen, and other special characters.
        <br>
        Example: JPA: ABC1256NN12 (instead of ABC-1256/NN-12).
      </small>
    </p>
  </div>
</div>
<div class="response-table"></div>
