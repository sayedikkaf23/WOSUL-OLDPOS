$(window).load(function(){
	$("body").addClass("loaded");
})
$(window).scroll(function() {
	var height = $(window).scrollTop();
	if (height > 50) 
	{
		$('html').addClass('sticky');				
	} else {					
		$('html').removeClass('sticky');				
	}							
});						
			
$(document).ready(function() {				
	$(".scrollToTop").click(function(event) {
		event.preventDefault();					
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;				
	});
	$('.close_menu').click(function() {
		$("body").removeClass("courses_show");
	});
	$('.submenu-toggle').click(function() {
		$(this).parent().toggleClass('submenu_active');
	});
	$('.toggle-menu').click(function() {
		$("html").toggleClass("menu-show");
	});
	$('.header-menu-overlay').click(function() {
		$("html").removeClass("menu-show");
	});
	$('.navbar-nav-responsive a').click(function() {
		$("html").removeClass("menu-show");
	});
	$('.custom_close').click(function() {
		$("body").removeClass("custom_show");
	});
	
});

