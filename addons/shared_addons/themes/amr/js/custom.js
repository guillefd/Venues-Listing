
/* Menu Slide JS  */

$(document).ready(function(){
  $(".menu-btn").on('click',function(e){
      e.preventDefault();
		
		//Check this block is open or not..
      if(!$(this).prev().hasClass("open")) {
        $(".header").slideDown(400);
        $(".header").addClass("open");
        $(this).find("i").removeClass().addClass("fa fa-chevron-up");
      }
      
      else if($(this).prev().hasClass("open")) {
        $(".header").removeClass("open");
        $(".header").slideUp(400);
        $(this).find("i").removeClass().addClass("fa fa-chevron-down");
      }
  });

}); 

/* Scroll to Top */

  $(".totop").hide();

  $(function(){
    $(window).scroll(function(){
      if ($(this).scrollTop()>300)
      {
        $('.totop').slideDown();
      } 
      else
      {
        $('.totop').slideUp();
      }
    });

    $('.totop a').click(function (e) {
      e.preventDefault();
      $('body,html').animate({scrollTop: 0}, 500);
    });

  });

/* Feature Item */

$('.feature-item').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceIn');
}, { offset: '60%' });
	

/* Navigation Tab */
/* Tab navigation toggle */
$('#myTab a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
});


/* Pricing Table JS */

$('.p-one').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });

$('.p-two').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });

$('.p-three').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });

$('.p-four').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('fadeInLeft');
}, { offset: '75%' });


/* Tesimonial JS */

$('.t-one').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceInLeft');
}, { offset: '80%' });

$('.t-two').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceInLeft');
}, { offset: '80%' });

$('.t-three').waypoint(function(down){
	$(this).addClass('animation');
	$(this).addClass('bounceInLeft');
}, { offset: '80%' });


/* Inner Support page JS */

$("#slist a").click(function(e){
   e.preventDefault();
   $(this).next('p').toggle(200);
});

/* Inner Coming Soon Page JS */
/* Countdown */

$(function(){
 launchTime = new Date("Fri Dec 20 2013 15:00:00 GMT-0300");
	$("#countdown").countdown({until: launchTime, format: "dHMS"});
});



/* prettyPhoto Gallery */

jQuery(".prettyphoto").prettyPhoto({
   overlay_gallery: false, social_tools: false
});

/* Isotype */

// cache container
var $container = $('#portfolio');
// initialize isotope
$container.isotope({
  // options...
});

// filter items when filter link is clicked
$('#filters a').click(function(){
  var selector = $(this).attr('data-filter');
  $container.isotope({ filter: selector });
  return false;
});               


  