<?php
    //referencia a un archivo que contiene la clase base de datos
    include('../template/class.php');

    /*
    $email:     toma el valor del campo con nombre 'username' del form de index.html
    $pass:      toma el valor del campo con nombre 'password' del form de index.html
    $db:        crea una instancia de la base de datos a partir del archivo incluido anteriormente
    */
    $email = $_POST['username'];
    $pass = $_POST['password'];
    $db = new BaseDatos();

    if($email != "")
        $email = strtolower($_POST['username']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificacion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Acceso</h1>
    </header>
    <main>
        <section>
            <?php
                //result: variable usada para el almacenamiento de datos en este caso de un select
                //querySingle: una consulta simple
                $result = $db->querySingle("SELECT pass FROM Usuario WHERE email = '$email'");

                //comprueba si el resultado es igual a la contraseña ingresada y que no sea un campo vacio
                if(password_verify($pass, $result) && $result != ""){
                    echo "<h4>Acceso Correcto</h4>";
                    session_start();
                    $_SESSION['email'] = $email;
                    $_SESSION['pass'] = $pass;
                    //Redirecciona automaticamente a la siguiente pagina
                    echo '<meta http-equiv="refresh" content="0; url=mainInventario.php" />';
                    echo "<a href=mainInventario.php><button>Inventario</button></a>";
                }else{
                    echo "<h4>Acceso Denegado</h4>";
                }
                //crea un boton para regresar a index.html
                echo "<a href=../index.html><button>volver</button></a>";
            ?>
        </section>
    </main>
</body>
</html>