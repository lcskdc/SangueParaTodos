var clientId = '180179707012-7be2skqjaki93th23b8dfkek54qct9q8.apps.googleusercontent.com';
var apiKey = 'AIzaSyCqI6TnUZrEXiwdJX_SyN0bSdEpSaLv4tk';

var scopes = 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email';

(function() {
    var po = document.createElement('script');
    po.type = 'text/javascript';
    po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
})();

function render() {
    gapi.client.setApiKey(apiKey);
    window.setTimeout(checkAuth, 1);
}

function checkAuth() {
    $('#btn-login-gplus').click(handleAuthClick);
}

function makeApiCall() {
    $('#controles .btn').click(function(){
      disabledControles();
    });    
    gapi.client.load('plus', 'v1', function() {
        var request = gapi.client.plus.people.get({
            userId: 'me'
        });
        request.execute(function(ret) {
            //console.log(ret);
            var nome = ret.displayName;
            var email = ret.emails[0].value;
            var sexo = ret.gender;
            var imagem = ret.image.url;
            var id = ret.id;
            var data = {nome: nome, email: email, sexo: sexo, urlImagem: imagem, id: id, tipo: 'googleplus'};
            efetuaLoginSocial(data);            
        });
    });
}

function handleAuthClick(event) {
    var ret = gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, makeApiCall);
}
