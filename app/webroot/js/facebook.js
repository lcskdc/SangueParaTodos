var fbPicture = null;
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
        version: 'v2.4' // use version 2.1
    });
    checkLoginState();
};

function facebookLogout() {
    FB.logout(function(response) {
        document.location.href = '/Login/sair/';
    });
}

function onFriendsListLoaded(response)
{
  var divTarget=document.getElementById("friends-list-container");
  var data=response.data;
  //alert("divTarget="+divTarget+"ndata="+data);
  for (var friendIndex=0; friendIndex<data.length; friendIndex++)
  {
      console.log(friendIndex);
    var divContainer = document.createElement("div");
    divContainer.innerHTML="<hr><img src='http://graph.facebook.com/"+data[friendIndex].id+"/picture'></img>"+
"<br>"+data[friendIndex].name;
    divTarget.appendChild(divContainer);
  }
}
 
/**
 * 
 * @returns {facebook login response}
 */
function facebookLogin(callback) {
    FB.login(function(response) {
        if (response.authResponse) {
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID
            url_img = "";
            FB.api('/me', function(response) {

                FB.api('/me/friends', onFriendsListLoaded);
                /*    
                 FB.api('/me/?fields=picture', function(resp) {
                 url_img = resp.picture.data.url;
                 var data = {email: response.email, nome: response.name, id: response.id, sexo: response.gender, urlImagem: url_img, tipo: 'facebook'}
                 console.log(callback);
                 efetuaLoginSocial(data, callback);
                 });
                 */
            });

        }
    }, {
        scope: 'public_profile,email,publish_actions,user_friends'
    });
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

function sortMethod(a, b) {
    var x = a.name.toLowerCase();
    var y = b.name.toLowerCase();
    return ((x < y) ? -1 : ((x > y) ? 1 : 0));
}


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

function compartilharSolicitacao(colaborador_id, solicitacao_id) {

    var gkey;
    $.post('/Demanda/gera_identificacao', {solicitacao_id: solicitacao_id, colaborador_id: colaborador_id}, function(data) {
        var gkey = $.parseJSON(data).chave;
        var params = {};
        var rnd_img = Math.ceil(Math.random() * 5);
        var server = 'www.sangueparatodos.com.br';
        server = 'localhost:9090';

        params['message'] = 'Doe sangue e salve vidas';
        params['name'] = 'Doação de sangue';
        params['description'] = 'Um amigo precisa de doações, você pode obter mais detalhes através do endereço http://' + server + '/Local/vdemanda?k=' + gkey + '&v={user-id}';
        params['link'] = 'http://' + server + '/Local/vdemanda?k=' + gkey + '&v={user-id}';
        params['picture'] = 'http://www.sangueparatodos.com.br/img/compartilhar-solicitacao-' + rnd_img + '.jpg';
        params['caption'] = 'Sua ajuda pode salvar vidas';
        params['fb_ref'] = gkey;
        params['fb_source'] = 'home_multiline';


        $('#modalSolicitacaoCompartilhada .modal-body').html('<img src="/img/carregando_p.gif" />&nbsp;Estamos compartilhando a informação.');
        $('#modalSolicitacaoCompartilhada').modal();

        FB.api('/me/feed', 'post', params, function(response) {
            if (!response || response.error) {
                facebookLogin(function() {
                    compartilharSolicitacao(colaborador_id, solicitacao_id);
                });
            } else {
                $.post('/Demanda/compartilhado', {colaborador: colaborador_id, demanda: solicitacao_id}, function(data) {
                    $('#modalSolicitacaoCompartilhada .modal-body').html('<img src="/img/compartilhar-solicitacao-1.jpg" height="60" />Obrigado por compartilhar esta solicitação.');
                });
            }
        });
    });

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
        if (callback != undefined) {
            console.log('executou callback');
            callback();
        } else {
            //document.location.href = '/Login/interno/';
        }
    });
}