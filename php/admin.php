<?php
    include('../template/class.php');
    $db = new BaseDatos();

    session_start();
    $emailSession = $_SESSION['email'] ?? null;
    $passSession = $_SESSION['pass'] ?? null;

    $revisar = $db->querySingle("SELECT pass FROM Usuario WHERE email = '$emailSession'");
    $nivel = $db->querySingle("SELECT nivel FROM Usuario WHERE email = '$emailSession'");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div id=btn_admin><a href=mainInventario.php id=a_admin><button>Volver</button></a></div>
        <h1>Panel de administrador</h1>
    </header>
    <main>
        <section>
            <?php
                if($nivel != 1 && !password_verify($passSession, $revisar)){
                    echo "<h4>No deberias estar aqui :/</h4>";
                }
                else{
                    $result = $db->query("SELECT * FROM Usuario");

                    echo "<table>";
                    echo "<tr>
                    <td>ID:     </td>
                    <td>Correo: </td>
                    <td>Nombre: </td>
                    <td>Codigo: </td>
                    <td>Nivel:  </td>
                    </tr>";

                    while($ids = $result->fetchArray(SQLITE3_ASSOC)){
                        $temporal = 0;
                        foreach($ids as $id){
                            switch ($temporal){
                                case 0:
                                    echo "<tr>";
                                    echo "<td>$id</td>";
                                    break;
                                case 3:
                                    break;
                                case 5:
                                    if($id == 1)
                                        echo "<td>Administrador</td>";
                                    else
                                        echo "<td>Trabajador</td>";
                                    echo "</tr>";
                                    break;
                                default:
                                    echo "<td>$id</td>";
                                    break;
                            }
                            $temporal++;
                        }
                    }
                    echo "</table>";

                    echo "<br><h3>Generar codigo para trabajador</h3>";
                    echo "<form method=post><input type=submit name=generar value='Generar codigo para trabajador'></form>";

                    echo "<br><h3>Generar codigo para trabajador</h3>";
                    echo "<form method=post><input type=submit name=generarA value='Generar codigo para administrador'></form>";

                    
                    if(isset($_REQUEST['generar'])){
                        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $random = substr(str_shuffle($permitted_chars), 0, 6);
                        
                        $db->exec("INSERT INTO Usuario (code,nivel,email,nombre,pass) VALUES ('$random','0','','','');");
                        echo '<meta http-equiv="refresh" content="0; url=admin.php" />';
                    }
                    if(isset($_REQUEST['generarA'])){
                        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $random = substr(str_shuffle($permitted_chars), 0, 6);
                        
                        $db->exec("INSERT INTO Usuario (code,nivel,email,nombre,pass) VALUES ('$random','1','','','');");
                        echo '<meta http-equiv="refresh" content="0; url=admin.php" />';
                    }

                    echo "<br><h3>Eliminar trabajador por ID</h3>";
                    echo "<form method=post><select name=selectId id=select>";

                    $selectId = $_POST['selectId'] ?? null;

                    while($users = $result->fetchArray(SQLITE3_ASSOC)){
                        $temporal = 0;
                        foreach($users as $user){
                            if($temporal % 6 == 0){
                                echo "<option value=$user>";
                            }
                            if($temporal % 6 == 0){
                                echo "$user</option>";
                            }
                            $temporal++;
                        }
                    }
                    echo "</select><input type=submit name=eliminar value='Eliminar'></form>";
                    

                    if(isset($_REQUEST['eliminar'])){
                        $db->exec("DELETE FROM Usuario WHERE id_usuario = $selectId");
                        echo '<meta http-equiv="refresh" content="0; url=admin.php" />';
                    }
                }
            ?>
        </section>

    </main>
</body>
</html>