<?php
// $Id: views-view-field--office--address.tpl.php,v 1.1 2009/05/29 07:31:20 juneeveek Exp $
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
<font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#494955;">
	<?php
    $output = str_replace('</div>', '<br>', $output);
    print strip_tags($output,'<br>'); 
  ?>
</font>