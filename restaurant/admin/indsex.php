<?php 
include("admin/bd.php");

$sentencia=$conexion->prepare("SELECT * FROM tbl_banners ORDER BY id DESC limit 1 ");
$sentencia->execute();
$lista_banners= $sentencia->fetchAll(PDO::FETCH_ASSOC);

$sentencia=$conexion->prepare("SELECT * FROM tbl_colaboradores ORDER BY id DESC");
$sentencia->execute();
$lista_colaboradores= $sentencia->fetchAll(PDO::FETCH_ASSOC);


$sentencia=$conexion->prepare("SELECT * FROM tbl_testimonios ORDER BY id DESC limit 2");
$sentencia->execute();
$lista_testimonios= $sentencia->fetchAll(PDO::FETCH_ASSOC);

$sentencia=$conexion->prepare("SELECT * FROM tbl_menu ORDER BY id DESC limit 4");
$sentencia->execute();
$lista_menu= $sentencia->fetchAll(PDO::FETCH_ASSOC);

if($_POST){

   $nombre=filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
  $correo=filter_var($_POST["correo"],FILTER_VALIDATE_EMAIL);
  $mensaje=filter_var($_POST["mensaje"],FILTER_SANITIZE_STRING);

  if($nombre && $correo && $mensaje) {
   
    $sql="INSERT INTO 
    tbl_comentarios (nombre, correo, mensaje)
     VALUES (:nombre, :correo,:mensaje)";

    $resultado = $conexion->prepare($sql);
    $resultado ->bindParam(':nombre',$nombre, PDO::PARAM_STR);
    $resultado ->bindParam(':correo',$correo, PDO::PARAM_STR);
    $resultado ->bindParam(':mensaje',$mensaje, PDO::PARAM_STR);
    $resultado -> execute();

  }
  header("Location:index.php");
}

?>
<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer" />



</head>

<body>

<!-- Sección de menú de navegación  -->
<nav id="inicio" class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="#"> <i class="fas fa-utensils"></i>  Restaurante El manjar </a>
    
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
   <span class="navbar-toggler-icon"></span>
  </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#inicio">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#menu">Menú del día</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#chefs">Chefs</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#testimonios">Testimonio</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#contacto">Contacto</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#horario">Horarios</a>
            </li>

        </ul>
    </div>
</div>
</nav>
<!-- Sección de banner -->
<section  class="container-fluid p-0">
<div class="banner-img" style="position:relative; background:url('images/slider-image1.jpg') center/cover no-repeat; height:400px; ">

     <div class="banner-text"  style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); text-align:center; color:#fff;">
       
     <?php  foreach($lista_banners as $banner){  ?>
        
        <h1><?php echo $banner['titulo'];?></h1>
        <p><?php echo $banner['descripcion'];?></p>
        <a href="<?php echo $banner['link'];?>" class="btn btn-primary">Ver Menú</a>
      <?php  } ?>

     </div>

  </div>


</section>

<section id="id" class="container mt-4 text-center">
    <div class="jumbotron bg-dark text-white">
      <br/>
        <h2>¡Bienvenidos queridos clientes al Restaurante El manjar</h2>
        <p> Proyecto de software</p>
      <br/>
    </div>
</section>

<!-- Sección de Chefst  -->
<section id="chefs" class="container mt-4 text-center">
<h2>Nuestros Chefs</h2>
    <div class="row">
    
    <?php foreach($lista_colaboradores as $colaborador ){ ?>
    <div class="col-md-4">
      <div class="card">
          <img src="images/colaboradores/<?php echo $colaborador["foto"]; ?>" 
          class="card-img-top"
          alt="Chef 1"
           />

      <div class="card-body">
        <h5 class="card-title"><?php echo $colaborador["titulo"]; ?></h5>
        <p class="card-text"><?php echo $colaborador["descripcion"]; ?> </p>
        <div class="social-icons mt-3">
          <a href="<?php echo $colaborador["linkfacebook"]; ?>" class="text-dark me-2"><i class="fab fa-facebook"></i></a>
          <a href="<?php echo $colaborador["linkinstagram"]; ?>" class="text-dark me-2"><i class="fab fa-instagram"></i></a>
          <a href="<?php echo $colaborador["linklinkedin"]; ?>" class="text-dark me-2"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>
      </div>
    </div>
<?php  } ?>

    </div>
</section>

<!-- Sección de testimonios -->
<section id="testimonios" class="bg-light py-5">
<div class="container">

      <h2 class="tex-center mb-4">Testimonios  </h2>

      <div class="row">

      <?php foreach ($lista_testimonios as $testimonio){ ?>
        
      <div class="col-md-6 d-flex">
          <div class="card mb-4 w-100">
            <div class="card-body">
              <p class="card-text"> <?php echo $testimonio["opinion"];?> </p>
            </div>
            <div class="card-footer text-muted">
            <?php echo $testimonio["nombre"];?>
            </div>
          </div>
        </div>

      <?php } ?>

      </div>

</div>
</section>

<!-- Sección de menú de comida  -->
<section id="menu" class="container mt-4">
  <h2 class="text-center"> Menú ( nuestra recomendación ) </h2>
  <br/>

  <div class="row row-cols-1 row-cols-md-4 g-4">
  
     
      <?php foreach($lista_menu as $registro ) { ?>
      <div class="col d-flex">
        <div class="card">
          <img src="images/menu/<?php echo  $registro["foto"];?>" alt="Tortillas de
        maíz con carne y frijoles negros" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title"> <?php echo  $registro["nombre"];?> </h5>
            <p class="card-text small"><strong> <?php echo  $registro["ingredientes"];?> </strong> </p>
            <p class="card-text"><strong> Precio:</strong> $<?php echo  $registro["precio"];?> </p>
          </div>
        </div>
      </div>
      <?php } ?>
      
  </div>

</section>
<br/>
<br/>

<!-- Sección de contacto -->
<section id="contacto"  class="container mt-4">

<h2>Contacto </h2>
<p> Estamos aquí para servirle,</p>

<form action="?" method="post">

<div class="mb-3">
  <label for="name">Nombre:</label><br />
  <input type="text" class="form-control" name="nombre" placeholder="Escribe tu nombre..." required><br />
</div>

<div class="mb-3">
  <label for="email">Correo electrónico:</label><br />
  <input type="email" class="form-control" name="correo" placeholder="Escribe tu correo electronico..." required>
  <br/>
</div>

<div class="mb-3">
  <label for="message">Mensaje:</label><br />
  <textarea id="message" class="form-control" name="mensaje" rows="6" cols="50"></textarea><br />
</div>

  <input type="submit" class="btn btn-primary" value="Enviar mensaje">
  
</form>

</section>
<br/><br/>

<!-- Sección de horarios  -->
<div id="horario" class="text-center bg-light p-4">
  <h3 class="mb-4"> Horario de atención al cliente</h3>

  <div>
    <p> <strong>Lunes a Viernes </strong></p>
    <p> <strong>10:00 a.m. - 7:00 p.m. </strong></p>
  </div>

  <div>
    <p> <strong>Sábado </strong></p>
    <p> <strong>9:00 a.m. - 1:00 p.m. </strong></p>
  </div>


  <div>
    <p> <strong>Domingo</strong></p>
    <p> <strong> Los domingos no se trabaja por ley </strong></p>
  </div>



</div>




  <footer class="bg-dark text-light text-center py-3">
    <!-- place footer here -->
    <p> &copy; Restaurante EL manjar 2024.</p>

  </footer>


  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>




</body>
</html>