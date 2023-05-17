<?php
    //referencia a un archivo que contiene la clase base de datos
    include('../template/class.php');

    /*
    $selectId: Toma la opcion seleccionada y si no hay nada la vuelve nula
    $db: instancia la base de datos
    */

    $selectId = $_POST['selectId'] ?? null;
    $selectEstado = $_POST['selectEstado'] ?? null;
    $selectMarca = $_POST['selectMarca'] ?? null;
    $selectUbicacion = $_POST['selectUbicacion'] ?? null;
    $db = new BaseDatos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Inventario</h1>
    </header>
    <main>
        <section>
            <?php
                //detecta si se conecto a la base de datos
                if ($db) {
                    echo "<p>Conectado a inventario</p>";
                } else{
                    echo "<p>Error al intentar abrir inventario</p>";
                }

                $result = $db->query("SELECT * FROM Productos");
                
                //si todas las variables valen 0 muestra todos los datos y en caso de no ser asi hace las comparaciones respectivas
                if($selectId == 0 && $selectEstado == 0 && $selectMarca == 0 && $selectUbicacion == 0){
                    $productosSelect = $result;
                }
                else{
                    if($selectEstado != 0){
                        if($selectMarca != 0){
                            if($selectUbicacion !=0){
                                if($selectId != 0)
                                $productosSelect = $db->query("SELECT * FROM Productos WHERE id_estado = '$selectEstado' AND id_marca = '$selectMarca' AND id_bodega = '$selectUbicacion' AND id_producto = '$selectId' ");
                                else
                                $productosSelect = $db->query("SELECT * FROM Productos WHERE id_estado = '$selectEstado' AND id_marca = '$selectMarca' AND id_bodega = '$selectUbicacion'");
                            }
                            else if($selectId != 0){
                                $productosSelect = $db->query("SELECT * FROM Productos WHERE id_estado = '$selectEstado' AND id_marca = '$selectMarca' AND id_producto = '$selectId' ");
                            }
                            else
                            $productosSelect = $db->query("SELECT * FROM Productos WHERE id_estado = '$selectEstado' AND id_marca = '$selectMarca'");
                        }
                        else if($selectUbicacion !=0){
                            if($selectId != 0)
                                $productosSelect = $db->query("SELECT * FROM Productos WHERE id_estado = '$selectEstado' AND id_bodega = '$selectUbicacion' AND id_producto = '$selectId'");
                            else
                                $productosSelect = $db->query("SELECT * FROM Productos WHERE id_estado = '$selectEstado' AND id_bodega = '$selectUbicacion'");
                        }
                        else if($selectId != 0){
                            $productosSelect = $db->query("SELECT * FROM Productos WHERE id_producto = '$selectId' ");
                        }
                        else
                            $productosSelect = $db->query("SELECT * FROM Productos WHERE id_estado = '$selectEstado'");
                    }
                    else if($selectMarca != 0){
                        if($selectUbicacion != 0){
                            if($selectId != 0){
                                $productosSelect = $db->query("SELECT * FROM Productos WHERE id_marca = '$selectMarca' AND id_bodega = '$selectUbicacion' AND id_producto = '$selectId'");
                            }
                            else
                                $productosSelect = $db->query("SELECT * FROM Productos WHERE id_marca = '$selectMarca' AND id_bodega = '$selectUbicacion'");
                        }
                        else if($selectId != 0)
                            $productosSelect = $db->query("SELECT * FROM Productos WHERE id_marca = '$selectMarca' AND id_producto = '$selectId'");
                        else
                            $productosSelect = $db->query("SELECT * FROM Productos WHERE id_marca = '$selectMarca'");
                    }
                    else if($selectUbicacion !=0){
                        if($selectId != 0)
                            $productosSelect = $db->query("SELECT * FROM Productos WHERE id_bodega = '$selectUbicacion' AND id_producto = '$selectId'");
                        else
                            $productosSelect = $db->query("SELECT * FROM Productos WHERE id_bodega = '$selectUbicacion'");
                    }
                    else
                        $productosSelect = $db->query("SELECT * FROM Productos WHERE id_producto = '$selectId'");
                }
                
                /*
                $a: contador para organizar en filas y columnas
                $b: numero de columnas
                $c: numero maximo de ids
                $estadoSelect: toma los datos de la tabla estado
                $marcaSelect: toma los datos de la tabla marca
                $bodegaSelect: toma los datos de la tabla bodega
                */
                $a = 0;
                $b = 6;
                $c = 0;
                $estadoSelect = $db->query("SELECT * FROM Estado");
                $marcaSelect = $db->query("SELECT * FROM Marca");
                $bodegaSelect = $db->query("SELECT * FROM Bodega");

                //creamos un form
                echo "<form method=post action=mainInventario.php>";
                //llamamos a funciones que crean los select
                select("Id", $result, $b, 0);
                select("Estado", $estadoSelect, 2, 1);
                select("Marca", $marcaSelect, 2, 1);
                select("Ubicacion", $bodegaSelect, 2, 1);
                echo "<input type=submit></form>";

                /* 
                mientras que el while se encarga de pasar por cada campo
                comprobando en donde le toca ir a cada campo y agregandolo en una tabla
                */
                echo "<table>";
                while ($row = $productosSelect->fetchArray(SQLITE3_ASSOC)) {
                    foreach($row as $rows){
                        switch ($a){
                            case 0:
                                echo "<tr>";
                                break;
                            case 3:
                                $estadoSelect = $db->querySingle("SELECT estado FROM Estado WHERE id_estado = '$rows'");
                                echo "<td>$estadoSelect</td>";
                                break;
                            case 4:
                                $marcaSelect = $db->querySingle("SELECT marca FROM Marca WHERE id_marca = '$rows'");
                                echo "<td>$marcaSelect</td>";
                                break;
                            case 5:
                                $bodegaSelect = $db->querySingle("SELECT ubicacion FROM Bodega WHERE id_bodega = '$rows'");
                                echo "<td>$bodegaSelect</td>";
                                break;
                            case $b:
                                echo "</tr>";
                                $a = 0;
                                break;
                        }
                        if($a != 3 && $a != 4 && $a != 5){
                            echo "<td>$rows</td>";
                        }
                        $a++;
                    }
                }
                echo "</table>";
            ?>
            <?php
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
                                $c++;
                            }
                            $temporal++;
                        }
                    }

                    echo "</select>";
                }
            ?>
            
        </section>
    </main>
</body>
</html>