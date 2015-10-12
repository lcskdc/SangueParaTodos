$(function() {

    $('#local').focus();
    criarMapa();
    $("#btn-fechar").click(function() {
        window.close();
        opener.focus();
    });

    $('#btn-adicionar').click(function() {
        opener.adicionaLocal($('#local').val(), $('#posicao').val());
        window.close();
        opener.focus();
    });

    $("#aBuscaLocal").click(function() {
        buscaLocal();
    });

    $('#local').keyup(function(e) {
        if (!$("#imgLoading").is(":visible")) {
            if (e.keyCode == 13) {
                buscaLocal();
            } else {
                $('#btn-adicionar').attr("disabled", "disabled");
            }
        }
    });
});

function buscaLocal() {
    $('#imgLoading').show();
    
    buscaEndereco($('#local').val()).done(function(data) {
        if (data.status == "OK") {
            $('#local').removeClass('localNaoEncontrado').prop("title", "Informe o endereço");
            if (data.results.length > 1) {
                $('#btn-adicionar').attr("disabled", "disabled");
                $("#lista_locais").remove();
                var lista = document.createElement('ul');
                $.each(data.results, function(k, v) {
                    $(lista).prop("id", "lista_locais");
                    var g = v.geometry.location.lat + ',' + v.geometry.location.lng;
                    $(lista).append('<li rel="' + g + '">' + v.formatted_address + '</li>');
                    $("#modalLocais .modal-body").append(lista);
                    $("#modalLocais").modal("show");
                });
                $("#lista_locais li").click(function() {
                    $('#local').val($(this).text());
                    $('#posicao').val($(this).attr("rel"));
                    var g = $('#posicao').val().split(",");
                    $("#modalLocais").modal("hide");
                    $("#lista_locais").remove();
                    setPosicaoMapa({descricao: $('#local').val(), latitude: g[0], longitude: g[1]});
                    $('#btn-adicionar').attr("disabled", false);
                });
                $('#aBuscaLocal').show();
                $('#imgLoading').hide();                
            } else {
                var loc = data.results[0].geometry.location;
                $('#posicao').val(loc.lat + ',' + loc.lng);
                setPosicaoMapa({descricao: $('#local').val(), latitude: loc.lat, longitude: loc.lng});
                $('#btn-adicionar').attr("disabled", false);
                $('#aBuscaLocal').show();
                $('#imgLoading').hide();
            }
        } else if (data.status == "ZERO_RESULTS") {
            removeMarcacaoMapa();
            $('#posicao').val('');
            $('#local').addClass('localNaoEncontrado').prop("title", "O endereço informado não foi encontrado");
            $('#btn-adicionar').attr("disabled", "disabled");
            $('#aBuscaLocal').show();
            $('#imgLoading').hide();
        }
    });
    
}
