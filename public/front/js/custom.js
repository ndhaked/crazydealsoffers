$('.deal-list-slider').owlCarousel({
    loop:true,
    margin:30,
    nav: true,
    dots: false,
    responsiveClass:true,
    responsive:{
        0:{
            items:1,
            stagePadding: 30
        },
        480:{
            items:2
        },
        700:{
            items:3
        },
        1000:{
            items:4
        },
    }
})
$('.blog-detail-slider').owlCarousel({
    loop:true,
    margin:0,
    nav: true,
    dots: false,
    items: 1,
    responsiveClass:true,
})
$('.home-banner-slider').owlCarousel({
    loop:true,
    margin:0,
    nav: true,
    smartSpeed: 10000,
    dotsSpeed: 1000,
    dragEndSpeed: 1000,
    singleItem: true,
    animateIn: 'fadeIn',
    animateOut: 'fadeOut',
    pagination: false,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: false,
    dots: false,
    items: 1,
    autoHeight: true,
    responsiveClass:true,
	navText: ["<img src='"+_publicPath+"/front/images/icons/left-arrow-slider.svg'>","<img src='"+_publicPath+"/front/images/icons/right-arrow-slider.svg'>"]
})



// var settings ****************************************************
var $header = $('header');
// code setting  (mobile menu )*************************************

    

// 06. we add the class stick to the fixed header to change his appearance and the apparence of their content on scroll
$(window).scroll(function() {
if ($(this).scrollTop() > 100){  
    $($header).addClass("stick");
  }
  else {
    $($header).removeClass("stick"); 
  }
});
