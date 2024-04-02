<?php
$records = [];
for ($i = 1; $i <= 100; $i++) {
    $record = [
        'id' => $i,
        'jpa_number' => 'E6073080' . $i,
        'scanned_jpa' => 'https://scjpc.nyc3.digitaloceanspaces.com/2009%20JPA/FEBRUARY%202009%20JPA/E6073-0800.pdf',
        'received_date' => date('Y-m-d', strtotime("-$i days")),
        'billed_date' => date('Y-m-d', strtotime("-$i days")),
    ];

    $records[] = $record;
}
define("RECORDS", [
    "total" => 100,
    "records" => $records,
]);