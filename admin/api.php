<?php

add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/login', [
        'methods'  => 'POST',
        'callback' => 'custom_login_endpoint',
    ]);
});

function custom_login_endpoint($request): WP_Error|WP_REST_Response
{
    $creds = [];
    $creds['user_login'] = $request['username'];
    $creds['user_password'] = $request['password'];
    $creds['remember'] = true;

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        return new WP_Error('invalid_login', 'Invalid username or password', ['status' => 403]);
    }

    // Set the authentication cookies in the response
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    return new WP_REST_Response([
        'status' => 'success',
        'user_id' => $user->ID,
        'user_email' => $user->user_email,
        'user_nicename' => $user->user_nicename,
        'user_display_name' => $user->display_name,
    ]);
}
