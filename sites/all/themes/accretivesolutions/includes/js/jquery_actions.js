if (Drupal.jsEnabled) {
	// Document ready event
	$(document).ready(function(){											 
		$(".managers_trigger").click(function(){
			$("#managers_panel").slideToggle("slow");
			$(this).toggleClass("selected");
			return false;
		});		

		$(".map_trigger").click(function(){
			$("#map_panel").slideToggle("slow");
			return false;
		});
		
		$("input[@name='submitted[type_of_inquiry]']").click(function(){
			inquiry_value = $("input[@name='submitted[type_of_inquiry]']:checked").val();
		
			switch (inquiry_value) {
				case "Executive Search":
					$("#webform-component-consulting").slideUp("fast");
					$("#webform-component-executive_search").slideDown("fast");				
				break;
				
				case "Consulting":
					$("#webform-component-executive_search").slideUp("fast");
					$("#webform-component-consulting").slideDown("fast");				
				break;
				
				default:
					$("#webform-component-executive_search").slideUp("fast");
					$("#webform-component-consulting").slideUp("fast");							
			}			
		});		
		
		$('#navCareers ul.level1 ul').switchDDMenuLocation(); // call dropdown workaround																
		
		//for Apply Now button on jobs page
		/*$(".apply-now").click(function(){
			//$("input[name='term_id']").val(); 
			//$("input[name='node_id']").val();
			$.post("/job-application", { term_id: $("input[name='term_id']").val()});
			//alert($("input[name='term_id']").val());
	 });*/	
	});
	
	
	// Window resize event
	$(window).resize(function(){			
		$('#navCareers ul.level1 ul').switchDDMenuLocation(); // call dropdown workaround
	});	
}


/* 
	switchDDMenuLocation();
	Switch 2nd level dropdown position
	based on window width
*/
(function($){  
	$.fn.switchDDMenuLocation = function() {

		// Default options
		var defaults = {  
			classLeft: "ddLeft",  
    	classRight: "ddRight"  
   	};  
		
   	var options = $.extend(defaults, options);  
		var windowWidth = $(window).width(); // get window width 		
		
		if(windowWidth < 1300)
		{
			$(this).removeClass(options.classRight);				
			$(this).addClass(options.classLeft);
			$('#navCareers ul.level1 a.level1').addClass("reverse");
		}
		else
		{
			$(this).removeClass(options.classLeft);				
			$(this).addClass(options.classRight);
			$('#navCareers ul.level1 a.level1').removeClass("reverse");			
		}				
  };  
})(jQuery);