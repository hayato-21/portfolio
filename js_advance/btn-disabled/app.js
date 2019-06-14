$(function(){
    $('.js-form-validate').on('keyup',function(){
        if($(this).val()){
            $('.js-disabled-submit').prop('disabled', false);
        }else{
            $('.js-disabled-submit').prop('disabled', true);
        }
    });
});