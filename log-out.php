<?
session_start();

// Borrar variables de sesión
session_unset(); 

// cerrar la sesión
session_destroy(); 

//redirección a index

header("Location: /");
die();
?>