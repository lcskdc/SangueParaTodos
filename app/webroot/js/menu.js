var tempo = null;

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
    
    tempo = window.setInterval('removeMensagemPortal()',3500);
    
});

function removeMensagemPortal() {
    $('.msgs-portal').animate({top: '0',opacity: '0'},450,function(){
        $('.msgs-portal').remove();
    });
    window.clearInterval(tempo);
}
   