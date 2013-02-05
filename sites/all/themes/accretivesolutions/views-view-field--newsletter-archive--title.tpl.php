<?php
// $Id: views-view-field--newsletter-archive--title.tpl.php,v 1.1 2009/05/29 07:31:20 juneeveek Exp $
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
	$link = drupal_get_path_alias('node/'.$row->nid);
	$options['attributes']['style'] = 'text-decoration:none; color:#008530;';
	print l($row->node_title, url($link, array('absolute' => true)), $options);
?>