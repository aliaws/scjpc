<?php
$search_result = [
  "records" => [],
  "total" => 25000,
  "result_per_page" => [
    50, 100, 200, 500, 1000
  ],
];
$total = isset($_GET['per_page']) ? $_GET['per_page'] : 0;
for ($i = 0; $i < $total; $i++) {
  $record = [
    'id' => $i + 1,
    'jpa_number' => 'E6073080' . $i,
    'scanned_jpa' => 'https://scjpc.nyc3.digitaloceanspaces.com/2009%20JPA/FEBRUARY%202009%20JPA/E6073-0800.pdf',
    'received_date' => date('Y-m-d', strtotime("-$i days")),
    'billed_date' => date('Y-m-d', strtotime("-$i days")),
  ];

  $search_result["records"][$i] = $record;
}
define("SEARCH_RESULT", $search_result);

$migration_logs = [];
for ($i = 0; $i < 20; $i++) {
  $log = [
    'id' => $i + 1,
    'status' => array_rand(['Pending', 'Processing', 'Processed', 'Error'], 1),
    'jpas_s3_key' => 'dev/2024-03-29 14:03:47/jpa.csv',
    'no_jpas_created' => rand(1, 100),
    'no_jpas_updated' => rand(1, 100),
    'poles_s3_key' => 'dev/2024-03-29 14:03:47/poles.csv',
    'no_poles_created' => rand(1, 100),
    'no_poles_updated' => rand(1, 100),
    'error' => '',
    'created_at' => date('Y-m-d', strtotime("-$i days")),
  ];

  $migration_logs[$i] = $log;
}
define("MIGRATION_LOGS", $migration_logs);

$pole_result = [
  'pole_number' => '177593E',
  'status' => 'active',
  'unique_id' => '2302698',
  'base_owner' => 'E',
  'location' => "MAIN ST PL/W 298' S/O CHESTNUT ST",
  'latitude' => '33.741503',
  'class' => '2',
  'longitude' => '-117.86821',
  'pole_height' => '45',
  'top' => '0.0',
  'year_set' => '2016',
  'treatment' => 'FT',
  'city' => 'SANTA ANA',
  'code' => 'awe43',
  'repl_pole' => '177593E',
  'jpa_number' => ['E6029407656246', '177593E'],
  'bill_of_sale' => 'Pole BOS: Pole: 177593E - 5/19:  E BILL H $2275:',
  "companies" => [
    1 => [
      "company" => "A",
      "ant" => true, //ANT
      "gns" => "12K 43-13", // Grade & Space
      "anc" => "" // Additional Info
    ],
    2 => [
      "company" => "E",
      "ant" => false, //ANT
      "gns" => "12K 43-13", // Grade & Space
      "anc" => ".50:A1" // Additional Info
    ]
  ],
  'jpa_numbers' => [
    1 => [
      'jpa_number' => 'E6029407656246',
      'jpa_number_url' => 'https://scjpc.net/search/jpa/details/E6029407656246/?ref=%2Fsearch%2Fpole%2Fquick%2F%3Fq%3D177593E',
      'jpa_number_pdf' => 'https://scjpc.nyc3.digitaloceanspaces.com/2019%20JPA/MAY%202019%20JPA/E6029-407656246_FB.pdf',
    ],
    2 => [
      'jpa_number' => 'E6029407656246',
      'jpa_number_url' => 'https://scjpc.net/search/jpa/details/E6029407656246/?ref=%2Fsearch%2Fpole%2Fquick%2F%3Fq%3D177593E',
      'jpa_number_pdf' => 'https://scjpc.nyc3.digitaloceanspaces.com/2019%20JPA/MAY%202019%20JPA/E6029-407656246_FB.pdf',
    ]
  ]
];

define("POLE_RESULT", $pole_result);
