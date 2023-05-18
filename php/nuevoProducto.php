<?php
    include('../template/class.php');
    $db = new BaseDatos();

    session_start();
    $emailSession = $_SESSION['email'] ?? null;
    $passSession = $_SESSION['pass'] ?? null;

    $textNombre = $_POST['nombre'] ?? null;
    $textDescripcion = $_POST['descripcion'] ?? null;
    $selectEstado = $_POST['selectEstado'] ?? null;
    $selectMarca = $_POST['selectMarca'] ?? null;
    $selectUbicacion = $_POST['selectUbicacion'] ?? null;

    $revisar = $db->querySingle("SELECT pass FROM Usuario WHERE email = '$emailSession'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar producto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div id=btn_admin><a href=mainInventario.php id=a_admin><button>Volver</button></a></div>
        <h1>Agregar producto</h1>
    </header>
    <main>
        <section>
            <?php
                if(!password_verify($passSession, $revisar)){
                    echo "<h4>Session termino, vuelve a ingresar</h4>";
                }
                else{
                    $estadoSelect = $db->query("SELECT * FROM Estado");
                    $marcaSelect = $db->query("SELECT * FROM Marca");
                    $bodegaSelect = $db->query("SELECT * FROM Bodega");
                    //creamos un form
                    echo "<form method=post action=nuevoProducto.php>";
                    //llamamos a funciones que crean los select
                    echo "<h3>Nombre:</h3><input type=text placeholder=Nombre name=nombre>";
                    echo "<h3>Descripción:</h3><input type=text placeholder=Descripción name=descripcion>";
                    select("Estado", $estadoSelect, 2, 1);
                    select("Marca", $marcaSelect, 2, 1);
                    select("Ubicación", $bodegaSelect, 2, 1);
                    echo "<input type=submit value='Agregar'></form>";

                    if($selectUbicacion != '0' && $marcaSelect != '0' && $selectEstado != '0' && $textNombre != '' && $textDescripcion != ''){
                        $db->exec("INSERT INTO Productos (nombre, descripcion, id_estado, id_marca, id_bodega) VALUES ('$textNombre', '$textDescripcion', '$selectEstado', '$selectMarca', '$selectUbicacion')");
                    }
                    else if($selectUbicacion == '0' || $marcaSelect == '0' || $selectEstado == '0' || $textNombre == '' || $textDescripcion == ''){
                        echo "Faltan valores en los campos";
                    }
                }
            ?>
        </section>
    </main>
</body>
</html>