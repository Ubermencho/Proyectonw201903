<?php

  /* User Controller
   * 2017-06-14
   * Created By OJBA
   * Bitacora de Cambios:
   * -----------------------------------------------------------------------
   *| Fecha   | Usuario | Descripción                                      |
   * -----------------------------------------------------------------------
   */
  require_once('models/security/security.model.php');
  require_once("libs/validadores.php");
  function run(){
    $viewData =array();
    $viewData["mode"] = "";
    $viewData["modeDesc"] = "";
    $viewData["tocken"] = "";
    $viewData["errores"] = array();
    $viewData["haserrores"] = false;
    $viewData["readonly"] = false;
    $viewData["isinsert"] = false;
    $viewData["isRolEdit"] = false;
    //Arreglo para el combo de Tipos de usuario
    $viewData["tipoUsuarios"]= getTiposUsuario();
    $viewData["estadoUsuarios"]= getEstadoUsuario();

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        if(isset($_GET["mode"])){
          $viewData["mode"] = $_GET["mode"];
          $viewData["usrcod"] = intval($_GET["usrcod"]);
          switch ($viewData["mode"]) {
            case 'INS':
              $viewData["modeDesc"] = "Nuevo Usuario";
              $viewData["isinsert"] = true;
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
              redirectWithMessage("Accion Solicitada no disponible.", "index.php?page=users");
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
			$viewData["tocken"] = $_POST["tocken"];
			$viewData["usrcod"] = intval($_POST["usrcod"]);

            if($viewData["mode"]=="UPD" && (isset($_POST["btnDelRol"]) || isset($_POST["btnAddRol"]))){
                $viewData["isRolEdit"] = true;
            }else{
              $viewData["useremail"] = $_POST["txtCorreo"];
              $viewData["username"] = $_POST["txtName"];
              $viewData["usertipo"] =  $_POST["cmbTipo"];
              $viewData["userest"] =  $_POST["cmbEstado"];
              $viewData["userpswd"] =  $_POST["txtPswd"];


              // Validar la data
              if(!isValidEmail($viewData["useremail"])){
                  $viewData["errores"][] = "Correo en formarto incorrecto";
              }

              if(!isValidText($viewData["username"]) || isEmpty($viewData["username"])){
                  $viewData["errores"][] = "Nombre en formato Incorrecto";
              }
			 //Fix para validacion de contraseña en nuevos registros de usuario
              if( ( isEmpty($viewData["userpswd"]) || !isValidPassword($viewData["userpswd"]) ) && $viewData["mode"] =="INS" ){
                  $viewData["errores"][] = "Contraseña en formato incorrecto";
              }

              if((!isEmpty($viewData["userpswd"]) && $viewData["mode"] =="UPD" )){
                  if(!isValidPassword($viewData["userpswd"])){
                      $viewData["errores"][] = "Contraseña en formato incorrecto";
                  }
              }

            }

            $viewData["haserrores"] = count($viewData["errores"]) && true;

            switch ($viewData["mode"]) {
              case 'INS':
                $viewData["isinsert"] = true;

                if(!$viewData["haserrores"] && $viewData["usrcod"] == 0){

					//Se genera la contraseña salada y encriptada
					$fchingreso = time();
					$pswdSalted = "";

					if($fchingreso % 2 == 0){
						$pswdSalted = $viewData["userpswd"] . $fchingreso;
					}else{
						$pswdSalted = $fchingreso . $viewData["userpswd"];
					}

					$pswdSalted = md5($pswdSalted);
					$lastId = insertUsuario(	$viewData["username"],$viewData["useremail"],$fchingreso,$pswdSalted,
											$viewData["usertipo"], $viewData["userest"] );

					if( $lastId > 0 ){
						addBitacora("SEC001","Insert User",$viewData,"INFO");
						redirectWithMessage("Usuario Creado Satisfactoriamente.", "index.php?page=users");
						die();
					}else{
						$viewData["errores"][] = "Error al crear el usuario";
						$viewData["haserrores"] = true;
					}
                }
                $viewData["modeDesc"] = "Nuevo Usuario";
                break;
              case 'UPD':
                  if(!$viewData["haserrores"] && $viewData["usrcod"] > 0){
                    //Si es un boton de rol o el boton de usuario
                    if(!$viewData["isRolEdit"]){
                      //Se obtiene el usuario
                      $usuario = obtenerUsuarioPorCodigo($viewData["usrcod"]);
                      $pswdSalted='';
                      // Si no hay cambio de contraseña se usa la anterior
                      if(isEmpty($viewData["userpswd"])){
                        $pswdSalted = $usuario["userpswd"];
                      }else{
                        //Se genera nueva contraseña salando el valor cambiado
                        $fchingreso = $usuario['usuariofching'];
                        $pswdSalted = "";
                        if($fchingreso % 2 == 0){
                          $pswdSalted = $viewData["userpswd"] . $fchingreso;
                        }else{
                          $pswdSalted = $fchingreso . $viewData["userpswd"];
                        }
                        $pswdSalted = md5($pswdSalted);
                      }
                      // Se actualiza los datos del usuario
                      $affected = updateUsuario($viewData["usrcod"],
                                    $viewData["username"],
                                    $viewData["useremail"],
                                    $pswdSalted,
                                    $viewData["usertipo"],
                                    $viewData["userest"]
                                  );
                      // Si no hay error se redirige a la lista de usuarios
                      if($affected){
                        addBitacora("SEC001","Update User",$viewData,"INFO");
                        redirectWithMessage("Usuario Actualizado Satisfactoriamente.", "index.php?page=users");
                        die();
                      }else{
                      // Se muestra un error sobre la edicion del usuario
                        $viewData["errores"][] = "Error al editar el usuario";
                        $viewData["haserrores"] = true;
                      }
                    }else{
                      if(isset($_POST["btnAddRol"])){
                        agregarRolaUsuario($_POST["rolescod"], $viewData["usrcod"]);
                      }
                      if(isset($_POST["btnDelRol"])){
                        eliminarRolaUsuario($_POST["rolescod"], $viewData["usrcod"]);
                      }
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
                redirectWithMessage("Acción Solicitada no disponible.", "index.php?page=users");
                die();
            }

          }
        }else{
          //Cambia la seguridad del formulario
          $viewData["tocken"] = md5(time()."usertr");
          $_SESSION["user_tocken"] = $viewData["tocken"];
          $viewData["errores"][] = "Error para validar información.";
          $viewData["haserrores"] = true;
        }
    }

    // Cambia la seguridad del formulario para evitar ataques XHR.
    if($viewData["haserrores"]>0){
      $viewData["tocken"] = md5(time()."usertr");
      $_SESSION["user_tocken"] = $viewData["tocken"];
    }

    //Obtiene los datos del usuario y gestiona los valores de los arreglos
    if($viewData["usrcod"]>0){
      $usuario = obtenerUsuarioPorCodigo($viewData["usrcod"]);
      mergeFullArrayTo($usuario,$viewData);
      $viewData["modeDesc"] .= $viewData["username"];
      $viewData["tipoUsuarios"] = addSelectedCmbArray($viewData["tipoUsuarios"],"codigo",$viewData["usertipo"]);
      $viewData["estadoUsuarios"] = addSelectedCmbArray($viewData["estadoUsuarios"],"codigo",$viewData["userest"]);
      $viewData["rolesavailable"] = obtenerRolesDisponibles($viewData["usrcod"]);
      $viewData["rolesassign"] = obtenerRolesUsuario($viewData["usrcod"]);
      $tmpRole=Array();
      foreach($viewData["rolesassign"] as $rol ){
        $rol["readonly"] = $viewData["readonly"] && true;
        $rol["mode"] = $viewData["mode"];
        $rol["tocken"] = $viewData["tocken"];
        $tmpRole[] = $rol;
      }
      $viewData["rolesassign"] = $tmpRole;

    }

    renderizar("security/user", $viewData);
  }

  run();

?>
