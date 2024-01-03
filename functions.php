<?php
// Define the log file paths
$log_files = [];

foreach ($services as $service) {
    $log_files[$service['name']] = str_replace(' ', '_', strtolower($service['name'])) . '_status_log.txt';
}

// Function to check the status of a URL using cURL
function check_url_status($url, $method = 'GET', $data = null) {
    if ( empty( $url ) ) {
        return false;
    }

    $ch = curl_init($url);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_HEADER, true); // Include headers in the response
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);

    // Get the effective URL after following redirects
    $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

    // Get the headers from the response
    $headers = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));

    // Extract the final HTTP request method from the headers
    preg_match('/^HTTP\/\d+\.\d+\s+(\w+)/', $headers, $matches);
    $new_method = isset($matches[1]) ? strtoupper($matches[1]) : '';

    // Check if the effective URL is different from the original URL
    if ($effectiveUrl !== $url) {
        check_url_status($effectiveUrl, $new_method);
    }

    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($status_code === 200) {
        return true; // URL is up
    }

    return false; // URL is down or unreachable
}

// Function to log URL status
function log_url_status($name, $status, $log_file) {
    // Read the existing entries in the log file
    $existing_entries = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    // Extract the status and day from the last entry
    $last_entry = reset($existing_entries);
    $last_parts = array_map('trim', explode('|', $last_entry));
    $last_status = end($last_parts);
    $last_day = date('Y-m-d', strtotime(reset($last_parts))); // Get the date part of the last entry

    // Get the current day
    $current_day = date('Y-m-d');

    // Only log the status if it has changed or if it's a new day
    if ( trim( $last_status ) !== $status || $last_day !== $current_day) {
        // Construct the new log entry
        $log_entry = date('Y-m-d H:i:s') . " | $name | $status";

        // Add the new entry at the beginning of the existing entries
        array_unshift($existing_entries, $log_entry);

        // Write the updated entries back to the log file
        file_put_contents($log_file, implode("\n", $existing_entries));

        // If the status is down, send an email notification
        if ($status === 'DOWN') {
            $recipient_email = 'sebastien@cocartapi.com'; // Set your recipient email address
            $subject         = 'URL Status Alert';
            $message         = "The status of $name is DOWN.\n\nLog Entry: $log_entry";
            $headers         = 'From: hello@cocartapi.com'; // Set your email address

            mail($recipient_email, $subject, $message, $headers);
        }
    }
}

// Function to get the last status for each service
function get_last_status_for_services($log_file) {
    // Read the existing entries in the log file
    $existing_entries = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    $last_statuses = [];

    // Loop through each entry to get the last status for each service
    foreach ($existing_entries as $entry) {
        $parts = array_map('trim', explode('|', $entry));
        $service = $parts[1];
        $status = $parts[2];

        // Only update the last status for each service
        $last_statuses[$service] = $status;
    }

    return $last_statuses;
}

// Get the last status for each service
$last_statuses = get_last_status_for_services($log_file);

// Set the last request timestamp file
$last_request_file = 'last_request.txt';

// Check if the last request was made in the last minute
$last_request_time = file_get_contents($last_request_file);
$current_time = time();
$one_minute_ago = $current_time - 60;

// Check the status of each service
$statuses = [];

foreach ($services as $service) {
    // Make only requests if less than a minute
    if ($last_request_time <= $one_minute_ago) {
        $url    = $service['url'];
        $method = $service['method'];
        $data   = $service['data'];

        $status = check_url_status($url, $method, $data);

        // Log the current service status
        log_url_status($service['name'], $status ? 'UP' : 'DOWN', $log_files[$service['name']]);

        // Update the last request timestamp
        file_put_contents($last_request_file, $current_time);
    } else {
        $status = 'UP';
    }

    $statuses[$service['name']] = $status;
}

// Read the log files and get all incidents grouped by day for the last 7 days
$all_incidents_by_day = [];

foreach ($services as $service) {
    $name = $service['name'];
    $log_content = file_get_contents($log_files[$name]);
    $log_entries = array_filter(explode("\n", $log_content));

    foreach ($log_entries as $entry) {
        $timestamp = strtotime(explode(" | ", $entry)[0]);
        $day = date('Y-m-d', $timestamp);

        if ($timestamp >= strtotime('-' . $days_to_display . ' days')) {
            if (!isset($all_incidents_by_day[$day])) {
                $all_incidents_by_day[$day] = [];
            }

            $all_incidents_by_day[$day][] = $entry;
        }
    }
}

$all_statuses_today = array_column($all_incidents_by_day[date('Y-m-d')] ?? [], ' | ');

// Determine the global status message
$global_status_message = empty($all_statuses_today) ? 'No problems detected' : (in_array('DOWN', $all_statuses_today) ? 'Problem detected' : 'No problems detected');
