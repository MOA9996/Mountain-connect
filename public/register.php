<?php
session_start();

if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $nivel = $_POST["nivel"] ?? "";
    $especialidad = $_POST["especialidad"] ?? "";
    $provincia = $_POST["provincia"] ?? "";

    // Validaciones
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) ||
        empty($nivel) || empty($especialidad) || empty($provincia)) {
        $errors[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El formato del correo electrónico no es válido.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Evitar duplicados
    foreach ($_SESSION['usuarios'] as $u) {
        if ($u['username'] === $username) {
            $errors[] = "El nombre de usuario ya está en uso.";
            break;
        }
        if ($u['email'] === $email) {
            $errors[] = "El email ya está registrado.";
            break;
        }
    }

    if (empty($errors)) {
        // Guardar usuario en la lista de usuarios
        $nuevo_usuario = [
            "username" => $username,
            "email" => $email,
            "password" => $password, // en producción, usar password_hash
            "nivel" => $nivel,
            "especialidad" => $especialidad,
            "provincia" => $provincia
        ];

        $_SESSION['usuarios'][] = $nuevo_usuario;

        // Loguear automáticamente al usuario (opcional)
        $_SESSION['usuario'] = $nuevo_usuario;

        $success = "¡Registro completado con éxito!";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro de Usuario</title>
<link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Registro de Usuario</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <label>Nombre de usuario:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">

        <label>Contraseña:</label>
        <input type="password" name="password">

        <label>Confirmar contraseña:</label>
        <input type="password" name="confirm_password">

        <label>Nivel de experiencia:</label>
        <select name="nivel">
            <option value="">Seleccione...</option>
            <option value="Principiante" <?php if(($nivel ?? '') == "Principiante") echo 'selected'; ?>>Principiante</option>
            <option value="Intermedio" <?php if(($nivel ?? '') == "Intermedio") echo 'selected'; ?>>Intermedio</option>
            <option value="Avanzado" <?php if(($nivel ?? '') == "Avanzado") echo 'selected'; ?>>Avanzado</option>
        </select>

        <label>Especialidad:</label>
        <select name="especialidad">
            <option value="">Seleccione...</option>
            <option value="Senderismo" <?php if(($especialidad ?? '') == "Senderismo") echo 'selected'; ?>>Senderismo</option>
            <option value="Escalada" <?php if(($especialidad ?? '') == "Escalada") echo 'selected'; ?>>Escalada</option>
            <option value="Barranquismo" <?php if(($especialidad ?? '') == "Barranquismo") echo 'selected'; ?>>Barranquismo</option>
        </select>

        <label>Provincia:</label>
        <input type="text" name="provincia" value="<?php echo htmlspecialchars($provincia ?? ''); ?>">

        <button type="submit">Registrarse</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
