<form class="needs-validation" id="pole_detail" method="post" novalidate>
  <input type="hidden" name="unique_id" class="form-control" id="unique_id"
         value="<?php echo $_REQUEST['unique_id'] ?? ''; ?>" required/>
  <input type="hidden" id="action" name="action" value="pole_detail"/>
  <input type="hidden" id="admin_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>"/>
</form>
<!--<script type="text/javascript">-->
<!--  jQuery(`form`).submit()-->
<!--</script>-->
<div class="response-table"></div>
