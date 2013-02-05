<?php
/**
 * @file
 * Contains the list style plugin.
 */

/**
  * Implementation of views_plugin_style().
  */
class slider_2d_style_plugin extends views_plugin_style {
  function option_definition() {
    $options = parent::option_definition();
    $options['mousewheel'] = array('default' => NULL);
		$options['speed'] = array('default' => NULL);
    return $options;
  }
  
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['mousewheel'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use Mousewheel'),
      '#default_value' => $this->options['mousewheel'],
      '#description' => t('If set, mousewheel scrolling would be possible.'),
    );
    $form['speed'] = array(
      '#type' => 'radios',
      '#title' => t('Sliding Speed'),
      '#default_value' => $this->options['speed'],
      '#description' => t('Speed of sliding animation'),
			'#options' => array('slow' => t('Slow'), 'normal' => t('Normal'), 'fast' => t('Fast')),
    );		
  } 
}