<?php

function search($request) {
  $action = isset($request['action']) ? $request['action'] : ''; // Check if 'action' key exists
  $data = call_user_func_array('perform_'.$action, [$request]);
  $data["per_page"] = $_GET["per_page"];
  $data["page_number"] = $_GET["page_number"];
  return $data;
}
function perform_jpa_search($request) {
    //api calling curl
  return SEARCH_RESULT;
}
function perform_multiple_jpa_search($request){
  return SEARCH_RESULT;
}

function perform_advance_pole_search($request){
  return SEARCH_RESULT;
}

function perform_quick_pole_search($request){
  return POLE_RESULT;
}

function perform_website_doc_search($request){
  return SEARCH_RESULT;
}


function get_migration_logs() {
    return MIGRATION_LOGS;
}
function get_pole_result() {
    return POLE_RESULT;
}