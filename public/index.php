<?php
session_start(); // siempre al inicio
include_once('../config/config.php'); // tu configuración
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name; ?></title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>


<?php include('../includes/header.php'); ?> <!-- tu header -->

<main>
    
        <?php if(isset($_SESSION['usuario'])): ?>
                <!-- Contenido para usuarios logueados -->
                <div class="container">
            <label>
            <a href="/mountain-connect/public/routes/list.php?type=ruta">Rutas</a>
            </label>
            <img src="/mountain-connect/assets/images/ruta.png" width="150" height="100" alt="Ruta" >
        </div>
            <?php else: ?>
                <!-- Contenido para visitantes no logueados -->
                <div class="container">
            <label>
            <a href="register.php">Rutas</a>
            </label>
            <img src="/mountain-connect/assets/images/ruta.png" width="150" height="100" alt="Ruta" >
        </div>
        <?php endif; ?>

<?php if(isset($_SESSION['usuario'])): ?>
    <div class="container">
        <label>
        <a href="/mountain-connect/public/routes/list.php?type=escalada">Escalada</a>
        </label>
        <img src="/mountain-connect/assets/images/escalada.png" width="150" height="100" alt="Escalada" >
    </div>

        <?php else: ?>
            <!-- Contenido para visitantes no logueados -->
            <div class="container">
        <label>
        <a href="register.php">Escalada</a>
        </label>
        <img src="/mountain-connect/assets/images/escalada.png" width="150" height="100" alt="Escalada" >
    </div>
        <?php endif; ?>

<?php if(isset($_SESSION['usuario'])): ?>
    <div class="container">
        <label>
        <a href="/mountain-connect/public/routes/list.php?type=ferrata">Ferratas</a>
        </label>
        <img src="/mountain-connect/assets/images/ferrata.png" width="150" height="100" alt="Ferrata" >
    </div>

        <?php else: ?>
            <!-- Contenido para visitantes no logueados -->
            <div class="container">
        <label>
        <a href="register.php">Ferratas</a>
        </label>
        <img src="/mountain-connect/assets/images/ferrata.png" width="150" height="100" alt="Ferrata" >
    </div>
        <?php endif; ?>

</main>

<?php include('../includes/footer.php'); ?> 

</body>
</html>


