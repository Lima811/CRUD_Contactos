<?php
/*
Archivo:  tabpacientes.php
Objetivo: consulta general sobre pacientes y acceso a operaciones detalladas
Autor:    
*/
include_once("modelo\Usuario.php");
//include_once("modelo\UsuariosContactos.php");
include_once("modelo\Contactos.php");
session_start();
$sErr="";
$sNom="";
$arrPersHosp=null;
$oUsu = new Usuario();
$oPersHosp = new Contacto();
//$idVisualizador=$SESSION["usu"]->getPersHosp()->getIdPersonal();

	/*Verificar que exista sesión*/
	if (isset($_SESSION["usu"]) && !empty($_SESSION["usu"])){
		$oUsu = $_SESSION["usu"];
		$sNom = $oUsu->getPersHosp()->getNombre();//
		try{
			//$arrPersHosp = $oPersHosp->Buscar();
                                    // $idVisualizador=$oUsu->getPersHosp()->getIdPersonal();
                                     $arrPersHosp=$oPersHosp->buscarTodos();
		}catch(Exception $e){
			//Enviar el error específico a la bitácora de php (dentro de php\logs\php_error_log
			error_log($e->getFile()." ".$e->getLine()." ".$e->getMessage(),0);
			$sErr = "Error en base de datos, comunicarse con el administrador".$e->getMessage();
		}
	}
	else
		$sErr = "Falta establecer el login";
	
	if ($sErr == ""){
		include_once("cabecera.html");
		include_once("menu.php");
		include_once("asidedos.html");
	}
	else{
		header("Location: error.php?sError=".$sErr);
		exit();
	}
?>
		<section>
			<h3>Contactos</h3>
			<form name="formTablaGral" method="post" action="abcContactos2.php">
				<input type="hidden" name="txtClave">
				<input type="hidden" name="txtOpe">
                                <table border="1" class="tablas">
                                    <tr>
                                        <td>Clave</td>
                                        <td>Nombre contacto</td>
                                        <td>direccion</td>
                                        <td>telefono</td>
                                        <td>email</td>
                                        <td>id_visualizador</td>
                                        <td>Modificar</td>
                                        <td>Eliminar </td>
                                        <td>Mandar whatsapp</td>
                                    </tr>
                                    <?php 
                                        if($arrPersHosp!=null){
                                            foreach ($arrPersHosp AS $oPersHosp){
                                                ?>
                                    <tr>
                                        <td class="llave"><?php echo $oPersHosp->getIdContacto();?></td>
                                        <td><?php echo $oPersHosp->getNombreCompleto();?></td>
                                        <td><?php echo $oPersHosp->getDireccion();?></td>
                                        <td><?php echo $oPersHosp->getTelefono();?></td>
                                        <td><?php echo $oPersHosp->getEmail();?></td>
                                        <td><?php echo $oPersHosp->getIdPersonal();?></td>
                                        <td>
                                            <input type="submit" name="Submit" value="Modificar" onClick="txtClave.value=<?php echo $oPersHosp->getIdContacto(); ?>; txtOpe.value='m'"></td>
                                        <td><input type="submit" name="Submit" value="Borrar" onClick="txtClave.value=<?php echo $oPersHosp->getIdContacto(); ?>; txtOpe.value='b'"></td>
                                        <td>  <a href="https://wa.me/<?php echo $oPersHosp->getTelefono(); ?>" target="_blank">
                                        <button type='button'>Enviar WhatsApp</button>
                                                </a></td>
                                    </tr>
                                            <?php }
                                        }else{
                                            
                                            ?>
                                    <tr>
                                        <td colspan="2">No hay datos</td>
                                    </tr>
                                        <?php 
                                        }
                                        ?>
                                    
                                </table>
                                 <input type="submit" name="Submit" value="Crear Nuevo" onClick="txtClave.value='-1';txtOpe.value='a'">
			</form>
                       
		</section>
<?php
include_once("pie.html");
?>

