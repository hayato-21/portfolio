$(function(){
    $('button').click(function(){
        $('html,body').animate({scrollTop:0}, 500, 'swing');
    });
    
    $('a[href^="#"]').click(function(){
    var speed = 500;
    var href= $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    $("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
    // ボタンタグをヘッダーに使い、自動ページ移動
//    $("html,body").animate({
//        scrollTop:$('#salon').offset().top});
//    $("html,body").animate({
//        scrollTop:$('#stylist').offset().top});
//    $("html,body").animate({
//        scrollTop:$('#price').offset().top});
//    $("html,body").animate({
//        scrollTop:$('#access').offset().top});
    
  //参考サイト  https://techacademy.jp/magazine/9532
});