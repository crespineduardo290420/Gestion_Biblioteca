<?php
class Biblioteca {
    public function agregarLibro($libro) {
        $_SESSION['libros'][$libro->getId()] = $libro;
    }

    public function eliminarLibro($id) {
        if (isset($_SESSION['libros'][$id])) {
            unset($_SESSION['libros'][$id]);
        }
    }

    public function editarLibro($id, $titulo, $autor, $categoria) {
        if (isset($_SESSION['libros'][$id])) {
            $_SESSION['libros'][$id]->setTitulo($titulo);
            $_SESSION['libros'][$id]->setAutor($autor);
            $_SESSION['libros'][$id]->setCategoria($categoria);
        }
    }

    public function listarLibros() {
        return isset($_SESSION['libros']) ? $_SESSION['libros'] : [];
    }

    public function buscarLibro($titulo) {
        foreach ($this->listarLibros() as $libro) {
            if (strcasecmp($libro->getTitulo(), $titulo) == 0) {
                return $libro;
            }
        }
        return null;
    }

    public function prestarLibro($id, $usuario) {
        if (isset($_SESSION['libros'][$id]) && $_SESSION['libros'][$id]->estaDisponible()) {
            $_SESSION['libros'][$id]->prestar();
            $_SESSION['prestamos'][$id] = $usuario;
        }
    }

    public function devolverLibro($id) {
        if (isset($_SESSION['libros'][$id]) && !$_SESSION['libros'][$id]->estaDisponible()) {
            $_SESSION['libros'][$id]->devolver();
            unset($_SESSION['prestamos'][$id]);
        }
    }
}
?>
