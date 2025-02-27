<?php 
include("../../bd.php");

if($_POST){

    $titulo=(isset($_POST["titulo"]))?$_POST["titulo"]:"";
    $descripcion=(isset($_POST["descripcion"]))?$_POST["descripcion"]:"";
    $link=(isset($_POST["link"]))?$_POST["link"]:"";
    $button_text=(isset($_POST["button_text"]))?$_POST["button_text"]:"";
    
    // Manejo de imagen
    $image_url = "";
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $target_dir = "C:/xampp/htdocs/restaurant/images/"; // Ruta absoluta

        // Crear la carpeta si no existe
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($image_name);

        // Mover la imagen y guardar la ruta relativa
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = "images/" . basename($image_name); // Ruta accesible desde el navegador
        }
    }

    $sentencia=$conexion->prepare("INSERT INTO `tbl_banners`
             (`ID`, `titulo`, `descripcion`, `link`, `image_url`, `button_text`) 
             VALUES (NULL, :titulo, :descripcion, :link, :image_url, :button_text);");
    
    $sentencia->bindParam(":titulo",$titulo);
    $sentencia->bindParam(":descripcion",$descripcion);
    $sentencia->bindParam(":link",$link);
    $sentencia->bindParam(":image_url",$image_url);
    $sentencia->bindParam(":button_text",$button_text);
    
    $sentencia->execute();
    header("Location:index.php");
}

include ("../../templates/header.php"); 
?>

<br/>
<div class="card">
    <div class="card-header">
        Banners
    </div>
    <div class="card-body">

    <form action="" method="post" enctype="multipart/form-data">

    <div class="mb-3">
      <label for="titulo" class="form-label">Título:</label>
      <input type="text"
        class="form-control" name="titulo" id="titulo" placeholder="Escriba el título del banner">
    </div>

    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción:</label>
      <input type="text"
        class="form-control" name="descripcion" id="descripcion" placeholder="Escriba la descripción del banner">
    </div>

    <div class="mb-3">
      <label for="link" class="form-label">Link:</label>
      <input type="text"
        class="form-control" name="link" id="link" placeholder="Escriba el enlace">
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Imagen del Banner:</label>
      <input type="file" class="form-control" name="image" id="image" accept="image/*">
    </div>

    <div class="mb-3">
      <label for="button_text" class="form-label">Texto del Botón CTA:</label>
      <input type="text"
        class="form-control" name="button_text" id="button_text" placeholder="Escriba el texto del botón">
    </div>

    <button type="submit" class="btn btn-success">Crear banner</button>
    <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
    
    </form>

    </div>
    <div class="card-footer text-muted"></div>
</div>

<?php include ("../../templates/footer.php"); ?>
