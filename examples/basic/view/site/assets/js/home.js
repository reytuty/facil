$(function() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }

  if(myWidth<900){
  	divisao=4;
  }else if(myWidth<=1024){
  	divisao=4;
  }else if(myWidth<=1440){
  	divisao=5;
  }else if(myWidth<=1680){
  	divisao=6;
  }else if(myWidth<=1920){
  	divisao=6;
  }else if(myWidth>1920){
  	divisao=7;
  }

  newWidth = ((myWidth-18)/divisao)-4;
  newHeight = (newWidth/10)*6.25;
  /*margin top do texto da div da letra*/
  margintop = newHeight-100;
  margintop = parseInt(margintop)+'px';

  newHeight = parseInt(newHeight)+'px';
  newWidth = parseInt(newWidth)+'px';

  $('.textproduto').css('margin-top', margintop);
  $('.products').css('width', newWidth);
  $('.products').css('height', newHeight);
  $('.recover-open').css('width', newWidth);
  $('.recover-open').css('height', newHeight);
  $('.products .image').css('width', newWidth);
  $('.products .image').css('height', newHeight);

 if( window.location.href.indexOf( "equipe") > 0 ) {
    $( ".products div.scroll" ).css({ width   : parseInt( newWidth ) -38 });
    $(".products div.jScrollPaneContainer, .products div.description").css({
          height  : parseInt( newHeight ) - 51 ,
          width   : parseInt( newWidth ) -15
    }) ;
  }


 });

$(window).resize(function () {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }

  if(myWidth<900){
  	divisao=2;
  }else if(myWidth<=1024){
  	divisao=4;
  }else if(myWidth<=1440){
  	divisao=5;
  }else if(myWidth<=1680){
  	divisao=6;
  }else if(myWidth<=1920){
  	divisao=6;
  }else if(myWidth>1920){
  	divisao=7;
  }

  //newWidth = ((myWidth-40)/divisao)-4;
  newWidth = ((myWidth-18)/divisao)-4;
  newHeight = (newWidth/10)*6.25;
  /*margin top do texto da div da letra*/
  margintop = newHeight-100;
  margintop = parseInt(margintop)+'px';

  newHeight = parseInt(newHeight)+'px';
  newWidth = parseInt(newWidth)+'px';


  $('.textproduto').css('margin-top', margintop);
  $('.products').css('width', newWidth);
  $('.products').css('height', newHeight);
  $('.recover-open').css('width', newWidth);
  $('.recover-open').css('height', newHeight);
  $('.products .image').css('width', newWidth);
  $('.products .image').css('height', newHeight);

  if( window.location.href.indexOf( "equipe") > 0 ) {
    $( ".products div.scroll" ).css({ width   : parseInt( newWidth ) -38 });
    $(".products div.jScrollPaneContainer, .products div.description").css({
          height  : parseInt( newHeight ) - 51 ,
          width   : parseInt( newWidth ) -15
    }) ;
  }

});

function resizeLista(){

	var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }

  if(myWidth<900){
  	divisao=4;
  }else if(myWidth<=1024){
  	divisao=4;
  }else if(myWidth<=1440){
  	divisao=5;
  }else if(myWidth<=1680){
  	divisao=6;
  }else if(myWidth<=1920){
  	divisao=6;
  }else if(myWidth>1920){
  	divisao=7;
  }

  newWidth = ((myWidth-18)/divisao)-4;
  newHeight = (newWidth/10)*6.25;
  /*margin top do texto da div da letra*/
  margintop = newHeight-100;
  margintop = parseInt(margintop)+'px';

  newHeight = parseInt(newHeight)+'px';
  newWidth = parseInt(newWidth)+'px';

  $('.textproduto').css('margin-top', margintop);
  $('.products').css('width', newWidth);
  $('.products').css('height', newHeight);
  $('.recover-open').css('width', newWidth);
  $('.recover-open').css('height', newHeight);
  $('.products .image').css('width', newWidth);
  $('.products .image').css('height', newHeight);

}
