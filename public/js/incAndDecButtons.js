var incrementSizeVar = 0;
var incrementSpamAmount = 0;
$(function() {
    $(".inc").click(function() {
        if(isNaN(parseInt($(":input[name='size']").val())) ){
            var newValue =1;
        }else{
            var newValue = parseInt($(":input[name='size']").val()) + 1;
        }
        $(":input[name='size']").val(newValue);
        $('.inc').addClass('a' + newValue);
        incrementSizeVar = incrementSizeVar + newValue;
    });
    $(".dec").click(function() {
        if(isNaN(parseInt($(":input[name='size']").val()))){
            var newValue =1;
        }else{
            if(parseInt($(":input[name='size']").val()) <= 0){
                var newValue = 0;
            }else{
                var newValue = parseInt($(":input[name='size']").val()) - 1;
            }
        }
        $(":input[name='size']").val(newValue);
        $('.inc').addClass('a' + newValue);
        incrementSizeVar = incrementSizeVar + newValue;
    });
});
$(function() {
    $(".incSpam").click(function() {
        if(isNaN(parseInt($(":input[name='spam_amount']").val())) ){
            var newValue =1;
        }else{
            if(parseInt($(":input[name='size']").val()) >= 100){
                var newValue = 100;
            }else{
                var newValue = parseInt($(":input[name='spam_amount']").val()) + 1;
            }
        }
        $(":input[name='spam_amount']").val(newValue);
        $('.inc').addClass('a' + newValue);
        incrementSpamAmount = incrementSpamAmount + newValue;
    });
    $(".decSpam").click(function() {
        if(isNaN(parseInt($(":input[name='spam_amount']").val()))){
            var newValue =1;
        }else{
            if(parseInt($(":input[name='spam_amount']").val()) <= 0){
                var newValue = 0;
            }else{
                var newValue = parseInt($(":input[name='spam_amount']").val()) - 1;
            }
        }
        $(":input[name='spam_amount']").val(newValue);
        $('.inc').addClass('a' + newValue);
        incrementSpamAmount = incrementSpamAmount + newValue;
    });
});