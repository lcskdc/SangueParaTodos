$(function(){
    
    atualizaCaptcha();
    
    $('.denunciar_erro').click(function() {
        document.frmDenuncia.reset();
        $('#idDemanda').val($(this).attr("rel"));
        $('#modalDenunciarErro').modal();
    });
    
    $('#btn-envia-denuncia').click(function(){
        var acao = document.frmDenuncia.acao.value;
        var denuncia = $.trim($('#observacao_denuncia').val());
        var erro = false;
        $('#frmDenuncia .alert').remove();
        
        if(!acao>0 || acao==undefined) {
            erro = true;
            $('#frmDenuncia').prepend('<div class="alert alert-danger">É necessário informar o tipo</div>');
        } else if(acao==3 && (denuncia == "" || denuncia == undefined)) {
            erro = true;
            $('#frmDenuncia').prepend('<div class="alert alert-danger">É necessário informar a descrição do erro</div>');            
        }
        
        if(!erro) {
            $('#registrando').removeClass('escondido');
            $('#controles').addClass('escondido');
            //resposta_captcha 
            $.post('/Demanda/denuncia',$('#frmDenuncia').serialize(),function(data){
               var ret = $.parseJSON(data);
               if(ret.status=='ok') {
                 document.location.reload();
               } else {
                 $('#frmDenuncia').prepend('<div class="alert alert-danger">'+ret.msg+'</div>');
                 $('#registrando').addClass('escondido');
                 $('#controles').removeClass('escondido');
                 atualizaCaptcha();
               }
            });
        }
        return false;
    });
    
    $('#cancelar-denuncia').click(function(){
        $('#modalDenunciarErro').modal('hide');
    });

});

function atualizaCaptcha() {
    $.get('/Seguranca/captcha',function(data) {
        if($('#questao-captcha').length==0) {
            $('#controles').before(data);
        }else {
            $('#questao-captcha').html(data);
            
        }
    });
}