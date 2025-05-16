<?php
/*
Archivo:  login.php
Objetivo: verifica clave y contraseña contra repositorio a través de clases
Autor:   
*/
include_once("modelo\Usuario.php");
session_start();
$sErr="";
$sCve="";
$sNom="";
$sPwd="";
$oUsu = new Usuario();
	/*Verificar que hayan llegado los datos*/
	if (isset($_POST["txtCve"]) && !empty($_POST["txtCve"]) &&
		isset($_POST["txtPwd"]) && !empty($_POST["txtPwd"])){
		$sCve = $_POST["txtCve"];
		$sPwd = $_POST["txtPwd"];
		$oUsu->setClave($sCve);
		$oUsu->setPwd($sPwd);
		try{
			if ( $oUsu->buscarCvePwd() ){
				$sNom = $oUsu->getPersHosp()->getNombre();
				$_SESSION["usu"] = $oUsu;
				if ($oUsu->getPersHosp()->getTipo()== UsuariosContactos::TIPO_ADMIN)
					$_SESSION["tipo"] = "Administrador";
				else
					if ($oUsu->getPersHosp()->getTipo()== UsuariosContactos::TIPO_VISUALIZADOR)
					$_SESSION["tipo"] = "Visualizador";
				else
						$_SESSION["tipo"] = "no definido";
			}
			else{
				$sErr = "Usuario desconocido";
			}
		}catch(Exception $e){
			error_log($e->getFile()." ".$e->getLine()." ".$e->getMessage(),0);
			$sErr = "Error al acceder a la base de datos". $e->getMessage();
		}
	}
	else
		$sErr = "Faltan datos";
	//Enviar encabezado sin formato HTTP
	if ($sErr == "")
		header("Location: inicio.php");
	else
		header("Location: error.php?sError=".$sErr);
?>
