<?php
const SCJPC__FILE__ = __FILE__;
define('SCJPC_URL', plugins_url('/', SCJPC__FILE__));
const SCJPC_ASSETS_URL = SCJPC_URL . 'assets/';
define('SCJPC_PLUGIN_BASE', plugin_basename(SCJPC__FILE__));
define('SCJPC_PLUGIN_PATH', plugin_dir_path(SCJPC__FILE__));
const SCJPC_PLUGIN_FRONTEND_BASE = SCJPC_PLUGIN_PATH . "frontend/";
const SCJPC_PLUGIN_BACKEND_BASE = SCJPC_PLUGIN_PATH . "backend/";
const SCJPC_PLUGIN_ADMIN_BASE = SCJPC_PLUGIN_PATH . "admin/";
