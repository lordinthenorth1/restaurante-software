<?php 
include("../../bd.php");

if(isset($_GET['txtID'])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";
    
    $sentencia=$conexion->prepare("DELETE FROM tbl_comentarios WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    
    header("Location:index.php");
}

$sentencia=$conexion->prepare("SELECT * FROM `tbl_comentarios`");
$sentencia->execute();
$lista_comentarios= $sentencia->fetchAll(PDO::FETCH_ASSOC);

include ("../../templates/header.php"); ?>

<br/>
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📩 Bandeja de Comentarios</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Mensaje</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($lista_comentarios as $registro){ ?>
                <tr>
                        <td class="fw-bold">#<?php echo $registro["ID"];?></td>
                        <td><?php echo $registro["nombre"];?></td>
                        <td><?php echo $registro["correo"];?></td>
                        <td><?php echo substr($registro["mensaje"], 0, 50); ?>...</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $registro['ID']; ?>">🔍 Ver</button>
                            <a class="btn btn-danger btn-sm" href="index.php?txtID=<?php echo $registro['ID']; ?>" role="button">🗑️ Borrar</a>
                        </td>
                    </tr>

                    <!-- Modal para ver detalles del comentario -->
                    <div class="modal fade" id="modal<?php echo $registro['ID']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">📩 Mensaje de <?php echo $registro["nombre"]; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>✉️ Correo:</strong> <?php echo $registro["correo"]; ?></p>
                                    <p><strong>📝 Mensaje:</strong> <?php echo $registro["mensaje"]; ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php  }?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-muted text-center">📬 Administración de Comentarios | Restaurante El Manjar</div>
</div>

<?php include ("../../templates/footer.php"); ?>

<!-- Agregar Bootstrap para los pop-ups -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
