$(window).scroll(function(){if($(this).scrollTop()>20){$('header').addClass("sticky");}
else{$('header').removeClass("sticky");}});

//price //
$('#pricing-rupee').click(function () {
    $('#pricing-dollar').removeClass('show');
    $('#pricing-rupee').addClass('show');
    $('#rupee_value').removeClass('hidden');
    $('#rupee_value2').removeClass('hidden');
    $('#dollar_value').addClass('hidden');
    $('#dollar_value2').addClass('hidden');
});
$('#pricing-dollar').click(function () {
    $('#pricing-rupee').removeClass('show');
    $('#pricing-dollar').addClass('show');
    $('#rupee_value').addClass('hidden');
    $('#rupee_value2').addClass('hidden');
    $('#dollar_value').removeClass('hidden');
    $('#dollar_value2').removeClass('hidden');
});