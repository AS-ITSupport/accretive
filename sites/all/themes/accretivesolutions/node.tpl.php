<?php
// $Id: node.tpl.php,v 1.3 2009/03/19 21:51:00 iikka Exp $
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

  <?php print $picture ?>

  <?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>

  <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted; ?></span>
  <?php endif; ?>

  <div class="content">
    <?php print $content ?>
  </div>
  <div class="clear"></div>
  <?php if ($links||$taxonomy){ ?>
    <div>

      <?php if ($links): ?>
        <div class="links">
          <?php print $links; ?>
        </div>
      <?php endif; ?>
       <div class="clear"></div>
      <?php if ($node->type !='industry') :?>      
				<?php if ($taxonomy): ?>
          <div class="terms">
            <?php 						  
							if ($node->type == 'as_involved') $terms = '<span class="term-office">Office:</span> '.strip_tags($terms);
							print $terms 
						?>
          </div>
        <?php endif;?>
      <?php endif;?>

       <div class="clear"></div>

    </div>
  <?php }?>

</div>
