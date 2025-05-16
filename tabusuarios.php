<?php
/*
Archivo:  tabpersonal.php
Objetivo: consulta general sobre personal hospitalario y acceso a operaciones detalladas
Autor:    
*/
include_once("modelo\Usuario.php");
include_once("modelo\UsuariosContactos.php");
session_start();
$sErr="";
$sNom="";
$arrPersHosp=null;
$oUsu = new Usuario();
$oPersHosp = new UsuariosContactos();
	/*Verificar que exista sesión*/
	if (isset($_SESSION["usu"]) && !empty($_SESSION["usu"])){
		$oUsu = $_SESSION["usu"];
		$sNom = $oUsu->getPersHosp()->getNombre();
		try{
			$arrPersHosp = $oPersHosp->buscarTodos();
		}catch(Exception $e){
			//Enviar el error específico a la bitácora de php (dentro de php\logs\php_error_log
			error_log($e->getFile()." ".$e->getLine()." ".$e->getMessage(),0);
			$sErr = "Error en base de datos, comunicarse con el administrador";
		}
	}
	else
		$sErr = "Falta establecer el login";
	
	if ($sErr == ""){
		include_once("cabecera.html");
		include_once("menu.php");
		include_once("aside.html");
	}
	else{
		header("Location: error.php?sError=".$sErr);
		exit();
	}
?>
		<section>
			<h3>Usuarios (admin y visualizadores)</h3>
			<form name="formTablaGral" method="post" action="abcUsuariosCont.php">
				<input type="hidden" name="txtClave">
				<input type="hidden" name="txtOpe">
				<table border="1" class="tablas">
					<tr>
						<td>Clave</td>
						<td>Nombre</td>
                                                                                                <td>Numero de tipo</td>
                                                                                                <td>Genero</td>
                                               
						<td>Modificar </td>
                                                <td>Eliminar</td>
					</tr>
					<?php
						if ($arrPersHosp!=null){
							foreach($arrPersHosp as $oPersHosp){
					?>
					<tr>
						<td class="llave"><?php echo $oPersHosp->getIdPersonal(); ?></td>
						<td><?php echo $oPersHosp->getNombreCompleto(); ?></td>
                                                                                                  <?php  if($oPersHosp->getTipo()==1){
                                                                                                        echo "<td>".$oPersHosp->getTipo()."(admin)</td>";
                                                                                                  } else{
                                                                                                       echo "<td>".$oPersHosp->getTipo()."(visualizador)</td>";
                                                                                                  } ?>
                                                <td><?php echo $oPersHosp->getSexo(); ?></td>
						<td>
							<input type="submit" name="Submit" value="Modificar" onClick="txtClave.value=<?php echo $oPersHosp->getIdPersonal(); ?>; txtOpe.value='m'"></td>
                                                <td><input type="submit" name="Submit" value="Borrar" onClick="txtClave.value=<?php echo $oPersHosp->getIdPersonal(); ?>; txtOpe.value='b'"></td>
						
					</tr>
					<?php 
							}//foreach
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