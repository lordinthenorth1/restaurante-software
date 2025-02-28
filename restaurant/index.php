<?php 
include("admin/bd.php");
session_start(); // Iniciar sesión para evitar reenvíos

// Obtener todos los banners
$sentencia=$conexion->prepare("SELECT * FROM tbl_banners ORDER BY ID DESC");
$sentencia->execute();
$lista_banners= $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Ajustar rutas de imágenes
foreach ($lista_banners as &$banner) {
    if (!empty($banner['image_url']) && file_exists("images/" . basename($banner['image_url']))) {
        $banner['image_url'] = "images/" . basename($banner['image_url']);
    } else {
        $banner['image_url'] = "images/default-banner.jpg";
    }
}
unset($banner);

// Obtener otros datos
$lista_colaboradores = $conexion->query("SELECT * FROM tbl_colaboradores ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$lista_testimonios = $conexion->query("SELECT * FROM tbl_testimonios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$lista_menu = $conexion->query("SELECT * FROM tbl_menu ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$mensaje_enviado = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviar"])) {
    // Evitar reenvíos con sesión
    if (!isset($_SESSION['form_enviado'])) {
        $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
        $correo = filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL);
        $mensaje = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);

        if ($nombre && $correo && $mensaje) {
            // Verificar si el mensaje ya existe en la base de datos
            $verificar = $conexion->prepare("SELECT COUNT(*) FROM tbl_comentarios WHERE nombre = :nombre AND correo = :correo AND mensaje = :mensaje");
            $verificar->bindParam(":nombre", $nombre);
            $verificar->bindParam(":correo", $correo);
            $verificar->bindParam(":mensaje", $mensaje);
            $verificar->execute();
            $existe = $verificar->fetchColumn();

            if ($existe == 0) { // Solo insertar si no existe el mensaje
                $sql = "INSERT INTO tbl_comentarios (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
                $resultado = $conexion->prepare($sql);
                $resultado->bindParam(':nombre', $nombre);
                $resultado->bindParam(':correo', $correo);
                $resultado->bindParam(':mensaje', $mensaje);
                $resultado->execute();

                $mensaje_enviado = true;
                $_SESSION['form_enviado'] = true; // Evita reenvío con F5
            }
        }
    }
}

// Evitar que el formulario se envíe de nuevo con F5
if ($mensaje_enviado) {
    header("Location: index.php?success=1");
    exit();
}

// Limpiar la sesión después de redirección
if (isset($_GET['success'])) {
    unset($_SESSION['form_enviado']);
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
<!-- Swiper.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">



<style>
  /* 🔹 Cambiar el fondo del mapa para que coincida con el amarillo suave */
#mapa {
  background: #fdeacc;  /* Color amarillo suave */
  padding: 50px 0;
}
</style>

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
                <a class="nav-link" href="#chefs">Empleados destacados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#mapa">Sedes</a>
            </li>    
            <li class="nav-item">
                <a class="nav-link" href="#menu">Nuestro Menú</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#testimonios">Testimonios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#contacto">Contacto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#horario">Horarios</a>
            </li>
        </ul>

        <!-- Acceso alineado a la derecha -->
        <ul class="nav navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link btn btn-outline-light px-3" href="http://localhost/restaurant/admin/login.php" target="_blank" >Acceder al modo administrador</a>
            </li>
        </ul>
    </div>
</div>
</nav>
<!-- Sección de banner con carrusel -->
<section class="container-fluid p-0">
  <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
      <?php foreach ($lista_banners as $index => $banner) { ?>
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index == 0 ? 'active' : ''; ?>" aria-current="true" aria-label="Slide <?php echo $index + 1; ?>"></button>
      <?php } ?>
    </div>

    <div class="carousel-inner" style="height: 400px;">
      <?php $active = true; foreach($lista_banners as $banner) { ?>
        <div class="carousel-item <?php echo $active ? 'active' : ''; ?>" style="height: 400px;">
          <img src="<?php echo $banner['image_url']; ?>" class="d-block w-100" alt="Banner" style="height: 100%; object-fit: cover;">
          <div class="carousel-caption d-md-block" style="color:#000; text-shadow: 1px 1px 4px rgba(255,255,255,0.7);">
            <h1><?php echo $banner['titulo']; ?></h1>
            <p><?php echo $banner['descripcion']; ?></p>
            <a href="<?php echo $banner['link']; ?>" class="btn btn-primary"> <?php echo $banner['button_text']; ?> </a>
          </div>
        </div>
      <?php $active = false; } ?>
    </div>
    
    <!-- Controles del carrusel -->
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>
</section>

<section id="id" class="container mt-5 text-center">
    <div class="jumbotron bg-dark text-white p-5 rounded shadow-lg">
        <h2 class="display-4 fw-bold text-warning">🍽️ ¡Bienvenidos al Restaurante El Manjar! 🍷</h2>
        <p class="lead">Una experiencia gastronómica única, donde cada plato cuenta una historia. Descubre sabores inolvidables y vive momentos especiales con nosotros. ✨</p>
    </div>
</section>



<!-- Sección de Chefs Mejorada y con Efectos -->
<section id="chefs" class="container mt-5">
  <h2 class="text-center fw-bold mb-4">👨‍🍳 Nuestros Chefs Estrella</h2>
  <br></br>
  <p>Detrás de cada plato hay pasión, dedicación y un toque especial. Nuestro equipo de chefs estrella combina experiencia, creatividad y amor por la gastronomía para ofrecerte una experiencia única en cada bocado. Descubre quiénes están detrás de la magia en nuestra cocina. 🍽️✨</p>
  <br></br>
  <div class="row justify-content-center">
    
    <?php foreach($lista_colaboradores as $colaborador ){ ?>
    <div class="col-lg-4 col-md-6 col-sm-12 d-flex mb-4"> 
      <div class="chef-card">
        
        <!-- Imagen del Chef -->
        <div class="chef-img-container">
          <img src="images/colaboradores/<?php echo $colaborador["foto"]; ?>" 
          class="chef-img"
          alt="<?php echo $colaborador["titulo"]; ?>"/>
        </div>

        <!-- Contenido -->
        <div class="chef-info">
          <h5 class="chef-name"><?php echo $colaborador["titulo"]; ?></h5>
          <p class="chef-description"><?php echo $colaborador["descripcion"]; ?> </p>
          
          <!-- Redes sociales -->
          <div class="chef-socials">
            <a href="<?php echo $colaborador["linkfacebook"]; ?>" class="chef-icon facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="<?php echo $colaborador["linkinstagram"]; ?>" class="chef-icon instagram"><i class="fab fa-instagram"></i></a>
            <a href="<?php echo $colaborador["linklinkedin"]; ?>" class="chef-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

  </div>
</section>


<style>
/* 🔹 Sección sin fondo gris */
#chefs {
  background: #ffffff;
  padding: 50px 0;
}

/* 🔹 Animación de entrada */
.chef-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  text-align: center;
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 350px; /* Tamaño máximo de la tarjeta */
  margin: auto;
  transform: translateY(30px);
  opacity: 0;
  animation: fadeInUp 0.8s ease-in-out forwards;
}

/* 🔹 Efecto hover para que la tarjeta se eleve un poco */
.chef-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}

/* 🔹 Imagen del chef con zoom al pasar el mouse */
.chef-img-container {
  width: 100%;
  height: 250px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chef-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease-in-out;
}

.chef-card:hover .chef-img {
  transform: scale(1.1);
}

/* 🔹 Información del chef */
.chef-info {
  padding: 20px;
  flex-grow: 1;
}

.chef-name {
  font-size: 1.3em;
  font-weight: bold;
  margin-bottom: 5px;
  color: #333;
}

.chef-description {
  font-size: 0.95em;
  color: #777;
  margin-bottom: 15px;
}

/* 🔹 Redes sociales con iconos grises hasta hover */
.chef-socials {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 10px;
}

.chef-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: #f1f1f1;
  font-size: 16px;
  transition: all 0.3s ease;
  color: #aaa; /* Iconos en gris por defecto */
}

/* 🎨 Colores de redes sociales al hacer hover */
.chef-icon.facebook:hover {
  background: #1877F2;
  color: white;
}

.chef-icon.instagram:hover {
  background: #E4405F;
  color: white;
}

.chef-icon.linkedin:hover {
  background: #0077b5;
  color: white;
}

/* 🔹 Animación de entrada */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* 🔹 Responsive */
@media (max-width: 992px) {
  .chef-card {
    width: 90%;
    margin: auto;
  }
  
  .chef-img-container {
    height: 200px;
  }
  
  .chef-name {
    font-size: 1.2em;
  }
  
  .chef-description {
    font-size: 0.9em;
  }
  
  .chef-socials {
    gap: 5px;
  }
  
  .chef-icon {
    width: 30px;
    height: 30px;
    font-size: 14px;
  }
  
  .col-md-6, .col-sm-12 {
    margin-bottom: 20px;
  }
}

  </style>


<!-- Sección del mapa -->
<section id="mapa" class="py-5" style="background: linear-gradient(to right, #fdeacc, #fad0c4);">
  <div class="container">
    <h2 class="text-center mb-4"><strong>Conoce nuestras sedes</strong></h2>
    <p>Visítanos y disfruta de una experiencia gastronómica única. Aquí puedes ver nuestra ubicación y los puntos de referencia cercanos para que llegues sin complicaciones. ¡Te esperamos! 🍽️✨</p>
    <div id="mi_mapa" style="height: 500px; width: 100%;"></div>
  </div>
</section>

<!-- Agregar la librería Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let map = L.map('mi_mapa').setView([6.24415, -75.57495], 12);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    let locations = [
        [6.17938, -75.442241, "Aeropuerto (Rionegro) - Horario: 24 horas"],
        [6.17545757, -75.59133576, "Viva Envigado (Envigado) - Horario: 8:00 am a 9:00 pm"],
        [6.21194987, -75.57404774, "Éxito Poblado (Medellín) - Horario: 8:00 am a 8:00 pm"]
    ];

    locations.forEach(function(coords) {
        L.marker([coords[0], coords[1]]).addTo(map).bindPopup(coords[2]);
    });
});
</script>

<br/><br/>


<!-- Sección de menú de comida mejorada -->
<section id="menu" class="container mt-5">
  <h2 class="text-center fw-bold mb-4"> 🍽️ Menú (Nuestra Recomendación) </h2>
  <br/><br/>
  <p>Descubre nuestras recomendaciones especialmente seleccionadas para ti. Desde platillos clásicos hasta creaciones innovadoras, cada opción está preparada con ingredientes frescos y el toque especial de nuestros chefs. ¡Déjate sorprender y encuentra tu nueva comida favorita! 🍽️✨
</P>
<br/><br/>

  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <?php foreach($lista_menu as $registro ) { ?>
      <div class="swiper-slide">
        <div class="card card-hover d-flex flex-column align-items-stretch h-100">
          <div class="img-container">
            <img src="images/menu/<?php echo $registro["foto"]; ?>" alt="<?php echo $registro["nombre"]; ?>" class="card-img-top">
          </div>
          <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title text-center fw-bold"> <?php echo $registro["nombre"]; ?> </h5>
            <p class="card-text text-muted small text-center flex-grow-1">
              <strong> <?php echo $registro["ingredientes"]; ?> </strong>
            </p>
            <p class="card-text text-center fw-bold text-dark"> Precio: $<?php echo number_format($registro["precio"], 0, ',', '.'); ?></p>

            <!-- Botón para abrir el modal -->
            <button class="btn btn-warning text-dark fw-bold mt-auto" data-bs-toggle="modal" data-bs-target="#modal<?php echo $registro['ID']; ?>">Me interesa</button>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>

  <!-- Solo la paginación, sin sliders -->
  <div class="swiper-pagination"></div>

</section>

<!-- 🔥 Modales para cada platillo -->
<?php foreach($lista_menu as $registro ) { ?>
<div class="modal fade" id="modal<?php echo $registro['ID']; ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title fw-bold"><?php echo $registro["nombre"]; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img src="images/menu/<?php echo $registro["foto"]; ?>" class="img-fluid mb-3 rounded" alt="<?php echo $registro["nombre"]; ?>">
        <p><strong>Descripción:</strong> <?php echo $registro["ingredientes"]; ?></p>
        <p><strong>Precio:</strong> $<?php echo number_format($registro["precio"], 0, ',', '.'); ?></p>
      </div>
      <div class="modal-footer">
        <a href="https://wa.me/573116153722?text=Quiero%20comprar%20<?php echo urlencode($registro['nombre']); ?>" class="btn btn-success w-100 fw-bold" target="_blank">Comprar vía WhatsApp</a>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<style>
/* 🔹 Ajuste general de las cards */
.card {
  max-width: 280px;
  height: 100%;
  display: flex;
  flex-direction: column;
  border-radius: 15px;
  overflow: hidden;
  background: white;
  align-items: stretch;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* 🔹 Efecto de hover en la card */
.card-hover:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

/* 🔹 Contenedor de la imagen */
.img-container {
  height: 180px;
  overflow: hidden;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
}

/* 🔹 Efecto de zoom en la imagen */
.card-img-top {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease-in-out;
}

.card-hover:hover .card-img-top {
  transform: scale(1.08);
}

/* 🔹 Tamaño fijo para los títulos */
.card-title {
  min-height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}

/* 🔹 Asegura que todas las descripciones sean del mismo tamaño */
.card-text {
  min-height: 70px;
  max-height: 70px;
  overflow: hidden;
  text-overflow: ellipsis;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}

/* 🔹 Precio alineado */
.price-fixed {
  min-height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
}

/* 🔹 Botón de acción */
.btn-warning {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  font-weight: bold;
  border-radius: 8px;
  transition: background 0.3s ease, transform 0.2s ease-in-out;
}

.btn-warning:hover {
  background: #ff9900;
  transform: scale(1.05);
}

/* 🔹 Swiper mantiene la alineación */
.swiper-slide {
  display: flex;
  justify-content: center;
}

/* 🔹 Paginación personalizada (más baja y sin sliders) */
.swiper-pagination {
  position: relative !important;
  margin-top: 15px;
  display: flex;
  justify-content: center;
}

/* 🔹 Bullets personalizados */
.swiper-pagination-bullet {
  background-color: #ffcc00 !important;
  opacity: 0.6;
  width: 12px;
  height: 12px;
  margin: 0 4px;
}

.swiper-pagination-bullet-active {
  background-color: #ff9900 !important;
  opacity: 1;
}
</style>



<br/><br/>
<br/><br/>

<!-- Sección de Testimonios Mejorada -->
<section id="testimonios" class="container-fluid bg-light py-5">
  <div class="container">
    <h2 class="text-center mb-4">💬 ¿Qué dicen nuestros clientes?</h2>
    <p class="text-center text-muted">Las experiencias de quienes han disfrutado de nuestra comida.</p>

    <div id="carouselTestimonios" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        
        <?php $primero = true; foreach ($lista_testimonios as $testimonio) { ?>
          <div class="carousel-item <?php echo $primero ? 'active' : ''; ?>">
            <div class="card p-4 shadow-lg border-0 mx-auto" style="max-width: 800px;">
              <div class="d-flex align-items-center mb-3">
                <i class="fas fa-user-circle fa-3x me-3 text-primary"></i>
                <h5 class="mb-0"><?php echo $testimonio["nombre"]; ?></h5>
              </div>
              <p class="text-muted">"<?php echo $testimonio["opinion"]; ?>"</p>
              <div class="text-warning">
                ⭐⭐⭐⭐⭐
              </div>
            </div>
          </div>
          <?php $primero = false; } ?>

      </div>

      <!-- Controles del carrusel -->
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselTestimonios" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselTestimonios" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
  </div>
</section>

<!-- Estilos adicionales -->
<style>
  #testimonios {
    width: 100%;
    background: linear-gradient(to right, #fdeacc, #fad0c4);
    color: #fff;
  }

  #testimonios .card {
    border-radius: 15px;
    background: #ffffff;
    transition: transform 0.3s ease-in-out;
  }

  #testimonios .card:hover {
    transform: scale(1.02);
    box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.15);
  }

  #testimonios .text-warning {
    font-size: 1.2em;
  }

  .carousel-control-prev, .carousel-control-next {
    filter: invert(1);
  }
</style>

<!-- Agregar Bootstrap para el carrusel -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Agregar FontAwesome para iconos -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>


<?php 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL);
    $mensaje = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
    
    if($nombre && $correo && $mensaje) {
        $sql = "INSERT INTO tbl_comentarios (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
        $resultado = $conexion->prepare($sql);
        $resultado->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $resultado->bindParam(':correo', $correo, PDO::PARAM_STR);
        $resultado->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        $resultado->execute();
        
        echo '<script>document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById("successModal"));
                myModal.show();
              });
              </script>';
    }
}
?>

<?php 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL);
    $mensaje = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
    
    if($nombre && $correo && $mensaje) {
        $sql = "INSERT INTO tbl_comentarios (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
        $resultado = $conexion->prepare($sql);
        $resultado->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $resultado->bindParam(':correo', $correo, PDO::PARAM_STR);
        $resultado->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        $resultado->execute();
        
        echo '<script>document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById("successModal"));
                myModal.show();
              });
              </script>';
    }
}
?>

<?php 
$mensaje_enviado = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include("admin/bd.php");
    $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL);
    $mensaje = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
    
    if($nombre && $correo && $mensaje) {
        $sql = "INSERT INTO tbl_comentarios (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
        $resultado = $conexion->prepare($sql);
        $resultado->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $resultado->bindParam(':correo', $correo, PDO::PARAM_STR);
        $resultado->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        $resultado->execute();
        $mensaje_enviado = true;
    }
}
?>
<?php 
include("admin/bd.php");
$mensaje_enviado = false;

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar'])){
    $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL);
    $mensaje = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
    
    if($nombre && $correo && $mensaje) {
        $sql = "INSERT INTO tbl_comentarios (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
        $resultado = $conexion->prepare($sql);
        $resultado->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $resultado->bindParam(':correo', $correo, PDO::PARAM_STR);
        $resultado->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        if($resultado->execute()){
            $mensaje_enviado = true;
        }
    }
}
?>

<?php 
include("admin/bd.php");
$mensaje_enviado = false;

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar']) && !$mensaje_enviado){
    $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL);
    $mensaje = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
    
    if($nombre && $correo && $mensaje) {
        $sql = "INSERT INTO tbl_comentarios (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)";
        $resultado = $conexion->prepare($sql);
        $resultado->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $resultado->bindParam(':correo', $correo, PDO::PARAM_STR);
        $resultado->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        if($resultado->execute()){
            $mensaje_enviado = true;
        }
    }
}
?>

<!-- Sección de contacto -->
<section id="contacto" class="contacto-container">
  <div class="contact-box">
    <h2 class="text-center"><i class="fas fa-comments text-warning"></i> ¡Hablemos!</h2>
    <p class="text-center text-dark">Déjanos tu mensaje y te responderemos pronto. ✨</p>

    <form action="" method="post" id="contactForm">
      <div class="mb-2">
        <label for="name"><i class="fas fa-user"></i> Nombre:</label>
        <input type="text" class="form-control input-field" name="nombre" placeholder="Tu nombre..." required>
      </div>

      <div class="mb-2">
        <label for="email"><i class="fas fa-envelope"></i> Correo:</label>
        <input type="email" class="form-control input-field" name="correo" placeholder="Tu correo..." required>
      </div>

      <div class="mb-2">
        <label for="message"><i class="fas fa-comment-dots"></i> Mensaje:</label>
        <textarea id="message" class="form-control input-field" name="mensaje" rows="3" placeholder="Tu mensaje..." required></textarea>
      </div>

      <div class="text-center">
        <button type="submit" name="enviar" class="btn btn-warning btn-enviar">
          <i class="fas fa-paper-plane"></i> Enviar
        </button>
      </div>
    </form>
  </div>
</section>
<!-- Estilos optimizados -->
<style>
.contacto-container {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px 0;
  background: #f8f9fa;
}

.contact-box {
  background: white;
  border-radius: 15px;
  padding: 20px;
  width: 40%;
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
}

.contact-box:hover {
  transform: scale(1.02);
  box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.15);
}

.input-field {
  border-radius: 6px;
  padding: 6px;
  border: 1px solid #ddd;
  background: white;
  font-size: 14px;
}

.btn-enviar {
  background: #ffc107;
  border: none;
  border-radius: 6px;
  padding: 6px 15px;
  color: black;
  font-weight: bold;
  font-size: 16px;
}

.btn-enviar:hover {
  background: #ff9800;
}

@media (max-width: 992px) {
  .contact-box {
    width: 90%;
  }
}
</style>


<!-- FontAwesome para iconos -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>





  <footer class="bg-dark text-light text-center py-3">
    <!-- place footer here -->
    <footer class="bg-dark text-light py-4">
    <div class="container">
        <div class="row">
            <!-- Información Básica -->
            <div class="col-md-6 text-center text-md-start">
                <h5>🍽 Restaurante El Manjar</h5>
                <p>Sabores que enamoran, ingredientes de calidad y pasión en cada plato.</p>
            </div>

            <!-- Redes Sociales -->
            <div class="col-md-6 text-center text-md-end">
                <h5>🌎 Síguenos</h5>
                <a href="https://www.facebook.com" class="text-light me-3" target="_blank">
                    <i class="fab fa-facebook fa-lg"></i>
                </a>
                <a href="https://www.instagram.com" class="text-light me-3" target="_blank">
                    <i class="fab fa-instagram fa-lg"></i>
                </a>
                <a href="https://www.twitter.com" class="text-light" target="_blank">
                    <i class="fab fa-twitter fa-lg"></i>
                </a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-3">
            <p class="mb-0">&copy; 2024 Restaurante El Manjar. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
<!-- Contenedor de botones flotantes -->
<div class="floating-buttons">
    <!-- Botón flotante de WhatsApp -->
    <a href="https://wa.me/573116153722" target="_blank" class="floating-btn whatsapp">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
    </a>

    <!-- Botón flotante de Gmail -->
    <a href="mailto:contacto@elmanjar.com" target="_blank" class="floating-btn gmail">
        <i class="fas fa-envelope"></i>
    </a>

    <!-- Botón flotante de Instagram -->
    <a href="https://www.instagram.com/restaurant" target="_blank" class="floating-btn instagram">
        <i class="fab fa-instagram"></i>
    </a>
</div>

<!-- Estilos con Animaciones Mejoradas -->
<style>
  .floating-buttons {
      position: fixed;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      flex-direction: column;
      gap: 15px; /* Espaciado uniforme entre botones */
      z-index: 1000;
  }

  .floating-btn {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.3);
      transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
      color: white;
      font-size: 26px;
      text-decoration: none;
      animation: float 2.5s infinite ease-in-out;
  }

  /* Estilos específicos para cada botón */
  .whatsapp { background-color: #25d366; animation-delay: 0s; }
  .gmail { background-color: #D44638; animation-delay: 0.2s; }
  .instagram { background-color: #E1306C; animation-delay: 0.4s; }

  .floating-btn img {
      width: 40px;
      height: 40px;
  }

  /* Efecto hover: escalado y vibración */
  .floating-btn:hover {
      transform: scale(1.3);
      box-shadow: 4px 4px 20px rgba(0, 0, 0, 0.4);
      animation: vibrate 0.3s infinite ease-in-out;
  }

  /* Animación flotante */
  @keyframes float {
      0%, 100% {
          transform: translateY(-2px);
      }
      50% {
          transform: translateY(4px);
      }
  }

  /* Animación de vibración al hacer hover */
  @keyframes vibrate {
      0% { transform: translateX(0); }
      25% { transform: translateX(2px); }
      50% { transform: translateX(-2px); }
      75% { transform: translateX(2px); }
      100% { transform: translateX(0); }
  }

</style>

<!-- Agregar FontAwesome para iconos (si no lo tienes ya cargado) -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>





  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>


<!-- Swiper.js JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>




</body>
<script>
  var swiper = new Swiper('.mySwiper', {
    slidesPerView: 4, // Mostrar 4 platillos a la vez en pantallas grandes
    spaceBetween: 20, // Espacio entre cada platillo
    loop: true, // Hace que el carrusel sea infinito
    autoplay: {
      delay: 2500, // Cambia cada 2.5 segundos automáticamente
      disableOnInteraction: false // Sigue rotando después de interactuar
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      1024: { slidesPerView: 4 }, // En pantallas grandes, mostrar 4
      768: { slidesPerView: 3 },  // En tablets, mostrar 3
      480: { slidesPerView: 2 },  // En móviles, mostrar 2
      320: { slidesPerView: 1 }   // En móviles pequeños, mostrar 1
    }
  });
</script>

</html>
