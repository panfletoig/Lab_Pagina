<?php
include("../template/class.php");

$db = new BaseDatos();

session_start();
$email = $_SESSION['email'] ?? null;
$pass = $_SESSION['pass'] ?? null;
$selectDelete = $_POST['selectDelete'] ?? null;

$confirmacion = false;
$revisar = $db->querySingle("SELECT pass FROM Usuario Where email = '$email'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div id="div_btn"><a href="mainInventario.php"><button>Volver</button></a></div>
        <h1>Eliminar producto</h1>
    </header>
    <main>
        <section>
            <?php
                if(!password_verify($pass, $revisar))
                    echo "<p>Session Caducada</p><a href='../login.php'><button>Iniciar sesión</button></a>";
                else if($confirmacion == false){
                    $Salida = $db->query("SELECT * FROM Productos WHERE id_estado = '2' OR id_estado = '4'");
                    echo "<form method=post action=mainInventario.php>";
                    select("Delete", $Salida, 8, 0, 0, false);
                    echo "<input type=submit value='Eliminar Producto'></form>";

                }
                else if($confirmacion == true){
                    $db->exec("DELETE FROM Productos WHERE serializado = '$selectDelete'");
                }
            ?>
        </section>
    </main>
</body>
</html>