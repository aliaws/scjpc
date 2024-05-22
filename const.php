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
    'jpa_number_2' => 'jpa_number_2','date_received' => 'date_received','billed_date' => 'jpa_number_2',
    'pdf_s3_key' => 'pdf_s3_key'
];

const POLE_SORT_KEYS = [
    'unique_id' => 'unique_id','pole_number' => 'pole_number','status' => 'status',
    'location' => 'location','jpa_number_2' => 'jpa_number_2','billed_date' => 'billed_date',
    'city' => 'city'
];

define("CHOICES", !empty($_POST['choices']) ? $_POST['choices'] : ['unique_id', 'pole_number', 'status', 'location', 'city', 'jpa_number_2', 'billed_date']);
const RESULTS_PER_PAGE = [25, 50, 100, 200];
const STRING_FILTER = ['contains' => 'Contains', 'exact' => 'Exact', 'begins_with' => 'Begins With'];
const STATUS_LABELS = ['A' => 'Active', 'I' => 'Inactive', 'D' => 'Dead'];