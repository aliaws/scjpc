<?php
function load_bootstrap_assets(): void {

  wp_enqueue_style('bootstrap_css', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
//  wp_enqueue_script('bootstrap_js', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js");


  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

  wp_enqueue_script('jquery');

  wp_enqueue_style('frontend_css', SCJPC_ASSETS_URL . 'css/frontend.css', false, '2.1');
  wp_enqueue_script('frontend_js', SCJPC_ASSETS_URL . 'js/frontend.js', false, '2.2', true);
}

/**
 * @param $date
 * @return false|string
 * December 24, 2023
 */
function change_date_format($date) {
  return date('F j, Y', strtotime($date));
}

function get_active_columns(): array {
  $active_columns = [];
  foreach (CHOICES as $checked_column) {
    $active_columns[$checked_column] = $checked_column;
  }
  return $active_columns;
}

function print_checkboxes($group): void {
  $active_columns = CHOICES ? get_active_columns() : '';
  foreach ($group as $key => $column) {
    if (CHOICES) {
      $checked = !empty($active_columns[$key]) ? 'checked' : '';
    } else {
      $checked = $column['default'] ? 'checked' : '';

    }
    if ($key != "all") {
      echo '<div  class="col-md-6"><label for="id_choices_' . $key . '"><input id="id_choices_' . $key . '" name="choices[]" type="checkbox" value="' . $key . '" ' . $checked . '> ' . $column['label'] . '</label></div>';
    } else {
      echo '<div class="col-md-6"><label for="id_choices_' . $key . '"><input id="id_choices_' . $key . '"  type="checkbox" value="all"> ' . $column['label'] . '</label></div>';
    }
  }
}

function add_download_query_vars_filter($vars){
    $vars [] = "download_scjpc";
    return $vars;
}
add_filter('query_vars', 'add_download_query_vars_filter');


function process_download_request() {
    $download_scjpc =  get_query_var('download_scjpc');
    if ($download_scjpc) {
        $client = new \Aws\S3\S3Client([
            'region'  => 'us-east-2',
            'version' => '2006-03-01',
            'credentials' => [
                'key'    => get_option('scjpc_aws_key'),
                'secret' => get_option('scjpc_aws_secret'),
            ]
        ]);

        try {

            // Retrieve the object from S3
            $result = $client->getObject([
                'Bucket' => 'scjpc-data',
                'Key'    => 6
            ]);

            // Set headers for file download
            header('Content-Type: ' . $result['ContentType']);
            header('Content-Disposition: attachment; filename="' . basename($download_scjpc) . '"');
            header('Content-Length: ' . $result['ContentLength']);

            // Output the file content
            echo $result['Body'];
            die;
        } catch (Aws\Exception\AwsException $e) {
            // Output error message if fails
            echo "Error downloading file: " . $e->getMessage();
            wp_die();
        }
    }
}
add_action('template_redirect', 'process_download_request');
