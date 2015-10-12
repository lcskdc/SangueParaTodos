$(function() {
    $('#nascimento').mask('99/99/9999');
    $('#telefone').mask('(99)9999-99999');

    $('#btn-login-facebook').click(function() {
        facebookLogin();
        return false;
    });

    $('#alterar-senha').click(function() {

        var htmlSenha = '<p id="psenha"><label for="senha"><img src="/img/key_icon.gif" align="absmiddle" />Senha<span class="require"> *</span></label><input type="password" placeholder="Senha" name="senha" id="senha" value="" class="form-control" /></p><p id="pconfirmasenha"><label for="confirmaSenha"><img src="/img/key_icon.gif" align="absmiddle" />Confirmação<span class="require"> *</span></label><input type="password" name="confirmaSenha" placeholder="Confirme a senha" id="confirmaSenha" value="" class="form-control" /></p>';
        $(this).parent().after(htmlSenha);
        $(this).parent().hide();
        $('#senha').focus();
    });

    $('#uf').change(function() {
        var uf = $(this).val();
        $('#cidade').attr('disabled', 'disabled');
        $('#cidade').after('<img class="img-loading" src="/img/carregando_p.gif" />');
        if (uf != "") {
            $.getJSON('/Login/cidades/' + uf, function(data) {
                $('#cidade option').remove();
                $('#cidade').append('<option value="">Escolha</option>');
                $.each(data, function(k, v) {
                    $('#cidade').append('<option value="' + v.Cidade.id + '">' + v.Cidade.nome + '</option>');
                });
                $('#cidade').removeAttr('disabled');
                $(".img-loading").remove();
            });
        } else {
            $('#cidade option').remove();
            $(".img-loading").remove();
            $('#cidade').append('<option value="">Escolha</option>');
        }

    });

});

function validateEmail(email) {
    //var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var re = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
    return re.test(email);
}

function validaFormLogin() {
    if (!validateEmail($('#email').val()))
        return false;
    if ($('#senha').val().length < 6)
        return false;
    return true;
}

function enviaFormLogin() {
    if (validaFormLogin()) {
        $.post('/Login/valida/', {email: $('#email').val(), senha: $('#senha').val()}, function(data) {
            data = $.parseJSON(data);
            
            if (data.isLogado == true) {
                document.location.href = '/Login/interno/';
            } else {
                $('#modalUsuarioSenhaInvalido').modal();
                $('#controles .btn').removeAttr('disabled');
            }
        });
    } else {
        $('#modalEmailSenhaIncorretos').modal();
        $('#controles .btn').removeAttr('disabled');
    }
}
