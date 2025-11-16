<?php
session_start();
include '../../includes/header.php';


$type = $_GET['type'] ?? 'ruta';
$type = strtolower($type);

// asigna un id a cada ruta 
if (!empty($_SESSION['rutas'])) {
    foreach ($_SESSION['rutas'] as $i => $r) {
        $_SESSION['rutas'][$i]['id'] = $i;
    }
}

// Filtro del tipo de ruta
$filtradas = [];
if (!empty($_SESSION['rutas'])) {
    foreach ($_SESSION['rutas'] as $ruta) {
        if (isset($ruta['tipo']) && strtolower($ruta['tipo']) === $type) {
            $filtradas[] = $ruta;
        }
    }
}

$titulo = ucfirst($type);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Listado</title>
<link rel="stylesheet" href="../../assets/css/register.css">

<style>
.container { max-width:900px; margin:0 auto; padding:20px; position: relative; }

.btn-crear {
    display: inline-block;
    padding: 10px 18px;
    background-color: #2b7a78;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 20px;
}

.ruta { 
    border:1px solid #ccc; 
    border-radius:8px; 
    padding:20px; 
    margin:15px 0; 
    background: #f9f9f9;
}

.ruta h3 { 
    margin-top:0; 
    color: #2b7a78;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.imagenes { 
    display:grid; 
    grid-template-columns: repeat(auto-fill,minmax(150px,1fr)); 
    gap:15px; 
    margin-top:15px; 
}

.imagenes img { 
    width:100%; 
    height:120px; 
    object-fit:cover; 
    border-radius:6px; 
    border: 2px solid #ddd;
}

.ruta-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.epoca-tag {
    background: #e3f2fd;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8em;
    color: #1976d2;
}

.btn-editar {
    padding: 8px 12px;
    background: #1976d2;
    color: white;
    border-radius: 6px;
    text-decoration: none;
}

.btn-eliminar {
    padding: 8px 12px;
    background: #c62828;
    color: white;
    border-radius: 6px;
    text-decoration: none;
}

.tipo-selector {
    position: absolute;
    top: 25px;
    left: 15px;
    background: white;
    padding: 5px 10px;
    border-radius: 6px;
}
</style>
</head>
<body>

<div class="container">

    <div class="tipo-selector">
        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" onchange="cambiarTipo(this.value)">
            <option value="ruta" <?php if($type==='ruta') echo 'selected'; ?>>Rutas</option>
            <option value="escalada" <?php if($type==='escalada') echo 'selected'; ?>>Escalada</option>
            <option value="ferrata" <?php if($type==='ferrata') echo 'selected'; ?>>Ferratas</option>
        </select>
    </div>

    <script>
    function cambiarTipo(tipo) {
        window.location.href = '?type=' + tipo;
    }
    </script>

    <h2 style="text-align:center;">Listado de <?php echo htmlspecialchars($titulo); ?>s</h2>

    <!-- Botón crear -->
    <div style="text-align:center;">
        <a href="/mountain-connect/public/routes/create.php?type=<?php echo urlencode($type); ?>" class="btn-crear">
            Crear nueva <?php echo htmlspecialchars($type); ?>
        </a>
    </div>

    <!-- Listado -->
    <?php if (!empty($filtradas)): ?>
        <?php foreach ($filtradas as $ruta): ?>
            <div class="ruta">
                <h3><?php echo htmlspecialchars($ruta['nombre']); ?></h3>

                <div class="ruta-info">
                    <p><strong>Dificultad:</strong> <?php echo htmlspecialchars($ruta['dificultad']); ?></p>
                    <p><strong>Distancia:</strong> <?php echo htmlspecialchars($ruta['distancia']); ?> km</p>
                    <p><strong>Desnivel:</strong> <?php echo htmlspecialchars($ruta['desnivel']); ?> m</p>
                    <p><strong>Duración:</strong> <?php echo htmlspecialchars($ruta['duracion']); ?></p>
                    <p><strong>Provincia:</strong> <?php echo htmlspecialchars($ruta['provincia']); ?></p>
                    <p><strong>Nivel Técnico:</strong> <?php echo htmlspecialchars($ruta['nvl_tec']); ?>/5</p>
                    <p><strong>Nivel Físico:</strong> <?php echo htmlspecialchars($ruta['nvl_fis']); ?>/5</p>
                </div>

                <?php if (!empty($ruta['epoca'])): ?>
                <strong>Época recomendada:</strong>
                <div>
                    <?php foreach ($ruta['epoca'] as $epoca): ?>
                        <span class="epoca-tag"><?php echo ucfirst(htmlspecialchars($epoca)); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($ruta['descripcion'])); ?></p>

                <strong>Imágenes:</strong>
                <?php if (!empty($ruta['imagenes'])): ?>
                    <div class="imagenes">
                        <?php foreach ($ruta['imagenes'] as $img): ?>
                            <img src="/mountain-connect/uploads/photos/<?php echo htmlspecialchars($img); ?>" 
                                alt="Imagen de <?php echo htmlspecialchars($ruta['nombre']); ?>">
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p><em>No hay imágenes</em></p>
                <?php endif; ?>

                
                <div style="margin-top: 15px; display:flex; gap:10px;">
                    <a href="edit.php?id=<?php echo $ruta['id']; ?>" class="btn-editar">Editar</a>

                    <a href="delete.php?id=<?php echo $ruta['id']; ?>" 
                        class="btn-eliminar"
                        onclick="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                    Eliminar
                    </a>
                </div>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align:center; padding:40px; color:#666;">
            No hay <?php echo htmlspecialchars($type); ?>s registradas.
        </div>
    <?php endif; ?>

</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>
