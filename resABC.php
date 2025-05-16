<?php
/*
Archivo:  resABC.php
Objetivo: ejecuta la afectación al personal y retorna a la pantalla de consulta general
Autor:    
*/
include_once("modelo/UsuariosContactos.php");
include_once("modelo/Usuario.php");
session_start();

$sErr=""; $sOpe = ""; $sCve = "";
$oPersHosp = new UsuariosContactos();

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
            $oPersHosp->setFechaNacim(DateTime::createFromFormat('Y-m-d', $_POST["txtFecNacim"]));
            $oPersHosp->setSexo($_POST["rbSexo"]);
            $oPersHosp->setTipo($_POST["cmbTipo"]);
        }

        try {
            if ($sOpe == 'a') {
                // **1. Insertar usuario en `UsuariosContactos`**
                $nResultado = $oPersHosp->insertar();

                if ($nResultado == 1) { 
                    
                    $nIdPersonal = $oPersHosp->getIdPersonal();

                    // **3. Insertar automáticamente en `usuario`**
                    $oUsu = new Usuario();
                    if ($oUsu->insertarUsuario($nIdPersonal, $_POST["txtNombre"])) {
                        echo "Usuario registrado correctamente con ID: " . $nIdPersonal;
                           $mensaje="Se ha insertado correctamente el nuevo contacto usuario ";
                    } else {
                        echo "Error al registrar usuario en la tabla `usuario`.";
                    }
                } else {
                    echo "Error al insertar en `UsuariosContactos`.";
                }
            } else if ($sOpe == 'b') {
                  $oPersHosp->setIdContacto(intval($sCve));
                $nResultado = $oPersHosp->borrar();
                 $mensaje="Se ha borrado correctamente el contacto agregado como: ".$oPersHosp->getNombre();;
            } else {
                $nResultado = $oPersHosp->modificar();
                 $mensaje="Se ha modificado correctamente  el contacto con nombre: ".$oPersHosp->getNombre();;
            }
              if ($nResultado) {
        echo "<script src='js/script.js'></script>";
        echo "<script>
            mostrarPopup(
                '$mensaje',
                'img/aprobado.gif',
                'tabusuarios.php'
            );
        </script>";
        exit(); 
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
    header("Location: tabusuarios.php");
} else {
    header("Location: error.php?sError=" . urlencode($sErr)); // Codificar error
}
exit();
?>