<?php
/* Programas Controller
 * 2017-06-19
 * Created By OJBA
 * Last Modification 2014-10-14 20:04
 */
  require_once "models/security/programas.model.php";


  function run(){
      $data = array();
      $data["fltNombre"] = "";
      $filter = '';
      if(isset($_SESSION["programas_context"])){
        $filter = $_SESSION["programas_context"]["filter"];
      }

      if($_SERVER["REQUEST_METHOD"] == "POST"){
        $filter = $_POST["fltNombre"];
        $_SESSION["programas_context"] = array("filter"=>$filter);
      }
      $data["fltNombre"] = $filter;
      $data["programas"] = obtenerFuncionesPorFiltro($filter,'%');
      renderizar("security/programas", $data );
  }

  run();
?>
