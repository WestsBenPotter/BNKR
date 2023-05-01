//JS FANCY STUFF


//document load
$( document ).ready(function() {
  positionContent();
});




//resize window
$( window ).resize(function() {
  positionContent();
});




//POSITION function
function positionContent(){
  //get heights
  var contentHeight = $(".content").height();
  var windowHeight = $(".wrapper").height();
  var margintop = (windowHeight/2 - contentHeight/2)-30;
  //position in window
  $(".content").css("margin-top", margintop);
};


$("input[type='submit']").click(function(){
  $("#loading").fadeIn(50);
});


//ANIMATE ARROW function
//function animateArrow(){
//  $("#arrow").stop().animate({bottom: '-30px'}, 200);
//  $("#arrow").animate({bottom: '-400px'}, 500);
//  $("#arrow").animate({bottom: '100%'}, 0);
//  $("#arrow").animate({bottom: '-80px'}, 1500);
//  $("#arrow").animate({bottom: '-50px'}, 200);
//}
//setInterval(function() {
//    animateArrow();
//}, 30 * 1000);
