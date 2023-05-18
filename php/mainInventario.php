<?php
    //referencia a un archivo que contiene la clase base de datos
    include('../template/class.php');
    /*
    $selectId:          Toma la opcion seleccionada y si no hay nada la vuelve nula
    $selectEstado:      Toma la opcion del estado de el producto
    $selectMarca:       Toma la opcion de la marca del producto
    $selectUbicacion:   Toma la opcion de la ubicacion del producto
    $db:                Instancia la base de datos
    */
    
    $selectId = $_POST['selectId'] ?? null;
    $selectEstado = $_POST['selectEstado'] ?? null;
    $selectMarca = $_POST['selectMarca'] ?? null;
    $selectUbicacion = $_POST['selectUbicacion'] ?? null;
    $db = new BaseDatos();

    session_start();
    $emailSession = $_SESSION['email'] ?? null;
    $passSession = $_SESSION['pass'] ?? null;

    $revisar = $db->querySingle("SELECT pass FROM Usuario WHERE email = '$emailSession'");
    $nivel = $db->querySingle("SELECT nivel FROM Usuario WHERE email = '$emailSession'");
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
        <?php
        if($nivel == 1){
            echo "<div id=btn_admin>
            <a href=admin.php id=a_admin><button>Panel de administrador</button></a>
            <a href=nuevoProducto.php id=a_admin><button>Agregar Producto</button></a>
            </div>";
        }
        ?>
        <h1>Inventario</h1>
    </header>
    <main>
        <section>
            <?php
                
                if(password_verify($passSession, $revisar)){

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
                    select("ID", $result, $b, 0);
                    select("Estado", $estadoSelect, 2, 1);
                    select("Marca", $marcaSelect, 2, 1);
                    select("Ubicación", $bodegaSelect, 2, 1);
                    echo "<input type=submit value='Actualizar tabla'></form>";
                    
                    /* 
                    agregamos los encabezados y luego
                    mientras que el while se encarga de pasar por cada campo,
                    comprobando en donde le toca ir a cada campo y agregandolo en una tabla
                    */
                    echo "<table>";
                    echo "<tr>
                    <td>ID:     </td>
                    <td>Nombre: </td>
                    <td>Descripción: </td>
                    <td>Estado: </td>
                    <td>Ubicación:  </td>
                    </tr>";

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
                }
                else{
                    echo "<p>Lo sentimos :c</p>";
                    echo "<p>seccion cerrada</p>";
                    echo "<p>Vuelve a iniciar seccion</p>";
                    echo "<a href=../index.html><button>Iniciar Session</button></a>";
                }
            ?>         
        </section>
    </main>
</body>
</html>