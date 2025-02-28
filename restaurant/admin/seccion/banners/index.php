<?php 
include("../../bd.php");

if(isset($_GET['txtID'])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";

    // Eliminar la imagen del servidor antes de borrar el registro
    $sentencia=$conexion->prepare("SELECT image_url FROM tbl_banners WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $banner = $sentencia->fetch(PDO::FETCH_ASSOC);
    if ($banner && file_exists("C:/xampp/htdocs/restaurant/" . $banner['image_url'])) {
        unlink("C:/xampp/htdocs/restaurant/" . $banner['image_url']);
    }

    // Borrar el registro
    $sentencia=$conexion->prepare("DELETE FROM tbl_banners WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    
    header("Location:index.php");
}

$sentencia=$conexion->prepare("SELECT * FROM `tbl_banners`");
$sentencia->execute();
$lista_banners= $sentencia->fetchAll(PDO::FETCH_ASSOC);

include ("../../templates/header.php"); 
?>

<br/>
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📢 Gestión de Banners</h5>
        <a class="btn btn-light btn-sm" href="crear.php" role="button">➕ Agregar Banner</a>
    </div>
    <div class="card-body">
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Título</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Enlace</th>
                    <th scope="col">Imagen</th>
                    <th scope="col">Texto del Botón</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_banners as $key => $value) { ?>
                    <tr>
                        <td scope="row" class="fw-bold">#<?php echo $value['ID']; ?></td>
                        <td><?php echo $value['titulo']; ?></td>
                        <td><?php echo $value['descripcion']; ?></td>
                        <td><a href="<?php echo $value['link']; ?>" target="_blank" class="text-primary">🔗 Ver enlace</a></td>
                        <td>
                            <?php 
                            $image_path = "/restaurant/" . $value['image_url']; // Ruta accesible desde el navegador
                            if (!empty($value['image_url'])) { 
                            ?>
                                <img src="<?php echo $image_path; ?>" width="100" class="rounded shadow-sm" alt="Imagen del banner">
                            <?php } else { ?>
                                <img src="/restaurant/images/default-banner.jpg" width="100" class="rounded shadow-sm" alt="Imagen por defecto">
                            <?php } ?>
                        </td>
                        <td><?php echo $value['button_text']; ?></td>
                        <td>
                            <a class="btn btn-sm btn-danger" href="index.php?txtID=<?php echo $value['ID']; ?>" role="button">🗑️ Borrar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
    <div class="card-footer text-muted text-center">Administración de banners | Restaurante El Manjar</div>
</div>

<?php include ("../../templates/footer.php"); ?>
