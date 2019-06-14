$(function(){
    
    var targetHeight = $('.js-float-menu-target').height();
    $(window).on('scroll' , function(){
        $('.js-float-menu').toggleClass('float-active', $(this).scrollTop() > targetHeight);
    });
    
    $('.js-toggle-sp-menu').on('click', function(){
        $(this).toggleClass('active');
        $('.js-toggle-sp-menu-target').toggleClass('active');
    });
});