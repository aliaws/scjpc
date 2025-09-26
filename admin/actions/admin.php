<?php


function redirect_to_login_with_redirect_to() {
  if (!is_user_logged_in() && is_singular('member')) {
    global $wp;
    $current_url = esc_url(home_url(add_query_arg($_GET, $wp->request)));
    $redirect_url = "/login?redirect_to=" . urlencode($current_url);
    wp_redirect($redirect_url);
    exit;
  }
}

add_action('template_redirect', 'redirect_to_login_with_redirect_to');


// Enforce ACF group order on CPT 'member' while keeping other boxes after them
add_filter('get_user_option_meta-box-order_member', function ($order) {
  $pref = [
    'acf-group_662d0256002a6', // 1
    'acf-group_66464f0e51512', // 2
    'acf-group_664639ed6409d', // 3
    'acf-group_662e732c50241', // 4
    'acf-group_665623aed72ff', // 5
  ];

  // Existing order strings (may be empty on first load)
  $existing_normal = isset($order['normal']) && is_string($order['normal'])
    ? array_values(array_filter(array_map('trim', explode(',', $order['normal']))))
    : [];

  // Remove our preferred IDs from existing to avoid duplicates, then append the rest
  $rest = array_values(array_diff($existing_normal, $pref));
  $normal = implode(',', array_merge($pref, $rest));

  return [
    'normal'   => $normal,
    'side'     => isset($order['side']) ? $order['side'] : '',
    'advanced' => isset($order['advanced']) ? $order['advanced'] : '',
  ];
});
