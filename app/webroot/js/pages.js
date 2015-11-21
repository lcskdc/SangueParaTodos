  $(function() {
    
    $('#hp-define-local').click(function(){
        var endereco = $(this).val();
        var cidade = $('#hp-cidade').val();
        var uf = $('#hp-uf').val();
        endereco = endereco+', '+cidade+', '+uf;      
        definirLocal(endereco);
    });
    
    $('.hp-outro-end').click(function(){
      $("#lista_locais li").remove();
      $(this).hide();
      disabledCtrls();
      $('#hp-endereco').val('');
      $('#hp-uf option:first').prop('selected','selected');
      $('#hp-cidade option').attr('remove');
      $('#hp-cidade').append('<option value="">Selecione a cidade</option>')
      $('#hp-uf').removeAttr('disabled');
      $('#hp-define-local').show();
    });

    $('#hp-endereco').keyup(function(e) {
      if (e.keyCode == 13) {
        var endereco = $(this).val();
        var cidade = $('#hp-cidade').val();
        var uf = $('#hp-uf').val();
        endereco = endereco+', '+cidade+', '+uf;
        definirLocal(endereco);
      }
    });
    
    $('#hp-cidade').change(function(){
      var cidade = $(this).val();
      if(cidade!="") {
        $('#hp-endereco').select().focus();
        $('#hp-endereco, #hp-define-local').removeAttr('disabled','disabled');
      } else {
        $('#hp-endereco, #hp-define-local').attr('disabled','disabled');
      }
    });
    
    $('#hp-uf').change(function(){
      var uf = $(this).val();
      if(uf=="0") {
         $('#hp-cidade, #hp-endereco, #hp-define-local').attr('disabled','disabled');
         $('#hp-cidade option:first').attr("selected","selected");
      } else {
        $.getJSON('/Login/cidades/'+uf,function(data) {
          $('#hp-cidade option').remove();
          $('#hp-cidade').append('<option value="">Selecione a cidade</option>');
          $.each(data,function(k,v) {
            $('#hp-cidade').append('<option value="'+v.Cidade.nome+'">'+v.Cidade.nome+'</option>');
          });
          $('#hp-cidade').removeAttr('disabled');
          $('#hp-cidade').focus();
        });
      }
    });
    
    $('[data-toggle="tooltip"]').tooltip()

    $('.icon-minha-localizacao').click(function(){
      getLocalizacao();
    });

    $('.icon-edit-localizacao').click(function() {
      $('#modalAlteraEndereco').modal();
      $('.hp-outro-end').hide();
      $("#lista_locais li").remove();
      disabledCtrls();
      $('#hp-endereco').val('');
      $('#hp-uf option:first').prop('selected','selected');
      $('#hp-cidade option').attr('remove');
      $('#hp-cidade').append('<option value="">Selecione a cidade</option>')
      $('#hp-uf').removeAttr('disabled');
      $('#hp-define-local').show();
    });
  });
  
  function disabledCtrls() {
    $('#hp-uf, #hp-cidade, #hp-define-local, #hp-endereco').attr('disabled','disabled');
  }
  
  function enabledCtrls() {
    $('#hp-uf, #hp-cidade, #hp-define-local, #hp-endereco').removeAttr('disabled');
  }
  
  function definirLocal(endereco) {
    disabledCtrls();
    buscaEndereco(endereco).done(function(data) {
      if (data.status == "OK") {
        $('#endereco').removeClass('localNaoEncontrado').prop("title", "Informe o endereço");
        if (data.results.length > 1) {
          $("#lista_locais").remove();
          var lista = document.createElement('ul');
          $.each(data.results, function(k, v) {
            $(lista).prop("id", "lista_locais");
            var g = v.geometry.location.lat + ',' + v.geometry.location.lng;
            $(lista).append('<li rel="' + g + '">' + v.formatted_address + '</li>');
            $("#modalAlteraEndereco .modal-body").append(lista);
          });
          $('.hp-outro-end').show();
          $('#hp-define-local').hide();
          $("#lista_locais li").click(function() {
            $('#hp-endereco').val($(this).attr("rel"));
            var g = $('#hp-endereco').val().split(",");
            $("#modalLocais").modal("hide");
            $("#lista_locais").remove();

            $.post('/Local/setLocalizacaoUsuario',{latitude: g[0], longitude: g[1]},function(){
              criarMapaLocalizacaoManual({latitude: g[0], longitude: g[1]});
            });

            $("#modalAlteraEndereco").modal("hide");
          });
        } else {
          var loc = data.results[0].geometry.location;
          $('#hp-endereco').val(loc.lat + ',' + loc.lng);

          $.post('/Local/setLocalizacaoUsuario',{latitude: loc.lat, longitude: loc.lng},function(){
            criarMapaLocalizacaoManual({latitude: loc.lat, longitude: loc.lng});
          });

          $("#modalAlteraEndereco").modal("hide");
        }
      } else if (data.status == "ZERO_RESULTS") {
        $('#endereco').addClass('localNaoEncontrado').prop("title", "O endereço informado não foi encontrado");
        enabledCtrls();
      }
    });
  }