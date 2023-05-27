<?php
include("../template/class.php");

$db = new BaseDatos();

session_start();
$email = $_SESSION['email'] ?? null;
$pass = $_SESSION['pass'] ?? null;
$idEliminar = $_POST['hidden'] ?? null;

$confirmacion = $_POST['confirmacion'] ?? null;
$revisar = $db->querySingle("SELECT pass FROM Usuario Where email = '$email'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/styleAdmin.css">
</head>
<body>
    <header>
        <div id="btn_admin"><a href="mainInventario.php"><button>Volver</button></a></div>
        <h1>Eliminar producto</h1>
    </header>
    <main>
        <img class="wave" src="../img/wave.png" alt="wave">
        <section>
            <?php
                if(!password_verify($pass, $revisar))
                    echo "<p>Session Caducada</p><a href='../login.php'><button>Iniciar sesión</button></a>";
                else if($confirmacion == ''){
                    $Salida = $db->query("SELECT * FROM Productos WHERE id_estado = '2' OR id_estado = '4'");
                    echo "<form method=post action=delete.php>
                    <label>Serial: $idEliminar</label>";
                    
                    echo "<input type=hidden name=hidden value=$idEliminar><input type=submit value='Eliminar Producto' name=confirmacion></form>";
                    echo $confirmacion;

                }
                else if($confirmacion == "Eliminar Producto"){
                    $idEliminar = intval($idEliminar);
                    $db->exec("DELETE FROM Productos WHERE serializado = '$idEliminar'");
                    echo "Producto: $idEliminar ha sido eliminado";
                }
            ?>

        </section>
    </main>
</body>
</html>