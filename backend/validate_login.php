<?php
/**
 * This hock is used for send recovery password mail if user create from csv 
 */
add_action('wp_login_failed', 'ads_check_failed_login_for_password_recovery');
function ads_check_failed_login_for_password_recovery($username)
{
    ads_send_password_recovery_email_on_incorrect_password($username);
}

function ads_send_password_recovery_email_on_incorrect_password($username)
{
    $user = get_user_by('email', $username);
    if (!$user) {
        $user = get_user_by('login', $username);
    }
    if ($user) {
        $csv_data = get_user_meta($user->ID, 'created_from_csv', true);

        if ($csv_data === '1') {
            $response = retrieve_password($user->user_login);
            if (is_wp_error($response)) {
                error_log('Error sending password recovery email: ' . $response->get_error_message());
            } else {
                error_log('Password recovery email sent successfully.');
                delete_user_meta($user->ID, 'created_from_csv');
            }
        }
    }
}
/**
 * This filter is used for create custom error messag if user create from csv 
 */
add_filter('authenticate', 'ads_authenticate_username_password', 25, 3);
function ads_authenticate_username_password($user, $username, $password)
{
    $getUser = get_user_by('email', $username);
    if (!$getUser) {
        $getUser = get_user_by('login', $username);
    }
    $csv_data = !is_bool($getUser) ? get_user_meta($getUser->ID, 'created_from_csv', true) : "";
    if ($csv_data === "1") {
        return new WP_Error(
            'incorrect_password',
            sprintf(
                __('<strong>Error:</strong> Please check your email at ' . $getUser->user_email . ' We have sent the password recovery to your email address.'),
            )
        );
    }
    return $user;
}