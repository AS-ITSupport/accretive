<?php

// $Id: page-front.tpl.php,v 1.13 2009/04/21 18:23:55 iikka Exp $

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
  
<body id="homepage">
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
<br>
<!--
<a href="http://www.facebook.com/AccretiveSolutions" target="_blank"><img src="/sites/default/files/fb_like.jpg" locale="en" alt="Accretive Solutions on Facebook"></a>
-->
<a href="http://www.linkedin.com/company/accretive-solutions?trk=fc_badge" target="_blank"><img src="/sites/default/files/linkedin2.jpg" locale="en" alt="Accretive Solutions on LinkedIn"></a>

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
        <div id="homemainBox">
            <div id="homeContent">
            	<div id="homeFlash"></div>
                <div id="homeColRight">
                  <div id="homeColContent">
                    <!-- 
                    <h1>There's No Substitute For Experience</h1>
                    Accretive Solutions is a national consulting and executive search firm that delivers business solutions to help companies manage and improve their financial, operational and IT performance. We have made a name for ourselves over the years, based on our ability to deliver results that consistently exceed our client's expectations. <a href=http://www.accretivesolutions.com/about-as>Learn More</a><br><br>
                    -->
                  </div>
                  <div id="homeNews">	
                    <?php print $home_news; ?>                    
                  </div>   
		</div> <!-- / #homeColRight  -->
                <div class="cleaner">&nbsp;</div>
            </div>  <!-- / #homeContent  -->

        </div>

        </div>

<!-- Shadows end -->

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

<!-- Mainbox end -->

<?php print $closure ?>

</body>

</html>
