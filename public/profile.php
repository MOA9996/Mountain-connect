<?php
session_start();

// en caso de no existir una sesión iniciada, te llevará a la página de loguin, no dejando acceder a profile.php
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = &$_SESSION['usuario'];       
$rutas = $_SESSION['rutas'] ?? [];      // Rutas creadas por el usuario

// en caso de introducir el parámetro edit se irá al modo edición
$modo_editar = isset($_GET['edit']);    

$mensaje = "";

// página alternativa de edición de los datos del perfil
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['actualizar_perfil'])) {

    $usuario['email']        = trim($_POST['email']);
    $usuario['nivel']        = trim($_POST['nivel']);
    $usuario['especialidad'] = trim($_POST['especialidad']);
    $usuario['provincia']    = trim($_POST['provincia']);

    $mensaje = "Datos actualizados correctamente.";

    // volver al modo normal del perfil
    $modo_editar = false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Perfil de Usuario</title>
<link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">

    <h2>Perfil de <?php echo htmlspecialchars($usuario['username']); ?></h2>

    <?php if ($mensaje): ?>
        <div class="success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>


    <?php if (!$modo_editar): ?>

        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
        <p><strong>Nivel de experiencia:</strong> <?php echo htmlspecialchars($usuario['nivel']); ?></p>
        <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($usuario['especialidad']); ?></p>
        <p><strong>Provincia:</strong> <?php echo htmlspecialchars($usuario['provincia']); ?></p>

        <button onclick="window.location.href='profile.php?edit=1'">
            Editar perfil
        </button>

        <hr>

        <h3>Rutas creadas por ti</h3>

        <?php if (!empty($rutas)): ?>
            <ul>
                <?php foreach ($rutas as $ruta): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($ruta['nombre']); ?></strong>
                        (<?php echo htmlspecialchars($ruta['tipo']); ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No has creado ninguna ruta todavía.</p>
        <?php endif; ?>


    <?php else: ?>

        <h3>Editar tu información</h3>

        <form method="post">
            <input type="hidden" name="actualizar_perfil" value="1">

            <label>Email:</label>
            <input type="email" name="email"
                value="<?php echo htmlspecialchars($usuario['email']); ?>" required>

            <label>Nivel de experiencia:</label>
            <input type="text" name="nivel"
                value="<?php echo htmlspecialchars($usuario['nivel']); ?>">

            <label>Especialidad:</label>
            <input type="text" name="especialidad"
                value="<?php echo htmlspecialchars($usuario['especialidad']); ?>">

            <label>Provincia:</label>
            <input type="text" name="provincia"
                value="<?php echo htmlspecialchars($usuario['provincia']); ?>">

            <button type="submit">Guardar</button>
            <button type="button" onclick="window.location.href='profile.php'">
                Cancelar
            </button>
        </form>

    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
