(function($) {                                          // Compliant with jquery.noConflict()
$.fn.jCarouselLite = function(o) {
    o = $.extend({
        btnPrev: null,
        btnNext: null,
        btnUp: null,            //additional variable for up button
        btnDown: null,          //additional variable for down button
        btnGo: null,
        mouseWheel: false,
        auto: null,

        speed: 200,
        easing: null,

        vertical: false,
        circular: true,
        visible: 3,
        start: 0,
        scroll: 1,
        categories: 0,         // additional variable, number of categories used for up/down events
        numPanels: null,       // additional variable, number of panels per category, string separated by "|"
        vid: 0,                // additional variable, this will hold the vocabulary id
        categoryNames: null,   // additional variable, this will hold the category names
        hashNames: null,        // additional variable, an array that contains node hash names

        beforeStart: null,
        afterEnd: null
    }, o || {});

    return this.each(function() {                           // Returns the element collection. Chainable.                              
        
        var brokenpanels = o.numPanels.split("|");
        var brokenCategories = o.categoryNames.split("|");
                
        var running = false, animCss=o.vertical?"top":"left", sizeCss=o.vertical?"height":"width";
        var div = $(this), ul = $("ul", div), tLi = $("li", ul), tl = tLi.size(), v = o.visible;
        
        var curr_panel = 1;
        var curr_category = 1;
        var prev_panel = 1;
        var prev_category = 1;
        var mytimer = 0;
        var checked = false;    //used for arrow key checker

        if (location.hash) {
          hash = location.hash.slice(1).split("/");
          category_name = hash[0];
          node_name = hash[1];
          
          //find the category in hash
          for(var a in brokenCategories) {
            if(brokenCategories[a].toLowerCase() == category_name) {
              curr_category = parseInt(a) + 1;
            }
          }
          
                     
          //find node name from hash
          for(var b in o.hashNames[curr_category-1]) {
            if(o.hashNames[curr_category-1][b] == node_name) {
              curr_panel = parseInt(b) + 1;
            }
          }
            
          if(parseInt(curr_category) > o.categories) {
            curr_category = 1;  
          }
          
          
          $("#categoryName").html(brokenCategories[curr_category-1]);           
          $("#pfCount").html(curr_panel+"/"+brokenpanels[curr_category-1]);
          
          $('#slidesList').find('li').eq(0).load("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid);          
        }        				
								
        // Purr code
        var purrOptions = {
          fadeInSpeed: 50,
          fadeOutSpeed: 50,
          removeTimer: 300,
          isSticky: false,
          usingTransparentPNG: true
        }
          
        var notice = '<div class="notice">'
              + '<div class="notice-body">' 
              + '<p></p>'
              + '</div>'
            + '</div>';
        
        
        var timer = {
          time: 0,
          now: function(){ return (new Date()).getTime(); },
          start: function(){ this.time = this.now(); },
          since: function(){ return this.now()-this.time; }
        }
				timer.start();
        
        if(o.circular) {
            ul.prepend(tLi.slice(tl-v-1+1).clone())
              .append(tLi.slice(0,v).clone());
              
            o.start += v;
        }
        
        //check cookies for arrow keys image
        var x = readCookie('arrowKeyChecker');
        if(x) {
          document.getElementById('pfNavigator').style.background = 'none';
          checked = true;
        }
        

                
        if(o.categories <= 1) {
          $(o.btnUp + "," + o.btnDown).addClass("disable");
        }

        var li = $("li", ul), itemLength = li.size(), curr = o.start;
        div.css("visibility", "visible");

        li.css({overflow: "hidden", float: o.vertical ? "none" : "left"});
        ul.css({margin: "0", padding: "0", position: "relative", "list-style-type": "none", "z-index": "1"});
        div.css({overflow: "hidden", position: "relative", "z-index": "2", left: "0px"});

        var liSize = o.vertical ? height(li) : width(li);   // Full li size(incl margin)-Used for animation
        var ulSize = liSize * itemLength;                   // size of full ul(total length, not just for the visible items)
        var divSize = liSize * v;                           // size of entire div(total length for just the visible items)
        var event_checker = 0;
        
        li.css({width: li.width(), height: li.height()});
        ul.css(sizeCss, ulSize+"px").css(animCss, -(curr*liSize));

        div.css(sizeCss, divSize+"px");                     // Width of the DIV. length of visible images
        
      
			function SimpleCallback(in_text, in_param, in_param2) {				
				list_id = $('#slidesList').find('li').eq(in_param).attr('id');
								 				
				if( typeof(list_id) == "undefined" ) {
					slide_to = 2;
				} else {
					slide_to = in_param;
				}
				
				$('#slidesList').find('li').eq(slide_to).html(in_text);				

				return go(in_param);			
			}
			
			
      $(document).keyup(function(e){        
                                 
           switch( e.keyCode ){         
             case 40://down (->)
								 checkKeyPress();
								 if(o.categories > 1) {
                    $(o.btnDown).addClass("active");
										checkPressTimer();
										if(timer.since() > 1000){
											align("vertical");
											set_category("down");                  
											$("#pfCategoryBottom").html(brokenCategories[curr_category-1]);
											event_checker = 1;                  
											//return go(curr+o.scroll);
											MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr+o.scroll);
										}
								 } 
								 								
             break;
             case 38://up (->)
								 checkKeyPress();
								 if(o.categories > 1) {             
                  $(o.btnUp).addClass("active");
									
										checkPressTimer();
										if(timer.since() > 1000) {
	
											align("vertical");
											set_category("up");
											$("#pfCategoryTop").html(brokenCategories[curr_category-1]);
											event_checker = 1;
											//return go(curr-o.scroll);
											MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr-o.scroll);
										}
									}
             break;
             case 39://right (->)
								 checkKeyPress();
								 
                $(o.btnNext).addClass("active");

								checkPressTimer();
								if(timer.since() > 1000) {
									
									align("horizontal");
									set_category("right");
									event_checker = 1;
									MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr+o.scroll);
								}
             break;           
             case 37://left (<-)
								 checkKeyPress();
                 $(o.btnPrev).addClass("active");

								checkPressTimer();
								if(timer.since() > 1000) {

									align("horizontal");
									set_category("left");
									event_checker = 1;
									//return go(curr-o.scroll);             
									MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr-o.scroll);
								}
             break;
           }
       });        

        if(o.btnPrev)
            $(o.btnPrev).click(function() {
								checkPressTimer();
								if(timer.since() > 1000) {
                                          
                  $(o.btnPrev).addClass("active");
                  align("horizontal");
                  set_category("left");
									MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr-o.scroll);
                  //return go(curr-o.scroll);
             }
            });
                                
        if(o.btnNext)
            $(o.btnNext).click(function() {
								checkPressTimer();
								if(timer.since() > 1000) {                  
									
									$(o.btnNext).addClass("active");
									align("horizontal");
									set_category("right");
									//return go(curr+o.scroll);
									MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr+o.scroll);
								}

            });                      

        if(o.btnUp)  
            $(o.btnUp).click(function() {    
               							 
                $(o.btnUp).addClass("active");
								
								checkPressTimer();
								if(timer.since() > 1000) {
								
									align("vertical");
									set_category("up");
									$("#pfCategoryTop").html(brokenCategories[curr_category-1]);
									//return go(curr-o.scroll);
									MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr-o.scroll);
								}

            });

        if(o.btnDown)
            $(o.btnDown).click(function() {                
                                        
                $(o.btnDown).addClass("active");
								
								checkPressTimer();
								if(timer.since() > 1000) {
								
									align("vertical");
									set_category("down");
									$("#pfCategoryBottom").html(brokenCategories[curr_category-1]);
									MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr+o.scroll);
									//return go(curr+o.scroll);
								}

            });
        
        //add hovers to arrow buttons
        $(o.btnUp).hover(
          function () {            
            if(curr_category == 1) {
              cat = o.categories;
            } else {
              cat = curr_category - 1;
            }

            $(this).addClass('active');
            $("#pfCategoryTop").html(brokenCategories[cat-1]);
          }, 
          function () {
            $(this).removeClass('active');
            $("#pfCategoryTop").html("&nbsp;");
          }
        );
        
        $(o.btnDown).hover(
          function () {
            if(curr_category == o.categories) {            
              cat = 1;
            } else {
              cat = curr_category + 1;
            }            
            $(this).addClass('active');
            $("#pfCategoryBottom").html(brokenCategories[cat-1]);
          }, 
          function () {
            $(this).removeClass('active');
            $("#pfCategoryBottom").html("&nbsp;");
          }
        );


        $(o.btnNext).hover(
          function () {
            $(this).addClass('active');
          }, 
          function () {
            $(this).removeClass('active');
          }
        );

        $(o.btnPrev).hover(
          function () {
            $(this).addClass('active');
          }, 
          function () {
            $(this).removeClass('active');
          }
        );




        if(o.btnGo)
            $.each(o.btnGo, function(i, val) {
                $(val).click(function() {
                    //return go(o.circular ? o.visible+i : i);
                });
            });

        if(o.mouseWheel && div.mousewheel)            
            div.mousewheel(function(e, d) {
                                    
                if(d>0) {
                  $(o.btnPrev).addClass("active");
									checkPressTimer();
									if(timer.since() > 1000) {
									
										align("horizontal");
										set_category("left");
										MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr-o.scroll);
									}
                } else {
                  $(o.btnNext).addClass("active");
									
									checkPressTimer();
									if(timer.since() > 1000) {
									
										align("horizontal");
										set_category("right");
										event_checker = 1;
										//return go(curr+o.scroll);          
										
										//go(curr+o.scroll)
										MakeNewAJAXCall("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, SimpleCallback, 'GET', '', curr+o.scroll);
									}
                }
                //return d>0 ? go(curr-o.scroll) : go(curr+o.scroll);
            });                      

        if(o.auto)
            setInterval(function() {
                //go(curr+o.scroll);
            }, o.auto+o.speed);

        function vis() {
            return li.slice(curr).slice(0,v);
        };
        
        //set the current/prev/next category content for each up/down event
        function set_category(direction) {
          
          //set previous panels and category
          prev_category = curr_category;
          prev_panel = curr_panel;
					timer.start();
          
          switch (direction) {
            case "up":
              curr_panel = 1;
              if(curr_category == 1) {            
                curr_category = o.categories;
              } else {
                curr_category--;
              }
							$( notice ).purr(purrOptions);
            break;
            
            case "down":							
              curr_panel = 1;
              if(curr_category == o.categories) {            
                curr_category = 1;
              } else {
                curr_category++;
              }  
							$( notice ).purr(purrOptions);
							
            break;
            
            case "left":
							panels = brokenpanels[curr_category-1];
              
              if(curr_panel == 1) {
                if(curr_category == 1) {
                  curr_category = o.categories;
                }
                else {
                  curr_category--;
                }
                new_panels = brokenpanels[curr_category-1];
                curr_panel = new_panels;
                
								$( notice ).purr(purrOptions);
              } else {
                curr_panel--;
              }
            break;
            
            case "right":
              panels = brokenpanels[curr_category-1];
              if(curr_panel == panels) {
                curr_panel = 1;
                if(curr_category == o.categories) {
                  curr_category = 1;                              
                }
                else {                  
                  curr_category++;
                }								
                $( notice ).purr(purrOptions);
              } else {
                curr_panel++;
              }            
            break;
          }
          $(".notice-body p").html(brokenCategories[curr_category-1]);
          $("#pfCount").html(curr_panel+"/"+brokenpanels[curr_category-1]);
          $("#categoryName").html(brokenCategories[curr_category-1]);
          location.hash = '#'+brokenCategories[curr_category-1].toLowerCase()+'/'+o.hashNames[curr_category-1][curr_panel-1];
        }
				
				function checkKeyPress() {
					 if(x == null && checked == false) {
						 createCookie('arrowKeyChecker','1',7);
						 document.getElementById('pfNavigator').style.background = 'none';
					 }               					
				}
				
				function checkPressTimer() {
					
					if(timer.since() > 1000) {
						running = false;
					}					
				}
        
        //set alignment of panels for each arrow event
        function align(alignment) {
          
          switch (alignment) {
            case "vertical":
                o.vertical = true;
                animCss = "top";
                sizeCss = "height";
                
                ul.css({left:"0px"});
            
            break;
            
            case "horizontal":
                o.vertical = false;
                animCss = "left";
                sizeCss = "width";
                
                ul.css({top:"0px"});
            
            break;
          }
          li.css({overflow: "hidden", float: o.vertical ? "none" : "left"});
          ul.css({margin: "0", padding: "0", position: "relative", "list-style-type": "none", "z-index": "1"});
          div.css({overflow: "hidden", position: "relative", "z-index": "2", left: "0px"});
  
          liSize = o.vertical ? height(li) : width(li);   // Full li size(incl margin)-Used for animation
          ulSize = liSize * itemLength;                   // size of full ul(total length, not just for the visible items)
          divSize = liSize * v;                           // size of entire div(total length for just the visible items)
  
          li.css({width: li.width(), height: li.height()});
          ul.css(sizeCss, ulSize+"px").css(animCss, -(curr*liSize));
          div.css(sizeCss, divSize+"px");   
          
        }
                
        //disables active and hover class on buttons
        function disableClass() {
          $(o.btnPrev).removeClass();
          $(o.btnNext).removeClass();
          $(o.btnUp).removeClass('active');
          $(o.btnDown).removeClass('active');
          $("#pfCategoryTop").html("&nbsp;");
          $("#pfCategoryBottom").html("&nbsp;");
        }
        
        function createCookie(name,value,days) {
          if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
          }
          else var expires = "";
          document.cookie = name+"="+value+expires+"; path=/";
        }
        
        function readCookie(name) {
          var nameEQ = name + "=";
          var ca = document.cookie.split(';');
          for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
          }
          return null;
        }
        
        
        
        function go(to) {            
				//function go() {
							

							//alert(running);
							if(!running) {								
							//	if(running == false) {
								//alert('not running');
									
									/*if(events_cnt > 0) {
										align("horizontal");
										set_category("right");
										to = curr+o.scroll;
										//alert(to);
										//alert('events cnt is greater than zero');
										remove_cnt = true;
									}*/
									
									list_id = $('#slidesList').find('li').eq(to).attr('id');
									
									 
									if(o.beforeStart)
											o.beforeStart.call(this, vis());
									
									
									if( typeof(list_id) == "undefined" ) {
										slide_to = 2;
									} else {
										slide_to = to;
									}
							
							
							//MakeNewAJAXCall("/slider_panel/1/1/2", SimpleCallback, 'GET');
							//$('#slidesList').find('li').eq(slide_to).load("/slider_panel/"+curr_category+"/"+curr_panel+"/"+o.vid, function()          
							//$('#slidesList').find('li').eq(slide_to).queue(function()          
							//{
									 
									 running = true;                
									if(o.circular) {            // If circular we are in first or last, then goto the other end                    
											if(to<=o.start-v-1) {           // If first, then goto last                      
													ul.css(animCss, -((itemLength-(v*2))*liSize)+"px");
													// If "scroll" > 1, then the "to" might not be equal to the condition; it can be lesser depending on the number of elements.
													curr = to==o.start-v-1 ? itemLength-(v*2)-1 : itemLength-(v*2)-o.scroll;
											} else if(to>=itemLength-v+1) { // If last, then goto first
													
														ul.css(animCss, -( (v) * liSize ) + "px" );
														// If "scroll" > 1, then the "to" might not be equal to the condition; it can be greater depending on the number of elements.                        
														curr = to==itemLength-v+1 ? v+1 : v+o.scroll;                        
											} else curr = to;
									} else {                    // If non-circular and to points to first or last, we just return.
											if(to<0 || to>itemLength-v) return;
											else curr = to;
									} // If neither overrides it, the curr will still be "to" and we can proceed.
									 
									 
									 
										 ul.animate(
													animCss == "left" ? { left: -(curr*liSize) } : { top: -(curr*liSize) } , o.speed, o.easing,												
													function() {															
															if(o.afterEnd)
																	o.afterEnd.call(this, vis());
															
															//$( notice ).trigger('close');
															
															if(event_checker == 1) {
																disableClass();
																event_checker = 0;
															}
													}
													//{queue:true, duration:1000}
											);
									 
									 /*ul.queue(function() {																						
											//$( notice ).trigger('close');
											disableClass();
											ul.dequeue();
											running = false;
											//alert(timer.since());
											
											/*if(remove_cnt == true) {
												events_cnt--;
												if(events_cnt > 0) {
													go(to);
											}
												
											}
																						
									 });*/
									 
									 
									 
								 //});                                                                
																						 
	
									
									// Disable buttons when the carousel reaches the last/first, and enable when not
									if(!o.circular) {
											$(o.btnPrev + "," + o.btnNext).removeClass("disabled");
											$( (curr-o.scroll<0 && o.btnPrev)
													||
												 (curr+o.scroll > itemLength-v && o.btnNext)
													||
												 []
											 ).addClass("disabled");
									}
									checkPressTimer();
	
							}
							return false;
						//}
        };
    });
};

function css(el, prop) {
    return parseInt($.css(el[0], prop)) || 0;
};
function width(el) {
    return  el[0].offsetWidth + css(el, 'marginLeft') + css(el, 'marginRight');
};
function height(el) {
    return el[0].offsetHeight + css(el, 'marginTop') + css(el, 'marginBottom');
};

})(jQuery);