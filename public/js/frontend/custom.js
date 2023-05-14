
$(document).ready(function(){
    $('#screenshortslider').owlCarousel({
        loop:false,
        margin:30,
        nav:true,
        autoplay: false,
        animateOut: 'slideOutDown',
        animateIn: 'flipInX',
        stagePadding:30,
         smartSpeed:2500,
        mouseDrag  : true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:4
            }
        }
    })

    $(window).scroll(function(){
        if($(window).scrollTop() >= 200) {
            $('header').addClass('header-scrolled');
        }
        else{
            $('header').removeClass('header-scrolled');
        }
    });    

    $("#mobile-nav-toggle").click(function(){
        $('body').addClass('mobile-nav-active');
        $('#mobile-body-overly').css("display", "block");
    });
    $("#mobile-body-overly").click(function(){
        $('body').removeClass('mobile-nav-active');
        $('#mobile-body-overly').css("display", "none");
    });

    /*modal js */
    $(".termandcondition").click(function(){
        $('body,html').addClass('modal-open');
        $('#termandcondition').show();
        $('#mobile-body-overly').css("display", "block");
    });
    $(".privacypolicy").click(function(){
        $('body,html').addClass('modal-open');
        $('#privacypolicy').show();
        $('#mobile-body-overly').css("display", "block");
    });
    $(".modal .close").click(function(){
        $('body,html').removeClass('modal-open');
        $('#mobile-body-overly').css("display", "none");
        $('#privacypolicy').hide();
        $('#termandcondition').hide();
        document.title =APP_NAME;
        $('.scroll_dowo.active').removeClass('active');
    });


});


$(document).ready(function(){
    //prevent the default action for the click event
    var is_first = true;
    $('#header ul li a').click(function(event){
     var buffer_margin = 0;
     
     if(is_first){
      buffer_margin = 0;
      is_first=false;
     }
     else{
      buffer_margin = 100;
     }
     //console.log(buffer_margin);
     //event.preventDefault();
     //get the full url - like mysitecom/index.htm#home
     var full_url = this.href;
     
     //split the url by # and get the anchor target name - home in mysitecom/index.htm#home
     var parts = full_url.split("#");
     
     var trgt = parts[1];
      //get the top offset of the target anchor
      var target_offset = $("#"+trgt).offset();   
     if(target_offset !== 'undefined' && $.trim(target_offset) !== ''){ 
      var top_margin = $('header').height()+ buffer_margin;
      var target_top = target_offset.top - top_margin;
      
      //goto that anchor by setting the body scroll top to anchor top
      $('html, body').animate({scrollTop:target_top}, 1500);
    //   $('#header').addClass('header-scrolled');
     }
     
    });
   });  

if (document.getElementById("home")) {
  var type = window.location.hash.substr(1);
  window.history.pushState({url: "" + APP_URL + ""}, '', APP_URL);
  $(document).ready(function(){
    if(type){ 
        $('.scroll_dowo.active').removeClass('active');
          $("#"+type+'_').addClass('active');
           LinkTitle = $("#"+type+'_').attr('title');
           //document.title =LinkTitle;
          document.onload = setTimeout(function () { $('html, body').animate({
              scrollTop: $("#"+type).offset().top-70
          }, 500); }, 500);
    }   
  });
}
$(document).on('click', 'a[href^="#"]', function(e) { 
    // target element id
    var id = $(this).attr('href');
    LinkTitle = $(this).attr('title');
    $('.scroll_dowo.active').removeClass('active');
    $(this).addClass('active');
    window.history.pushState({url: "" + APP_URL + ""}, LinkTitle, '');
    document.title =LinkTitle;
    // target element
    var $id = $(id);
    if ($id.length === 0) {
        return;
    }
      // prevent standard hash navigation (avoid blinking in IE)
      e.preventDefault();
      
      // top position relative to the document
      
      if(LinkTitle=='Why Us'){
         var pos = $id.offset().top -133;
         $('body, html').animate({scrollTop: pos});
      }else if(LinkTitle=='About Us'){
         var pos = $id.offset().top;
         $('body, html').animate({scrollTop: pos});
      }else{
        var pos = $id.offset().top -65;
        $('body, html').animate({scrollTop: pos});
      }
});
/*scroller bar*/