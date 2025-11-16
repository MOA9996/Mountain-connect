<?php
session_start();

//  Carpeta donde se guardarán las imágenes
$upload_dir = __DIR__ . '/../../uploads/photos/';

// Crear carpeta si no existe
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$errors = [];
$success = "";

// Inicializar array de rutas en sesión
if (!isset($_SESSION['rutas'])) {
    $_SESSION['rutas'] = [];
}

//  Obtener el tipo desde la URL (por ejemplo ?type=ruta / escalada / ferrata)
$tipo_url = $_GET['type'] ?? '';
$tipo_url = strtolower($tipo_url);

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $dificultad = $_POST["dificultad"] ?? "";
    $distancia = $_POST["distancia"] ?? "";
    $desnivel = $_POST["desnivel"] ?? "";
    $duracion = $_POST["duracion"] ?? "";
    $provincia = $_POST["provincia"] ?? "";
    $epoca = $_POST["epoca"] ?? [];
    $descripcion = $_POST["descripcion"] ?? "";
    $nvl_tec = $_POST["nvl_tec"] ?? "";
    $nvl_fis = $_POST["nvl_fis"] ?? "";
    //  Si hay tipo en la URL, tiene prioridad
    $tipo = $tipo_url ?: ($_POST['tipo'] ?? '');

    //  Validaciones
    if (empty($nombre)) $errors[] = "El nombre de la ruta es obligatorio.";
    if (empty($dificultad)) $errors[] = "Selecciona una dificultad.";
    if (empty($distancia) || !is_numeric($distancia)) $errors[] = "La distancia es obligatoria y debe ser un número.";
    if (empty($desnivel) || !is_numeric($desnivel)) $errors[] = "El desnivel es obligatorio y debe ser un número.";
    if (empty($duracion)) $errors[] = "La duración es obligatoria.";
    if (empty($provincia)) $errors[] = "La provincia es obligatoria.";
    if (empty($descripcion)) $errors[] = "La descripción es obligatoria.";
    if (empty($nvl_tec)) $errors[] = "Selecciona un nivel técnico.";
    if (empty($nvl_fis)) $errors[] = "Selecciona un nivel físico.";
    if (empty($tipo)) $errors[] = "Selecciona un tipo de ruta.";

    //  Validación y guardado de imágenes
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB
    $uploaded_images = $_FILES['imagenes'] ?? null;
    $saved_images = [];

    if ($uploaded_images && isset($uploaded_images['name']) && count($uploaded_images['name']) > 0) {
        for ($i = 0; $i < count($uploaded_images['name']); $i++) {
            if (empty($uploaded_images['name'][$i])) continue;

            $file_name = $uploaded_images['name'][$i];
            $file_type = $uploaded_images['type'][$i];
            $file_tmp = $uploaded_images['tmp_name'][$i];
            $file_size = $uploaded_images['size'][$i];

            if ($uploaded_images['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = "Error al subir la imagen '$file_name'.";
                continue;
            }

            if (!in_array($file_type, $allowed_types)) {
                $errors[] = "El archivo '$file_name' no es un formato permitido. Solo jpg, jpeg y png.";
                continue;
            }

            if ($file_size > $max_size) {
                $errors[] = "El archivo '$file_name' supera el tamaño máximo de 2MB.";
                continue;
            }

            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $safe_name = uniqid('img_', true) . '.' . $ext;

            if (!move_uploaded_file($file_tmp, $upload_dir . $safe_name)) {
                $errors[] = "No se pudo guardar la imagen '$file_name'.";
                continue;
            }

            $saved_images[] = $safe_name;
        }
    }

    // ✅ Guardar ruta si no hay errores
    if (empty($errors)) {
        $ruta = [
            'nombre' => $nombre,
            'tipo' => $tipo, // 👈 ahora sí se guarda el tipo para que list.php pueda filtrar
            'dificultad' => $dificultad,
            'distancia' => $distancia,
            'desnivel' => $desnivel,
            'duracion' => $duracion,
            'provincia' => $provincia,
            'epoca' => $epoca,
            'descripcion' => $descripcion,
            'nvl_tec' => $nvl_tec,
            'nvl_fis' => $nvl_fis,
            'imagenes' => $saved_images
        ];

        $_SESSION['rutas'][] = $ruta;

        $success = "✅ Ruta registrada correctamente.";
        $_POST = [];
        $filesArray = [];

        // 🔁 Redirigir al listado del tipo correspondiente
        header("Location: /mountain-connect/public/routes/list.php?type=$tipo");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear nueva ruta</title>
<link rel="stylesheet" href="../../assets/css/register.css">
<style>
#preview div { display:inline-block; position:relative; margin:5px; }
#preview img { width:100px; height:100px; object-fit:cover; border:1px solid #ccc; border-radius:6px; }
#preview button { position:absolute; top:0; right:0; background:red; color:white; border:none; cursor:pointer; border-radius:50%; width:22px; height:22px; line-height:18px; }
.success {
    background: #e6ffed;
    border: 1px solid #2e7d32;
    color: #2e7d32;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
}
.error {
    background: #ffeaea;
    border: 1px solid #c62828;
    color: #c62828;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<?php include '../../includes/header.php'; ?>

<div class="container">
    <h2>Creación de rutas</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Tipo de ruta:</label>
        <select name="tipo" required <?php echo $tipo_url ? 'disabled' : ''; ?>>
            <option value="">Seleccione...</option>
            <option value="ruta" <?php if(($tipo_url ?: $_POST['tipo'] ?? '')=='ruta') echo 'selected'; ?>>Ruta</option>
            <option value="escalada" <?php if(($tipo_url ?: $_POST['tipo'] ?? '')=='escalada') echo 'selected'; ?>>Escalada</option>
            <option value="ferrata" <?php if(($tipo_url ?: $_POST['tipo'] ?? '')=='ferrata') echo 'selected'; ?>>Ferrata</option>
        </select>

        <label>Nombre de ruta:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>

        <label>Dificultad:</label>
        <select name="dificultad" required>
            <option value="">Seleccione...</option>
            <?php
            $difs = ['Fácil','Moderada','Difícil','Muy difícil'];
            foreach ($difs as $d) {
                $sel = ($_POST['dificultad'] ?? '') == $d ? 'selected' : '';
                echo "<option value='$d' $sel>$d</option>";
            }
            ?>
        </select>

        <label>Distancia (km):</label>
        <input type="number" step="0.1" name="distancia" value="<?php echo htmlspecialchars($_POST['distancia'] ?? ''); ?>" required>

        <label>Desnivel Positivo (m):</label>
        <input type="number" name="desnivel" value="<?php echo htmlspecialchars($_POST['desnivel'] ?? ''); ?>" required>

        <label>Duración estimada:</label>
        <input type="text" name="duracion" placeholder="Ej: 2-3 horas" value="<?php echo htmlspecialchars($_POST['duracion'] ?? ''); ?>" required>

        <label>Provincia:</label>
        <input type="text" name="provincia" value="<?php echo htmlspecialchars($_POST['provincia'] ?? ''); ?>" required>

        <label>Época recomendada:</label>
        <div class="checkbox-group">
            <?php
            $epocas = ['primavera','verano','otoño','invierno'];
            foreach ($epocas as $e) {
                $chk = in_array($e, $_POST['epoca'] ?? []) ? 'checked' : '';
                echo "<label><input type='checkbox' name='epoca[]' value='$e' $chk> " . ucfirst($e) . "</label>";
            }
            ?>
        </div>

        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?>">
        <label>Nivel Técnico (1-5):</label>
        <select name="nvl_tec" required>
            <option value="">Seleccione...</option>
            <?php for ($i=1;$i<=5;$i++) {
                $sel = ($_POST['nvl_tec'] ?? '') == $i ? 'selected' : '';
                echo "<option value='$i' $sel>$i</option>";
            } ?>
        </select>

        <label>Nivel Físico (1-5):</label>
        <select name="nvl_fis" required>
            <option value="">Seleccione...</option>
            <?php for ($i=1;$i<=5;$i++) {
                $sel = ($_POST['nvl_fis'] ?? '') == $i ? 'selected' : '';
                echo "<option value='$i' $sel>$i</option>";
            } ?>
        </select>

        <label>Imágenes de la ruta (jpg, jpeg, png, máx 2MB):</label>
        <input type="file" id="imagenesInput" name="imagenes[]" multiple accept=".jpg,.jpeg,.png">
        <div id="preview"></div>

        <button type="submit">Crear Ruta</button>
        <button type="button" onclick="window.location.href='list.php?type=<?php echo $tipo_url ?: 'ruta'; ?>'">Ver rutas existentes</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>

<script>
const input = document.getElementById('imagenesInput');
const preview = document.getElementById('preview');
let filesArray = [];

input.addEventListener('change', (e) => {
    for (const file of e.target.files) filesArray.push(file);
    renderPreview();
    input.value = '';
});

function renderPreview() {
    preview.innerHTML = '';
    filesArray.forEach((file, index) => {
        const div = document.createElement('div');
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.textContent = '×';
        btn.addEventListener('click', () => {
            filesArray.splice(index, 1);
            renderPreview();
        });
        div.appendChild(img);
        div.appendChild(btn);
        preview.appendChild(div);
    });
}

document.querySelector('form').addEventListener('submit', () => {
    const dataTransfer = new DataTransfer();
    filesArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
});
</script>

</body>
</html>
