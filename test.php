<?php

  require("./models/empresas.model.php");

  $Empresa["empdsc"] = "Colonia";
  $Empresa["emprtn"] = "2450-982345098";
  $Empresa["empdir"] = "ASdasdf asdf asdfklj Honduras";
  $Empresa["emptel"] = "12341234134";
  $Empresa["emptel2"] = "123412342345";
  $Empresa["empurl"] = "http://someurl.com.hn";
  $Empresa["empusring"] = "obtancourthunicah@gmail.com";
  $Empresa["empest"] ="ACT";
  $Empresa["empctc"] = "Somebody";
  $Empresa["emptip"] = "RTL";
  $Empresa["empresaId"] = 2;

  echo actualizarEmpresa($Empresa);
  //print_r(obtenerEmpresa(1));



?>
