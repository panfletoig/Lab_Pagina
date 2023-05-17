<?php
    //referencia a un archivo que contiene la clase base de datos
    include('../template/class.php');

    /*
    $option: Toma la opcion seleccionada y si no hay nada la vuelve nula
    $db: instancia la base de datos
    */

    $option = $_POST['selectOption'] ?? null;
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
        <section class="options">
            <form method="post" action="mainInventario.php">
                <select name="selectOption" id="select">
                    <option value="none">none</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <input type="submit">
            </form>

        </section>
        <section>
            <table>
                <?php
                    //detecta si se conecto a la base de datos
                    if ($db) {
                        echo "<p>Conectado a inventario</p>";
                    } else{
                        echo "<p>Error al intentar abrir inventario</p>";
                    }
                    
                    //si es none toma todos los datos y si no lo es toma solo los de la opcion
                    if($option != "none")
                        $result = $db->query("SELECT * FROM Productos WHERE id_producto = '$option'");
                    else
                        $result = $db->query("SELECT * FROM Productos");

                    /* 
                    $a: contador para organizar en filas y columnas
                    $b: numero de columnas

                    mientras que el while se encarga de pasar por cada campo
                    comprobando en donde le toca ir a cada campo
                    */
                    $a = 0;
                    $b = 3;
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        foreach($row as $rows){
                            if($a == 0){
                                echo "<tr>";
                            }
                            if($a == $b){
                                echo "</tr>";
                                $a = 0;
                            }
                            echo "<td>$rows</td>";
                            $a++;
                        }
                    }
                ?>
            </table>
        </section>
    </main>
</body>
</html>