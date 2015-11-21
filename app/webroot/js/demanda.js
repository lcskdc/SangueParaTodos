$(function() {
    $('#aceite-termo-de-uso').change(function(){
       $('#btn_cadastro').prop('disabled',!$(this).prop('checked'));
    });
    
    $('#btn_utiliza_local_manual').click(function(){
       $('#adicionar_local').hide();
       $('#verificado').val('S');
       $('#frmDemanda').submit();
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
    
    $('#frmDemanda').submit(function(){
        var posicao = $('#posicao').val();
        var id_local = $('#id_local').val();
        var verificado = $('#verificado').val();
        if(posicao!=""&&!id_local>0&&verificado=='N') {
            var p = posicao.split(',');
            $.getJSON('/Local/itens_proximos/'+p[0]+'/'+p[1],function(data){
              $('.list-group .list-group-item').remove();
              $.each(data,function(k,v) {
                 $('.list-group').append('<a href="#self" class="list-group-item" rel="'+v.id+'"><h4 class="list-group-item-heading" rel="'+v.nome+'">'+v.nome+' ['+v.distancia+'km]</h4><div class="list-group-item-text">'+v.endereco+'</div></a>');
              });
              $('.list-group .list-group-item').click(function(){
                var id_local = $(this).attr('rel');
                var local = $(this).find('h4').attr('rel');
                $('#adicionar_local').hide();
                $('#id_local').val(id_local);
                $('#instituicao').val(local);
                $('#verificado').val('S');
                $('#frmDemanda').submit();
              });
              $('#modalLocaisProximos').modal('show');
            });
            return false;
        }
        
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