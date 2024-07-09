<?php
function time_ago($timestamp1, $timestamp2) {
  $difference = abs($timestamp1 - $timestamp2);
    if($difference < 60) {
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
    if($state == "STARTED"){
        return [
            'style' => 'font-weight: bold',
            'class' => 'badge bg-success text-white'
        ];
    }else{
        return [
            'style' => 'font-style: italic',
            'class' => 'badge bg-info text-white'
        ];
    }
}
?>