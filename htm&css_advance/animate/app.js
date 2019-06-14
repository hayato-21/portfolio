// https://webdesignday.jp/inspiration/technique/3636/
// もし、とりたすさんみたいな二段階アニメーションにしたい場合は、処理の最後にまた処理を加える。milkさんのMaxにコピーしているやつ。
$(function(){
   
    // pageTopへのナビゲーション
    $('button').click(function(){
        $('html,body').animate({scrollTop:0}, 500, 'swing');
    });
    
    /* スクロールした時にアニメーション。 */
    /* スーパーごり押し。 */
    var container = $('.container').height();
    
    $(window).on('scroll', function(){
        $('.animation-box').toggleClass('active', $(this).scrollTop() > container*0.3);
    });
    
    $(window).on('scroll', function(){
        $('.animation-box2').toggleClass('active', $(this).scrollTop() > container*0.7);
    });
    
     $(window).on('scroll', function(){
        $('.animation-box3').toggleClass('active', $(this).scrollTop() > container*1);
    });
    
    /* ちょっと細かいアニメーション */
    var animation = $('.animation-box').height();
    var animation2 = $('.animation-box2').height();
    var animation3 = $('.animation-box3').height();
    var animationTotalHeight = animation + animation2 + animation3;
    
    $(window).on('scroll', function(){
        $('.img-container').toggleClass('active',$(this).scrollTop() > container + animationTotalHeight + animation);
        
        $('.text-container').toggleClass('active',$(this).scrollTop() > container + animationTotalHeight + animation);
    });
});