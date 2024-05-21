<?php
function load_bootstrap_assets(): void {
    wp_enqueue_style('bootstrap_css', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");

    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

    wp_enqueue_script('jquery');

    wp_enqueue_style('frontend_css', SCJPC_ASSETS_URL . 'css/frontend.css', false, '4.1');
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

function add_download_query_vars_filter($vars) {
    $vars [] = "download_scjpc";
    $vars [] = "search_attachments";
    return $vars;
}

add_filter('query_vars', 'add_download_query_vars_filter');


function process_download_request() {

    $download_scjpc = get_query_var('download_scjpc');
    if ($download_scjpc) {
        $client = new \Aws\S3\S3Client([
            'region' => 'us-east-2',
            'version' => '2006-03-01',
            'credentials' => [
                'key' => get_option('scjpc_aws_key'),
                'secret' => get_option('scjpc_aws_secret'),
            ]
        ]);

        try {

            // Retrieve the object from S3
            $result = $client->getObject([
                'Bucket' => 'scjpc-data',
                'Key' => $download_scjpc
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

function ads_posts_where($where, &$wp_query) {

    global $wpdb;
    $where .= ' AND (0 = 0 ';
    if ($search = $wp_query->get('search_attachment')) {
        $where .= " AND ( " . $wpdb->posts . ".post_title LIKE '%" . esc_sql($wpdb->esc_like($search)) . "%'";
        $where .= " OR " . $wpdb->posts . ".post_content LIKE '%" . esc_sql($wpdb->esc_like($search)) . "%' ) ";
    }
    $where .= ' )';
    return $where;
}

function search_attachments() {
    $search_attachments = get_query_var('search_attachments');
    if ($search_attachments) {
        $search_param = 'Salvage';
        add_filter('posts_where', 'ads_posts_where', 10, 2);
        $args = [
            'post_type' => 'attachment',
            'posts_per_page' => add_filter( 'posts_where', 'ads_posts_where', 10, 2 ),
            'search_attachment' => $search_param,
            'tax_query' => [
                'relation' => 'AND',
                [
                    'taxonomy' => 'bwdmfmx_mediafiles_category',
                    'field' => 'term_id',
                    'terms' => [15, 16, 17, 18, 19, 20]
                ],
            ],
            'post_status' => 'inherit',
        ];
        $query = new WP_Query($args);
        echo "<pre>";
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                echo 'Title: ' . get_the_title() . '<br>';
                echo 'URL: ' . wp_get_attachment_url(get_the_ID()) . '<br>';
                echo 'MIME Type: ' . get_post_mime_type() . '<br>';
                $terms = get_the_terms(get_the_ID(), 'bwdmfmx_mediafiles_category');
                print_r($terms);
                echo "--------------------------<br/>";

            }
        } else {
            echo 'No posts found.';
        }
        wp_reset_postdata();
        die;
    }
}


function search_web_and_docs($query) {
    $query->set('post_type', 'attachment');
    $args = [
        'post_type' => 'attachment',
        'posts_per_page' => 500,
        'tax_query' => [
            'relation' => 'AND',
            [
                'taxonomy' => 'bwdmfmx_mediafiles_category',
                'field' => 'term_id',
                'terms' => [15, 16, 17, 18, 19, 20]
            ],
        ],
        'post_status' => 'inherit',
    ];

    if (!empty($_REQUEST['s'])) {
        add_filter('posts_where', 'ads_posts_where', 10, 2);
        $args['s'] = $_REQUEST['s'];
    }
    foreach ($args as $key => $val) {
        $query->set($key, $val);
    }
}

add_action('elementor/query/search_web_doc', 'search_web_and_docs');

add_action('template_redirect', 'process_download_request');
add_action('template_redirect', 'search_attachments');


add_shortcode('post_url_change', 'post_url_change');
function post_url_change() {
    global $post;
    if ($post->post_type == 'attachment') {
        return $post->guid;
    } else {
        return get_permalink($post);
    }
}
