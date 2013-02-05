<?php
// $Id: views-view-unformatted--newsletter-archive.tpl.php,v 1.2 2009/06/01 06:11:22 juneeveek Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <font style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; color:#494955;"><?php print $title; ?></font>
<?php endif; ?>
<table border="0" cellpadding="0" cellspacing="0">
<?php foreach ($rows as $id => $row): ?>
    <?php print $row; ?>
<?php endforeach; ?>
	<tr>
  	<td colspan="2"><img src="<?php print url(path_to_theme(), array('absolute' => true)) ?>/images/spacer.gif" width="100" height="10" /></td>
	</tr>    
</table>
