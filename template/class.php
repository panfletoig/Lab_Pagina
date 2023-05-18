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

    /*
    Funcion con cuatro parametros que crea un select de tipo html
    $nombreIdentificador: nombre con el cual se identifica el select
    $select: este es el array de datos que utilizara
    $bb: variable que indica el numero de columnas
    $comparacion: cada cuanto tiene que cerrar la opcion

    primero crea un select, titulo y una opcion llamada Todos
    despues entra en un ciclo, empieza inicializando una variable temporal
    luego recorre por cada valor comparando si tiene o no tiene que poner
    una opcion nueva abriendo y cerrando etiquetas donde le corresponde
    asignandole su respectivo identificador y por ultimo se reinicia
    la variable temporal volviendo a ciclar, cuando acaba el ciclo
    cerramos el select
    */
    function select($nombreIdentificador, $select, $bb, $comparacion)
    {
        echo "</select>
        <h3>$nombreIdentificador:</h3>
        <select name=select$nombreIdentificador id=select>
        <option value=0>Todos</option>";
        while($ids = $select->fetchArray(SQLITE3_ASSOC)){
            $temporal = 0;
            foreach($ids as $id){
                if($temporal % $bb == 0){
                    echo "<option value=$id>";
                }
                if($temporal % $bb == $comparacion){
                    echo "$id</option>";
                }
                $temporal++;
            }
        }

        echo "</select>";
    }
?>