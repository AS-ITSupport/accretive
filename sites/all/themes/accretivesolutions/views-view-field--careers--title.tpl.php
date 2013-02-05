<?php
// $Id: views-view-field--careers--title.tpl.php,v 1.2 2009/08/07 08:25:30 jessem Exp $
 /**
  * This template is used to print a single field in a view. It is not
  * actually used in default Views, as this is registered as a theme
  * function which has better performance. For single overrides, the
  * template is perfectly okay.
  *
  * Variables available:
  * - $view: The view object
  * - $field: The field handler object that can process the input
  * - $row: The raw SQL result that can be used
  * - $output: The processed output that will normally be used.
  *
  * When fetching output from the $row, this construct should be used:
  * $data = $row->{$field->field_alias}
  *
  * The above will guarantee that you'll always get the correct data,
  * regardless of any changes in the aliasing that might happen if
  * the view is modified.
  */
?>

<?php 

				//initialize
				$formatted_title = '';
				//count the number of letters
				$num_letters = strlen($row->node_title);
				//search for multiple ampersand with space or converted already
				for($i=0;$i<$num_letters;$i++) {
					if ($row->node_title[$i]=='&' && $row->node_title[$i+1]!='a') {
						$formatted_title .= '&amp;';
					} else if ($row->node_title[$i]=='"') {
					  $formatted_title .= '&quot;';
					} else {
						$formatted_title .= $row->node_title[$i];
					}		
				}
				
			  print '<a href="/'.drupal_get_path_alias('node/'.$row->nid).'" title="'.$formatted_title.'">'.$formatted_title.'</a>';
				
?>