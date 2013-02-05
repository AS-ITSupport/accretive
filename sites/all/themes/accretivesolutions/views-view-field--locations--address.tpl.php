<?php
// $Id: views-view-field--locations--address.tpl.php,v 1.1 2009/06/17 00:55:12 juneeveek Exp $
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
<?php print $output; ?>
<a href="#" onclick="javascript:document.getElementById('direction-form').style.display = 'block';">Get Directions</a>
<div id="direction-form">
  <form action="http://maps.google.com/maps" method="get" target="_blank">
    From: <input type="text" MAXLENGTH="100" name="saddr" id="saddr" value="" class="direction-address"  />
    <INPUT value="Go" TYPE="SUBMIT"><input type="hidden" name="daddr" value="<?php print $row->location_street.', '.$row->location_postal_code.', '.$row->location_province ?>"/>
  </form>
</div>