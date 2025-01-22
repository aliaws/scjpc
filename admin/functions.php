<?php
function time_ago($timestamp1, $timestamp2) {
  $difference = abs($timestamp1 - $timestamp2);
  if ($difference < 60) {
    return $difference . " second" . ($difference == 1 ? "" : "s");
  } elseif ($difference < 3600) {
    $minutes = floor($difference / 60);
    return $minutes . " minute" . ($minutes == 1 ? "" : "s");
  } elseif ($difference < 86400) {
    $hours = floor($difference / 3600);
    return $hours . " hour" . ($hours == 1 ? "" : "s");
  } elseif ($difference < 2592000) {
    $days = floor($difference / 86400);
    return $days . " day" . ($days == 1 ? "" : "s");
  } elseif ($difference < 31536000) {
    $months = floor($difference / 2592000);
    return $months . " month" . ($months == 1 ? "" : "s");
  } else {
    $years = floor($difference / 31536000);
    return $years . " year" . ($years == 1 ? "" : "s");
  }
}

function convert_date_time_format($datetime) {
  $datetime = substr_replace($datetime, ' ', 10, 1);
  $datetime = substr_replace($datetime, ':', 13, 1);
  $datetime = substr_replace($datetime, ':', 16, 1);
  return $datetime;
}

function getStatusStyles($status) {
  switch ($status) {
    case 'Processed':
      return [
        'style' => 'font-weight: bold',
        'class' => 'badge bg-success text-white'
      ];
    case 'Pending':
      return [
        'style' => 'font-style: italic',
        'class' => 'badge bg-info text-white'
      ];
    case 'Processing':
      return [
        'style' => 'font-style: italic',
        'class' => 'badge bg-primary text-white'
      ];
    default:
      return [
        'style' => '',
        'class' => ''
      ];
  }
}

function getStateStyles($state) {
  if ($state == "STARTED") {
    return [
      'style' => 'font-weight: bold',
      'class' => 'badge bg-success text-white'
    ];
  } else {
    return [
      'style' => 'font-style: italic',
      'class' => 'badge bg-warning text-white'
    ];
  }
}

function getStyles($state) {
  $style = "font-weight: bold; background-color: $state;";
  return [
    'style' => $style,
    'class' => 'badge text-dark',
  ];
}


add_action('add_attachment', 'scjpc_modify_attachment_title', 10, 1);
function scjpc_modify_attachment_title($attachment_id): void {
  // Get the attachment post object
  $attachment = get_post($attachment_id);

  // Ensure it's an attachment post type
  if ($attachment && 'attachment' === $attachment->post_type) {
    // Get the file path for the attachment
    $file_path = get_attached_file($attachment_id);

    // Get file info
    $file_info = pathinfo($file_path); // Get all the file information (name, extension, etc.)
    $file_name = $file_info['filename']; // File name without an extension
    $file_extension = $file_info['extension']; // File extension (e.g., 'jpg', 'pdf')
    $file_size = filesize($file_path);  // Get file size in bytes

    // Convert file size to human-readable format
    $human_readable_size = size_format($file_size, 2);  // Format it (e.g., 2 MB, 250 KB)
    $file_icon = scjpc_get_extension_icon($file_extension);

    // Construct the new title: fileName.extension | fileSize fileIcon
    $new_title = sprintf('%s  %s.%s (%s)', $file_icon, $file_name, $file_extension, $human_readable_size);

    // Update the title of the attachment
    wp_update_post([
      'ID' => $attachment_id,
      'post_title' => $new_title,
    ]);
  }
}

function scjpc_get_extension_icon($extension) {
  $file_icons = [
    'png' => '<img decoding="async" src="/wp-content/uploads/2024/11/image.png" alt="" width="16px">',      // Image (PNG)
    'jpeg' => '<img decoding="async" src="/wp-content/uploads/2024/11/image.png" alt="" width="16px">',     // Image (JPEG)
    'jpg' => '<img decoding="async" src="/wp-content/uploads/2024/11/image.png" alt="" width="16px">',      // Image (JPG)
    'webp' => '<img decoding="async" src="/wp-content/uploads/2024/11/image.png" alt="" width="16px">',      // Image (JPG)
    'gif' => '<img decoding="async" src="/wp-content/uploads/2024/11/gif.png" alt="" width="16px">',      // Image (GIF)
    'tiff' => '<img decoding="async" src="/wp-content/uploads/2024/11/tiff.png" alt="" width="16px">',     // Image (TIFF)
//    'xls' => '📊',       // Excel (XLS)
//    'xlsx' => '📊',      // Excel (XLSX)
    'xls' => '<img decoding="async" src="/wp-content/uploads/2024/11/excel.png" alt="" width="16px">',       // Excel (XLS)
    'xlsx' => '<img decoding="async" src="/wp-content/uploads/2024/11/excel.png" alt="" width="16px">',      // Excel (XLSX)
    'msexcel' => '<img decoding="async" src="/wp-content/uploads/2024/11/excel.png" alt="" width="16px">',      // Excel (XLSX)
    'csv' => '<img decoding="async" src="/wp-content/uploads/2024/11/csv.png" alt="" width="16px">',       // CSV (Excel)
//    'pdf' => '📄',       // PDF
    'pdf' => '<img decoding="async" src="/wp-content/uploads/2024/06/pdf.gif" alt="" width="16px">',       // PDF
    'mp4' => '🎥',       // Video (MP4)
    'avi' => '🎥',       // Video (AVI)
    'mov' => '🎥',       // Video (MOV)
    'mkv' => '🎥',       // Video (MKV)
    'mp3' => '🎵',       // Audio (MP3)
    'wav' => '🎵',       // Audio (WAV)
    'flac' => '🎵',      // Audio (FLAC)
    'aac' => '🎵',       // Audio (AAC)
    'ogg' => '🎵',       // Audio (OGG)
    'webm' => '🎥',      // Video (WEBM)
    'txt' => '📄',       // Text file (TXT)
//    'doc' => '📄',       // Word Document (DOC)
//    'docx' => '📄',      // Word Document (DOCX)
    'doc' => '<img decoding="async" src="/wp-content/uploads/2024/11/word.svg" alt="" width="16px">',       // Word Document (DOC)
    'docx' => '<img decoding="async" src="/wp-content/uploads/2024/11/word.svg" alt="" width="16px">',      // Word Document (DOCX)
    'msword' => '<img decoding="async" src="/wp-content/uploads/2024/11/word.svg" alt="" width="16px">',      // Word Document (DOCX)
//    'ppt' => '📈',       // PowerPoint (PPT)
    'ppt' => '<img decoding="async" src="/wp-content/uploads/2024/11/powerpoint.png" alt="" width="16px">',       // PowerPoint (PPT)
    'pptx' => '<img decoding="async" src="/wp-content/uploads/2024/11/powerpoint.png" alt="" width="16px">',      // PowerPoint (PPTX)
    'zip' => '📦',       // ZIP Archive
    'rar' => '📦',       // RAR Archive
    '7z' => '📦',        // 7z Archive
    'json' => '<img decoding="async" src="/wp-content/uploads/2024/11/json.png" alt="" width="16px">',      // JSON file
    'xml' => '<img decoding="async" src="/wp-content/uploads/2024/11/xml.png" alt="" width="16px">',       // XML file
    'html' => '<img decoding="async" src="/wp-content/uploads/2024/11/html.png" alt="" width="16px">',      // HTML file
    'css' => '🎨',       // CSS file
    'js' => '💻',        // JavaScript file
    'default' => '🗃️'
  ];
  return !empty($file_icons[$extension]) ? $file_icons[$extension] : $file_icons['default'];
}


// Step 1: Add 'Member Code' column
add_filter('manage_member_posts_columns', 'scjpc_add_member_code_column');
function scjpc_add_member_code_column($columns) {
  // Split the array into two parts: before and after the second-last position
  $before = array_slice($columns, 0, 2, true); // Get the elements before the last column
  $after = array_slice($columns, 2, null, true); // Get the last column

  // Add 'member_code' just before the last column
  return $before + ['member_code' => __('Member Code', 'scjpc')] + $after;
}

// Step 2: Display data in the 'Member Code' column
add_action('manage_member_posts_custom_column', 'scjpc_add_member_code_column_data', 10, 2);
function scjpc_add_member_code_column_data($column, $post_id): void {
  if ($column == 'member_code') {
    echo get_post_meta($post_id, 'member_code', true);
  }
}

// Step 3: Make the 'member_code' column sortable
add_filter('manage_edit-member_sortable_columns', 'scjpc_member_code_column_sortable');
function scjpc_member_code_column_sortable($columns) {
  $columns['member_code'] = 'member_code'; // Make the column sortable by meta_key 'member_code'
  return $columns;
}

// Step 4: Modify the query to sort by the 'member_code' meta field
add_action('pre_get_posts', 'scjpc_sort_member_code_column');
function scjpc_sort_member_code_column($query) {
  if (!is_admin() || !$query->is_main_query()) {
    return;
  }

  // Check if sorting by 'member_code' is requested
  if (isset($_GET['orderby']) && 'member_code' === $_GET['orderby']) {
    // Sort by the 'member_code' meta key
    $query->set('meta_key', 'member_code'); // The custom field (meta_key) to sort by
    $query->set('orderby', 'meta_value'); // Sort by meta_value
  }
}

function scjpc_internal_log( $message, $heading = '', $level = 'info' ): void {
  if ( gettype( $message ) != 'string') {
    $message = print_r( $message, true );
  }
  // Prepare the log entry
  $timestamp = date('Y-m-d H:i:s');
  $log_entry = sprintf("[%s] [%s] %s\n", $timestamp, strtoupper($level), $message) . "\n";

  // Write the log entry heading to the log file
  if ($heading != '') {
    $heading = sprintf("[%s] [%s] %s\n", $timestamp, strtoupper($level), $heading);
    file_put_contents(scjpc_get_internal_log_file(), $heading, FILE_APPEND);
  }
  // Write the log entry to the log file
  file_put_contents(scjpc_get_internal_log_file(), $log_entry, FILE_APPEND);
}
function scjpc_get_internal_log_file(): string {
  return sprintf( "%s/%s.log", SCJPC_PLUGIN_PATH, date('Y-F-d' ) );
}
