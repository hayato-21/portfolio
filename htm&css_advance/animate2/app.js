//参考サイト  https://designsupply-web.com/knowledgeside/4141/
//位置を取得して、横からのスライドのアニメーション
$(function(){
  const obj = $(".scroll-animation-obj");
  const hopIn = $(".scroll-animation-hop");
  const leftIn = $(".scroll-animation-left");
  const rightIn = $(".scroll-animation-right");
  $(window).on('scroll',function(){  // eachはマッチした各要素に対して指定した関数を実行します。
    obj.each(function(){
      const objPos = $(this).offset().top;  // offset()は、マッチしている要素集合の1つ目の要素のdocumentを基準にした現在の座標を取得します。display:noneや、hidden要素、border,margin,paddingを考慮しての位置計算はサポートしない。visibility:hiddenが設定された要素の座標の取得が可能。
      // この場合、指定された要素をトップからの位置を取得するもの。
      const scroll = $(window).scrollTop();
      const windowH = $(window).height();
      if(scroll > (objPos + objPos*0.1) - windowH){  //(obj*0.3+obj)とやれば、70％の位置でできるのでは、ないか？  //もとのは、objPos
        $(this).css({
          'opacity': '1'
        });
      } else {
        $(this).css({
          'opacity': '0'
        });
      }
    });
    hopIn.each(function(){
      const objPos = $(this).offset().top;
      const scroll = $(window).scrollTop();
      const windowH = $(window).height();
      if(scroll > (objPos + objPos*0.1) - windowH){
        $(this).css({
          'transform': 'translate(0,0)'
        });
      } else {
        $(this).css({
          'transform': 'translate(0,60px)'
        });
      }
    });
    leftIn.each(function(){
      const objPos = $(this).offset().top;
      const scroll = $(window).scrollTop();
      const windowH = $(window).height();
      if(scroll > (objPos + objPos*0.1) - windowH){
        $(this).css({
          'transform': 'translate(0,0)'
        });
      } else {
        $(this).css({
          'transform': 'translate(-120px,0)'
        });
      }
    });
    rightIn.each(function(){
      const objPos = $(this).offset().top;
      const scroll = $(window).scrollTop();
      const windowH = $(window).height();
      if(scroll > (objPos + objPos*0.1) - windowH){
        $(this).css({
          'transform': 'translate(0,0)'
        });
      } else {
        $(this).css({
          'transform': 'translate(120px,0)'
        });
      }
    });
  });
});
// スムースなナビゲーション
// 参考サイト https://techacademy.jp/magazine/9532
//$(function(){
//  $('a[href^="#"]').click(function(){
//    var speed = 500;
//    var href= $(this).attr("href");
//    var target = $(href == "#" || href == "" ? 'html' : href);  //説明があるサイト　https://changeup.tech/article/jquery-smooth-scroll/
//    var position = target.offset().top;
//    $("html, body").animate({scrollTop:position}, speed, "swing");
//    return false; //falseにしないと、リンクが飛んでしまうため。
//  });
//});

// 家でやること　js中級の理解（Ajaxを除く）、html中上級の理解。phpのコードを見たい。
// ここですること　模写2をする。phpBtoCのhtmlを少しずつつくっていく。
//