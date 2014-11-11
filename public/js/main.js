$(document).ready(function(){
    $(".page").click(function(){
        $('.searchform').attr('action',$(this).attr('href'));
        $('.searchform').submit();
        return false;
    });
    
    $('#photoimg').change(function(){
        $("#preview").append($("#preview1").html());
        $("#preview1").html('<img src="/img/loading.gif" align="absmiddle"  alt="Загрузка...."/>');
        //$("#preview1").show();
        $("#imageform").ajaxForm({target: '#preview1'}).submit();
        
        setTimeout(function(){
            $('#photoimg').val('');
        },2000);
    });
});

