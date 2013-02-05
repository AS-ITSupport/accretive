<?php
// $Id: views-view-field--events--title.tpl.php,v 1.3 2009/07/21 06:51:17 jessem Exp $
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
 $node_info = node_load($row->nid);	
?>
<?php
			  //initialize
				$formatted_title = '';
				//count the number of letters
				$num_letters = strlen($node_info->title);
				//search for multiple ampersand with space or converted already
				for($i=0;$i<$num_letters;$i++) {
					if ($node_info->title[$i]=='&' && $node_info->title[$i+1]!='a') {
						$formatted_title .= '&amp;';
					} else if ($node_info->title[$i]=='"') {
					  $formatted_title .= '&quot;';
					} else {
						$formatted_title .= $node_info->title[$i];
					}		
				}
				
			  print '<a href="/'.$node_info->path.'" title="'.$formatted_title.'">'.$formatted_title.'</a>';

?>