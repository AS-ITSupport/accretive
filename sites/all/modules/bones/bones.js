//$Id: bones.js,v 1.1.4.3 2009/03/23 21:48:34 usonian Exp $


function bones_toggle_menu_option() {

  selected = $("input[@name=option]:checked").val();
  
  if (selected == 'createnew') { //Hide the existing menu pulldown
    $('#bones-plid').hide('slow');
    $('#bones-newmenu').show('slow');  
  }
  
  if (selected == 'existing') { //Hide the new menu textfield
    $('#bones-plid').show('slow');
    $('#bones-newmenu').hide('slow');    
  }
  
  if (selected == 'none') { //Hide both
    $('#bones-plid').hide();
    $('#bones-newmenu').hide();         
  }
  
}


function bones_details_center() {
	
  var win_h = $(window).height();  
  var win_w = $(window).width();

  $('#bones-detail-mask').css('width', win_w + 'px');
  $('#bones-detail-mask').css('height', $(document).height() + 'px');
  $('#bones-detail-mask').show();
  
  //Set the popup window to center  
  $('#bones-batch-detail').css('top',  win_h/2-$('#bones-batch-detail').height());  
  $('#bones-batch-detail').css('left', win_w/2-$('#bones-batch-detail').width()/2);  
}
