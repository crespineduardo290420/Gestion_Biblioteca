<?php
class Libro {
    private $id;
    private $titulo;
    private $autor;
    private $categoria;
    private $disponible;

    public function __construct($id, $titulo, $autor, $categoria) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->disponible = true;
    }

    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getAutor() { return $this->autor; }
    public function getCategoria() { return $this->categoria; }
    public function estaDisponible() { return $this->disponible; }
    public function prestar() { $this->disponible = false; }
    public function devolver() { $this->disponible = true; }
}
?>
