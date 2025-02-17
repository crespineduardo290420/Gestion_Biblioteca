<?php
session_start();
require_once "Libro.php";
require_once "Biblioteca.php";

if (!isset($_SESSION['libros'])) {
    $_SESSION['libros'] = [];
    $biblioteca = new Biblioteca();
    $biblioteca->agregarLibro(new Libro(1, "1984", "George Orwell", "Distopía"));
    $biblioteca->agregarLibro(new Libro(2, "Fahrenheit 451", "Ray Bradbury", "Ciencia Ficción"));
}
?>
