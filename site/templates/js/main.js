$(function() {

  
  // Cache DOM elements. Define vars.
  // ------------------------------ /
  var trigger = $('.nav-trigger'),
  body = $('body'),
  scroll = $('.scrolly'),
  toggler,
  open,
  close;


  // Functions
  // ------------------------------ /
  // toggler adds or removes class to DOM Element
  // @param   string  domel The DOM Element to add class to or remove class form
  // @param   string  classname The name of the class to add or remove
  toggler = function(domel,classname) {
    
    open = function() {
      domel.addClass(classname);
    };
    
    close = function() {
      domel.removeClass(classname);
    };  
    
    if(domel.hasClass(classname)) {
      close();
    } else {
      open();      
    }
    
  };





  // Mobile Nav
  // ------------------------------ /
  trigger.on('click', function() {
    toggler(body, 'nav-active');
    return false;
  });




  // Scrolly
  // ------------------------------ /
  scroll.scrolly(500);



});