<?php 
include("../../bd.php");

if(isset($_GET["txtID"])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";
    $sentencia=$conexion->prepare("DELETE FROM tbl_testimonios WHERE ID=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    
    header("Location:index.php");
}

$sentencia=$conexion->prepare("SELECT * FROM `tbl_testimonios`");
$sentencia->execute();
$lista_testimonios= $sentencia->fetchAll(PDO::FETCH_ASSOC);

include ("../../templates/header.php"); ?>

<br/>
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">💬 Gestión de Testimonios</h5>
        <a class="btn btn-light btn-sm" href="crear.php" role="button">➕ Agregar Testimonio</a>  
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Opinión</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_testimonios as $value) { ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $value['ID']; ?></td>
                            <td><?php echo substr($value['opinion'], 0, 50); ?>...</td>
                            <td><?php echo $value['nombre']; ?></td>
                            <td>
                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $value['ID']; ?>">🔍 Ver</button>
                                <a class="btn btn-info btn-sm text-white" href="editar.php?txtID=<?php echo $value['ID']; ?>" role="button">✏️ Editar</a>
                                <a class="btn btn-danger btn-sm" href="index.php?txtID=<?php echo $value['ID']; ?>" role="button">🗑️ Borrar</a>
                            </td>
                        </tr>

                        <!-- Modal para ver detalles del testimonio -->
                        <div class="modal fade" id="modal<?php echo $value['ID']; ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">💬 Opinión de <?php echo $value["nombre"]; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>📝 Opinión:</strong> <?php echo $value["opinion"]; ?></p>
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
    <div class="card-footer text-muted text-center">📢 Administración de Testimonios | Restaurante El Manjar</div>
</div>

<?php include ("../../templates/footer.php"); ?>

<!-- Agregar Bootstrap para los pop-ups -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
