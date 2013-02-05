<?php
// $Id: views-view-unformatted--careers-location.tpl.php,v 1.4 2009/09/14 09:06:13 jessem Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */  
 
 $office_location = array();
  //load nodes for job types 
  $query = db_query('SELECT n.* FROM {node} n where n.type=\'job\' ORDER BY n.title ASC');
  //fetch data, then populate office array
  while ($node = db_fetch_object($query)) {    
    $node_info = node_load($node->nid);
    foreach($node_info->taxonomy as $taxo_data) {
	  $v_info = taxonomy_vocabulary_load($taxo_data->vid);
	  if ($v_info->name=='Offices') {
	    if (!in_array($taxo_data->name,$office_location)) {
	      $office_location[] = $taxo_data->name;
	    }	  
	  }
	}
  }
  sort($office_location);
  
  $output .= '<ul>';
  foreach($office_location as $office) {
    $output .= '<li><a href="#'.str_replace(' ','',$office).'">'.$office.'</a></li>';
  }
  $output .= '</ul>';
  
  print $output;
?>
