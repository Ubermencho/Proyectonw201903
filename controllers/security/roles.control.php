<?php
/*
* Control roles mostrar los roles
* Creado por belzze
*
*/

require_once 'models/security/roles.model.php';
function run(){
    $data = array();
    $data["fltDsc"]="";
    $filter = '';
    if(isset($_SESSION["roles_context"])){
      $filter = $_SESSION["roles_context"]["filter"];
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
      $filter = $_POST["fltDsc"];
      $_SESSION["roles_context"] = array("filter" =>$filter);
    }
    $data["fltDsc"] = $filter;

    $data["roles"] = obtenerRolesPorFiltro($filter, '%');

    renderizar("security/roles", $data);
}
run();
 ?>
