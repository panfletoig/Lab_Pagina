<?php
    class BaseDatos extends SQLite3{
        function __construct(){
            $this->open("..\sqlite\usuario.db");
        }
    }

    $email = $_POST['username'];
    $pass = $_POST['password'];

    $db = new BaseDatos();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main>
        <section>
            <?php
                if ($db) {
                    echo "<p>Conexion exitosa con la base de datos</p>";
                } else{
                    echo "<p>Error al intentar abrir la base de datos</p>";
                }
            
                $result = $db->querySingle("SELECT pass FROM Usuario WHERE email = '$email'");

                if($result == $pass && $result != ""){
                    echo "<p>Acceso Correcto</p>";
                    echo "<a href=../html/mainInventario.php><button>Inventario</button></a>";
                    echo "<a href=../index.html><button>volver</button></a>";
                    //echo '<meta http-equiv="refresh" content="0; url=../html/mainInventario.php" />';
                }else{
                    echo "<p>Acceso Denegado</p>";
                }
            ?>
        </section>
    </main>
</body>
</html>