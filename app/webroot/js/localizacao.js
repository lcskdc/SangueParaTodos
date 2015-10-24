var map;
var markers = [];
var latLng;
var mapOptions;

/**
 * 
 * @param {Coordenadas} position - https://developer.mozilla.org/en-US/docs/Web/API/Geolocation.getCurrentPosition 
 * @returns {void}
 * Metodo que retorna envia as posicoes obtidas da GeoLocalizacao do usuario, apos permiss�o
 */
function mostraMapa(position) {
    //console.log('1 - exibirPosicoesMapa');
    exibirPosicoesMapa(position.coords);
}

/**
 * 
 * @param {Geolocation Error} err - https://developer.mozilla.org/en-US/docs/Web/API/PositionError
 * @returns {void}
 * M�todo que exibe ao usu�rio problemas na aquisi��o das coordenadas
 */
function errorHandler(err) {
    $.post('/Local/loc_usuario/', function(r) {
        jObj = $.parseJSON(r);
        console.log(jObj);
        if (jObj.latitude != undefined && jObj.longitude != undefined) {
            exibirPosicoesMapa(jObj, false);
        } else {
            $('#modalAlteraEndereco').modal();
        }
    });
}

/**
 * 
 * @returns {void}
 * Metodo inicial, solitia ao usuario as coordenadas, se obter sucesso na aquisicao,
 * entao exibe marcadores no Mapa, se existirem, atraves de consulta Ajax
 */
function getLocalizacao() {
    console.log('passou aqui geoLocalização');
    if (navigator.geolocation) {
        var options = {timeout: 6000}; //timeout de 60 seconds
        navigator.geolocation.getCurrentPosition(mostraMapa, errorHandler, options);
    } else {
        $('#modalAlteraEndereco').modal();
    }
}

/**
 * 
 * @returns {void}
 * Metodo que cria o mapa no primeiro Ecra existente, com a classe "mapa"
 * Como padrao, define o centro como sendo Porto Alegre
 */
function criarMapa() {
    /* Localizacao padrao Porto Alegre */

    $.post('/Local/loc_usuario').done(function(data) {
        latLng = new google.maps.LatLng(-29.9565731, -51.0869992);
        mapOptions = {zoom: 12, center: latLng}
        map = new google.maps.Map($('.mapa')[0], mapOptions);
        var ret = $.parseJSON(data);
        console.log(ret);
        if (ret.latitude != undefined) {
            console.log('passou aqui 1');
            var pos = {coords: {
                    accuracy: null,
                    altitude: null,
                    altitudeAccuracy: null,
                    heading: null,
                    latitude: ret.latitude,
                    longitude: ret.longitude,
                    speed: null
                }
            };
            mostraMapa(pos);
        } else {
            console.log('passou aqui 2');
            getLocalizacao();
        }


    });



}

function criarMapaLocalizacaoManual(posicao) {
    /* Localizacao padrao Porto Alegre */
    latLng = new google.maps.LatLng(posicao.latitude, posicao.longitude);
    mapOptions = {zoom: 12, center: latLng}
    map = new google.maps.Map($('.mapa')[0], mapOptions);

    var pos = {coords: {
            accuracy: null,
            altitude: null,
            altitudeAccuracy: null,
            heading: null,
            latitude: posicao.latitude,
            longitude: posicao.longitude,
            speed: null
        }
    };

    mostraMapa(pos);
}

/**
 * 
 * @param {String} conteudo
 * @param {Marker - https://developers.google.com/maps/documentation/javascript/reference#Marker} marker
 * @returns {void}
 * Metodo que adiciona a marcacao, um texto, recebido por parametro.
 * Este texto e exibido quando o usuario clica no ponto do Mapa
 */
function adicionaInfoMapa(conteudo, marker) {
    //console.log(conteudo);
    //console.log(marker);
    var infowindow = new google.maps.InfoWindow({
        content: '<div class="info">' + conteudo + '</div>',
        disableAutoPan: true,
        maxWidth: 350
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });
}

function buscaEndereco(endereco) {
    var urlConsulta = "http://maps.google.com/maps/api/geocode/json?address=" + endereco + "&sensor=false";
    return $.getJSON(urlConsulta, function(data) {
        return data.results;
    });
}

function setPosicaoMapa(posicao) {
    removeMarcacaoMapa();
    latLng = new google.maps.LatLng(posicao.latitude, posicao.longitude);
    var opts = {
        position: latLng,
        map: map,
        draggable: false,
        title: posicao.descricao
    };
    var i = markers.length;
    markers[i] = new google.maps.Marker(opts);
    adicionaInfoMapa(posicao.descricao, markers[i]);

    initialLocation = new google.maps.LatLng(posicao.latitude, posicao.longitude);
    map.setCenter(initialLocation);
}

/**
 * 
 * @param {Local} Objeto Localizacao
 * @returns {void}
 * Metodo que adiciona uma marcacao no mapa
 */
function adicionaMarcacao(obj) {
    latLng = new google.maps.LatLng(obj.latitude, obj.longitude);
    var descricao = obj.descricao;

    var opts = {
        position: latLng,
        map: map,
        draggable: false,
        title: descricao
    };

    if (obj.options != undefined) {
        for (k in obj.options) {
            opts[k] = obj.options[k];
        }
    }

    if (obj.tipo == undefined) {
        var marker = new google.maps.Marker(opts);
        adicionaInfoMapa(obj.descricao, marker);
    } else if (obj.tipo == 'demanda') {
        var cor = obj.id_local > 0 ? '59E369' : '5477EB';
        opts.icon = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|' + cor;
        marker = new google.maps.Marker(opts);
        var d = obj.validade.split(' ');
        d = d[0].split('-');
        var data = d[2] + '/' + d[1] + '/' + d[0];
        var strTipoSangue = '';

        if (obj.tipos_sangue != undefined && obj.tipos_sangue != "") {
            if (obj.tipos_sangue == 'todos') {
                strTipoSangue = ' de <strong>qualquer tipo sanguíneo</strong>';
            } else {
                strTipoSangue = ' dos tipos sanguíneos <strong>' + obj.tipos_sangue + '</strong>';
            }
        }
        adicionaInfoMapa('<strong><em>' + obj.paciente + '</em></strong><br />necessita de <strong>' + obj.doadores + '</strong> doadores ' + strTipoSangue + '<br /><strong>Local:</strong> ' + obj.instituicao + '<br /><strong>Endereço:</strong> ' + obj.endereco + '<br />Válido até o dia <strong>' + data + '</strong><br /><a href="/Local/demandas/' + obj.id_colaborador + '/' + obj.id + '/"><img src="/img/icon-search.png" align="absmiddle" />&nbsp;Visualizar demanda</a>', marker);
    } else {
        opts.icon = '/img/icone-centro-coleta.png';
        marker = new google.maps.Marker(opts);
        adicionaInfoMapa('<strong><em>' + obj.descricao + '</em></strong><br />Endereço: ' + obj.endereco, marker);
    }


}

function removeMarcacaoMapa() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
}

/**
 * 
 * @param {Coordinates - https://developer.mozilla.org/en-US/docs/Web/API/Coordinates} p
 * @returns {void}
 * Recebe a localizacao adquirida do usuario, e executa chamada Ajax, a chamada Ajax retorna um objeto JSon com todos os locais proximos, retornados pelo algoritmo.
 */
function exibirPosicoesMapa(p, posicaoUsuario) {
    if (p.latitude != undefined && p.longitude != undefined) {

        $.post('/Login/coordenadas', {lat: p.latitude, lng: p.longitude}, function() {
            //Requisicao ajax, retorna um JSon com pontos aproximados, baseados na localizacao adquirida do Usuario
            var resp = $.getJSON('/Local/markers/' + p.latitude + '/' + p.longitude, function(data) {
                if (map != undefined) {
                    latLng = new google.maps.LatLng(p.latitude, p.longitude);
                    map.setCenter(latLng);
                    if (posicaoUsuario != false) {
                        adicionaMarcacao({latitude: p.latitude, longitude: p.longitude, descricao: 'Sua localiza&ccedil;&atilde;o', options: {icon: '/img/man.png'}});
                    }
                }
                $.each(data, function(k, local) {
                    adicionaMarcacao(local);
                });
            });
            $('.mapa-loading').removeClass('mapa-loading');
        }
        );


    }
}