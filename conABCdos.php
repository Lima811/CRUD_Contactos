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
$mensaje="";
$oPersHosp = new Contacto();

/* Verificar que exista la sesión */
if (isset($_SESSION["usu"]) && !empty($_SESSION["usu"])) {
    /* Verificar datos de captura mínimos */
    if (isset($_POST["txtClave"]) && !empty($_POST["txtClave"]) &&
        isset($_POST["txtOpe"]) && !empty($_POST["txtOpe"])) {

        $sOpe = $_POST["txtOpe"];
        $sCve = $_POST["txtClave"];//->id de contacto
        //var_dump($sCve);
        //exit();
        $oPersHosp->setIdPersonal($sCve);
       


        if ($sOpe != "b") {
            $oPersHosp->setNombre($_POST["txtNombre"]);
            $oPersHosp->setDireccion($_POST["txtDireccion"]);
             $oPersHosp->setTelefono($_POST["txtTelefono"]);
              $oPersHosp->setEmail($_POST["txtEmail"]);
              $oPersHosp->setIdPersonal($_POST["txtVisualizador"]);
            //$oPersHosp->setFechaNacim(DateTime::createFromFormat('Y-m-d', $_POST["txtFecNacim"]));
           // $oPersHosp->setSexo($_POST["rbSexo"]);
            //$oPersHosp->setTipo($_POST["cmbTipo"]);
        }

        try {
            if ($sOpe == 'a') {
                //$idVisualizador = $_SESSION["usu"]->getPersHosp()->getIdPersonal(); 
                //$oPersHosp->setIdPersonal($idVisualizador); // Asignar el ID visualizador al contacto

                 
                $nResultado = $oPersHosp->insertar();
                $mensaje="Se ha insertado correctamente el nuevo contacto para el visualizador ".$oPersHosp->getIdPersonal();
               
        
        //exit(); // Terminar ejecución para evitar redirección adicional
    

            } 
             else if ($sOpe == 'b') {
                   $oPersHosp->setIdContacto(intval($sCve));
                $nResultado = $oPersHosp->borrar();
                $mensaje="Se ha borrado correctamente el contacto agregado como: ".$oPersHosp->getNombre();;

                 
        
        //exit(); // Terminar ejecución para evitar redirección adicional
    
            } else {
                try{
                   // $idVisualizador = $_SESSION["usu"]->getPersHosp()->getIdPersonal(); 
                  $oPersHosp->setIdContacto(intval($sCve));
                $nResultado = $oPersHosp->modificarDos();
                $mensaje="Se ha modificado correctamente  el contacto con nombre: ".$oPersHosp->getNombre();;

               
                } catch (Exception $ex) {
                        $sErr=$ex->getMessage();
                }
                 
            }

        if ($nResultado) {
        echo "<script src='js/script.js'></script>";
        echo "<script>
            mostrarPopup(
                '$mensaje',
                'img/aprobado.gif',
                'tabcontactosdos.php'
            );
        </script>";
        exit(); 
    }

        } catch (Exception $e) {
            error_log($e->getFile() . " " . $e->getLine() . " " . $e->getMessage(), 0);
            $sErr = "Error en base de datos: " . $e->getMessage(); // Mostrar el error real
            echo "<script src='js/script.js'></script>";
        echo "<script>
            mostrarPopup(
                '¡No se pudo llevar a cabo la operacion!',
                'img/reprobado.gif',
                'tabcontactosdos.php'
            );
        </script>";
        }

    } else {
        $sErr = "Faltan datos.";
    }

} else {
    $sErr = "Falta establecer el login.";
}

/* Redireccionar según el resultado */
if ($sErr == "") {
    header("Location: tabcontactosdos.php");
} else {
    header("Location: error.php?sError=" . urlencode($sErr)); // Codificar error
}
exit();
?>


