<?php
// $Id: views-view-fields--newsletter-archive.tpl.php,v 1.1 2009/05/29 07:31:20 juneeveek Exp $
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
?>
<?php foreach ($fields as $id => $field): ?>
	<tr>
  	<td><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/square_bullet.jpg" /></td>
  	<td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#494955;"><?php print $field->content; ?></td>
	</tr>    
<?php endforeach; ?>
