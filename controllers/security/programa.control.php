<?php

  /* programa Controller
   * 2017-06-20
   * Created By JCHR14
   * Bitacora de Cambios:
   * -----------------------------------------------------------------------
   *| Fecha   | Usuario | Descripción                                      |
   * -----------------------------------------------------------------------
   */
  require_once 'models/security/programas.model.php';
  require_once "libs/validadores.php";
  function run(){
    $viewData =array();
    $viewData["mode"] = "";
    $viewData["modeDesc"] = "";
    $viewData["tocken"] = "";
    $viewData["errores"] = array();
    $viewData["haserrores"] = false;
    $viewData["readonly"] = false;

    //Arreglo para el combo de Tipos de usuario
    $viewData["tipoProgramas"]= getTiposFunciones();
    $viewData["estadoProgramas"]= getEstadoFunciones();

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["mode"])){
          $viewData["mode"] = $_GET["mode"];
          $viewData["fncod"] =$_GET["fncod"];
          switch ($viewData["mode"]) {
            case 'INS':
              $viewData["modeDesc"] = "Nuevo Programa";
              break;
            case 'UPD':
              $viewData["modeDesc"] = "Editar ";
              break;
            case 'DEL':
              $viewData["modeDesc"] = "Eliminar ";
              break;
            case 'DSP':
              $viewData["modeDesc"] = "Detalle de ";
              $viewData["readonly"] = 'readonly="readonly"';
              break;
            default:
              redirectWithMessage("Accion Solicitada no disponible.", "index.php?page=programas");
              die();
          }
          // tocken para evitar ataques xhr
          $viewData["tocken"] = md5(time()."usertr");
          $_SESSION["user_tocken"] = $viewData["tocken"];
        }
     }

     if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["tocken"]) && $_POST["tocken"] === $_SESSION["user_tocken"]){
          if(isset($_POST["mode"])){
            $viewData["mode"] = $_POST["mode"];
            $viewData["fncod"] = $_POST["txtCodigo"];
            $viewData["fndsc"] = $_POST["txtDescripcion"];
            $viewData["fnest"] =  $_POST["cmbEstado"];
            $viewData["fntyp"] =  $_POST["cmbTipo"];


            if(isEmpty($viewData["fndsc"])){
                $viewData["errores"][] = "Descripción en formato Incorrecto";
            }

            $viewData["haserrores"] = count($viewData["errores"]) && true;

            switch ($viewData["mode"]) {
              case 'INS':
                /*    $codigo=$viewData["fncod"];
                    $viewData["codigo"]="";
                    $viewData["codigo"]=obtenerProgramaPorCodigo($codigo);*/

                    /*if(empty($viewData["codigo"])){*/
                      $lastId = insertFuncion($viewData["fncod"],$viewData["fndsc"],
                                    $viewData["fnest"],
                                    $viewData["fntyp"]
                                  );
                  /*  }
                    else{
                      $viewData["errores"][] = "Código de programa ya existe";
                    }*/

                  if($lastId){
                    redirectWithMessage("Programa Creado Satisfactoriamente.", "index.php?page=programas");
                    die();
                  }else{
                    $viewData["errores"][] = "Error al crear el programa";
                    $viewData["haserrores"] = true;
                  }

                $viewData["modeDesc"] = "Nuevo Usuario";
                break;

              case 'UPD':
                if(!$viewData["haserrores"] && !empty($viewData["fncod"])){
                  //Se obtiene el usuario
                  //$programa = obtenerProgramaPorCodigo($viewData["fncod"]);
                  // Se actualiza los datos del usuario
                  $affected = updateFuncion($viewData["fncod"],
                                $viewData["fndsc"],
                                $viewData["fnest"],
                                $viewData["fntyp"]
                              );
                  // Si no hay error se redirige a la lista de usuarios
                  if($affected){
                    redirectWithMessage("Programa Actualizado Satisfactoriamente.", "index.php?page=programas");
                    die();
                  }else{
                  // Se muestra un error sobre la edicion del usuario
                    $viewData["errores"][] = "Error al editar la función";
                    $viewData["haserrores"] = true;
                  }
                }
                $viewData["modeDesc"] = "Editar ";
                break;
              case 'DEL':
                $viewData["modeDesc"] = "Eliminar ";
                //No se implementará
                break;
              case 'DSP':
                $viewData["modeDesc"] = "Detalle de ";
                $viewData["readonly"] = 'readonly="readonly"';
                break;
              default:
                redirectWithMessage("Acción Solicitada no disponible.", "index.php?page=programas");
                die();
            }

          }
        }else{
          //Cambia la seguridad del formulario
          $viewData["tocken"] = md5(time()."usertr");
          $_SESSION["user_tocken"] = $viewData["tocken"];
          $viewData["errores"][] = "Error para validar información.";
        }
    }

    //Obtiene los datos del usuario y gestiona los valores de los arreglos
    if(!empty($viewData["fncod"])){
      $programa = obtenerFuncionPorCodigo($viewData["fncod"]);
      mergeFullArrayTo($programa,$viewData);
      $viewData["modeDesc"] .= $viewData["fndsc"];
      $viewData["tipoProgramas"] = addSelectedCmbArray($viewData["tipoProgramas"],"codigo",$viewData["fntyp"]);
      $viewData["estadoProgramas"] = addSelectedCmbArray($viewData["estadoProgramas"],"codigo",$viewData["fnest"]);
    }
    // Cambia la seguridad del formulario para evitar ataques XHR.
    if($viewData["haserrores"]>0){
      $viewData["tocken"] = md5(time()."usertr");
      $_SESSION["user_tocken"] = $viewData["tocken"];
    }
    renderizar("security/programa", $viewData);
  }

  run();

?>
