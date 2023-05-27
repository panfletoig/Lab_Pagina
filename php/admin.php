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
    <link rel="stylesheet" href="../css/styleAdmin.css">
</head>
<body>
    <header>
        <div id=btn_admin><a href=mainInventario.php id=a_admin><button class ="up">Volver</button></a></div>
        <h1>Panel de administrador</h1>
    </header>
    <main>
        <img class="wave" src="../img/wave.png" alt="wave">
        <section>
            <?php
                if(password_verify($passSession, $revisar) && $nivel == 1){
                    $result = $db->query("SELECT * FROM Usuario");

                    echo "<h1>Trabajadores</h1>";
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
                    echo "<form method=post><input type=submit class='up' name=generar value='Generar codigo para trabajador'></form>";

                    echo "<br><h3>Generar codigo para administrador</h3>";
                    echo "<form method=post><input type=submit class='up' name=generarA value='Generar codigo para administrador'></form>";

                    
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
                    echo "</select><input type=submit class='up' name=eliminar value='Eliminar'></form>";
                    

                    if(isset($_REQUEST['eliminar'])){
                        $db->exec("DELETE FROM Usuario WHERE id_usuario = $selectId");
                        echo '<meta http-equiv="refresh" content="0; url=admin.php" />';
                    }

                    echo "<br><br><h1>Controles de inventario</h1>";
                    echo "<br><h3>Eliminar inventario</h3>";
                    echo "<form method=post><input type=submit class='up' name=delete value='Eliminar Inventario'></form>";

                    if(isset($_REQUEST['delete'])){
                        $db->exec("DELETE FROM Productos");
                        echo "<p>Inventario eliminado</p>";
                    }

                    echo "<br><h3>Realizar copia de seguridad</h3>";
                    echo "<form method=post><input type=text name=nameBackup placeholder='Nombre' required autocomplete=off><input type=submit class='up' name=copiaSeguridad value='Copia de seguridad'></form>";
                    if(isset($_REQUEST['copiaSeguridad'])){
                        $nameBackup = 'backups/' . $_POST['nameBackup']. ".db";
                        $backup = new SQLite3($nameBackup);
                        $db->backup($backup);
                        
                        echo "<p>Copia de seguridad realizada</p>";
                    }
                    
                    echo "<br><h3>Cargar copia de seguridad</h3>";
                    echo "<form method=post>
                    <select name=bknombre required>";
                        obtener_estructura_directorios("backups");
                    echo "</select>
                    <input type=submit class='up' name=backup value='Cargar backup'>
                    </form>";
                    if(isset($_REQUEST['backup'])){
                        $nameBackup = 'backups/' . $_POST['bknombre'];
                        echo "<p>Copia de seguridad cargada: $nameBackup</p>";
                        copy($nameBackup,"../php/usuario.db");
                    }

                    echo "<br><h3>Descargar copia de seguridad</h3>";
                    echo "<form method=post>
                    <select name=downloadSelect required>";
                        obtener_estructura_directorios("backups");
                    echo "</select>
                    <input type=submit class='up' name=download value='Descargar'>
                    </form>";

                    if(isset($_REQUEST['download'])){
                        $download = 'backups/' . $_POST['downloadSelect'];
                        echo "<p>Copia de seguridad lista para descargar: <a href='$download'>Descargar</a></p>";
                    }

                    echo "<br><h3>Subir copia de seguridad</h3>";
                    echo "<form method=post enctype='multipart/form-data'>
                    <input type=file id='nothing' name=subir_archivo accept='.db'>
                    <input type=submit class='up' name=upload value='Subir'>
                    </form>";

                    if(isset($_REQUEST['upload'])){
                        $directorio = "backups/";
                        $subir_archivo = $directorio.basename($_FILES['subir_archivo']['name']);
                        move_uploaded_file($_FILES['subir_archivo']['tmp_name'], $subir_archivo);
                        echo "<p>Se ha subido el archivo</p>";
                    }
                }
                else{
                    echo "<h4>No deberias estar aqui :/</h4>";
                }
            ?>
        </section>

    </main>
</body>
</html>

<?php
    function obtener_estructura_directorios($ruta){
        // Se comprueba que realmente sea la ruta de un directorio
        if (is_dir($ruta)){
            // Abre un gestor de directorios para la ruta indicada
            $gestor = opendir($ruta);

            // Recorre todos los elementos del directorio
            while (($archivo = readdir($gestor)) !== false)  {
                // Se muestran todos los archivos y carpetas excepto "." y ".."
                if ($archivo != "." && $archivo != "..") {
                    echo "<option value=$archivo>" . $archivo . "</option>";
                }
            }
            
            // Cierra el gestor de directorios
            closedir($gestor);
        } else {
            echo "No es una ruta de directorio valida<br/>";
        }
    }
?>