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


$columns = [
  'all' => [
    "label" => "All",

    "default" => true,
  ],
  "unique_id" => [
    "type" => "checkbox",
    "label" => "Unique ID",

    "default" => false,

  ],
  "pole_number" => [
    "label" => "Pole Number",
    "hidden" => false,
    "default" => false,
  ],
  "pole_replacement" => [
    "label" => "Pole Replacement",

    "default" => false,
  ],
  "longitude" => [
    "label" => "Longitude",

    "default" => false,
  ],
  "latitude" => [
    "label" => "Latitude",

    "default" => false,
  ],
  "pole_class" => [
    "label" => "Pole Class",
    "hidden" => false,
    "default" => false,
  ],
  "base_owner" => [
    "label" => "Base Owner",

    "default" => false,
  ],
  "location" => [
    "label" => "Location",

    "default" => false,
  ],
  "height" => [
    "label" => "Height",
    "hidden" => false,
    "default" => false,
  ],
  "top_extenstion" => [
    "label" => "Top Extenstion",

    "default" => false,
  ],
  "year_set" => [
    "label" => "Year Set",
    "hidden" => false,
    "default" => false,
  ],
  "treatment" => [
    "label" => "Treatment",
    "default" => false,
  ],
  "removal_relinquishment" => [
    "label" => "Removal/Relinquishment",
    "default" => false,
  ],
  "member_to_remove" => [
    "label" => "Member to remove pole/Member relinquished",
    "default" => false,
  ],
  "jpa_details" => [
    "label" => "JPA Details",
    "default" => false,
  ],
  "bill_of_sale" => [
    "label" => "Bill of Sale",
    "default" => false,
  ],
  "members_code" => [
    "label" => "Members code and Grade/Space info",
    "default" => false,
  ],
  "antenna_info" => [
    "label" => "Antenna info",
    "default" => false,
  ],
];
define("CHECK_BOXES_LABELS", $columns);

$extra_columns = [
  "jpa_details" => [
    "JPA Number",
    "JPA Number 2",
    "Billed Date",
  ],
  "members_code" => [
    "Company 1",
    "Company 2",
    "Company 3",
    "Company 4",
    "Company 5",
    "Company 6",
    "Company 7",
    "Company 8",
    "Company 9",
    "Company 10",
    "Company 1 GnS",
    "Company 2 GnS",
    "Company 3 GnS",
    "Company 4 GnS",
    "Company 5 GnS",
    "Company 6 GnS",
    "Company 7 GnS",
    "Company 8 GnS",
    "Company 9 GnS",
    "Company 10 GnS",
    "Anc For Company 1",
    "Anc For Company 2",
    "Anc For Company 3",
    "Anc For Company 4",
    "Anc For Company 5",
    "Anc For Company 6",
    "Anc For Company 7",
    "Anc For Company 8",
    "Anc For Company 9",
    "Anc For Company 10",
    "Anc For Company 1a",
    "Anc For Company 2a",
    "Anc For Company 3a",
  ],
  "antenna_info" => [
    "Antenna For Company 1",
    "Antenna For Company 2",
    "Antenna For Company 3",
    "Antenna For Company 4",
    "Antenna For Company 5",
    "Antenna For Company 6",
    "Antenna For Company 7",
    "Antenna For Company 8",
    "Antenna For Company 9",
    "Antenna For Company 10",
  ]
];

$total = 5;
$columns_values = [];

for ($i = 0; $i < $total; $i++) {
  $columns_values[$i] = [
    'unique_id' => uniqid('unique_id_'),
    'pole_number' => uniqid('pole_number_'),
    'pole_replacement' => uniqid('pole_replacement_'),
    'longitude' => uniqid('longitude_'),
    'latitude' => uniqid('latitude_'),
    'pole_class' => uniqid('pole_class_'),
    'base_owner' => uniqid('base_owner_'),
    'location' => uniqid('location_'),
    'height' => uniqid('height_'),
    'top_extenstion' => uniqid('top_extenstion_'),
    'year_set' => uniqid('year_set_'),
    'treatment' => uniqid('treatment_'),
    'removal_relinquishment' => uniqid('removal_relinquishment_'),
    'member_to_remove' => uniqid('member_to_remove_'),
    "jpa_details" => [
      "jpa_number" => uniqid('jpa_number_'),
      "JPA Number 2" => uniqid('JPA Number 2_'),
      "billed_date" => uniqid('billed_date'),
    ],
    'bill_of_sale' => uniqid('bill_of_sale_'),
    "members_code" => [
      "company_1" => uniqid('company_1_'),
      "company_2" => uniqid('company_2_'),
      "Company 3" => uniqid('Company 3_'),
      "company_4" => uniqid('company_4_'),
      "company_5" => uniqid('company_5_'),
      "company_6" => uniqid('company_6_'),
      "company_7" => uniqid('company_7_'),
      "company_8" => uniqid('company_8_'),
      "company_9" => uniqid('company_9_'),
      "company_10" => uniqid('company_10_'),
      "company_1_gn_s" => uniqid('company_1_gn_s_'),
      "company_2_gn_s" => uniqid('company_2_gn_s_'),
      "company_3_gn_s" => uniqid('company_3_gn_s_'),
      "company_4_gn_s" => uniqid('company_4_gn_s_'),
      "company_5_gn_s" => uniqid('company_5_gn_s_'),
      "company_6_gn_s" => uniqid('company_6_gn_s_'),
      "company_7_gn_s" => uniqid('company_7_gn_s_'),
      "company_8_gn_s" => uniqid('company_8_gn_s_'),
      "company_9_gn_s" => uniqid('company_9_gn_s_'),
      "company_10_gn_s" => uniqid('company_10_gn_s_'),
      "anc_for_company_1" => uniqid('anc_for_company_1_'),
      "anc_for_company_2" => uniqid('anc_for_company_2_'),
      "anc_for_company_3" => uniqid('anc_for_company_3_'),
      "anc_for_company_4" => uniqid('anc_for_company_4_'),
      "anc_for_company_5" => uniqid('anc_for_company_5_'),
      "anc_for_company_6" => uniqid('anc_for_company_6_'),
      "anc_for_company_7" => uniqid('anc_for_company_7_'),
      "anc_for_company_8" => uniqid('anc_for_company_8_'),
      "anc_for_company_9" => uniqid('anc_for_company_9_'),
      "anc_for_company_10" => uniqid('anc_for_company_10_'),
      "anc_for_company_1a" => uniqid('anc_for_company_1a_'),
      "anc_for_company_2a" => uniqid('anc_for_company_2a_'),
      "anc_for_company_3a" => uniqid('anc_for_company_3a_'),
    ],
    "antenna_info" => [
      "antenna_for_company_1" => uniqid('antenna_for_company_1_'),
      "antenna_for_company_2" => uniqid('antenna_for_company_2_'),
      "antenna_for_company_3" => uniqid('antenna_for_company_3_'),
      "antenna_for_company_4" => uniqid('antenna_for_company_4_'),
      "antenna_for_company_5" => uniqid('antenna_for_company_5_'),
      "antenna_for_company_6" => uniqid('antenna_for_company_6_'),
      "antenna_for_company_7" => uniqid('antenna_for_company_7_'),
      "antenna_for_company_8" => uniqid('antenna_for_company_8_'),
      "antenna_for_company_9" => uniqid('antenna_for_company_9_'),
      "antenna_for_company_10" => uniqid('antenna_for_company_10_'),
    ],
  ];
}

define("COLUMNS_VALUES", $columns_values);
define("EXTRA_COLUMNS_LABELS", $extra_columns);


