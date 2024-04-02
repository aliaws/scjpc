<?php

load_bootstrap_assets();
include_once  SCJPC_PLUGIN_FRONTEND_BASE."forms/jpa_search_form.php";

if(!empty($_REQUEST['jpa_number'])) {
  include_once  SCJPC_PLUGIN_FRONTEND_BASE."table/jpa_results.php";
}

?>