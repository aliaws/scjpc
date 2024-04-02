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
        'id' => $i,
        'jpa_number' => 'E6073080' . $i,
        'scanned_jpa' => 'https://scjpc.nyc3.digitaloceanspaces.com/2009%20JPA/FEBRUARY%202009%20JPA/E6073-0800.pdf',
        'received_date' => date('Y-m-d', strtotime("-$i days")),
        'billed_date' => date('Y-m-d', strtotime("-$i days")),
    ];

    $search_result["records"][$i] = $record;
}
define("SEARCH_RESULT", $search_result);