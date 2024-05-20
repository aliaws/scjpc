<form class="needs-validation" id="pole_detail" action="<?php echo get_permalink(get_the_ID()); ?>" method="get"
      novalidate>
  <input type="hidden" name="unique_id" class="form-control" id="unique_id"
         value="<?php echo $_GET['unique_id'] ?? ''; ?>" required/>
  <input type="hidden" id="action" name="action" value="pole_detail"/>
</form>
