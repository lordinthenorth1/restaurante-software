<?php 
include("../../bd.php");

if(isset($_GET['txtID'])){
    $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";

    // Proceso de borrado que busca la imagen y la elimina
    $sentencia=$conexion->prepare("SELECT * FROM `tbl_colaboradores` WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro_foto=$sentencia->fetch(PDO::FETCH_LAZY);
   
    if(isset($registro_foto['foto']) && !empty($registro_foto['foto'])){
        $foto_path = "../../../images/colaboradores/" . $registro_foto['foto'];
        if(file_exists($foto_path)){
            unlink($foto_path);
        }
    }
    
    // Borra en la base de datos
    $sentencia=$conexion->prepare("DELETE FROM tbl_colaboradores WHERE ID=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    
    header("Location:index.php");
}

$sentencia=$conexion->prepare("SELECT * FROM `tbl_colaboradores`");
$sentencia->execute();
$lista_colaboradores= $sentencia->fetchAll(PDO::FETCH_ASSOC);

include ("../../templates/header.php"); 
?>

<br/>
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">👨‍🍳 Gestión de Colaboradores</h5>
        <a class="btn btn-light btn-sm" href="crear.php" role="button">➕ Agregar Colaborador</a>
    </div>
    <div class="card-body">
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Info</th>
                    <th scope="col">Redes Sociales</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($lista_colaboradores as $value) { ?>
                <tr>
                    <td class="fw-bold">#<?php echo $value['ID']; ?></td>
                    <td><?php echo $value['titulo']; ?></td>
                    <td>
                        <img src="../../../images/colaboradores/<?php echo $value['foto']; ?>" width="60" class="rounded shadow-sm" alt="Foto Colaborador">
                    </td>
                    <td><?php echo $value['descripcion']; ?></td>
                    <td>
                        <a href="<?php echo $value['linkfacebook']; ?>" target="_blank" class="text-primary">🌐 Facebook</a><br/>
                        <a href="<?php echo $value['linkinstagram']; ?>" target="_blank" class="text-danger">📸 Instagram</a><br/>
                        <a href="<?php echo $value['linklinkedin']; ?>" target="_blank" class="text-info">💼 LinkedIn</a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-info text-white" href="editar.php?txtID=<?php echo $value['ID']; ?>" role="button">✏️ Editar</a>
                        <a class="btn btn-sm btn-danger" href="index.php?txtID=<?php echo $value['ID']; ?>" role="button">🗑️ Borrar</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
    <div class="card-footer text-muted text-center">Administración de Colaboradores | Restaurante El Manjar</div>
</div>

<?php include ("../../templates/footer.php"); ?>
