$(function() {
    
    $('#btn-info-ativacao').click(function(){
        $('#modalMsgAtivacao').modal();
    });
    
    $('#btn-reenvia-email').click(function(){
        $(this).after('enviando<img src="/img/carregando_p.gif" />').remove();
        $.post('/Login/reenvia_email',function(){
            document.location.href = '/Login/interno/';
        });
    });
    
});