<div class="card-wrapper d-flex justify-content-center">
  <div class="card custom-card p-4">
    <form class="needs-validation" id="jpa_search" method="post" novalidate>
      <div class="mb-3">
        <label for="jpa_number_visible" class="form-label">Enter JPA Number</label>
        <input type="text" name="jpa_number_visible" id="jpa_number_visible"
               value="<?php echo $_REQUEST['jpa_number'] ?? ''; ?>" required/>
        <input type="hidden" id="jpa_number" name="jpa_number" value="<?php echo $_REQUEST['jpa_number'] ?? ''; ?>"/>
        <input type="hidden" id="action" name="action" value="jpa_search"/>
        <input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
        <input type="hidden" id="page_number" name="page_number"
               value="<?php echo $_REQUEST['page_number'] ?? '1'; ?>"/>
        <input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>
        <input type="hidden" id="sort_key" name="sort_key"
               value="<?php echo $_REQUEST['sort_key'] ?? 'jpa_unique_id'; ?>"/>
        <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_REQUEST['sort_value'] ?? 'asc'; ?>"/>
        <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
        <input type="hidden" id="page_slug" name="page_slug"
               value="<?php echo get_post_field('post_name', get_the_ID()); ?>"/>
        <!--        <div id="jpa_number_feedback" class="invalid-feedback">-->
        <!--          JPA number is Required.-->
        <!--        </div>-->
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
<?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php"; ?>
<div class="response-table">
  <?php if (!empty($_REQUEST) && !empty($_REQUEST['jpa_number'])) {
    $_REQUEST['action'] = 'jpa_search';
    include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/jpa_results.php";
  } ?>
</div>
<div
  class="database-update-information alert alert-primary mt-4"><?php echo scjpc_database_update_information(); ?></div>
