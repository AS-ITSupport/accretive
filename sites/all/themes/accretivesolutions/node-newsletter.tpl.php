<?php
// $Id: node-newsletter.tpl.php,v 1.1 2009/06/15 06:54:29 juneeveek Exp $
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">


  <div class="content">
    <?php print theme('image', $header_img_path) ?>
    <div class="newsletter-introduction"><?php print $introduction ?></div>
    <div class="newsletter-published-date"><?php print $published_date ?></div>
		<div class="body"><?php print $body ?></div>
  </div>
  <div class="clear"></div>

</div>
