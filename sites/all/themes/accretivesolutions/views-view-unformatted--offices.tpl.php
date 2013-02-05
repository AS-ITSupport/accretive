<?php
// $Id: views-view-unformatted--offices.tpl.php,v 1.5 2009/07/29 01:23:07 jessem Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
 //get total row numbers
 $num_rows = count($rows);
 //get remaining set
 $rem_rows = $num_rows - 4;
 //initialize
 $ctr = 0;
 //check the items quantity
 $col2 =($num_rows%2==0?$num_rows-($rem_rows/2):($num_rows-floor($rem_rows/2)));
 //distribute items on their respective columns by markings
 $column_begin = array(1,5,($col2+1));
 $column_end = array(4,$col2,$num_rows);
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>

<?php foreach ($rows as $id => $row): ?>
  <?php $ctr++;?>
  <?php if (in_array($ctr,$column_begin)) print '<div class="container-offices"><div class="spacer">';?>
  <div class="<?php print $classes[$id]; ?>">
    <?php print $row; ?>
  </div>
  <?php if (in_array($ctr,$column_end)) print '</div></div>';?>
<?php endforeach; ?>
