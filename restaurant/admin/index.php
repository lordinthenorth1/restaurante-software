<?php include("templates/header.php"); ?>
<br/>
<div class="row align-items-md-stretch justify-content-center">
    <div class="col-md-10">
        <div class="h-100 p-5 bg-dark text-white rounded shadow-lg text-center">
            <h1 class="fw-bold"> ¡Bienvenido, <?php echo $_SESSION["usuario"]; ?>! </h1>
            <p class="lead">Administra el sitio web del restaurante de forma rápida y sencilla.</p>
            <hr class="my-4 border-light">
            <p class="fs-5">Explora las opciones del panel y mantén tu contenido siempre actualizado.</p>
        </div>
    </div>
</div>

<?php include("templates/footer.php"); ?>
