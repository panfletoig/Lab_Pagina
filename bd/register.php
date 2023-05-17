<?php
    //referencia a un archivo que contiene la clase base de datos
    include('../template/class.php');

    /*
    $email: guarda el email para enviarlo a la base de datos
    $name: guarda el nombre para enviarlo a la base de datos
    $pass: guarda la contraseña para enviarla a la base de datos
    $code: Codigo de seguridad otrogado por la empresa para un registro correcto
    $db: Instancia la base de datos
    */
    $email = $_POST['correoUsuario'];
    $name = $_POST['nombreUsuario'];
    $pass = $_POST['passUsuario'];
    $code = $_POST['codeUsuario'];
    $db = new BaseDatos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro correcto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main>
        <section>
            <?php    
                //Detecta si se conecto a la base de datos 
                if ($db) {
                    echo "<p>Conexion exitosa con la base de datos</p>";
                } else{
                    echo "<p>Error al intentar abrir la base de datos</p>";
                }
                //Envia los datos a la base de datos
                //exec = ejecuta codigo sql
                $db->exec("INSERT into Usuario('email', 'nombre', 'pass') VALUES ('$email','$name','$pass')");
            ?>
            <a href="../index.html">
                <button>Ingresar</button>
            </a>
        </section>
    </main>
</body>
</html>