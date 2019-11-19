<?php
/**
 * PHP Version 5
 * Modelo de Datos para la Usuarios y Seguridad.
 *
 * @category Data_Model
 * @package  Models\Security
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  Comercial http://
 *
 * @version 1.0.0
 *
 * @link http://url.com
 */
require_once "libs/dao.php" ;

function obtenerUsuarioPorEmail($userEmail){
   $usuario = array();
   $sqlstr = sprintf("SELECT `usercod`,`useremail`, `username`, `userpswd`,
   UNIX_TIMESTAMP(`userfching`) as userfching, `userpswdest`, `userpswdexp`,
   `userest`, `useractcod`, `userpswdchg`,
   `usertipo`
      FROM usuario where useremail = '%s';",$userEmail);
   $usuario = obtenerUnRegistro($sqlstr);
   return $usuario;
}

function obtenerUsuarioPorFiltro($userEmail, $userType){
   $usuario = array();
   $sqlstr = sprintf("SELECT `usercod`, `useremail`, `username`,
   `userest`, `usertipo`
      FROM usuario where useremail like '%s' and usertipo like '%s';",
      $userEmail.'%' , $userType);
   $usuarios = obtenerRegistros($sqlstr);
   return $usuarios;
}

function obtenerUsuariosPorTipo($userType, $userEst='ACT', $userName ='%'){
 $usuario = array();
 $sqlstr = sprintf("SELECT `usercod`,`useremail`, `username`, `userpswd`,
 UNIX_TIMESTAMP(`userfching`) as userfching, `userpswdest`, `userpswdexp`,
 `userest`, `useractcod`, `userpswdchg`,
 `usertipo`
    FROM usuario where usertipo = '%s' and userest='%s' and username like '%s';",
      $userName,$userEst,$userName.'%');

 $usuario = obtenerRegistros($sqlstr);
 return $usuario;
}

function obtenerUsuarioPorCodigo($usercod){
   $usuario = array();
   $sqlstr = sprintf("SELECT `usercod`,`useremail`, `username`,`userpswd`,
     UNIX_TIMESTAMP(`userfching`) as userfching,
   `userest`, `usertipo`
      FROM usuario where usercod = %d;",$usercod);
   $usuario = obtenerUnRegistro($sqlstr);
   return $usuario;
}

function estaEnRol($usercod, $rolcod){
   $sqlstr = "select 1 as ok from roles_usuarios  where usercod = %d and rolescod = '%';";
   $data = array();
   $data = obtenerUnRegistro(sprintf($sqlstr,$usercod,$rolcod));
   if(count($data) > 0){
     return true;
   }
   return false;
}

function estaAutorizado($usercod, $assetcod){
    $sqlstr = "select * from
    funciones_roles a inner join roles_usuarios b on a.rolescod = b.rolescod
    where a.fnrolest = 'ACT' and b.usercod=%d and a.fncod='%s' limit 1;";
    $data = array();
    $data = obtenerUnRegistro(sprintf($sqlstr,$usercod,$assetcod));
    if(count($data) > 0){
        return true;
    }
    return false;
}

function insertUsuario($userName, $userEmail,
                      $timestamp, $password, $userType = 'SYS', $userEst = 'ACT'){

  //userType= 'SYS' usuario normal, 'CNS' Consultor , 'CLT' Cliente, 'ADM' administrador del sitio
  //-----------------------------------------------------------------


   $strsql = "INSERT INTO `usuario` (
       `useremail`, `username`, `userpswd`,
       `userfching`, `userpswdest`, `userpswdexp`,
       `userest`, `useractcod`, `userpswdchg`,
       `usertipo`) VALUES ('%s', '%s','%s',
        FROM_UNIXTIME(%s), 'VGT', NULL,
        '%s', '', NULL, '%s');";
   $strsql = sprintf($strsql, valstr($userEmail),
                               valstr($userName),
                               $password,
                               $timestamp,
                               $userEst,
                               $userType);

   if(ejecutarNonQuery($strsql)){
       return getLastInserId();
   }
   return 0;
}

function updateUsuario($usercod, $userName, $userEmail,
                      $password, $userType, $userEst ){

  //userType= 'SYS' usuario normal, 'CNS' Consultor , 'CLT' Cliente, 'ADM' administrador del sitio
  //-----------------------------------------------------------------


   $strsql = "UPDATE `usuario` set
       `useremail` = '%s', `username` = '%s', `userpswd` = '%s',
       `userest` = '%s',
       `usertipo` = '%s' where `usercod` = %d;";
   $strsql = sprintf($strsql, valstr($userEmail),
                               valstr($userName),
                               $password,
                               $userEst,
                               $userType, $usercod);

   $affected = ejecutarNonQuery($strsql);
   return ($affected > 0);
}
//funciones adiciones para datos
function getTiposUsuario(){
 return array(
   array("codigo"=>"ADM","valor"=>"Administrador"),
   array("codigo"=>"USR","valor"=>"Usuario"),
   array("codigo"=>"CNS","valor"=>"Consultor"),
   array("codigo"=>"CLT","valor"=>"Cliente")
 );
}

function getEstadoUsuario(){
 return array(
   array("codigo"=>"PND","valor"=>"Sin Activar"),
   array("codigo"=>"ACT","valor"=>"Activo"),
   array("codigo"=>"SPD","valor"=>"Suspendido"),
   array("codigo"=>"INA","valor"=>"Inactivo")
 );
}

function obtenerRolesDisponibles($usercod){
  $sqlstr = "select b.rolescod, b.rolesdsc, a.usercod
from roles b left join roles_usuarios a
on a.rolescod = b.rolescod and a.usercod=%d
where a.usercod is null ;";
  $roles = obtenerRegistros(sprintf($sqlstr, $usercod));
  return $roles;
}
function obtenerRolesUsuario($usercod){
  $sqlstr = "select b.rolescod, b.rolesdsc, a.usercod
from roles b inner join roles_usuarios a
on a.rolescod = b.rolescod
where a.usercod = %d ;";
  $roles = obtenerRegistros(sprintf($sqlstr, $usercod));
  return $roles;
}
function agregarRolaUsuario($rolcod,$usercod){
 $sqlstr = "INSERT INTO `roles_usuarios` (`usercod`, `rolescod`,`roleuserest`,`roleuserfch`)
            VALUES (%d, '%s','ACT' ,now());";
  return ejecutarNonQuery(sprintf($sqlstr, $usercod, $rolcod));
}

function eliminarRolaUsuario($rolcod,$usercod){
  $sqlstr = "Delete from`roles_usuarios` where  `usercod`= %d and `rolescod` = '%s';";
  return ejecutarNonQuery(sprintf($sqlstr, $usercod, $rolcod));
}
?>
