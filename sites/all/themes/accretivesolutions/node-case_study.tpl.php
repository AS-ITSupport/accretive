<?php
// $Id: node-case_study.tpl.php,v 1.1 2009/06/19 11:43:48 jessem Exp $
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
        <?php			
				   //get keys
				  $taxonomy_keys = (array_keys($taxonomy));					
					$terms = '<ul class="links">';
					//loop thru taxonomy keys then render other taxonomy types
					foreach($taxonomy_keys as $keys) {
					  $extracted = explode('_',$keys);
						$term_info = taxonomy_get_term($extracted[2]);
						$voc_info = taxonomy_vocabulary_load($term_info->vid);
						
						if ($voc_info->name!='Offices') {
						  $terms .= '<li class="'.$keys.'">'.$taxonomy[$keys]['title'].'</li>';
						}
					}
					$terms .= '</ul>';
				?>
				<?php if ($taxonomy): ?>
          <div class="terms">
            <?php 						  
							print $terms 
						?>
          </div>
        <?php endif;?>

       <div class="clear"></div>

    </div>
  <?php }?>

</div>
