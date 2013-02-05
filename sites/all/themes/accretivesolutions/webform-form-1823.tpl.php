<?php

// $Id: webform-form-1823.tpl.php,v 1.7 2009/10/12 08:02:32 jessem Exp $



/**

 * @file

 * Customize the display of a complete webform.

 *

 * This file may be renamed "webform-form-[nid].tpl.php" to target a specific

 * webform on your site. Or you can leave it "webform-form.tpl.php" to affect

 * all webforms on your site.

 *

 * Available variables:

 * - $form: The complete form array.

 * - $nid: The node ID of the Webform.

 *

 * The $form array contains two main pieces:

 * - $form['submitted']: The main content of the user-created form.

 * - $form['details']: Internal information stored by Webform.

 */

?>

<?php	  

	$disable_fields = FALSE;

	if($_POST['term_id']) {

		$disable_fields = TRUE;

		$term_id = $_POST['term_id'];

		$node_id = $_POST['node_id'];

	} else {

		//get recipient value and check if its not empty

		if(!empty($form['submitted']['recipient']['#value'])) {

			$disable_fields = TRUE;



			$recipient_values = explode("|", $form['submitted']['recipient']['#value']);

			$node_id = $recipient_values[2];

			

			$office_info = node_load($recipient_values[1]);

			if (!empty($office_info)) {

			  $keys = array_keys($office_info->taxonomy);

			}

			

			$term_id = $office_info->taxonomy[$keys[0]]->tid;						

		}

	}

	

	$office_info = taxonomy_get_term($term_id); 

	$term_nodes = accretivesolutions_control_select_nodes(array($office_info->tid), 'or', 0, FALSE, 'n.title ASC', 'office');

	while($row = db_fetch_object($term_nodes)) {

		$term_node_info = node_load($row->nid);

	}

	

	$job_info = node_load($node_id);        

	

	if (!empty($job_info)) { 

	  //find job types

	  foreach($job_info->taxonomy as $term) {                            

		  $v_info = taxonomy_vocabulary_load($term->vid);                

		  if($v_info->name == 'Job Types') {

			  $type = $term->name;

		  }

	  } 



	  $form['submitted']['job_title']['#value'] = $job_info->title;	  

      $form['submitted']['job_title']['#attributes']['readonly'] = 'readonly';

	  

	  $form['submitted']['office']['#value'] = $office_info->name;

	  $form['submitted']['office']['#type'] = 'textfield';

      $form['submitted']['office']['#size'] = 60;

      $form['submitted']['office']['#maxlength'] = 128;

	  $form['submitted']['office']['#attributes']['readonly'] = 'readonly';

	  

	  $form['submitted']['recipient']['#value'] = $type.'|'.$term_node_info->nid.'|'.$job_info->nid;

	} else {

      if (empty($form['submission'])) {

        $form['submitted']['job_title']['#prefix'] = str_replace('">','" style="display:none;">',$form['submitted']['job_title']['#prefix']);

      }      

	}



	// If editing or viewing submissions, display the navigation at the top.

	if (isset($form['submission_info']) || isset($form['navigation'])) {

	  print drupal_render($form['navigation']);

	  print drupal_render($form['submission_info']);

	}

	

	// Print out the main part of the form.

	// Feel free to break this up and move the pieces within the array.

	print drupal_render($form['submitted']);

	

	// Always print out the entire $form. This renders the remaining pieces of the

	// form that haven't yet been rendered above.

	print drupal_render($form);

	

	// Print out the navigation again at the bottom.

	if (isset($form['submission_info']) || isset($form['navigation'])) {

	  unset($form['navigation']['#printed']);

	  print drupal_render($form['navigation']);

	}
