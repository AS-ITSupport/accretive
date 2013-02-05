<?php
// $Id: page-as-involved-detailed.tpl.php,v 1.1 2009/09/04 05:12:42 jessem Exp $
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>">
  <head>
    <title><?php print $head_title ?></title>
    <?php print $head ?>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lte IE 7]><?php print phptemplate_get_ie_styles(); ?><![endif]--><!--If Less Than or Equal (lte) to IE 7-->
  </head>
    <body>
<!-- Mainbox start -->
		<div id="mainBox">
<!-- Header start -->
    	<div id="header">
            <div id="logo">     
              <?php
                  print '<a href="'. check_url($front_page) .'" title="'. check_plain($site_name) .'">';                      
                  if ($logo) {
                    print '<img src="'. check_url($logo) .'" alt="'. check_plain($site_name) .'" />';
                  }
                  if ($site_name) {
                    print '<span id="name">'. check_plain($site_name) .'</span>';
                  }
                  if ($site_slogan) {
                    print '<span id="slogan">'. check_plain($site_slogan) .'</span>';
                  }
                  print '</a>';
                ?>                   
             </div>        
       			<div id="headerCol2">
                <div id="navutil">
                    <?php print theme('links', $util_primary, NULL) ?>
                </div>            
                <div id="quickSearch">
                  <?php if ($search_box): ?><?php print $search_box ?><?php endif; ?>                
                </div>
            </div>
    		<div class="cleaner">&nbsp;</div>
    	</div>    
<!-- Header end -->
<!-- Navmain start --> 
        <div id="navMain">
          <?php if (isset($primary_links)) { print theme_nice_menus_primary_links('down', '2'); } ?>
        </div>  
<!-- Navmain end -->
<!-- Shadows start -->
<div id="shadowSides">        
	<?php if ($primary_links): ?>          
	<div id="shadowTop">	
    <?php endif; ?>
      <div id="shadowBottom">
    

    
<!-- Contentbox start -->
          <div id="contentBox">
                <div class="sectionHeader"><?php print $header_title ?>&nbsp;</div>
                <div class="cleaner">&nbsp;</div>
                <div class="breadcrumb">
                    <?php print $breadcrumb; ?>
                </div>
                <div class="cleaner">&nbsp;</div>
<!-- columnwide start -->                
                    <div id="colBlockLeftWide">                                                               
                      <div id="col1Wide">
                        <div class="content padding">
                          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
                          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
                          <?php if ($title): print '<h1'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h1>'; endif; ?>
                          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
                          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
                          <?php if ($show_messages && $messages): print $messages; endif; ?>
                          <?php print $help; ?>                                                                              
                          <?php print $content; ?>
                         </div>
                        </div>
                        
                        <div id="col3">
                          <div class="content padding">
                            <?php if ($right): ?>
                                <?php print $right ?>
                             <?php endif; ?>
                          </div>
                        </div>
                        
                        <div class="cleaner">&nbsp;</div>
                    </div>
                    
<!-- columnwide end -->                    
        </div>       
<!-- Contentbox end -->    
</div>    
<!-- Footer start -->
<div id="footer">
    <?php print $footer ?>
    <p class="copyright">
      <?php print $footer_message ?>
      <?php print $feed_icons ?>
  </p>
</div>
<!-- Footer end -->    
</div>
</div>
<!-- Shadows end -->
</div>
<!-- Mainbox end -->
  <?php print $closure ?>
  </body>
</html>
