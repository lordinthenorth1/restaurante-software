<?php 
// Asegurar la conexión a la base de datos con la ruta correcta
include("../../bd.php");

// Consulta para obtener los menús
$sentencia = $conexion->prepare("SELECT * FROM tbl_menu ORDER BY id DESC");
$sentencia->execute();
$lista_menu = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Sección de menú de comida -->
<section id="menu" class="container mt-4">
  <h2 class="text-center"> Menú ( nuestra recomendación ) </h2>
  <br/>

  <div class="row row-cols-1 row-cols-md-4 g-4">
      <?php foreach($lista_menu as $registro ) { ?>
      <div class="col d-flex">
        <div class="card">
          <img src="images/menu/<?php echo $registro["foto"]; ?>" alt="<?php echo $registro["nombre"]; ?>" class="card-img-top">
          <div class="card-body">
            <h5 class="card-title"> <?php echo $registro["nombre"]; ?> </h5>
            <p class="card-text small"><strong> <?php echo $registro["ingredientes"]; ?> </strong></p>
            <p class="card-text"><strong> Precio:</strong> $<?php echo number_format($registro["precio"], 0, ',', '.'); ?></p>

            <!-- Botón para abrir el modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal<?php echo $registro['ID']; ?>">Me interesa</button>
          </div>
        </div>
      </div>

      <!-- Modal para cada platillo -->
      <div class="modal fade" id="modal<?php echo $registro['ID']; ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><?php echo $registro["nombre"]; ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <img src="images/menu/<?php echo $registro["foto"]; ?>" class="img-fluid mb-3" alt="<?php echo $registro["nombre"]; ?>">
              <p><strong>Ingredientes:</strong> <?php echo $registro["ingredientes"]; ?></p>
              <p><strong>Precio:</strong> $<?php echo number_format($registro["precio"], 0, ',', '.'); ?></p>
            </div>
            <div class="modal-footer">
              <a href="https://wa.me/573116153722?text=Quiero%20comprar%20<?php echo urlencode($registro['nombre']); ?>" class="btn btn-success" target="_blank">Comprar</a>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
  </div>

  <!-- Botón grande para ver todos los menús -->
  <div class="text-center mt-4">
    <a href="todos-los-menus.php" class="btn btn-secondary btn-lg">Ver todos los platillos</a>
  </div>
</section>

<br/><br/>

<!-- Agregar Bootstrap para los pop-ups -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
