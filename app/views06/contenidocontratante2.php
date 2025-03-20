<?php

/* Nombre del Archivo: contenidocontratante2.php
   Descripción: Generar input para ingresar la cédula dependiendo el tipo de contratante seleccionado
   Utilizado en Reportes -> aaaaaaa
*/

$opcion = $_REQUEST['opcion'];

if ($opcion == '2') {
  // Para Ente: se muestra el campo y el input para ingresar la cédula.
  echo '
    <td class="tdtitulos">Rif/Cédula del Ente:</td>
    <td class="tdcampos">
      <input type="text" id="cedula_contratante" class="campos" placeholder="Ingrese la cédula" value="" onblur="verificarDocumento2(this.value, \'rif\' )">
    </td>
  ';
} elseif ($opcion == '3') {
  // Para Persona: se muestra el campo y el input para ingresar la cédula.
  echo '
    <td class="tdtitulos">Cédula de la Persona:</td>
    <td class="tdcampos">
      <input type="text" id="cedula_contratante" class="campos" placeholder="Ingrese la cédula" value="" onblur="verificarDocumento2(this.value, \'cedula\' )">
    </td>
  ';
}


?>