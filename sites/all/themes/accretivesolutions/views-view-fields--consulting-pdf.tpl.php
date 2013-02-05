<?php
// $Id: views-view-fields--consulting-pdf.tpl.php,v 1.5 2009/09/09 01:40:12 jessem Exp $
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->separator: an optional separator that may appear before a field.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
  $node_info = (node_load($row->nid));
?>
<?php
  //initialize
	$output = '';
  //get item count
	$item_num = $node_info->field_page_items[0]['value'];
	//set loop count
	$loop_items =  ($item_num==''?count($node_info->field_page_title):$item_num);
	//set clear
	$output .= '<br/>';
	//display the pdf files
	for($i=0;$i<$loop_items;$i++) {
	  $file_path = "'http://".$_SERVER['HTTP_HOST']."/".$node_info->field_page_pdf[$i]['filepath']."'";
 	  $output .= '<div class="link_spacer"><a href="#" onclick="javascript:window.open('.$file_path.')" >'.$node_info->field_page_title[$i]['value'].'</a></div>';	  	    }

	//display items
	print $output;
?>
 

