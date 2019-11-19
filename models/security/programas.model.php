<?php
/**
 * PHP Version 5
 * Modelo de Datos para Los funcioness
 *
 * @category Data_Model
 * @package  Models\funcioness
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  Comercial http://
 *
 * @version 1.0.0
 *
 * @link http://url.com
 */
require_once "libs/dao.php";

/**
 * Obtener programas por filtro
 *
 * @param string $fncod Codigo de funciones
 * @param string $Typ         Tipo de funciones
 *
 * @return array
 */
function obtenerFuncionesPorFiltro($fncod, $Typ)
{
    $programas = array();
    $sqlstr = sprintf(
        "SELECT * FROM funciones where fncod like '%s'
         and fntyp like '%s';",
        $fncod.'%', $Typ
    );
    $programas = obtenerRegistros($sqlstr);
    return $programas;
}
/**
 * Obtener funciones por Código
 *
 * @param string $fncod Código de funciones
 *
 * @return array
 */
function obtenerFuncionPorCodigo($fncod)
{
    $programa = array();
    $sqlstr = sprintf(
        "SELECT * FROM funciones where fncod = '%s';", valstr($fncod)
    );
    $programa = obtenerUnRegistro($sqlstr);
    return $programa;
}
/**
 * Ingresar nuevo funciones
 *
 * @param string $fncod Código de funciones
 * @param string $fndsc Descripción de funciones
 * @param string $fnest Estado de funciones
 * @param string $fntyp Tipo de funciones
 *
 * @return boolean
 */
function insertFuncion(
    $fncod,$fndsc, $fnest, $fntyp
) {
    $strsql = "INSERT INTO `funciones` (
        `fncod`,`fndsc`, `fnest`, `fntyp`)
         VALUES ('%s','%s', '%s','%s');";
    $strsql = sprintf(
        $strsql, valstr($fncod), $fndsc,
        $fnest, $fntyp
    );
    if (ejecutarNonQuery($strsql) ) {
        return true;
    }
    return 0;
}
/**
 * Actualizar funciones
 *
 * @param string $fncod Código de funciones
 * @param string $fndsc Descripción de funciones
 * @param string $fnest Estado de funciones
 * @param string $fntyp Tipo de funciones
 *
 * @return boolean
 */
function updateFuncion(
    $fncod, $fndsc, $fnest, $fntyp
) {
    $strsql = "UPDATE `funciones` set
              `fndsc` = '%s', `fnest` = '%s', `fntyp` = '%s'
              where `fncod` = '%s';";
    $strsql = sprintf(
        $strsql, $fndsc, $fnest,
        $fntyp, valstr($fncod)
    );
    $affected = ejecutarNonQuery($strsql);
    return ($affected > 0);
}
/**
 * Obtener Tipos de funciones
 *
 * @return array
 */
function getTiposfunciones()
{
    return array(
      array("codigo"=>"PGR","valor"=>"Página"),
      array("codigo"=>"FNC","valor"=>"Función")
    );
}
/**
 * Obtener Estados de funciones
 *
 * @return array
 */
function getEstadofunciones()
{
    return array(
      array("codigo"=>"ACT","valor"=>"Activo"),
      array("codigo"=>"INA","valor"=>"Inactivo")
    );
}
?>
