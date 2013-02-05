<?php
// $Id: location_views_handler_field_longitude.inc,v 1.1 2009/04/16 09:45:54 juneeveek Exp $

/**
 * @file
 * Longitude field handler.
 */

class location_views_handler_field_longitude extends location_views_handler_field_latitude {

  function render($values) {
    if ($this->options['style'] == 'dms') {
      return theme('location_longitude_dms', $values->{$this->field_alias});
    }
    return parent::render($values);
  }
}
