$(function() {

  
  // Cache DOM elements. Define vars.
  // ------------------------------ /
  var go,
      logo = $('.nav-logo');
  

 

  // Functions
  // ------------------------------ /
  // toggler adds or removes class to DOM Element
  // @param   string  domel The DOM Element to add class to or remove class form
  // @param   string  classname The name of the class to add or remove
  var toggler = function(domel,classname) {
    
    open = function() {
      $(domel).addClass(classname);
    };
    
    close = function() {
      $(domel).removeClass(classname);
    };  
    
    if($(domel).hasClass(classname)) {
      close();
    } else {
      open();      
    }
    
  };


  $(document).scroll(function() {
    go = $(document).scrollTop();

    if (go > 150) {
      logo.removeClass('hide');
    } else {
      logo.addClass('hide');
    }

  });



  $('.scrolly').scrolly();



  $('.make-grid').on('click', function() {
    toggler('.gridv','med-4');
    toggler('.fa','fa-th');
    toggler('.fa','fa-square');
    var tog = $(this).find('i').attr('class');
    console.log(tog);
    $.post("/", {gridview: tog});
    return false;
  });


});