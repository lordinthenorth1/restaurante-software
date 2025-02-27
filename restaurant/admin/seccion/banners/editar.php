<?php 
include("../../bd.php");

if(isset($_GET['txtID'])){
   $txtID=(isset($_GET["txtID"]))?$_GET["txtID"]:"";
   
   $sentencia=$conexion->prepare("SELECT * FROM `tbl_banners` WHERE ID=:id");
   $sentencia->bindParam(":id", $txtID);
   $sentencia->execute();

   $registro=$sentencia->fetch(PDO::FETCH_LAZY);
   $titulo=$registro["titulo"];
   $descripcion=$registro["descripcion"];
   $link=$registro["link"];
   $image_url=$registro["image_url"];
   $button_text=$registro["button_text"];
}

if($_POST){
  
  $titulo=(isset($_POST["titulo"]))?$_POST["titulo"]:"";
  $descripcion=(isset($_POST["descripcion"]))?$_POST["descripcion"]:"";
  $link=(isset($_POST["link"]))?$_POST["link"]:"";
  $button_text=(isset($_POST["button_text"]))?$_POST["button_text"]:"";
  $txtID=(isset($_POST["txtID"]))?$_POST["txtID"]:"";

  // Manejo de imagen
  if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
      $image_name = time() . '_' . $_FILES['image']['name'];
      $target_dir = "../../images/";

      if (!is_dir($target_dir)) {
          mkdir($target_dir, 0777, true);
      }

      $target_file = $target_dir . basename($image_name);
      
      if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
          $image_url = "images/" . basename($image_name);
      }
  }

  $sentencia=$conexion->prepare(" UPDATE `tbl_banners`
             SET titulo=:titulo, descripcion=:descripcion, link=:link, image_url=:image_url, button_text=:button_text
             WHERE ID=:id");
    
    $sentencia->bindParam(":titulo",$titulo);
    $sentencia->bindParam(":descripcion",$descripcion);
    $sentencia->bindParam(":link",$link);
    $sentencia->bindParam(":image_url",$image_url);
    $sentencia->bindParam(":button_text",$button_text);
    $sentencia->bindParam(":id",$txtID);

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
      <label for="titulo" class="form-label">ID:</label>
      <input type="text"
        class="form-control" value="<?php echo $txtID;?>" name="txtID" id="txtID" readonly>
    </div>

    <div class="mb-3">
      <label for="titulo" class="form-label">Título:</label>
      <input type="text"
        class="form-control" value="<?php echo $titulo;?>" name="titulo" id="titulo">
    </div>

    <div class="mb-3">
      <label for="descripcion" class="form-label">Descripción:</label>
      <input type="text"
        class="form-control" value="<?php echo $descripcion;?>" name="descripcion" id="descripcion">
    </div>

    <div class="mb-3">
      <label for="link" class="form-label">Link:</label>
      <input type="text"
        class="form-control" value="<?php echo $link;?>" name="link" id="link">
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Imagen del Banner:</label>
      <input type="file" class="form-control" name="image" id="image" accept="image/*">
      <?php if(!empty($image_url)) { ?>
        <img src="../../<?php echo $image_url; ?>" width="100" alt="Imagen actual">
      <?php } ?>
    </div>

    <div class="mb-3">
      <label for="button_text" class="form-label">Texto del Botón CTA:</label>
      <input type="text"
        class="form-control" value="<?php echo $button_text;?>" name="button_text" id="button_text">
    </div>

    <button type="submit" class="btn btn-success">Modificar banner</button>
    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
    </form>
    </div>
    <div class="card-footer text-muted">
    </div>
</div>
<?php include ("../../templates/footer.php"); ?>
