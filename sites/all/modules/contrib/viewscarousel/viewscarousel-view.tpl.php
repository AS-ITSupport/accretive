<?php
// $Id: viewscarousel-view.tpl.php,v 1.1 2009/03/24 08:32:29 juneeveek Exp $
/**
 * @file
 *  Views Carousel theme wrapper.
 *
 * @ingroup views_templates
 */
?>
<div class="item-list viewscarousel clear-block">
  <ul id="<?php print $viewscarousel_id ?>" class="<?php print $viewscarousel_class ?>">
    <?php foreach ($rows as $row): ?>
      <li><?php print $row ?></li>
    <?php endforeach; ?>
  </ul>
</div>