<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SSH PHP Exemplo Básico</title>
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container theme-showcase" role="main">
   <h1>SSH PHP Exemplo Básico</h1>
<div class="list-group">
<?php
if($_SESSION['connect']['tempo_espera'] == ""){
    $_SESSION['connect']['tempo_espera'] = "10";
}

if(count($_POST)){
    
    $_SESSION['connect']['connection_ip']   = $_POST['connection_ip'];
    $_SESSION['connect']['user']            = $_POST['user'];
    $_SESSION['connect']['pass']            = $_POST['pass'];
    $_SESSION['connect']['comando_shell']   = $_POST['comando_shell'];
    $_SESSION['connect']['tempo_espera']   = $_POST['tempo_espera'];
echo '<div class="form-group"><a href="/phpssh.php" class="btn btn-info">Voltar</a></div>';    
echo '<a href="#" class="list-group-item">Conexão SSH </a>';

if (!($connection=@ssh2_connect($_POST['connection_ip'], 22))) {
    echo '<div class="form-group"><a href="/phpssh.php" class="btn btn-info">Voltar</a></div>';
    echo '<a href="#" class="list-group-item list-group-item-danger">Erro de Conexão</a>';
    exit(1);
}
echo '<a href="#" class="list-group-item">Conexão SSH [OK]</a>';

if (!@ssh2_auth_password($connection,$_POST['user'],$_POST['pass'])) {
   echo '<a href="#" class="list-group-item list-group-item-danger">Erro de Usuário/Senha</a>';
   echo '<div class="form-group"><a href="/phpssh.php" class="btn btn-info">Voltar</a></div>';
   exit(1);
}
    echo '<a href="#" class="list-group-item list-group-item-success">Conexão [OK]</a>';

    $command = $_POST['comando_shell'];
    echo '<a href="#" class="list-group-item list-group-item-info">Executando... <b style="color:red">' . $command . "</b></a>"; 

    $stdout_stream = ssh2_exec($connection, $command);
    $stderr_stream = ssh2_fetch_stream($stdout_stream, SSH2_STREAM_STDERR);
    
    //Tempo de espera
    sleep($_POST['tempo_espera']);
    
    while($line = fgets($stderr_stream)) { echo '<a href="#" class="list-group-item">Erro: ' . $line . '</a>'; flush(); }
    while($line = fgets($stdout_stream)) { echo '<a href="#" class="list-group-item">Resultado: ' . $line."</a>"; flush(); }
    fclose($stdout_stream);
}
?>
</div>
<form role="form" action="" method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Comando Shell</label>
    <input type="text" name="comando_shell" class="form-control" value="<?php echo $_SESSION['connect']['comando_shell']; ?>" />
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Tempo espera do retorno</label>
    <input type="text" name="tempo_espera" class="form-control" value="<?php echo $_SESSION['connect']['tempo_espera']; ?>" />
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Ip de Conexão</label>
    <input type="text" name="connection_ip" class="form-control" value="<?php echo $_SESSION['connect']['connection_ip']; ?>" />
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Usuário</label>
    <input type="text" name="user" class="form-control" value="<?php echo $_SESSION['connect']['user']; ?>" />
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Senha</label>
    <input type="password" name="pass" class="form-control" value="<?php echo $_SESSION['connect']['pass']; ?>" />
  </div>
  <div class="form-group">
    <input type="submit" class="btn btn-default" value="EXECUTAR" /> <br>
  </div>
</form>
 
</div>
</body>
</html>