<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include_once __DIR__ . '/../config/config.php';
?>
<header>
    <h1>
    <img src="/mountain-connect/assets/images/logo.png" width="150" height="100" alt="Logo" class="logo">
    <?php echo htmlspecialchars($site_name); ?>
    </h1>
    <nav>
        <a href="/mountain-connect/public/index.php">Inicio</a>
        <?php if(isset($_SESSION['usuario'])): ?>
            <div class="user-menu">
                <a href="/mountain-connect/public/profile.php">
                    <?php echo htmlspecialchars($_SESSION['usuario']['username']); ?>
                </a>
            </div>
            <a href="/mountain-connect/public/logout.php">Cerrar Sesión</a>
        <?php else: ?>
            <a href="/mountain-connect/public/login.php">Iniciar Sesión</a>
            <a href="/mountain-connect/public/register.php">Registro</a>
        <?php endif; ?>
    </nav>
</header>
