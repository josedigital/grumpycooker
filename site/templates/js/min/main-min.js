$(function(){var n=$(".nav-trigger"),a=$("body"),c=$(".scrolly"),o,i,s;o=function(n,a){i=function(){n.addClass(a)},s=function(){n.removeClass(a)},n.hasClass(a)?s():i()},n.on("click",function(){return o(a,"nav-active"),!1})});