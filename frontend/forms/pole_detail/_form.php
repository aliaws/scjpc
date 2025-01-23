<form class="needs-validation" id="pole_detail" method="post" novalidate>
  <input type="hidden" name="unique_id" class="form-control" id="unique_id" value="<?php echo $_REQUEST['unique_id'] ?? ''; ?>" required/>
  <input type="hidden" id="action" name="action" value="pole_detail"/>
  <input type="hidden" id="search_query" name="search_query" value="<?php echo $_REQUEST['search_query'] ?? ''; ?>"/>
  <input type="hidden" id="page_slug" name="page_slug" value="<?php echo get_post_field('post_name', get_the_ID()); ?>"/>
  <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>

  <?php $query_id = empty ( $_REQUEST['query_id'] ) ? time() : $_REQUEST['query_id']; ?>
  <input type="hidden" id="query_id" name="query_id" value="<?php echo $query_id; ?>"/>
  <?php if ( ! empty ( $_REQUEST['go_back'] ) ) { ?>
    <input type="hidden" id="go_back" name="go_back" value="<?php echo $_REQUEST['go_back']; ?>"/>
  <?php } ?>

</form>

<?php include_once SCJPC_PLUGIN_FRONTEND_BASE . "table/spinner.php"; ?>
<div class="response-table"></div>
<div class="database-update-information alert alert-primary mt-4">
    <?php echo scjpc_database_update_information(); ?>
</div>
