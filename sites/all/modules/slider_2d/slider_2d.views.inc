<?php
// $I$


/**
  * Implementation of hook_views_plugin().
  */
function slider_2d_views_plugins() {
  return array(
    'module' => 'slider_2d',
    'style' => array(
      'slider_2d' => array(
        'title' => t('2d Slider'),
        'theme' => 'slider_2d_view',
        'help' => t('Display results in a carousel using jcarousel Lite.'),
        'handler' => 'slider_2d_style_plugin',
        'uses row plugin' => TRUE,
        'uses options' => TRUE,
        'type' => 'normal',
      ),
    /*'row' => array(
      'slider_2d' => array(
        'title' => t('Profile Fields'),
        'help' => t('Displays the fields with profile field privacy settings.'),
        'handler' => 'slider_2d_style_plugin_row',
        'parent' => 'fields',
        'theme' => 'slider_2d_views_row',
        'uses fields' => TRUE,
        'uses options' => TRUE,
        'type' => 'normal',      )
     ),  						*/
    ),
  );
}