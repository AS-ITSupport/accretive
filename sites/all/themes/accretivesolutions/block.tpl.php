<?php
// $Id: block.tpl.php,v 1.2 2008/12/04 15:47:46 iikka Exp $
?>
<div id="block-<?php print $block->module .'-'. $block->delta; ?>" class="block block-<?php print $block->module ?>">
  <?php if (!empty($block->title)): ?>
    <h2><?php print $block->title ?></h2>
  <?php endif;?>

  <div class="content">
    <?php print $block->content ?>
  </div>

</div>
