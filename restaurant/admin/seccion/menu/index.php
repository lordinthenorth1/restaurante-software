<?php 
include("../../bd.php");

if(isset($_GET['txtID'])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";
    
    // Obtener la foto antes de borrar el registro
    $sentencia = $conexion->prepare("SELECT * FROM `tbl_menu` WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_foto = $sentencia->fetch(PDO::FETCH_LAZY);
   
    if(isset($registro_foto['foto']) && file_exists("../../../images/menu/".$registro_foto['foto'])){
        unlink("../../../images/menu/".$registro_foto['foto']);
    }

    // Borrar el registro de la base de datos
    $sentencia = $conexion->prepare("DELETE FROM tbl_menu WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    
    header("Location:index.php");
}

$sentencia = $conexion->prepare("SELECT * FROM `tbl_menu`");
$sentencia->execute();
$lista_menu = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include ("../../templates/header.php"); 
?>

<br>
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🍽️ Gestión del Menú</h5>
        <a class="btn btn-light btn-sm" href="crear.php" role="button">➕ Agregar Platillo</a>  
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Ingredientes</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lista_menu as $registro){ ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $registro["ID"];?></td>
                            <td><?php echo $registro["nombre"];?></td>
                            <td><?php echo $registro["ingredientes"];?></td>
                            <td><img src="../../../images/menu/<?php echo $registro['foto']; ?>" width="60" class="rounded shadow-sm" alt=""></td>
                            <td class="text-success fw-bold">$<?php echo number_format($registro["precio"], 0, ',', '.'); ?></td>
                            <td>
                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $registro['ID']; ?>">🔍 Ver</button>
                                <a class="btn btn-info btn-sm text-white" href="editar.php?txtID=<?php echo $registro['ID']; ?>" role="button">✏️ Editar</a>
                                <a class="btn btn-danger btn-sm" href="index.php?txtID=<?php echo $registro['ID']; ?>" role="button">🗑️ Borrar</a>
                            </td>
                        </tr>

                        <!-- Modal para cada platillo -->
                        <div class="modal fade" id="modal<?php echo $registro['ID']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">🍽️ <?php echo $registro["nombre"]; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="../../../images/menu/<?php echo $registro["foto"]; ?>" class="img-fluid rounded mb-3" alt="<?php echo $registro["nombre"]; ?>">
                                        <p><strong>📝 Ingredientes:</strong> <?php echo $registro["ingredientes"]; ?></p>
                                        <p><strong>💰 Precio:</strong> $<?php echo number_format($registro["precio"], 0, ',', '.'); ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted text-center">📋 Administración del Menú | Restaurante El Manjar</div>
</div>

<?php include ("../../templates/footer.php"); ?>

<!-- Agregar Bootstrap para los pop-ups -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
