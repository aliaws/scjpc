<?php //if ( $redirect_url ) { ?>
<!--  <a class="btn" href="--><?php //echo $redirect_url . "&go_back=1" ?><!--" style="color: black;">Go Back</a> <br />-->
<?php //} ?>

<div class="scjpc-navigation-buttons d-flex">
  <a id="go-back" class="btn" style="color: black; margin-right: 10px; display: none;" onclick="window.history.back()">Go Back</a>
  <a id="go-forward" class="btn" style="color: black; display: none;" onclick="window.history.forward()">Go Forward</a>
</div>

<?php if (isset($search_key) && $search_key != '') { ?>
  <span>Search Key: <strong><?php echo $search_key; ?></strong></span>
<?php } ?>

<h5>No Records Found</h5>
