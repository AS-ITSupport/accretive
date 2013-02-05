<?php
// $Id: views-view-unformatted--careers.tpl.php,v 1.4 2009/09/14 11:21:55 jessem Exp $
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
 
 $jobs = array();
 $ctr++;
 foreach($variables['view']->result as $views_data) {
   $node_info = node_load($views_data->nid);
   foreach($node_info->taxonomy as $taxo_data) {
     $v_info = taxonomy_vocabulary_load($taxo_data->vid);
	 if ($v_info->name=='Offices') {
	   if (!in_array($taxo_data->name,$jobs)) {
	     if (!empty($taxo_data->name)) {
			$jobs[$taxo_data->name][] = array($views_data);
		 }
	   }	  
     }
   }    
 }
 
 ksort($jobs);
 $variables['num_offices'] = count($jobs);  
 $variables['job_listings'] = $jobs;
 
 
 $_SESSION['office_ctr']++;

 if ($_SESSION['office_ctr']==$variables['num_offices']) {  
   unset($_SESSION['office_ctr']);
   foreach($variables['job_listings'] as $office=>$jobs) {
     print '<div class="job_holder"><a name="'.str_replace(' ','',$office).'"></a><h3>'.$office.'</h3>';	
	 foreach($jobs as $job) {	  
	   print '<div class="views-field-title"><span class="field-content"><a href="/node/'.$job[0]->nid.'" title="'.$job[0]->node_title.'">'.$job[0]->node_title.'</a></span></div>';
	 }	
	 print '</div>';
   }
 }
?>
