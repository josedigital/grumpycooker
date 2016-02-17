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
  // toggler = function(domel,classname) {
    
  //   open = function() {
  //     domel.addClass(classname);
  //   };
    
  //   close = function() {
  //     domel.removeClass(classname);
  //   };  
    
  //   if(domel.hasClass(classname)) {
  //     close();
  //   } else {
  //     open();      
  //   }
    
  // };


  $(document).scroll(function() {
    go = $(document).scrollTop();

    if (go > 150) {
      logo.removeClass('hide');
    } else {
      logo.addClass('hide');
    }

  });



  $('.scrolly').scrolly();



});