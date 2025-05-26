<form class="needs-validation" id="jpa_detail_search" method="post" novalidate>
  <div class="mb-3">
      <input type="hidden" name="jpa_number" id="jpa_number" value="<?php echo $_REQUEST['jpa_number'] ?? ''; ?>" required/>
  </div>

  <input type="hidden" id="action" name="action" value="jpa_detail_search"/>
  <input type="hidden" id="per_page" name="per_page" value="<?php echo $_REQUEST['per_page'] ?? '50'; ?>"/>
  <input type="hidden" id="page_number" name="page_number" value="<?php echo $_REQUEST['page_number'] ?? '1'; ?>"/>
  <input type="hidden" id="last_id" name="last_id" value="<?php echo $_REQUEST['last_id'] ?? ''; ?>"/>
  <input type="hidden" id="search_query" name="search_query" value="<?php echo $_REQUEST['search_query'] ?? ''; ?>"/>
  <input type="hidden" id="page_slug" name="page_slug" value="<?php echo get_post_field('post_name', get_the_ID()); ?>"/>
  <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
  <input type="hidden" id="sort_key" name="sort_key" value="<?php echo $_POST['sort_key'] ?? 'unique_id'; ?>"/>
  <input type="hidden" id="sort_order" name="sort_order" value="<?php echo $_POST['sort_order'] ?? 'asc'; ?>"/>

  <?php $query_id = empty ( $_REQUEST['query_id'] ) ? time() : $_REQUEST['query_id']; ?>
  <input type="hidden" id="query_id" name="query_id" value="<?php echo $query_id; ?>"/>
  <?php if ( ! empty ( $_REQUEST['go_back'] ) ) { ?>
    <input type="hidden" id="go_back" name="go_back" value="<?php echo $_REQUEST['go_back']; ?>"/>
  <?php } ?>


</form>
<?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php"; ?>
<!--<div class="response-table"></div>-->

<div class="response-table">
  <?php if ( ! empty ( $_REQUEST ) && ! empty ( $_REQUEST['jpa_number'] ) ) {
    $_REQUEST['action'] = 'jpa_detail_search';
    include_once SCJPC_PLUGIN_FRONTEND_BASE . "results/pole_results.php";
  } ?>
</div>

<div class="database-update-information alert alert-primary mt-4">
    <?php echo scjpc_database_update_information(); ?>
</div>
