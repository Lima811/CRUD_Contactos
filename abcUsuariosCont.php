<?php
/*
Archivo:  abcPersHosp.php
Objetivo: edición sobre Personal Hospitalario
Autor:    
*/
include_once("modelo\UsuariosContactos.php");
session_start();

$sErr=""; $sOpe = ""; $sCve = ""; $sNomBoton ="Borrar";
$oUsurCont=new UsuariosContactos();
$bCampoEditable = false; $bLlaveEditable=false;

$oUsurCont = new UsuariosContactos();
	/*Verificar que haya sesión*/
	if (isset($_SESSION["usu"]) && !empty($_SESSION["usu"])){
		/*Verificar datos de captura*/
		if (isset($_POST["txtClave"]) && !empty($_POST["txtClave"]) &&
			isset($_POST["txtOpe"]) && !empty($_POST["txtOpe"])){
			$sOpe = $_POST["txtOpe"];
			$sCve = $_POST["txtClave"];
			if ($sOpe != 'a'){
				$oUsurCont->setIdPersonal($sCve);
				try{
					if (!$oUsurCont->Buscar()){
						$sError = "Usuario  no existe";
					}
				}catch(Exception $e){
					error_log($e->getFile()." ".$e->getLine()." ".$e->getMessage(),0);
					$sErr = "Error en base de datos, comunicarse con el administrador";
				}
			}
			if ($sOpe == 'a'){
				$bCampoEditable = true;
				$bLlaveEditable = true;
				$sNomBoton ="Agregar";
                                  $mensajeaux='Deseas'.$sNomBoton.'al usuario:'.$oUsurCont->getNombre().' con el id '.$oUsurCont->getIdPersonal().'?';
			}
			else if ($sOpe == 'm'){
				$bCampoEditable = true; //la llave no es editable por omisión
				$sNomBoton ="Modificar";
                                $mensajeaux='Deseas'.$sNomBoton.'al usuario:'.$oUsurCont->getNombre().' con el id '.$oUsurCont->getIdPersonal().'?';
			}else if($sOpe=='b'){
                                                $bCampoEditable = true; //la llave no es editable por omisión
				$sNomBoton ="Borrar";
                                $mensajeaux='Deseas'.$sNomBoton.'al usuario:'.$oUsurCont->getNombre().' con el id '.$oUsurCont->getIdPersonal().'?';
                        }
			//Si fue borrado, nada es editable y es el valor por omisión
		}
		else{
			$sErr = "Faltan datos";
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
			<form name="abcPH" action="resABC.php" method="post">
				<input type="hidden" name="txtOpe" value="<?php echo $sOpe;?>">
				<input type="hidden" name="txtClave" value="<?php echo $sCve;?>"/>
				Nombre
				<input type="text" name="txtNombre" 
					<?php echo ($bCampoEditable==true?'':' disabled ');?>
					value="<?php echo $oUsurCont->getNombre();?>"/>
				<br/>
				
				Fecha de Nacimiento (aaaa-mm-dd)
				<input type="date" name="txtFecNacim" 
					<?php echo ($bCampoEditable==true?'':' disabled ');?>
					value="<?php echo $sOpe == 'a'?'':$oUsurCont->getFechaNacim()->format('Y-m-d');?>"/>
				<br/>
				Sexo
				<input type="radio" name="rbSexo" value="F"
					<?php echo ($bCampoEditable==true?'':' disabled ');?>
					<?php echo ($oUsurCont->getSexo()=='F'?'checked="true"':'');?>/>Femenino
				<input type="radio" name="rbSexo" value="M"
					<?php echo ($bCampoEditable==true?'':' disabled ');?>
					<?php echo ($oUsurCont->getSexo()=='M'?'checked="true"':'');?>/>Masculino
				<br/>
				Tipo
				<select name="cmbTipo" <?php echo ($bCampoEditable==true?'':' disabled ');?>>
                                                                <option value="<?php echo UsuariosContactos::TIPO_ADMIN;?>"
					<?php echo ($oUsurCont->getTipo()== UsuariosContactos::TIPO_ADMIN?'selected="true"':'');?>>Administrador</option>
                                                                <option value="<?php echo UsuariosContactos::TIPO_VISUALIZADOR;?>"
					<?php echo ($oUsurCont->getTipo()==UsuariosContactos::TIPO_VISUALIZADOR?'selected="true"':'');?>>Visualizador</option>
					
				</select>
				<br/>
				<input type ="submit" value="<?php echo $sNomBoton;?>" 
				onClick="return mostrarVentanaEmergente(event, '<?php echo $mensajeaux;?>', 'Sí', 'No', 'tabusuarios.php', 'img/pregunta.gif');"/>
				<input type="submit" name="Submit" value="Cancelar" 
				 onClick="abcPH.action='tabusuarios.php';">
			</form>
		</section>
<?php
include_once("pie.html");
?>