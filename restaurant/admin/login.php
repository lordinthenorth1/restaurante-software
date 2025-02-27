<?php 
session_start();
if($_POST){
  include("bd.php");

  $usuario=(isset($_POST["usuario"]))?$_POST["usuario"]:"";
  $password=(isset($_POST["password"]))?$_POST["password"]:"";

  $password=md5($password);

  $sentencia=$conexion->prepare("SELECT *, count(*) as n_usuario
            FROM tbl_usuarios
            WHERE usuario=:usuario
            AND password=:password");
  $sentencia->bindParam(":usuario",$usuario);
  $sentencia->bindParam(":password",$password);
  $sentencia->execute();
  $lista_usuarios=$sentencia->fetch(PDO::FETCH_LAZY);
  $n_usuario=$lista_usuarios["n_usuario"];
  if($n_usuario==1){
    $_SESSION["usuario"]=$lista_usuarios["usuario"];
    $_SESSION["logueado"]=true;
    header("Location:index.php");
  }else{
    $mensaje="Usuario o contraseña incorrectos...";
  }
}
?>

<!doctype html>
<html lang="es">

<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #141E30, #243B55);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2);
      color: white;
    }
    .form-control {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
    }
    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }
    .btn-primary {
      background: #1e90ff;
      border: none;
    }
    .btn-primary:hover {
      background: #00bfff;
    }
  </style>
</head>

<body>
  <div class="container d-flex justify-content-center">
    <div class="col-md-4 login-container">
      <h2 class="text-center">🔑 Iniciar Sesión</h2>
      <br>
      <?php if(isset($mensaje)){?>
        <div class="alert alert-danger text-center" role="alert">
          <strong>Error:</strong> <?php echo $mensaje;?>
        </div>
      <?php } ?>
      <form action="login.php" method="post">
        <div class="mb-3">
          <label class="form-label">Usuario:</label>
          <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Ingrese su usuario">
        </div>
        <div class="mb-3">
          <label class="form-label">Contraseña:</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese su contraseña">
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>