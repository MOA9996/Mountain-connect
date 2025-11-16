<?php
session_start();

$id = $_GET['id'] ?? null;

if ($id !== null && isset($_SESSION['rutas'][$id])) {
    unset($_SESSION['rutas'][$id]);  
    $_SESSION['rutas'] = array_values($_SESSION['rutas']); // Reindexar
}

header("Location: list.php");
exit;
?>
