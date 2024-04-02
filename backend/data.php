<?php
$search_result = [
    "records" => [],
    "page" => 1,
    "total" => 25000,
    "result_per_page" => [
        50, 100, 200, 500, 1000
    ],
    "selected_per_page" => 50
];
for ($i = 0; $i < 100; $i++) {
    $record = [
        'id' => $i+1,
        'jpa_number' => 'E6073080' . $i,
        'scanned_jpa' => 'https://scjpc.nyc3.digitaloceanspaces.com/2009%20JPA/FEBRUARY%202009%20JPA/E6073-0800.pdf',
        'received_date' => date('Y-m-d', strtotime("-$i days")),
        'billed_date' => date('Y-m-d', strtotime("-$i days")),
    ];

    $search_result["records"][$i] = $record;
}
define("SEARCH_RESULT", $search_result);

$migration_logs = [];
for ($i = 0; $i < 100; $i++) {
    $log = [
        'id' => $i+1,
        'status' => array_rand(['Pending', 'Processing', 'Processed', 'Error'],1),
        'jpas_s3_key' => 'dev/2024-03-29 14:03:47/jpa.csv',
        'no_jpas_created' => rand(1,100),
        'no_jpas_updated' => rand(1,100),
        'poles_s3_key' => 'dev/2024-03-29 14:03:47/poles.csv',
        'no_poles_created' => rand(1,100),
        'no_poles_updated' => rand(1,100),
        'error' => '',
        'created_at' => date('Y-m-d', strtotime("-$i days")),
    ];

    $migration_logs[$i] = $log;
}
define("MIGRATION_LOGS", $migration_logs);