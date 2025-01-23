<?php

if ( ! empty ( $_GET ) || ! empty ( $_POST ) ) {
  $search_result = search_scjpc( $_REQUEST );
  if ( ! empty ( $search_result['s3_key'] ) ) {
    $_REQUEST['s3_key'] = $search_result['s3_key'];
  }
  $search_query        = urlencode(http_build_query($_REQUEST));
//  $search_query        = array_pop( $search_result['transient'] );
  $redirect_url        = $search_result['redirect_url'];
  $query_id            = $search_result['query_id'];
  $search_key          = $_REQUEST['jpa_number'] ?? '';
  $record_keys         = JPAS_KEYS;
  $sort_keys           = JPAS_SORT_KEYS;
  $total_pages         = isset ( $search_result["total_pages"] ) ? (int)$search_result["total_pages"] : 0;
  $page                = (int)$search_result["page_number"] ?? 1;
  $result_per_page     = $search_result['result_per_page'] ?? RESULTS_PER_PAGE;
  $num_results_on_page = $search_result['per_page'];
  $response_sort_key   = $search_result['sort_key'] ?? 'jpa_unique_id';
  $response_sort_order = $search_result['sort_order'] ?? 'asc';
  $total_records       = $search_result['total_records'] ?? 0;
  $search_results      = $search_result['results'] ?? [];
  $current_user        = wp_get_current_user();
  $user_id             = $current_user->ID;
  $user_email          = $current_user->user_email;
  $export_endpoint     = trim( get_option( 'scjpc_es_host' ), '/' ) . "/data-export";
  $base_cdn_url        = rtrim( get_option( 'scjpc_aws_cdn' ), '/' );
  $base_cdn_url        = str_starts_with( $base_cdn_url, 'https://' ) ? $base_cdn_url : "https://$base_cdn_url";

  if ($total_records > 0 && count($search_results) > 0) {
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/table/jpa_response.php';
  } else {
    if (!empty($search_result['errors_file_path']) && $search_result['errors_file_path'] != '') { ?>
      <div class="d-flex b justify-content-end mb-2 align-items-center" role="group">
        <div>
          <a class="btn" href="<?php echo $base_cdn_url . "/" . $search_result['errors_file_path']; ?>">Errors Lis</a>
        </div>
      </div>
    <?php }
    include_once SCJPC_PLUGIN_FRONTEND_BASE . '/partials/_not_found.php';
  }
}