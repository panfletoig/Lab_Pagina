<?php
    class BaseDatos extends SQLite3{
        function __construct(){
            $this->open("..\sqlite\productos.db");
        }
    }

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
                    if ($db) {
                        echo "<p>Conectado a inventario</p>";
                    } else{
                        echo "<p>Error al intentar abrir inventario</p>";
                    }
                    
                    if($option != "none")
                        $result = $db->query("SELECT * FROM Test WHERE id_producto = '$option'");
                    else
                        $result = $db->query("SELECT * FROM Test");

                    $a = 0;
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        foreach($row as $rows){
                            if($a == 0){
                                echo "<tr>";
                            }
                            if($a == 3){
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