$(function(){
    $('.js-show-modal').on('click',function(){
        var modalWidth = $('.js-show-modal-target').width();
        var windowWidth = $(window).width();
        $('.js-show-modal-target').attr('style','margin-left:'+(windowWidth/2 - modalWidth/2) + 'px');
        $('.js-show-modal-target').show();
        $('.js-show-modal-cover').show();
    });
    $('.js-hide-modal').on('click',function(){
        $('.js-show-modal-target').hide();
        $('.js-show-modal-cover').hide();
    })
});