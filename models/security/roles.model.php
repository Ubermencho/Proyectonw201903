<?php
/**
 * PHP Version 5
 * Modelo de Datos para la Entidad de Roles.
 *
 * @category Data_Model
 * @package  Models\Roles
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  Comercial http://
 *
 * @version 1.0.0
 *
 * @link http://url.com
 */
require_once 'libs/dao.php';

/**
 * Método para Obtener los Roles Registrados
 * obtenerRoles.
 *
 * @param string $rolCodigo Codigo del Rol
 *
 * @return array
 */
function obtenerRoles($rolCodigo)
{
    $rol = array();
    $sqlstr = sprintf("SELECT 'rolescod','rolesdsc','rolesest' FROM roles WHERE rolescod = %d;", $rolCodigo);
    $rol = obtenerUnRegistro($sqlstr);
    return $rol;
}
/**
 * Método para Obtener los Roles Registrados
 * obtenerRoles.
 *
 * @param mixed $rolDsc Descripción del Rol
 *
 * @return array
 */
function obtenerRolesDsc($rolDsc)
{
    $rol = array();
    $sqlstr = sprintf("SELECT 'rolescod','rolesdsc','rolesest' FROM roles WHERE rolesdsc = %d;", $rolDsc);
    $rol = obtenerUnRegistro($sqlstr);
    return $rol;
}
/**
 * Obtener Roles por Filtro
 *
 * @param string $rolesdsc Código Parcial o Completo
 * @param string $userType Nombre del Role Parcial o Completo
 *
 * @return array
 */
function obtenerRolesPorFiltro($rolesdsc, $userType)
{
    $usuario = array();
    $sqlstr = sprintf("SELECT `rolescod`,`rolesdsc`, `rolesest` FROM roles where rolescod like '%s' and rolesdsc like '%s';", $rolesdsc.'%', $userType);
    $usuarios = obtenerRegistros($sqlstr);
    return $usuarios;
}
/**
 * Obtiene los Estados de los Roles
 *
 * @return array
 */
function getEstadoRol()
{
    return array(
                array('codigo' => 'PND', 'valor' => 'Sin Activar'),
                array('codigo' => 'ACT', 'valor' => 'Activo'),
                array('codigo' => 'INA', 'valor' => 'Inactivo'),
            );
}
/**
 * Obtener Roles por el Código
 *
 * @param string $rolescod Codigo del Rol
 *
 * @return array
 */
function obtenerRolesPorCodigo($rolescod)
{
    $roles = array();
    $sqlstr = sprintf("SELECT rolescod, rolesdsc, rolesest FROM roles WHERE rolescod = '%s'", $rolescod);
    $roles = obtenerUnRegistro($sqlstr);
    return $roles;
}
/**
 * Insertar Nuevo Rol
 *
 * @param string $rolescod Codigo de Roles
 * @param string $rolesdsc Descripción de Roles
 * @param string $rolesest Estado de Roles
 *
 * @return boolean
 */
function insertRol($rolescod, $rolesdsc, $rolesest)
{
    $strsql = "INSERT INTO `roles` (`rolescod`, `rolesdsc`, `rolesest`) VALUES ('%s','%s', '%s');";
    $strsql = sprintf($strsql, valstr($rolescod), $rolesdsc, $rolesest);
    if (ejecutarNonQuery($strsql)) {
        return true;
    }
    return 0;
}
/**
 * Actualiza el Rol Especificado
 *
 * @param string $rolescod Código de Rol
 * @param string $rolesdsc Descripción del Rol
 * @param string $rolesest Estado del Rol
 *
 * @return integer Cantidad de Registros Modificados
 */
function updateRoles($rolescod, $rolesdsc, $rolesest)
{
    $strsql = "UPDATE `roles` SET `rolesdsc`='%s', `rolesest`='%s' WHERE `rolescod`='%s';";
    $strsql = sprintf($strsql, $rolesdsc, $rolesest, valstr($rolescod));
    $affected = ejecutarNonQuery($strsql);
    return $affected > 0;
}
/**
 * Asocia Programa a Rol Específico
 *
 * @param string $pgmcod Código de Programa
 * @param string $rolcod Código de Rol
 *
 * @return boolean
 */
function agregarProgramaARol($pgmcod, $rolcod)
{
    $sqlstr = "INSERT INTO `funciones_roles` (`fncod`, `rolescod`,`fnrolest`) VALUES ('%s', '%s', 'ACT' );";
    return ejecutarNonQuery(sprintf($sqlstr, $pgmcod, $rolcod));
}
/**
 * Elimina un Programa de un Rol Espcífico
 *
 * @param string $pgmcod Código de programa
 * @param string $rolcod Código de Rol
 *
 * @return boolean
 */
function eliminarProgramaARol($pgmcod, $rolcod)
{
    $sqlstr = "Delete from `funciones_roles` where  `fncod`= '%s' and `rolescod` = '%s';";
    return ejecutarNonQuery(sprintf($sqlstr, $pgmcod, $rolcod));
}
/**
 * Obtener Programas Disponibles
 *
 * @param string $rolcod Código de Rol
 *
 * @return array
 */
function obtenerProgramasDisponibles($rolcod)
{
    $sqlstr = "select b.fncod, b.fndsc, a.rolescod from funciones b left join funciones_roles a on a.fncod = b.fncod and a.rolescod='%s' where a.fncod is null ;";
    $pgms = obtenerRegistros(sprintf($sqlstr, $rolcod));
    return $pgms;
}
/**
 * Obtener Programas Asignados
 *
 * @param string $rolcod Código de Rol
 *
 * @return array
 */
function obtenerProgramasAsignados($rolcod)
{
    $sqlstr = "select b.fncod, b.fndsc, a.rolescod from funciones b inner join funciones_roles a on a.fncod = b.fncod where a.rolescod = '%s' ;";
    $pgms = obtenerRegistros(sprintf($sqlstr, $rolcod));
    return $pgms;
}

?>
