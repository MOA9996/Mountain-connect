<?php
session_start();

$id = $_GET['id'] ?? null;
// primero verificamos que la ruta tenga un id, luego buscamos la ruta por su id y luego reindexamos la lista de arrays con array_values
if ($id !== null && isset($_SESSION['rutas'][$id])) { 
    
    unset($_SESSION['rutas'][$id]);  
    $_SESSION['rutas'] = array_values($_SESSION['rutas']); 
}

header("Location: list.php");
exit;
?>
