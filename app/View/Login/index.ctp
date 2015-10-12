<script lang="javascript">


  
</script>
<?php ?>
<!--form name="login" id="form-login" class="forms" autocomplete="off" autocapitalize="off" autocorrect="off"-->
<div id="form-login" class="forms">
  <div class="setaabaixo">
    <div class="seta"><img src="/img/coracao_batendo.gif" />&nbsp;Login</div>
  </div>
  <div class="alert alert-error alert-hide"></div>
  <div id="dv-login">
    <p>
      <label for="email">Email: </label>
      <input type="text" name="email" id="email" value="" class="form-control" autocomplete="off" autocapitalize="off" autocorrect="off" autofocus="autofocus" placeholder="E-mail" />
    </p>
    <p>
      <label for="senha"><img src="/img/key_icon.gif" align="absmiddle" /> Senha</label>
      <input type="password" name="senha" id="senha" value="" class="form-control" autocomplete="off" autocapitalize="off" autocorrect="off" autofocus="autofocus" placeholder="Senha" />
    </p>
  </div>

  <hr />

  <div id="controles">
    <button id="btn-login" class="btn btn-lg btn-success">Login</button>
    <!--p id="esqueci-senha">
      <a href="/Login/esquecisenha">Esqueceu sua senha</a>
    </p-->
    <button id="btn-login-facebook" class="btn btn-primary"><img src="/img/icon-facebook.png" />Acessar via Facebook</button>
    <button id="btn-login-gplus" class="btn btn-danger"><img src="/img/icon-gplus.png" />Acessar via Google+</button>
    <button id="btn-login-twitter" class="btn btn-info"><img src="/img/icon_twitter.png" />Acessar via Twitter</button>
    <a class="a-esqueceu-senha" href="/Login/esquecisenha">Esqueceu sua senha?</a>
  </div>
</div>
<!--/form-->
<div id="freeze">
  <div class="freeze"></div>
  <div class="freeze-img"><img src="/img/carregando.gif" align="absmiddle" />&nbsp;Carregando</div>
</div>

<div class="modal fade" id="modalUsuarioSenhaInvalido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sangue para todos</h4>
      </div>
      <div class="modal-body">
        Usuário e/ou senha inválidos.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
        <a href="/Login/esquecisenha" type="button" class="btn btn-primary">Esqueceu sua senha?</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEmailSenhaIncorretos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sangue para todos</h4>
      </div>
      <div class="modal-body">
        Certifique-se de ter digitado um e-mail válido<br />
        A senha deve contér 8 ou mais caracteres
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Voltar</button>
      </div>
    </div>
  </div>
</div>

<script language="javascript">
  $(function() {

    $('[data-toggle="tooltip"]').tooltip();
    $('#email, #senha').val("");
    $('#email').select().focus();

    $('#btn-login').click(function() {
      $('#controles .btn').attr('disabled','disabled');
      enviaFormLogin();
    });
    
    $('#btn-login-twitter').click(function(){
       document.location.href = '/Login/loginTwitter/';
    });

    $('#email, #senha').keypress(function(event) {
      if (event.which == 13) {
        enviaFormLogin();
      }
    });
  });
</script>