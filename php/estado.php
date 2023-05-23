<?php
include('../template/class.php');
$db = new BaseDatos();

session_start();
$emailSession = $_SESSION['email'] ?? null;
$passSession = $_SESSION['pass'] ?? null;
$selectSerial = $_POST['selectSerial'] ?? null;
$date = $_POST['date'] ?? null;

$revisar = $db->querySingle("SELECT pass FROM Usuario WHERE email = '$emailSession'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Estado</title>
    <link rel="stylesheet" href="../css/styleT.css">
</head>
<body>
    <header>
        <?php
            if(password_verify($passSession, $revisar)){
                echo "<div id=btn_admin><a href=mainInventario.php id=a_admin><button>Volver</button></a></div>";
            }
        ?>
        <h1>Actualizar estado de producto</h1>
    </header>
    <main>
        <section>
            <?php
                if(!password_verify($passSession, $revisar)){
                    echo "<p>Session termino, vuelve a ingresar</p>";
                    echo "<a href=../index.html><button>Iniciar Session</button></a>";
                }
                else{
                    $result = $db->query("SELECT * from Productos WHERE id_estado = '1' OR id_estado = '3'");
                    echo "<form method=post action=estado.php>";
                    select("Serial", $result, 8, 0, 0, false);
                    echo "<br><h3>Fecha de salida: </h3><input type='date' name=date id=date required>
                    <input type=submit value='Agregar' name='agregarProducto'></form>";
                    if($date != '' && $selectSerial != 0){
                        $comprobacion = $db->querySingle("SELECT id_estado from Productos WHERE serializado = '$selectSerial'");
                        if($comprobacion == 1 || $comprobacion == 3){
                            $id_estado = $db->querySingle("SELECT id_estado FROM Productos WHERE serializado = '$selectSerial'");
                            $id_estado += 1;
                            $db->exec("UPDATE Productos SET id_estado = '$id_estado', FechaSalida = '$date' WHERE serializado = '$selectSerial';");
                            echo "<p>Producto: $selectSerial actualizado</p>";
                        }
                        else if($comprobacion != 1 || $comprobacion != 3){
                            echo "<p>Este producto ya fue actualizado</p>";
                        }
                    }
                }
            ?>
        </section>
        
    </main>
</body>
</html>