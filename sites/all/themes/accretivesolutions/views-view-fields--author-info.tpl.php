<?php
// $Id: views-view-fields--author-info.tpl.php,v 1.2 2009/07/21 02:55:40 rommelm Exp $
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
<table cellpadding="0" cellspacing="0">
<?php foreach ($fields as $id => $field): ?>
	<tr>
  	<?php
			if($field->class == 'title') {
				$style = 'font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#494955;';
			} elseif($field->class == 'nothing') {				
				$style = 'font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; color:#494955;';				
			} else {				
				$style = 'font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; color:#494955;';
			}
    	
		?>
		<td style="<?php print $style ?>">    
		<?php print $field->content; ?></td>
  </tr>
  <tr>
    <td><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="10"  alt=""/></td>
  </tr> 
<?php endforeach; ?>
</table>
