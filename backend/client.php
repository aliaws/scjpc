<?php

function search($request) {
    $data = perform_jpa_search($request);
    $data["per_page"] = $_GET["per_page"];
    $data["page"] = $_GET["page"];
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
  return SEARCH_RESULT;
}

function perform_website_doc_search($request){
  return SEARCH_RESULT;
}