var user_id = null;
var fbPicture = null;
var usuarioId = null;
// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
    if (response.status === 'connected') {
        var r = getUsuarioFacebook();
        hideBtnFacebook();
    } else if (response.status === 'not_authorized') {
        //nao faz nada
    } else {
        //nao faz nada
    }
}

function hideBtnFacebook() {
    $('.fb-login-button').hide();
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
}

/**
 * 
 * Metodo que incializa a API do Facebook
 * @param {String} appId
 * @param {boolean} cookie
 * @param {boolean} xfbml
 * @param {String} version
 * @returns {Facebook Object}
 */
window.fbAsyncInit = function() {
    FB.init({
        appId: '560560374073693',
        cookie: true, // enable cookies to allow the server to access 
        xfbml: true, // parse social plugins on this page
        version: 'v2.5' // use version 2.1
    });
    checkLoginState();
};

function facebookLogout() {
    FB.logout(function(response) {
        document.location.href = '/Login/sair/';
    });
}

/**
 * 
 * @returns {facebook login response}
 */
function facebookLogin(callback) {
    disabledControles();
    FB.login(function(response) {
        if (response.authResponse) {
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID
            url_img = "";
            FB.api('/me?fields=picture,email,name,gender', function(response) {
                url_img = response.picture.data.url;
                var data = {email: response.email, nome: response.name, id: response.id, sexo: response.gender, urlImagem: url_img, tipo: 'facebook'}
                efetuaLoginSocial(data, callback);
            });
        } else {
            enabledControles();
        }
    }, {
        scope: 'public_profile,email,user_friends'
    });
    
}

function testeFacebookLogin() {
    FB.api('/me?fields=picture,email,name,gender', function(response) {
        url_img = response.picture.data.url;
        var data = {email: response.email, nome: response.name, id: response.id, sexo: response.gender, urlImagem: url_img, tipo: 'facebook'}
        console.log('resp: '+response);
        efetuaLoginSocial(data, callback);
    });
    console.log('Passou aqui function teste');
}

/**
 * Insere script Facebook Assincrono
 * @param {type} d
 * @param {type} s
 * @param {type} id
 * @returns {undefined}
 */
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

/**
 * Método que retorna o objeto Usuário DO Facebook
 * @returns {Objeto Usuario Facebook}
 */
function getUsuarioFacebook(isLogout) {
    isLogout = isLogout == undefined ? false : isLogout;
    FB.api('/me', function(response) {
        return response;
    });
}

function compartilharSolicitacao(idUsuario, colaborador_id, solicitacao_id,callback) {

    if(idUsuario>0) {
        $.post('/Demanda/gera_identificacao', {solicitacao_id: solicitacao_id, colaborador_id: colaborador_id}, function(data) {
            var gkey = $.parseJSON(data).chave;
            var rnd_img = Math.ceil(Math.random() * 5);
            var server = 'www.sangueparatodos.com.br';
            //server = 'localhost:9090';

            var params = {
                method: 'feed',
                message : 'Doe sangue e salve vidas',
                name : 'Doação de sangue',
                description : 'Um amigo precisa de doações, você pode obter mais detalhes através do endereço http://' + server + '/Local/vdemanda?k=' + gkey + '&v={user-id}',
                link:'http://' + server + '/Local/vdemanda?k=' + gkey + '&v={user-id}',
                picture:'http://www.sangueparatodos.com.br/img/compartilhar-solicitacao-' + rnd_img + '.jpg',
                caption:'Sua ajuda pode salvar vidas',
                fb_ref:gkey,
                fb_source:'home_multiline'
            };

            var opts = {};
            
            FB.ui(params,
                function(response) {
                  if (response && response.post_id) {
                    $.post('/Demanda/compartilhado', {colaborador: colaborador_id, demanda: solicitacao_id});
                    var msgHtml = '<p><img src="/img/compartilhar-solicitacao-1.jpg" />&nbsp;Obrigado por nos ajudar a divulgar uma solicitação.</p>';
                    $('#modalSolicitacaoCompartilhada .modal-body').html(msgHtml);                    
                    if(callback!=undefined) {
                        $('#modalSolicitacaoCompartilhada .close').remove();
                        $('#modalSolicitacaoCompartilhada .modal-body').append('<p><a href="/Local/demandas">Fechar esta janela</a></p>');
                        opts = {backdrop:'static',keyboard:false};
                    }
                    $('#modalSolicitacaoCompartilhada').modal(opts);
                  }
                }
            );
        });
    }else{
        facebookLogin(
            function(){
                compartilhaDemandaAposLogin(colaborador_id,solicitacao_id);
            }
        );
    }

}

function compartilhaDemandaAposLogin(colaborador_id,solicitacao_id) {
    compartilharSolicitacao(usuarioId,colaborador_id,solicitacao_id,'redirect');
}

function efetuaLoginSocial(send, callback) {

    /*
     * email,
     * nome,
     * id,
     * sexo,
     * urlImagem,
     * tipo
     */

    $.post('/Login/loginSocial/', send, function(data) {
        var ret = $.parseJSON(data);
        usuarioId = ret.id;
        if (callback != undefined) {
            callback();
        } else {
            document.location.href = '/Login/interno/';
        }
    });
}

function disabledControles() {
    if($('#info-processando-login').length==0) {
        $('#btn-login').before('<div id="info-processando-login"><img src="/img/carregando_p.gif" align="absmiddle" />&nbsp;Processando seu pedido</div>');
    }
    $('#controles .btn, #email, #senha').attr('disabled','disabled');
}

function enabledControles() {
    $('#info-processando-login').remove();
    $('#controles .btn, #email, #senha').removeAttr('disabled');
}