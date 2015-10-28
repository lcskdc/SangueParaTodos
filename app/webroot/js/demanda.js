$(function() {
    $('#aceite-termo-de-uso').change(function(){
       $('#btn_cadastro').prop('disabled',!$(this).prop('checked'));
    });
    
    $('#validade').mask('99/99/9999');
    $('#adicionar_local').click(function() {
        var jnl = window.open("/Demanda/mapa","jnl","width=750,height=500,history=no,toolbars=no,location=yes");
        if(jnl) {
            jnl.focus();
        }
    });

    $('#instituicao').autocomplete({
        serviceUrl: '/Demanda/instituicoes',
        minChars: 3,
        onSelect: function (s) {
            var info = $.parseJSON(s.informacoes);
            adicionaLocal(info.local, info.posicao);
            $('#id_local').val(info.id);
        }
    });
    
    $('#todosTiposSangue').click(function(){
        $('.ckTipoSangue').each(function(k,v){
          $(v).prop("checked",!$(v).prop("checked"));
        });
    });

});

function adicionaLocal(local, posicao) {
    var elmLocal = document.createElement('div');
    var txtLocal = document.createElement('input');
    var txtPosicao = document.createElement('input');
    $('#id_local').val('');
    $(".local").remove();
    $(elmLocal).addClass("local emLinha").text("Local: "+local).attr("title",local);
    $(txtLocal).attr({
       type:'hidden',
       id:'local',
       name:'local'
    }).val(local).appendTo(elmLocal);
    $(txtPosicao).attr({
       type:'hidden',
       id:'posicao',
       name:'posicao'
    }).val(posicao).appendTo(elmLocal);
    $('#adicionar_local').parent().before(elmLocal);
}