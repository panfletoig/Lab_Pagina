<?php
include('../template/class.php');
$db = new BaseDatos();

session_start();
$emailSession = $_SESSION['email'] ?? null;
$passSession = $_SESSION['pass'] ?? null;
$selectSerial = $_POST['hidden'] ?? null;
$date = $_POST['date'] ?? null;
$ordenCompra = $_POST['ordenCompra'] ?? null;
$confirmacion = $_POST['confirmacion'] ?? null;

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
                    $selectSerial = intval($selectSerial);
                    $nombreP = $db->querySingle("SELECT nombre from Productos WHERE serializado = '$selectSerial'");
                    $descripcionP = $db->querySingle("SELECT descripcion from Productos WHERE serializado = '$selectSerial'");

                    $date = date("d-m-Y", strtotime($date));
                    if($date != '' && $selectSerial != 0 && $confirmacion == "confirmacion"){
                        $comprobacion = $db->querySingle("SELECT id_estado from Productos WHERE serializado = '$selectSerial'");
                        if($comprobacion == 1 || $comprobacion == 3){
                            $id_estado = $db->querySingle("SELECT id_estado FROM Productos WHERE serializado = '$selectSerial'");
                            $id_estado = 1 + intval($id_estado);
                            $db->exec("UPDATE Productos SET id_estado = '$id_estado', FechaSalida = '$date', OC = '$ordenCompra' WHERE serializado = '$selectSerial';");
                            echo "<p>Producto: $selectSerial actualizado</p>";
                            echo "<a href='mainInventario.php'><button>Volver</button></a>";
                        }
                        else if($comprobacion != 1 || $comprobacion != 3){
                            echo "<p>Este producto ya fue actualizado</p>";
                            echo "<a href='mainInventario.php'><button>Volver</button></a>";
                        }
                    }
                    else{
                        
                        echo "<form method=post action=estado.php>";
                        echo "
                        <label>Serial: $selectSerial</label>
                        <label>Nombre: $nombreP</label>
                        <label>Descripción: $descripcionP</label>
                        ";
                        echo "<h3>Fecha de salida: </h3>
                        <input type='date' name=date id=date required>
                        <h3>Orden de compra<h3>
                        <input type=text name=ordenCompra placeholder='Orden de compra' required autocomplete=off>
                        <input type=hidden name=hidden value=$selectSerial>
                        <input type=hidden name=confirmacion value=confirmacion>
                        <input type=submit value='Agregar' name='agregarProducto'></form>";
                    }
                }
            ?>
        </section>
        
    </main>
</body>
</html>