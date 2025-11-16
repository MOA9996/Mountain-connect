<?php
session_start();

$usuarios = $_SESSION['usuarios'] ?? [];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST["login"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($login) || empty($password)) {
        $error = "Por favor, complete todos los campos.";
    } else {
        $usuarioEncontrado = null;

        foreach ($usuarios as $u) {
            if (($u['username'] === $login || $u['email'] === $login) && $u['password'] === $password) {
                $usuarioEncontrado = $u;
                break;
            }
        }

        if ($usuarioEncontrado) {
            $_SESSION['usuario'] = [
                "username" => $usuarioEncontrado['username'],
                "email" => $usuarioEncontrado['email']
            ];
            header("Location: index.php");
            exit;
        } else {
            $error = "Credenciales incorrectas. Intente nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login de Usuario</title>
<link rel="stylesheet" href="../assets/css/register.css">

</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h2>Iniciar Sesión</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label>Usuario o Email:</label>
        <input type="text" name="login" placeholder="Ej. juan o juan@mail.com">

        <label>Contraseña:</label>
        <input type="password" name="password">

        <button type="submit">Entrar</button>
    </form>


</div>

</body>
</html>
