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