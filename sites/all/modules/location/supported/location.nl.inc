<?php
// $Id: location.nl.inc,v 1.1 2009/04/16 09:47:13 juneeveek Exp $

// Netherlands

function location_province_list_nl() {
  return array(
    'DR' => "Drenthe",
    'FL' => "Flevoland",
    'FR' => "Friesland",
    'GE' => "Gelderland",
    'GR' => "Groningen",
    'LI' => "Limburg",
    'NB' => "Noord Brabant",
    'NH' => "Noord Holland",
    'OV' => "Overijssel",
    'UT' => "Utrecht",
    'ZE' => "Zeeland",
    'ZH' => "Zuid Holland",
  );
}

function location_map_link_nl_providers() {
  return array(
    'google' => array(
      'name' => 'Google Maps',
      'url' => 'http://maps.google.nl',
      'tos' => 'http://www.google.nl/help/terms_maps.html',
    ),
  );
}

function location_map_link_nl_default_providers() {
  return array('google');
}

function location_map_link_nl_google($location = array()) {
  $query_params = array();

  foreach (array('street', 'city', 'postal_code', 'country') as $field) {
    if (isset($location[$field])) {
      $query_params[] = $location[$field];
    }
  }

  if (count($query_params)) {
    return ('http://maps.google.nl?q='. urlencode(implode(', ', $query_params)));
  }
  else {
    return NULL;
  }
}