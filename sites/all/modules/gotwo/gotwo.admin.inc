<?php
// $Id: gotwo.admin.inc,v 1.1.2.14 2009/08/26 18:53:11 hass Exp $

/**
 * @file
 * Administrative page callbacks for the gotwo module.
 */

/**
 * Implementation of hook_settings
 */
function gotwo_admin_settings_form() {
  $form['gotwo_numeric'] = array(
    '#type' => 'checkbox',
    '#title' => t('Numerical urls'),
    '#description' => t('Use numbers instead of a more friendlier url. "go/1234" instead of "go/some/location".'),
    '#default_value' => variable_get('gotwo_numeric', FALSE),
  );
  $form['gotwo_max_length'] = array(
    '#type' => 'textfield',
    '#title' => t('Maximum length of target labels'),
    '#description' => t('Target labels are the parts after the "go/" part of the shown url. The absolute maximum is 128.'),
    '#default_value' => min(variable_get('gotwo_max_length', 128), 128),
    '#size' => 10,
  );
  $form['gotwo_disclaimer_boolean'] = array(
    '#type' => 'checkbox',
    '#title' => t('Disclaimer'),
    '#description' => t('Check to add a disclaimer before redirecting to the Gotwo links'),
    '#default_value' => variable_get('gotwo_disclaimer_boolean', FALSE),
  );
  $form['gotwo_disclaimer_time'] = array(
    '#type' => 'select',
    '#title' => t('# of seconds until refresh'),
    '#options' => drupal_map_assoc(array(0, 1, 2, 3, 4, 5, 10, 15, 30, 45, 60)),
    '#description' => t('Number of seconds until the page will be redirected to the requested URL, 0 means no refresh'),
    '#default_value' => variable_get('gotwo_disclaimer_time', 0),
  );
  $form['gotwo_disclaimer_text'] = array(
    '#type' => 'textarea',
    '#title' => t('Disclaimer Text'),
    '#description' => t('The disclaimer that will be presented to the user before they are redirected<br /><strong>Variables available:</strong><br /> %url = url to be redirected to <br />%seconds = # of seconds until page redirects'),
    '#default_value' => variable_get('gotwo_disclaimer_text',''),
  );

  return system_settings_form($form);
}

/**
 * Shows the list of go redirects
 */
function _gotwo_list() {
  $access = user_access('edit gotwo redirects');

  $header = array(
    array('data' => t('ID'), 'field' => 'gid'),
    array('data' => t('Label'), 'field' => 'src'),
    array('data' => t('Destination'), 'field' => 'dst'),
    array('data' => t('Counter'), 'field' => 'cnt', 'sort' => 'desc'),
  );
  if ($access) {
    $header[] = array('data' => t('Operations'));
  }

  $sql = 'SELECT * FROM {gotwo}';
  $tablesort = tablesort_sql($header);
  $result = pager_query($sql . $tablesort, 50);

  $rows = array();
  while ($go = db_fetch_object($result)) {
    $i = count($rows);
    $rows[$i] = array(
      'data' => array(
        $go->gid,
        check_plain($go->src),
        check_plain($go->dst),
        $go->cnt
      )
    );
    if ($access) {
      $rows[$i]['data'][] = l(t('Reset'), 'admin/build/gotwo/reset/'. $go->gid, array('title' => t('Reset the counter'))) .' · '. l(t('Delete'), 'admin/build/gotwo/delete/'. $go->gid);
    }
  }

  if (!$rows) {
    $rows[] = array(array('data' => t('No redirects available.'), 'colspan' => ($access ? 5 : 4)));
  }

  $output = theme('table', $header, $rows);
  $output .= theme('pager', NULL, 50, 0);

  return $output;
}

/**
 * Create confirmation form for click counter item reset.
 */
function gotwo_reset_form($form_state, $edit) {
  $form['gid'] = array('#type' => 'value', '#value' => $edit->gid);

  return confirm_form(
    $form,
    t('Reset the counter for %label', array('%label' => $edit->src)),
    'admin/build/gotwo',
    '<p>'. t('Are you sure you want to reset the click counter for %label? This action cannot be undone.', array('%label' => $edit->src)) .'</p>',
    t('Reset'),
    t('Cancel')
  );
}

/**
 * Form submit handler for click counter item reset.
 */
function gotwo_reset_form_submit($form, &$form_state) {
  db_query("UPDATE {gotwo} SET cnt = %d WHERE gid = %d", 0, $form_state['values']['gid']);
  $form_state['redirect'] = 'admin/build/gotwo';
}

/**
 * Build delete form
 */
function gotwo_delete_form($form_state, $edit) {
  $form['gid'] = array('#type' => 'value', '#value' => $edit->gid);

  return confirm_form(
    $form,
    t('Delete go redirect %label', array('%label' => $edit->src)),
    'admin/build/gotwo',
    '<p>'. t('Are you sure you want to delete the go redirect %label? This action cannot be undone. The link will become broken, a new one might be automatically created when a node linking to it is edited.', array('%label' => $edit->src)) .'</p>',
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Delete redirect form submitted
 */
function gotwo_delete_form_submit($form, &$form_state) {
  db_query('DELETE FROM {gotwo} WHERE gid = %d', $form_state['values']['gid']);
  $form_state['redirect'] = 'admin/build/gotwo';
}

/**
 * Manually add a go redirect
 */
function gotwo_add_form(){

  $form['src'] = array(
    '#type' => 'textfield',
    '#title' => t('Label'),
    '#description' => t('The label used in the go url, this will automatically be made suitable.'),
    '#required' => TRUE,
  );
  $form['dst'] = array(
    '#type' => 'textfield',
    '#title' => t('Destination'),
    '#description' => t('The target url. Can be a relative drupal url or an absolute url.'),
    '#required' => TRUE,
    '#maxlength' => 255,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Add'),
  );
  return $form;
}

/**
 * Go redirect submitted
 */
function gotwo_add_form_submit($form, &$form_state) {
  $res = _gotwo_get($form_state['values']['dst'], $form_state['values']['src']);
  $form_state['redirect'] = 'admin/build/gotwo';
}
