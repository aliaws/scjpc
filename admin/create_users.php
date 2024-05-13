<?php
function create_users_from_csv_on_activation()
{
    $csv_file = SCJPC_PLUGIN_PATH. 'scjpc_users.csv';

    if (!file_exists($csv_file)) {
        return;
    }
    if (($handle = fopen($csv_file, 'r')) !== false) {
        while (($data = fgetcsv($handle)) !== false) {
            if ($data[0] == 'Username') {
                continue;
            }
            $username = $data[0];
            $email = $data[1];
            $first_name = $data[2];
            $last_name = $data[3];
            $admin = $data[4]; // 0 or 1

            if (email_exists($email)) {
                continue;
            }

            $role = ($admin == 1) ? 'administrator' : 'subscriber';

            $user_data = array(
                'user_login' => $username,
                'user_email' => $email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'role' => $role,
            );

            $user_id = wp_insert_user($user_data);

            if (is_wp_error($user_id)) {
                continue;
            }

            add_user_meta($user_id, 'created_from_csv', true);
        }
        fclose($handle);
    }
}
register_activation_hook(SCJPC_PLUGIN_PATH. 'index.php', 'create_users_from_csv_on_activation');
