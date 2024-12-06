<?php
function create_users_from_csv_on_activation() {
  $csv_file = SCJPC_PLUGIN_PATH . 'scjpc_users.csv';

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

//register_activation_hook(SCJPC_PLUGIN_PATH. 'index.php', 'create_users_from_csv_on_activation');


function update_user_passwords_from_csv() {
  // Path to your CSV file
  $csv_file = SCJPC_PLUGIN_PATH . 'update_pass.csv';

  // Check if the file exists
  if (!file_exists($csv_file)) {
    error_log("CSV file not found: $csv_file");
    return;
  }

  // Open the CSV file
  if (($handle = fopen($csv_file, "r")) !== FALSE) {
    // Loop through the rows
    while (($data = fgetcsv($handle)) !== false) {
      $username = $data[0];
      $password = $data[1];

      if ($username == 'Username' && $password == 'Password') {
        continue;
      }
      // Get user by username
      $user = get_user_by('login', $username);

      // If user exists, update the password
      if ($user) {
        wp_set_password($password, $user->ID);
        delete_user_meta($user->ID, 'created_from_csv');
        error_log("Password updated for user: $username");
      } else {
        error_log("User not found: $username");
      }
    }
    // Close the CSV file
    fclose($handle);
  } else {
    error_log("Unable to open CSV file: $csv_file");
  }
}

//register_activation_hook(SCJPC_PLUGIN_PATH. 'index.php', 'update_user_passwords_from_csv');
