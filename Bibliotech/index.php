<?php
require_once "autoload.php"; // Carga los datos.
session_start();

$biblioteca = new Biblioteca();

// Asegurar que las variables están definidas antes de usarlas
$resultadoBusqueda = null;
$mensajeBusqueda = "";

//Datos cosntantes por si se borran todos los datos, estos datos apareceren siempre.
if (!isset($_SESSION['libros'])) {
    $_SESSION['libros'] = [];
    $biblioteca->agregarLibro(new Libro(1, "1984", "George Orwell", "Distopía"));
    $biblioteca->agregarLibro(new Libro(2, "Fahrenheit 451", "Ray Bradbury", "Ciencia Ficción"));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['agregar'])) {
        $nuevoId = empty($_SESSION['libros']) ? 1 : max(array_keys($_SESSION['libros'])) + 1;
        $nuevoLibro = new Libro($nuevoId, $_POST['titulo'], $_POST['autor'], $_POST['categoria']);
        $biblioteca->agregarLibro($nuevoLibro);
    }

    if (isset($_POST['eliminar'])) {
        $biblioteca->eliminarLibro($_POST['id']);
    }

    if (isset($_POST['editar'])) {
        $biblioteca->editarLibro($_POST['id'], $_POST['titulo'], $_POST['autor'], $_POST['categoria']);
    }

    if (isset($_POST['buscar'])) {
        $resultadoBusqueda = $biblioteca->buscarLibro($_POST['titulo_buscar']);
        if (!$resultadoBusqueda) {
            $mensajeBusqueda = "El libro no está en la lista de libros.";
        }
    }

    if (isset($_POST['prestar'])) {
        $biblioteca->prestarLibro($_POST['id'], $_POST['usuario']);
    }

    if (isset($_POST['devolver'])) {
        $biblioteca->devolverLibro($_POST['id']);
    }
}
?>

<!-- Creacion de HTML tabla de libros -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Biblioteca</title>
</head>
<body>

<h1>Gestión de Biblioteca</h1>

<!-- Formulario para agregar libros -->
<form method="post">
    <input type="text" name="titulo" placeholder="Título" required>
    <input type="text" name="autor" placeholder="Autor" required>
    <input type="text" name="categoria" placeholder="Categoría" required>
    <button type="submit" name="agregar">Agregar Libro</button>
</form>

<!-- Buscador de libros -->
<h2>Buscar Libro</h2>
<form method="post">
    <input type="text" name="titulo_buscar" placeholder="Título del libro" required>
    <button type="submit" name="buscar">Buscar</button>
</form>

<?php if ($resultadoBusqueda): ?>
    <h3>Resultado de Búsqueda</h3>
    <p><?php echo $resultadoBusqueda->getTitulo() . " - " . $resultadoBusqueda->getAutor() . " (" . $resultadoBusqueda->getCategoria() . ")"; ?></p>
<?php elseif ($mensajeBusqueda): ?>
    <p><?php echo $mensajeBusqueda; ?></p>
<?php endif; ?>


<!-- Tabla de libros -->
<h2>Lista de Libros</h2>
<ul>
    <?php foreach ($biblioteca->listarLibros() as $libro): ?>
        <li>
            <!-- Parte de editar y eliminar libros, se debe llenar los campos Titulo, Autor y categoria -->
            <?php echo $libro->getTitulo() . " - " . $libro->getAutor() . " (" . $libro->getCategoria() . ") - " . ($libro->estaDisponible() ? "Disponible" : "Prestado"); ?>
            <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $libro->getId(); ?>">
                <input type="text" name="titulo" placeholder="Nuevo título" required>
                <input type="text" name="autor" placeholder="Nuevo autor" required>
                <input type="text" name="categoria" placeholder="Nueva categoría" required>
                <button type="submit" name="editar">Editar</button>
                <button type="submit" name="eliminar">Eliminar</button>
            </form>
            
            <!-- Verificavion de estado del libro -->
            <?php if ($libro->estaDisponible()): ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $libro->getId(); ?>">
                    <input type="text" name="usuario" placeholder="Nombre del usuario" required>
                    <button type="submit" name="prestar">Prestar</button>
                </form>
            <?php else: ?>

                <!-- Prestamo de libros -->
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $libro->getId(); ?>">
                    <button type="submit" name="devolver">Devolver</button>
                </form>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
