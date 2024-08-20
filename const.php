<?php
const SCJPC__FILE__ = __FILE__;
define('SCJPC_URL', plugins_url('/', SCJPC__FILE__));
const SCJPC_ASSETS_URL = SCJPC_URL . 'assets/';

define('SCJPC_PLUGIN_BASE', plugin_basename(SCJPC__FILE__));
define('SCJPC_PLUGIN_PATH', plugin_dir_path(SCJPC__FILE__));
const SCJPC_PLUGIN_FRONTEND_BASE = SCJPC_PLUGIN_PATH . 'frontend/';
const SCJPC_PLUGIN_BACKEND_BASE = SCJPC_PLUGIN_PATH . 'backend/';
const SCJPC_PLUGIN_ADMIN_BASE = SCJPC_PLUGIN_PATH . 'admin/';
const DISTANCES = [
  75 => '75 feet',
  150 => '150 feet',
  300 => '300 feet',
];
define("BASE_OWNERS", get_option('scjpc_base_owners'));
const POLES_KEYS = [
  'unique_id' => 'Unique ID', 'pole_number' => 'Pole Number', 'status' => 'Status', 'location' => 'Location',
  'city' => 'City', 'jpa_number_2' => 'JPA Number', 'billed_date' => 'Billed Date'
];
const JPAS_KEYS = [
  'jpa_unique_id' => 'Unique ID', 'jpa_number_2' => 'JPA Number 2', 'pdf_s3_key' => 'Scanned JPA',
  'date_received' => 'Date Received', 'billed_date' => 'Billed Date'
];

const JPAS_SORT_KEYS = [
  'jpa_number_2' => 'jpa_number_2', 'date_received' => 'date_received', 'billed_date' => 'jpa_number_2',
  'pdf_s3_key' => 'pdf_s3_key'
];

const POLE_SORT_KEYS = [
  'unique_id' => 'unique_id', 'pole_number' => 'pole_number', 'status' => 'status',
  'location' => 'location', 'jpa_number_2' => 'jpa_number_2', 'billed_date' => 'billed_date',
  'city' => 'city'
];

//define("CHOICES", !empty($_POST['choices']) ? $_POST['choices'] : ['unique_id', 'pole_number', 'status', 'location', 'city', 'jpa_number_2', 'billed_date']);
define("CHOICES", !empty($_REQUEST['choices']) ? $_REQUEST['choices'] : ['pole_number', 'status']);
const RESULTS_PER_PAGE = [25, 50, 100, 200];
const STRING_FILTER = ['contains' => 'Contains', 'exact' => 'Exact', 'begins_with' => 'Begins With'];
const STATUS_LABELS = ['A' => 'Active', 'I' => 'Inactive', 'D' => 'Dead'];

//icon
const EDIT_ICON = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
  <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
  <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
</svg>";