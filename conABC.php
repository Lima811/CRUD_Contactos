<?php
/*
Archivo:  resABC.php
Objetivo: ejecuta la afectación al personal y retorna a la pantalla de consulta general
Autor:    
*/
include_once("modelo/Contactos.php");
include_once("modelo/Usuario.php");
session_start();

$sErr=""; $sOpe = ""; $sCve = "";
$oPersHosp = new Contacto();

/* Verificar que exista la sesión */
if (isset($_SESSION["usu"]) && !empty($_SESSION["usu"])) {
    /* Verificar datos de captura mínimos */
    if (isset($_POST["txtClave"]) && !empty($_POST["txtClave"]) &&
        isset($_POST["txtOpe"]) && !empty($_POST["txtOpe"])) {

        $sOpe = $_POST["txtOpe"];
        $sCve = $_POST["txtClave"];
        
        $oPersHosp->setIdPersonal($sCve);
       


        if ($sOpe != "b") {
            $oPersHosp->setNombre($_POST["txtNombre"]);
            $oPersHosp->setDireccion($_POST["txtDireccion"]);
             $oPersHosp->setTelefono($_POST["txtTelefono"]);
              $oPersHosp->setEmail($_POST["txtEmail"]);
            //$oPersHosp->setFechaNacim(DateTime::createFromFormat('Y-m-d', $_POST["txtFecNacim"]));
           // $oPersHosp->setSexo($_POST["rbSexo"]);
            //$oPersHosp->setTipo($_POST["cmbTipo"]);
        }

        try {
            if ($sOpe == 'a') {
                $idVisualizador = $_SESSION["usu"]->getPersHosp()->getIdPersonal(); 
                $oPersHosp->setIdPersonal($idVisualizador); // Asignar el ID visualizador al contacto

                echo "<script src='js/script.js'></script>";
  
    
                $nResultado = $oPersHosp->insertar();

            } 
             else if ($sOpe == 'b') {
                   
                $nResultado = $oPersHosp->borrar();
            } else {
                try{
                   // $idVisualizador = $_SESSION["usu"]->getPersHosp()->getIdPersonal(); 
                  $oPersHosp->setIdPersonal(intval($sCve));
                $nResultado = $oPersHosp->modificar();
                } catch (Exception $ex) {
                        $sErr=$ex->getMessage();
                }
                 
            }

            if ($nResultado != 1) {
                $sErr = "Error en base de datos, comunicarse con el administrador.";
            }

        } catch (Exception $e) {
            error_log($e->getFile() . " " . $e->getLine() . " " . $e->getMessage(), 0);
            $sErr = "Error en base de datos: " . $e->getMessage(); // Mostrar el error real
        }

    } else {
        $sErr = "Faltan datos.";
    }

} else {
    $sErr = "Falta establecer el login.";
}

/* Redireccionar según el resultado */
if ($sErr == "") {
    header("Location: tabcontactos.php");
} else {
    header("Location: error.php?sError=" . urlencode($sErr)); // Codificar error
}
exit();
?>
