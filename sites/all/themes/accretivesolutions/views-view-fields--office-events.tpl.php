<?php

// $Id: views-view-fields--office-events.tpl.php,v 1.2 2009/10/23 02:52:35 jessem Exp $

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

 $node_info = node_load($row->nid);

?>

<?php foreach ($fields as $id => $field): ?>

  <?php if (!empty($field->separator)): ?>

    <?php print $field->separator; ?>

  <?php endif; ?>



  <<?php print $field->inline_html;?> class="views-field-<?php print $field->class; ?>">

    <?php if ($field->label): ?>

      <label class="views-label-<?php print $field->class; ?>">

        <?php print $field->label; ?>:

      </label>

    <?php endif; ?>

    <?php

      // $field->element_type is either SPAN or DIV depending upon whether or not

      // the field is a 'block' element type or 'inline' element type.

      if ($id=='title') {

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

          $field->content = '<a href="/'.$node_info->path.'" title="'.$formatted_title.'">'.$formatted_title.'</a>';

      }

      //remove all day word for date field

      if ($id=='field_event_date_value') {

        $field->content = str_replace(' (All day)','',$field->content);

      }        

  ?>

      <<?php print $field->element_type; ?> class="field-content"><?php print $field->content; ?></<?php print $field->element_type; ?>>

  </<?php print $field->inline_html;?>>

<?php endforeach; ?>