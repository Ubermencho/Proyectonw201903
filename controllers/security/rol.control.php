<?php
/*
* Control roles mostrar los roles
* Creado por belzze
*
*/


  require_once 'models/security/roles.model.php';
  require_once 'libs/validadores.php';
  function run(){
    $viewData =array();
    $viewData["mode"] = "";
    $viewData["modeDesc"] = "";
    $viewData["tocken"] = "";
    $viewData["errores"] = array();
    $viewData["haserrores"] = false;
    $viewData["readonly"] = false;
    $viewData["isupdate"] = false;
    $viewData["isinsert"] = false;
    $viewData["isPgmEdit"] = false;

    //Arreglo para el combo de Estado de roles
    $viewData["estadoRol"]= getEstadoRol();
    //--------------------------------------
    //Esto es para decirle que va a hacer la pagina porque puede agregar, editar omostrar, no se eliminan solo se desactivan
    if($_SERVER["REQUEST_METHOD"] == "GET"){

        if(isset($_GET["mode"])){

          $viewData["mode"] = $_GET["mode"];
          $viewData["rolescod"] =$_GET["rolescod"];

          switch ($viewData["mode"]) {
            case 'INS':
              $viewData["modeDesc"] = "Nuevo Rol";
              $viewData["isinsert"] = true;
              break;
            case 'UPD':
              $viewData["isupdate"] = 'readonly="readonly"';
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
            redirectWithMessage("Accion Solicitada no disponible.", "index.php?page=roles");
            die();
        }
        // tocken para evitar ataques xhr
        $viewData["tocken"] = md5(time()."roltr");
        $_SESSION["rol_tocken"] = $viewData["tocken"];
      }
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
       if(isset($_POST["tocken"]) && $_POST["tocken"] === $_SESSION["rol_tocken"]){
         if(isset($_POST["mode"])){

           $viewData["mode"] = $_POST["mode"];
           $viewData["tocken"] = $_POST["tocken"];
           $viewData["rolescod"] = $_POST["rolescod"];


           if($viewData["mode"]=="UPD" && (isset($_POST["btnDelPgm"]) || isset($_POST["btnAddPgm"]))){
               $viewData["isPgmEdit"] = true;
           }else{
             $viewData["rolescod"] = $_POST["txtCodigo"];
             $viewData["rolesdsc"] = $_POST["txtName"];
             $viewData["rolesest"] =  $_POST["cmbEstado"];


             if(isEmpty($viewData["rolesdsc"])){
                 $viewData["errores"][] = "Descripción en formato Incorrecto";
             }
           }

           $viewData["haserrores"] = count($viewData["errores"]) && true;

           switch ($viewData["mode"]) {
             case 'INS':

			if( isset($viewData["rolescod"]) && $viewData["rolescod"]!='' &&
				isset($viewData["rolesdsc"]) && $viewData["rolesdsc"] != '' ){

                     $lastId = insertRol($viewData["rolescod"],$viewData["rolesdsc"],
                                   $viewData["rolesest"]
                                 );

                 if($lastId){
                   redirectWithMessage("Rol Creado Satisfactoriamente.", "index.php?page=roles");
                   die();
                 }else{
                   $viewData["errores"][] = "Error al crear el rol";
                   $viewData["haserrores"] = true;
                 }

			}else{

				  if(!isset($viewData["rolescod"]) || $viewData["rolescod"] == ''){
					  $viewData["haserrores"] = true;
					  $viewData["errores"][] = "Error codigo rol no valido.";
				  }

				  if(!isset($viewData["rolesdsc"]) || $viewData["rolesdsc"]==''){
					  $viewData["haserrores"] = true;
					  $viewData["errores"][] = "Error nombre del rol no valido.";
				  }
			  }

               $viewData["modeDesc"] = "Nuevo Rol";
               break;

             case 'UPD':
               $viewData["isupdate"] = 'readonly="readonly"';
               if(!$viewData["haserrores"] && !empty($viewData["rolescod"])){
                 if(!$viewData["isPgmEdit"]){
                   $affected = updateRoles($viewData["rolescod"],
                                 $viewData["rolesdsc"],
                                 $viewData["rolesest"]
                               );
                   // Si no hay error se redirige a la lista de usuarios
                   if($affected){
                     redirectWithMessage("Rol Actualizado Satisfactoriamente.", "index.php?page=roles");
                     die();
                   }else{
                   // Se muestra un error sobre la edicion del usuario
                     $viewData["errores"][] = "Error al editar el Rol";
                     $viewData["haserrores"] = true;
                   }
                }else{
                  if(isset($_POST["btnAddPgm"])){
                    agregarProgramaARol($_POST["programacod"], $viewData["rolescod"]);
                  }
                  if(isset($_POST["btnDelPgm"])){
                    eliminarProgramaARol($_POST["programacod"], $viewData["rolescod"]);
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
               redirectWithMessage("Acción Solicitada no disponible.", "index.php?page=roles");
               die();
           }

         }
       }else{
         //Cambia la seguridad del formulario
         $viewData["tocken"] = md5(time()."roltr");
         $_SESSION["rol_tocken"] = $viewData["tocken"];
         $viewData["errores"][] = "Error para validar información.";
       }
   }





    //Obtiene los datos del usuario y gestiona los valores de los arreglos
    if(!empty($viewData["rolescod"])){
      $roles = obtenerRolesPorCodigo($viewData["rolescod"]);
      mergeFullArrayTo($roles,$viewData);
      $viewData["modeDesc"] .= $viewData["rolesdsc"];
      $viewData["estadoRol"] = addSelectedCmbArray($viewData["estadoRol"],"codigo",$viewData["rolesest"]);
      $viewData["prgavailable"] =  obtenerProgramasDisponibles($viewData["rolescod"]);
      $viewData["prgassign"] = obtenerProgramasAsignados($viewData["rolescod"]);
      $tmpRole=Array();
      foreach($viewData["prgassign"] as $rol ){
        $rol["readonly"] = $viewData["readonly"] && true;
        $rol["mode"] = $viewData["mode"];
        $rol["tocken"] = $viewData["tocken"];
        $tmpRole[] = $rol;
      }
      $viewData["prgassign"] = $tmpRole;
    }
    // Cambia la seguridad del formulario para evitar ataques XHR.
    if($viewData["haserrores"]>0){
      $viewData["tocken"] = md5(time()."roltr");
      $_SESSION["rol_tocken"] = $viewData["tocken"];
    }
    renderizar("security/rol", $viewData);
  }

run();
 ?>
