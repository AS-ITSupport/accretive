(function($) {
Drupal.behaviors.slider_2d = function(context) {
  $.each(Drupal.settings.slider_2d, function(id) {
    this.btnNext = "#pfNavRight";
    this.btnPrev = "#pfNavLeft";
    this.btnUp = "#pfNavTop";
    this.btnDown = "#pfNavBottom";
    this.visible = 1;
    this.vertical = false;
    this.categories = this.categories;
    this.numPanels = this.numPanels;    
    this.mouseWheel = parseInt(this.mouseWheel);
    this.categoryNames = this.categoryNames;
    this.vid = parseInt(this.vid);
    this.hashNames = this.hashNames;
    $('#' + id).jCarouselLite(this);
    //alert(this.hashNames[0][0]);
    //preload images
    for (var x = 0; x <= this.imagesPath.length-1; x++)
    {
       preload_image_object = new Image();
       preload_image_object.src = this.imagePath+this.imagesPath[x];
    } 
  });	     
}

})(jQuery)