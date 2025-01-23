<?php if (isset($search_key) && $search_key != '') { ?>
  <span>Search Key: <strong><?php echo $search_key; ?></strong></span>
<?php } ?>

<?php if ( $redirect_url ) { ?>
  <a class="btn" href="<?php echo $redirect_url . "&go_back=1" ?>" style="color: black;">Go Back To Search Results</a>
<?php } ?>

<h5>No Records Found</h5>
