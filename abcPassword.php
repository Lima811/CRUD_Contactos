<?php
/*
Archivo:  abcPersHosp.php
Objetivo: edición sobre Personal Hospitalario
Autor:    
*/
include_once("modelo\PasswordUsuarios.php");
session_start();

$sErr=""; $sOpe = ""; $sCve = ""; $sNomBoton ="Borrar";
$oUsurCont=new Password();
$bCampoEditable = false; $bLlaveEditable=false;

//$oUsurCont = new ();
	/*Verificar que haya sesión*/
	if (isset($_SESSION["usu"]) && !empty($_SESSION["usu"])){
		/*Verificar datos de captura*/
		if (isset($_POST["txtClave"]) && !empty($_POST["txtClave"]) &&
			isset($_POST["txtOpe"]) && !empty($_POST["txtOpe"])){
			$sOpe = $_POST["txtOpe"];
			$sCve = $_POST["txtClave"];
                                                       
                                                        
			if ($sOpe != 'a'){
				$oUsurCont->setIdPassword($sCve);
				try{
                                                                                   //$idVisualizador=$oUsu->getPersHosp()->getIdPersonal();
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
			}
			else if ($sOpe == 'm'){
                            
				$bCampoEditable = true; //la llave no es editable por omisión
				$sNomBoton ="Modificar";
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
			<form name="abcPH" action="pasABC.php" method="post">
				<input type="hidden" name="txtOpe" value="<?php echo $sOpe;?>">
				<input type="hidden" name="txtClave" value="<?php echo $sCve;?>"/>
				Password
				<input type="text" name="txtPassword" 
					<?php echo ($bCampoEditable==true?'':' disabled ');?>
                                                                                value="<?php echo $oUsurCont->getPassword();?>"/>
				<br/>
                                                                 
				
				
				
				<input type ="submit" value="<?php echo $sNomBoton;?>" 
				onClick="return evaluaPassword(txtPassword);"/>
				<input type="submit" name="Submit" value="Cancelar" 
				 onClick="abcPH.action='tabpassword.php';">
			</form>
		</section>
<?php
include_once("pie.html");
?>


