<?php
    require_once("libs/dao.php");

    function obtenerTodasBitacoras(){
      $sqlstr = "Select * from bitacora;";
      return obtenerRegistros($sqlstr);
    }

    function obtenerBitacoras($prg,$tip,$meses,$json="true"){
      $sqlstr = "select a.bitacorafch, a.bitprograma, a.bitdescripcion, a.bitTipo, ifnull(b.usuarionom,'SYSTEM') as bitusuario ";
      if($json){
        $sqlstr .= ", a.bitobservacion ";
      }else{
        $sqlstr .= ", '' as bitobservacion ";
      }
      $sqlstr .= sprintf(" from bitacora a left join usuario b on a.bitusuario = b.usuariocod where a.bitprograma like '%s' ", $prg.'%');
      if($meses != "ALL") $sqlstr .= sprintf(" and a.bitacorafch >= DATE_SUB(CURDATE(), INTERVAL %d MONTH ) ",$meses);
      if($tip != "ALL") $sqlstr .= sprintf(" and a.bitTipo = '%s'", $tip);
      $sqlstr .= " order by a.bitacorafch desc;";

      //die($sqlstr);
      return obtenerRegistros($sqlstr);

    }
    function insertBitacora($fecha = null, $programa = "", $desc = "", $obsr = "", $tipo = 'INF', $usuario = 0){
      $updStr =  "INSERT INTO `bitacora` ( `bitacorafch`, `bitprograma`,
        `bitdescripcion`, `bitobservacion`, `bitTipo`, `bitusuario`)
        VALUES ('%s', '%s',
        '%s', '%s', '%s', %d);";
        $rfch = ($fecha===null)? date("Y-m-d h:i:s") : $fecha;
        $robs = (is_array($obsr))? json_encode($obsr): $obsr;

        $updStr = sprintf($updStr, $rfch,
                                $programa, $desc,
                              $robs, $tipo, $usuario);

        ejecutarNonQuery($updStr);
        return getLastInserId();
    }

    function getTiposBitacora(){
      return array(
        array("codigo"=>"ALL","valor"=>"Mostrar Todos"),
        array("codigo"=>"INF","valor"=>"Información"),
        array("codigo"=>"WRN","valor"=>"Advertencia"),
        array("codigo"=>"ERR","valor"=>"Error")
      );
    }

    function getMesesBitacora(){
      return array(
        array("codigo"=>"ALL","valor"=>"Todos"),
        array("codigo"=>"3","valor"=>"Últimos 3 meses"),
        array("codigo"=>"6","valor"=>"Últimos 6 meses"),
        array("codigo"=>"12","valor"=>"Últimos 12 meses")
      );
    }

 ?>
