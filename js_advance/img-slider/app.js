var currentItemNum = 1;
var $slideContainer = $('.slider__container');
var slideItemNum = $('.slider__item').length;
var slideItemWidth = $('.slider__item').innerWidth();
var slideContainerWidth = slideItemWidth * slideItemNum;
var DURATION = 500;

$slideContainer.attr('style', 'width:' + slideContainerWidth + 'px');


$('.js-slide-next').on('click',function(){
    if(currentItemNum < slideItemNum){
        $slideContainer.animate({left: '-=' + slideItemWidth + 'px'}, DURATION);
        currentItemNum++;
    }
});
$('.js-slide-prev').on('click',function(){
    if(currentItemNum > 1){
        $slideContainer.animate({left: '+=' + slideItemWidth + 'px'},DURATION);
        currentItemNum--;
    }
});

// クロージャを使った書き方。
var slider = (function(){
    var currentItemNum = 1;
    var $slideContainer = $('.slider__container');
    var slideItemNum = $('.slider__item').length;
    var slideItemWidth = $('.slider__item').innerWidth();
    var slideContainerWidth = slideItemWidth * slideItemNum;
    var DURATION = 500;
    
    return {
        slidePrev: function(){
            if(currentItemNum > 1){
              $slideContainer.animate({left: '+=' + slideItemWidth + 'px'},DURATION);
              currentItemNum--;
            }
        },
        slideNext: function(){
            if(currentItemNum < slideItemNum){
              $slideContainer.animate({left: '-=' + slideItemWidth + 'px'}, DURATION);
              currentItemNum++;
            }
        },
        init: function(){
            $slideContainer.attr('style', 'width:' + slideContainerWidth + 'px');
            var that = this;
            $('.js-slide-next').on('click', function(){
                that.slideNext();
            });
            $('.js-slide-prev').on('click', function(){
                that.slidePrev();
            });
        }
    }

});


//1の時に、prevを消す。
//5の時に、nextを消す。
//自動で切り替わる。

//上のこんな簡単な課題もできないのかよ。