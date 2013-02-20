<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for Accretive7.
 */


/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */

function accretive7_preprocess_html(&$variables, $hook) {
  $variables['classes_array'][] = drupal_html_class('page-home-variant-1');
}
