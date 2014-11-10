$(document).ready(function(){
    $(".page").click(function(){
        $('.searchform').attr('action',$(this).attr('href'));
        $('.searchform').submit();
        return false;
    });
});

