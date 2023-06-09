<?php
    //referencia a un archivo que contiene la clase base de datos
    include('../template/class.php');
    
    /*
    $email:     guarda el email para enviarlo a la base de datos
    $name:      guarda el nombre para enviarlo a la base de datos
    $pass:      guarda la contraseña para enviarla a la base de datos como un hash
    $rpass:     sirve para comparar que la contraseña sea igual
    $code:      Codigo de seguridad otrogado por la empresa para un registro correcto
    $db:        Instancia la base de datos
    */
    $email = $_POST['correoUsuario'] ?? null;
    $name = $_POST['nombreUsuario'] ?? null;
    $pass = $_POST['passUsuario'] ?? null;
    $rpass = $_POST['repetirUsuario'] ?? null;
    $code = $_POST['codeUsuario'] ?? null;
    $db = new BaseDatos();
    
    //si no esta vacio el email lo pasa a lowecase
    if($email != "")
        $email = strtolower($_POST['correoUsuario']);
    //si no esta vacio el pass lo vuelve un hash
    if($pass != "")
        $pass = password_hash($_POST['passUsuario'], PASSWORD_BCRYPT);
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Registro</h1>
    </header>
    <main>
        <section>

            <img class="wave" src="../img/wave.png" alt="wave">
            
            <div class="container">
                <div class="img">
                    <img src="../img/programming.svg" alt="programming">
                </div>

                <div class="login-content">
                    <form action="register.php" id="form" method="post">
                    <img src="../img/avatar.svg" alt="avatar">

                    <div class="input-div user">
                        <div class="i">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="div">
                            <input type="text" name="correoUsuario" placeholder="Usuario" required>
                        </div>
                    </div>
                    
                    <div class="input-div user">
                        <div class="i">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="div">
                            <input type="text" name="nombreUsuario" placeholder="Nombre" maxlength="50" required>
                        </div>
                    </div> 

                    <div class="input-div pass">
        
                        <div class="i">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                        <div class="div">
                            <input type="password" name="passUsuario" placeholder="Contraseña" required>
                        </div>
                        
                    </div>

                    <div class="input-div pass">
        
                        <div class="i">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                        <div class="div">
                            <input type="password" name="repetirUsuario" placeholder="Contraseña" required>
                        </div>
                        
                    </div>

                    <div class="input-div pass">
        
                        <div class="i">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                        <div class="div">
                            <input type="text" name="codeUsuario" placeholder="Código" required autocomplete="off">
                        </div>
                        
                    </div>

                    

                    
                    <a href="../login.html"> Iniciar sesión</a>
                    <input class="btn" id="submit" type="submit" value="Registrarse">
                    </form>
                </div>
            </div>
            
        </section>
        <section>
            <?php    
                //$comprobar: Comprueba que el codigo existe en la base de datos
                $comprobar = $db->querySingle("SELECT EXISTS(SELECT * from Usuario WHERE code = '$code')"); 
                
                /*
                si existe permite realizar comprobaciones extra para evitar que se registre con un codigo existente, 
                email repetidos, email validos, que la contraseña sea igual al repetirla, y que el nombre no este vacio
                si se cumplen todas las condiciones se hace un update donde se agrega el email, nombre y el hash del la contraseña
                */
                if($comprobar == 1){
                    $lineaCodigo = $db->querySingle("SELECT email from Usuario WHERE code = '$code'");
                    if($lineaCodigo != ''){
                        echo "<p>Este codigo ya esta asociado a una cuenta</p>";
                    }
                    else{  
                        if(!validarEmail($email) && $email != ''){
                            echo "<p>Email no valido</p>";
                        }
                        else{
                            $norepetir = $db->querySingle("SELECT EXISTS(SELECT * from Usuario WHERE email = '$email')"); 
                            if($norepetir != 0)
                                echo "<p>Email ya registrado</p>";
                            else{
                                
                                if(!password_verify($rpass, $pass)){
                                    echo "<p>Las contraseñas no coinciden</p>";
                                }
                                else{
                                    if($pass == "" && $rpass == "")
                                    echo "<p>Campos vacios</p>";
                                    else{
                                        if(!validarNombre($name)){
                                            echo "<p>El nombre: $name contiene caracteres no admitidos</p>";
                                        }
                                        else{
                                            $db->exec("UPDATE Usuario SET email = '$email', nombre = '$name', pass = '$pass' WHERE code = '$code';");
                                            echo '<meta http-equiv="refresh" content="0; url=../login.html" />';
                                        }
                                    }
                                }
                            }
                        }
                        
                    }
                }
                else if($code != "")
                    echo "<p>El codigo ingresado es incorrecto o inexistente</p>";
            ?>
        </section>

        <!--SCRIPT JS PARA ICONOS-->
        <script src="https://kit.fontawesome.com/c838166335.js" crossorigin="anonymous"></script>
    </main>
</body>
</html>

<?php
    //funcion que valida que el correo tenga @ y un punto y lo da como valido
    function validarEmail($str){
        return (false !== strpos($str, "@") && false !== strpos($str, "."));
    }
    //funcion que comprueba que los caracteres usados esten en los caracteres permitidos
    function validarNombre($str){
        $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZáÁéÉíÍóÓúÚñÑ ";
        for ($i=0; $i<strlen($str); $i++){
            if (strpos($permitidos, substr($str,$i,1))===false){
               return false;
            }
        }
        return true;
    }
?>