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
    $selectUbicacion = $_POST['selectUbicación'] ?? null;
    $selectModelo = $_POST['selectModelo'] ?? null;
    $selectSerial = $_POST['serial'] ?? null;

    $nombreMarca = $_POST['nombreMarca'] ?? null;
    $nombreUbicacion = $_POST['nombreUbicacion'] ?? null;

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
        <h1>Agregar</h1>
    </header>
    <main>
        <section>
            <h2>Producto:</h2>
            <?php
                if(!password_verify($passSession, $revisar)){
                    echo "<p>Session termino, vuelve a ingresar</p>";
                    echo "<a href=../index.html><button>Iniciar Session</button></a>";
                }
                else{
                    $productoSelect = $db->query("SELECT * FROM Productos");
                    $estadoSelect = $db->query("SELECT * FROM Estado");
                    $marcaSelect = $db->query("SELECT * FROM Marca");
                    $bodegaSelect = $db->query("SELECT * FROM Bodega");
                    //creamos un form
                    echo "
                    <form method=post action=nuevoProducto.php>
                    <h3>Nombre:</h3>
                    <input type=text placeholder=Nombre name=nombre required autocomplete=off>
                    <h3>Descripción:</h3>
                    <input type=text placeholder=Descripción name=descripcion required autocomplete=off>";
                    select("Estado", $estadoSelect, 2, 1, 0);
                    select("Modelo", $productoSelect, 8, 3, 1);
                    echo "<h3>Serial:</h3><input type=text placeholder=Serial name=serial required autocomplete=off>";
                    select("Marca", $marcaSelect, 2, 1, 0);
                    select("Ubicación", $bodegaSelect, 2, 1, 0);
                    echo "<input type=submit value='Agregar' name='agregarProducto'></form>";

                    if(isset($_REQUEST['agregarProducto'])){
                        $comprobar =  $db->querySingle("SELECT EXISTS(SELECT * from Productos WHERE serializado = '$selectSerial')");
                        $bool = (($selectUbicacion != "0" && $marcaSelect != "0" && $selectEstado != "0" && $textNombre != '' && $textDescripcion != '' && $selectModelo != "0" && $selectSerial != '') && $comprobar == 0) == true ? true : false;


                        if($bool){
                            $db->exec("INSERT INTO Productos (nombre, descripcion, modelo, serializado, id_estado, id_marca, id_bodega) VALUES ('$textNombre', '$textDescripcion', '$selectModelo', '$selectSerial', '$selectEstado', '$selectMarca', '$selectUbicacion')");
                            echo "<p>Producto: $textNombre agregado</p>";
                        }
                        else if($selectUbicacion == '0'){
                            echo "<p>Campo ubicación vacio</p>";
                        }
                        else if($comprobar == 1){
                            echo "<p>El serial ya existe</p>";
                        }
                        else if($marcaSelect == '0'){
                            echo "<p>Campo marca vacio</p>";
                        }
                        else if($selectEstado == '0'){
                            echo "<p>Campo estado vacio</p>";
                        }
                        else if(($nombreExiste == $textNombre && $descripcionExiste == $textDescripcion && $id_estadoExiste == $estadoSelect && $id_marcaExiste == $marcaSelect && $id_bodegaExiste == $bodegaSelect))
                        echo "<p>Este producto ya existe</p>";
                    }
                }
            ?>
        </section>
        <section>
            <h2>Marca:</h2>
            <?php
                if(password_verify($passSession, $revisar)){
                    echo "
                    <form method=post action=nuevoProducto.php>
                    <h3>Nombre:</h3>
                    <input type=text placeholder=Nombre Marca name=nombreMarca required autocomplete=off>
                    <input type=submit value=Agregar name='agregarMarca' autocomplete=off>
                    </form>";
                    $existe = $db->querySingle("SELECT EXISTS(SELECT * from Marca WHERE marca = '$nombreMarca')");
                    if($nombreMarca != '' && isset($_REQUEST['agregarMarca']) && $existe != 1){
                        $db->exec("INSERT INTO Marca (marca) VALUES ('$nombreMarca')");
                        echo "<p>Marca: $nombreMarca fue añadido</p>";
                    }
                    else if($existe == 1)
                        echo "<p>Esta marca ya existe</p>";
                }
            ?>
        </section>
        <section>
            <h2>Ubicación:</h2>
            <?php
                if(password_verify($passSession, $revisar)){
                    echo "
                    <form method=post action=nuevoProducto.php>
                    <h3>Nombre:</h3>
                    <input type=text placeholder=Ubicación name=nombreUbicacion required autocomplete=off>
                    <input type=submit value=Agregar name='agregarUbicacion' autocomplete=off>
                    </form>";

                    $existe = $db->querySingle("SELECT EXISTS(SELECT * from Bodega WHERE ubicacion = '$nombreUbicacion')");
                    if($nombreUbicacion != '' && isset($_REQUEST['agregarUbicacion']) && $existe != 1){
                        $db->exec("INSERT INTO Bodega (ubicacion) VALUES ('$nombreUbicacion')");
                        echo "<p>Ubicacion: $nombreUbicacion fue añadido</p>";
                    }
                    else if($existe == 1)
                        echo "<p>Esta ubicacion ya existe</p>";
                }
            ?>
        </section>
    </main>
</body>
</html>