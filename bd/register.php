<?php
    class BaseDatos extends SQLite3{
        function __construct(){
            $this->open("..\sqlite\usuario.db");
        }
    }

    $email = $_POST['correoUsuario'];
    $name = $_POST['nombreUsuario'];
    $pass = $_POST['passUsuario'];
    $code = $_POST['codeUsuario'];

    $db = new BaseDatos();
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
    <main>
        <section>
            <?php     
                if ($db) {
                    echo "<p>Conexion exitosa con la base de datos</p>";
                } else{
                    echo "<p>Error al intentar abrir la base de datos</p>";
                }

                $db->exec("insert into Usuario('email', 'nombre', 'pass') VALUES ('$email','$name','$pass')");
            ?>
            <a href="../index.html">
                <button>Ingresar</button>
            </a>
        </section>
    </main>
</body>
</html>