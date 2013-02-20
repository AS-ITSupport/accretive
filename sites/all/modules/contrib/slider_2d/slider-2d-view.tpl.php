<?php
// $Id: slider_2d-view.tpl.php,v 1.1 2009/03/24 08:28:26 juneeveek Exp $
/**
 * @file
 *  Slider 2d theme wrapper.
 *
 * @ingroup views_templates
 */
 $ctr = 1;
?>
<div id="pfNavigator">
	<div id="pfCategoryTop"></div>		
 	<div id="pfCategoryBottom"></div>		
  <div id="pfNavTop"><span></span></div>
  <div id="pfNavRight"><span></span></div>
  <div id="pfNavBottom"><span></span></div>																
  <div id="pfNavLeft"><span></span></div>							
  
	<div class="clear">&nbsp;</div>
  <div id="<?php print $slider_2d_id ?>" class="item-list slider_2d clear-block">
    <ul id="slidesList">
        <?php foreach ($rows as $row): ?>
          <!--<li id="container_php print $ctr ?>">php print $row ?></li> -->
          <li><?php print $row ?></li>
          <?php $ctr++; ?>
        <?php endforeach; ?>
    </ul>
  </div>
</div>

  