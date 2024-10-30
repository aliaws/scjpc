<?php load_bootstrap_assets();
load_admin_assets();
$base_cdn_url = rtrim(get_option('scjpc_aws_cdn'), '/');
$base_cdn_url = str_starts_with($base_cdn_url, 'https://') ? $base_cdn_url : "https://$base_cdn_url";
if (!defined('IS_ADMIN')) {
  define("IS_ADMIN", true);
} ?>
<div class='admin-search'>
  <?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "forms/jpa_search/_form.php"; ?>
  <div class="modal fade" id="jpaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Update PDF</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-between">
            <div>
              <span class="fw-bold">Unique ID: </span>
              <span class="edit_unique_id"></span>
            </div>
            <div>
              <span class="fw-bold">JPA Number 2: </span>
              <span class="edit_jpa_number"></span>
            </div>
          </div>
          <form id="update_jpa_search" method="post" novalidate enctype="multipart/form-data">
            <div class="my-3">
              <label for="update_file" class="form-label">Update PDF File.</label>
              <input name="pdf_s3_key" id="update_file" class="form-control" type="file" accept=".pdf">
              <input name="jpa_unique_id" id="form_unique_id" class="form-control" type="hidden" value="">
              <input name="jpa_number_2" id="form_jpa_number" class="form-control" type="hidden" value="">
              <input type="hidden" name="action" id="admin_ajax_url" value="jpa_search_update_pdf"/>
              <input name="s3_key" id="s3_key" class="form-control" type="hidden" value="">
              <input name="aws_cdn_url" id="aws_cdn_url" class="form-control" type="hidden"
                     value="<?php echo $base_cdn_url; ?>">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button id="update_submit" type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>