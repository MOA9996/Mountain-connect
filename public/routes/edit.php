<?php
session_start();
include '../../includes/header.php';


$id = $_GET['id'];

$ruta = $_SESSION['rutas'][$id];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];

    // actaulizamos los datos de la ruta 
    $_SESSION['rutas'][$id] = [
        'id' => $id,
        'tipo' => $_POST['tipo'],
        'nombre' => $_POST['nombre'],
        'dificultad' => $_POST['dificultad'],
        'distancia' => $_POST['distancia'],
        'desnivel' => $_POST['desnivel'],
        'duracion' => $_POST['duracion'],
        'provincia' => $_POST['provincia'],
        'nvl_tec' => $_POST['nvl_tec'],
        'nvl_fis' => $_POST['nvl_fis'],
        'descripcion' => $_POST['descripcion'],
        'epoca' => $_POST['epoca'] ?? [],
        'imagenes' => $_SESSION['rutas'][$id]['imagenes']   
    ];

    // redirigimos al list.php del tipo correspondiente d e ruta 
    header("Location: list.php?type=" . urlencode($_POST['tipo']));
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar ruta</title>
<link rel="stylesheet" href="../../assets/css/register.css">
</head>
<body>

<div class="container">
    <h2>Editar <?php echo htmlspecialchars($ruta['tipo']); ?></h2>

    <form action="" method="POST">

        <input type="hidden" name="id" value="<?php echo $ruta['id']; ?>">

        <label>Tipo:</label>
        <select name="tipo">
            <option value="ruta" <?php if ($ruta['tipo'] === 'ruta') echo 'selected'; ?>>Ruta</option>
            <option value="escalada" <?php if ($ruta['tipo'] === 'escalada') echo 'selected'; ?>>Escalada</option>
            <option value="ferrata" <?php if ($ruta['tipo'] === 'ferrata') echo 'selected'; ?>>Ferrata</option>
        </select>

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($ruta['nombre']); ?>" required>

        <label>Dificultad:</label>
        <input type="text" name="dificultad" value="<?php echo htmlspecialchars($ruta['dificultad']); ?>">

        <label>Distancia (km):</label>
        <input type="number" step="0.1" name="distancia" value="<?php echo htmlspecialchars($ruta['distancia']); ?>">

        <label>Desnivel (m):</label>
        <input type="number" name="desnivel" value="<?php echo htmlspecialchars($ruta['desnivel']); ?>">

        <label>Duración:</label>
        <input type="text" name="duracion" value="<?php echo htmlspecialchars($ruta['duracion']); ?>">

        <label>Provincia:</label>
        <input type="text" name="provincia" value="<?php echo htmlspecialchars($ruta['provincia']); ?>">

        <label>Nivel Técnico (1-5):</label>
        <input type="number" name="nvl_tec" min="1" max="5" value="<?php echo htmlspecialchars($ruta['nvl_tec']); ?>">

        <label>Nivel Físico (1-5):</label>
        <input type="number" name="nvl_fis" min="1" max="5" value="<?php echo htmlspecialchars($ruta['nvl_fis']); ?>">

        <label>Épocas recomendadas:</label>
        <div class="checkbox-group">
            <?php
            $epocas = ['primavera','verano','otoño','invierno'];
            foreach ($epocas as $epoca): 
            ?>
                <label>
                    <input type="checkbox" name="epoca[]" value="<?php echo $epoca; ?>" 
                        <?php if (in_array($epoca, $ruta['epoca'] ?? [])) echo 'checked'; ?>>
                    <?php echo ucfirst($epoca); ?>
                </label>
            <?php endforeach; ?>
        </div>

        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?php echo htmlspecialchars($ruta['descripcion']); ?>">

        <button type="submit" class="btn-crear">Guardar cambios</button>

    </form>
</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
