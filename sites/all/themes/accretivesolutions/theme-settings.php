<?php
/**
* Implementation of THEMEHOOK_settings() function.
*
* @param $saved_settings
*   array An array of saved settings for this theme.
* @return
*   array A form array.
*/
function accretivesolutions_settings($saved_settings) {
  /*
   * The default values for the theme variables. Make sure $defaults exactly
   * matches the $defaults in the template.php file.
   */
  $defaults = array(
    'headquarters_office' => 279,
  );

  // Merge the saved variables and their default values
  $settings = array_merge($defaults, $saved_settings);
	
  $form['headquarters_office'] = array(
    '#type' => 'textfield',
    '#title' => 'Node ID for Headquarters Office',
    '#description' => 'Please enter the node id of the headquarters office. This value is used to set default email routing to this office.',
    '#default_value' => $settings['headquarters_office'],
  );
	
  return $form;
}