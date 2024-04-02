<?php

if($_GET){
  if(!empty($_GET['call_method'])){
    define("RESULTS", search());
  }
}
function search() {

  if (function_exists($_GET['call_method'])) {
    return call_user_func($_GET['call_method'], $_GET);
  } else {
    echo "Function  does not exist.";
  }
}
function jpa_search() {
  return RECORDS;
}
function performMultpleJpASearch(){
  return RECORDS;
}

function performAdvancePoleSearch(){
  return RECORDS;
}

function peformQuickPoleSearch(){
  return RECORDS;
}

function peformWebsiteDocSearch(){
  return RECORDS;
}