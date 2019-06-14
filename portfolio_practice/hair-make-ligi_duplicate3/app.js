$(function(){
   $('a[href^="#"]').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false; 
   });
    
   //SPメニュー
    $('.js-toggle-sp-menu').on('click', function(){
        $(this).toggleClass('active');
        $('.js-toggle-sp-menu-target').toggleClass('active');
    });
        
    //SP-MENU-CLOSE　参考 https://teratail.com/questions/128115
    $('.nav-menu a').on('click', function() {
      $('.nav-menu').toggleClass('active');
    });
});