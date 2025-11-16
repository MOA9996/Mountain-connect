<?php
session_start(); 
include_once('../config/config.php');
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


<?php include('../includes/header.php'); ?> 

<main>
    
        <?php if(isset($_SESSION['usuario'])): ?>
                
                <div class="container">
            <label>
            <a href="/mountain-connect/public/routes/list.php?type=ruta">Rutas</a>
            <!-- A los usuarios logueados les llevará al list.php, añadiendo en la url el parámetro de que nos muestre la lista de rutas tipo ruta
            ( misma estructura para el resto de tipos de rutas)-->
            </label>
            <img src="/mountain-connect/assets/images/ruta.png" width="150" height="100" alt="Ruta" >
        </div>
            <?php else: ?>
                
                <div class="container">
            <label>
            <a href="register.php">Rutas</a>
            <!-- A los usuarios no logueados les llevará a la página de registro ( misma estructura para el resto de tipos de rutas) -->
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


