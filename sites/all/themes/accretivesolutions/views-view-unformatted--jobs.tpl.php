<?php
// $Id: views-view-unformatted--jobs.tpl.php,v 1.3 2009/09/14 07:19:35 jessem Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
		<h3>
			<?php
				//strip html tags
				print strip_tags($title); 
			?>
		</h3>
<?php endif; ?>
<ul>
<?php foreach ($rows as $id => $row): ?>
    <?php print $row; ?>
<?php endforeach; ?>
</ul>

