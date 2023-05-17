<?php
    /*
    Crea una clase para la base de datos basada en SQLite3 
    donde el unico valor incluido es la ubicacion de la 
    base de datos || php incluye la libreria sobre sqlite
    */
    class BaseDatos extends SQLite3{
        function __construct(){
            $this->open("..\sqlite\usuario.db");
        }
    }
?>