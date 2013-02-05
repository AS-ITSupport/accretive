<?php
// $Id: views-view-list--events-years.tpl.php,v 1.1 2009/07/23 09:10:36 jessem Exp $
/**
 * @file views-view-list.tpl.php
 * Default simple view template to display a list of rows.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $options['type'] will either be ul or ol.
 * @ingroup views_templates
 */
?>
<ul>
<?php if (!empty($title)) : ?>
  <?php $news_path = explode('?',$_SERVER['REQUEST_URI']);?>
  <li><a href="<?php print $news_path[0].'?year='.strip_tags($title); ?>"><?php print strip_tags($title); ?></a></li>
<?php endif; ?>
</ul>