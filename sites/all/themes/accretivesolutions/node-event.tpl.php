<?php

// $Id: node-event.tpl.php,v 1.5 2009/11/16 11:29:49 jessem Exp $

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

    <?php

	  print $content; 

	?>

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

      <?php if ($taxonomy): ?>

        <div class="terms">

          <?php print $terms ?>

        </div>

      <?php endif;?>



       <div class="clear"></div>



    </div>

  <?php }?>

  <?php  if ($node->field_event_email[0]['email']!=''&&$node->field_event_show_registration[0]['value']==1) {  ?>

  <div>

      <br/>

      <form name="eventform" method="post" action="/event-registration">        

        <input type="hidden" value="<?php print $node->nid;?>" name="node_id"/>       

        <input type="hidden" value="<?php print $node->title;?>" name="event_title"/>

        <a onclick="javascript:document.eventform.submit()" style="cursor:pointer;">Event Registration</a>

      </form>

  </div>

  <?php } ?>

</div>
