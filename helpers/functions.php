<?php
/**
 * Convert timestamp to "time ago" format
 * 
 * @param string $datetime Timestamp in MySQL format (Y-m-d H:i:s)
 * @return string Relative time string (e.g. "5 minutes ago")
 */
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    // Time units in seconds
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
    $month = $day * 30;
    $year = $day * 365;
    
    if ($diff < $minute) {
        return $diff < 5 ? 'just now' : $diff . ' seconds ago';
    } elseif ($diff < $hour) {
        $minutes = floor($diff / $minute);
        return $minutes == 1 ? '1 minute ago' : $minutes . ' minutes ago';
    } elseif ($diff < $day) {
        $hours = floor($diff / $hour);
        return $hours == 1 ? '1 hour ago' : $hours . ' hours ago';
    } elseif ($diff < $week) {
        $days = floor($diff / $day);
        return $days == 1 ? 'yesterday' : $days . ' days ago';
    } elseif ($diff < $month) {
        $weeks = floor($diff / $week);
        return $weeks == 1 ? '1 week ago' : $weeks . ' weeks ago';
    } elseif ($diff < $year) {
        $months = floor($diff / $month);
        return $months == 1 ? '1 month ago' : $months . ' months ago';
    } else {
        $years = floor($diff / $year);
        return $years == 1 ? '1 year ago' : $years . ' years ago';
    }
}