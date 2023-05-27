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
    $ordenCompra = $_POST['OC'] ?? null;

    $nombreMarca = $_POST['nombreMarca'] ?? null;
    $nombreUbicacion = $_POST['nombreUbicación'] ?? null;
    $nombreModelo = $_POST['nombreModelo'] ?? null;

    $revisar = $db->querySingle("SELECT pass FROM Usuario WHERE email = '$emailSession'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar producto</title>
    <link rel="stylesheet" href="../css/styleAgregar.css">
</head>
<body>
    <header>
        <div id=btn_admin><a href=mainInventario.php id=a_admin><button>Volver</button></a></div>
        <h1>Agregar Producto</h1>
    </header>
    <main>
        <img class="wave" src="../img/wave.png" alt="wave">
        <section>
            <h2>Producto:</h2>
            <?php
                if(!password_verify($passSession, $revisar)){
                    echo "<p>Session termino, vuelve a ingresar</p>";
                    echo "<a href=../index.html><button>Iniciar Session</button></a>";
                }
                else{
                    $estadoSelect = $db->query("SELECT * FROM Estado");
                    $marcaSelect = $db->query("SELECT * FROM Marca");
                    $bodegaSelect = $db->query("SELECT * FROM Bodega");
                    $modeloSelect = $db->query("SELECT * FROM Modelo");
                    
                    //creamos un form
                    echo "
                    <form method=post action=nuevoProducto.php>
                    <h3>Serial:</h3>
                    <input type=text placeholder=Serial name=serial required autocomplete=off>
                    <h3>Nombre:</h3>
                    <input type=text placeholder=Nombre name=nombre required autocomplete=off>
                    <h3>Descripción:</h3>
                    <input type=text placeholder=Descripción name=descripcion required autocomplete=off>
                    <h3>Estado:</h3>
                    <select name=selectEstado id=select>
                    <option value=1>Entrada de equipo nuevo</option>
                    <option value=3>Entrada de equipo usado</option>
                    </select>
                    <h3>Orden de compra:</h3>
                    <input type=text placeholder='Orden de compra' name=OC autocomplete=off>";
                    
                    select("Modelo", $modeloSelect, 2, 1, 0, true);
                    select("Marca", $marcaSelect, 2, 1, 0, true);
                    select("Ubicación", $bodegaSelect, 2, 1, 0, true);
                    echo "<input type=submit value='Agregar' name='agregarProducto'></form>";

                    if(isset($_REQUEST['agregarProducto'])){
                        $comprobar =  $db->querySingle("SELECT EXISTS(SELECT * from Productos WHERE serializado = '$selectSerial')");
                        $bool = (($selectUbicacion != "0" && $marcaSelect != "0" && $selectEstado != "0" && $textNombre != '' && $textDescripcion != '' && $selectModelo != "0" && $selectSerial != '') && $comprobar == 0) == true ? true : false;
                        

                        if($bool){
                            $selectSerial = intval($selectSerial);
                            $selectEstado = intval($selectEstado);
                            $selectMarca = intval($selectMarca);
                            $selectUbicacion = intval($selectUbicacion);
                            if($selectEstado == 1 && $ordenCompra != ''){
                                $db->exec("INSERT INTO Productos (nombre, descripcion, modelo, serializado, id_estado, id_marca, id_bodega, OC) VALUES ('$textNombre', '$textDescripcion', '$selectModelo', '$selectSerial', '$selectEstado', '$selectMarca', '$selectUbicacion', '$ordenCompra')");
                                echo "<p>Producto: $textNombre agregado</p>";
                            }
                            else if ($selectEstado == 3){
                                $db->exec("INSERT INTO Productos (nombre, descripcion, modelo, serializado, id_estado, id_marca, id_bodega, OC) VALUES ('$textNombre', '$textDescripcion', '$selectModelo', '$selectSerial', '$selectEstado', '$selectMarca', '$selectUbicacion', '$ordenCompra')");
                                echo "<p>Producto: $textNombre agregado</p>";
                            }
                            else{
                                echo "<p>Se requiere una orden de compra</p>";
                            }
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
                    }
                }
            ?>
        </section>
        <section>
            <?php
                if(password_verify($passSession, $revisar)){
                    agregar("Marca");
                    $ultimaMarca = $db->querySingle("SELECT marca FROM Marca WHERE id_marca = (SELECT MAX(id_marca)FROM Marca)");
                    $existe = $db->querySingle("SELECT EXISTS(SELECT * from Marca WHERE marca = '$nombreMarca')");
                    if($nombreMarca != '' && isset($_REQUEST['agregarMarca']) && $existe != 1){
                        $db->exec("INSERT INTO Marca (marca) VALUES ('$nombreMarca')");
                        echo '<meta http-equiv="refresh" content="0; url=nuevoProducto.php" />';
                    }
                    else if($existe == 1)
                        echo "<p>Esta marca ya existe</p>";
                    echo "<p>Ultima marca añadida: $ultimaMarca</p>";
                }
            ?>
        </section>
        <section>
            <?php
                if(password_verify($passSession, $revisar)){
                    agregar("Ubicación");

                    $ultimaUbicacion = $db->querySingle("SELECT ubicacion FROM Bodega WHERE id_bodega = (SELECT MAX(id_bodega)FROM Bodega)");
                    $existe = $db->querySingle("SELECT EXISTS(SELECT * from Bodega WHERE ubicacion = '$nombreUbicacion')");
                    
                    if($nombreUbicacion != '' && isset($_REQUEST['agregarUbicacion']) && $existe != 1){
                        $db->exec("INSERT INTO Bodega (ubicacion) VALUES ('$nombreUbicacion')");
                        echo '<meta http-equiv="refresh" content="0; url=nuevoProducto.php" />';
                    }
                    else if($existe == 1)
                        echo "<p>Esta ubicacion ya existe</p>";
                    echo "<p>Ultima marca añadida: $ultimaUbicacion</p>";
                }
            ?>
        </section>
        <section>
            <?php
                if(password_verify($passSession, $revisar)){
                    agregar("Modelo");

                    $ultimaModelo = $db->querySingle("SELECT modelo FROM Modelo WHERE id_modelo = (SELECT MAX(id_modelo)FROM Modelo)");
                    $existe = $db->querySingle("SELECT EXISTS(SELECT * from Modelo WHERE modelo = '$nombreModelo')");
                    
                    if($nombreModelo != '' && isset($_REQUEST['agregarModelo']) && $existe != 1){
                        $db->exec("INSERT INTO Modelo (modelo) VALUES ('$nombreModelo')");
                        echo '<meta http-equiv="refresh" content="0; url=nuevoProducto.php" />';
                    }
                    else if($existe == 1)
                        echo "<p>Este Modelo ya existe</p>";
                    echo "<p>Ultimo modelo añadido: $ultimaModelo</p>";
                }
            ?>
        </section>
        
    </main>
</body>
</html>
<?php
    function agregar($nombreAgregar){
        echo "<br><br><h2>Agregar $nombreAgregar:</h2><br>";
        echo "<form method=post action=nuevoProducto.php>
        <h3>Nombre:</h3>
        <input type=text placeholder=$nombreAgregar name=nombre$nombreAgregar required autocomplete=off>
        <input type=submit value=Agregar name='agregar$nombreAgregar'>
        </form>
        ";
    }
?>