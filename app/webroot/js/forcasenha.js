function atualizaForcaSenha() {
    $('.txtsenha').keyup(function(){
       var forcasenha = verificaForcaSenha($(this).val());
       $('.forcasenha').removeClass('forcasenha-fraca forcasenha-regular forcasenha-boa');
       //console.log('$(this).next(.forcasenha): '+$(this).next('.forcasenha').length);
       
        if(forcasenha == 1) {
           $('.forcasenha').addClass('forcasenha-fraca');
       } else if(forcasenha == 2) {
           $('.forcasenha').addClass('forcasenha-regular');
       } else {
           $('.forcasenha').addClass('forcasenha-boa');
       }
    });
}
function verificaForcaSenha(password) {
    var strengthLevel = 1;
    //console.log('password.length: '+password.length);
    if (password.length < 6) {
        strengthLevel = 1;
    }
    if (password.length >= 6 && password.match(/[a-zA-Z]+/) && password.match(/[0-9]+/)) {
        strengthLevel = 2;
    }
    if (password.length >= 8 && password.match(/[a-zA-Z]+/) && password.match(/[0-9]+/)) {
        strengthLevel = 3;
    }
    if (password.length >= 12 && password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/)) {
        strengthLevel = 4;
    }
    if (password.match(/^(.)\1+$/)) {
        strengthLevel = 1;
    }
    return strengthLevel;
}