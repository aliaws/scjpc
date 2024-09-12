<?php
if (empty($_GET['s'])) {
	add_filter( 'relevanssi_hits_filter', 'scjpc_nice_hits_filter' );
	function scjpc_nice_hits_filter( $hits ) {
	  $hits[0] = [];
	  return $hits;
	}
}
?>