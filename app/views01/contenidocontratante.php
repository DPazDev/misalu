<?php

/* Nombre del Archivo: contenidocontratante.php
  Descripción: Generar input para ingresar la cédula dependiendo el tipo de contratante seleccionado.
  Utilizado en Entes -> Registro Individual
*/

$opcion = $_REQUEST['opcion'];
$cedulaTitular = $_REQUEST['cedulaTitular'];

if ($opcion == '1') {
    // Para Titular: la cédula se envía como input oculto y se verifica automáticamente.
    echo '
    <tr>
      <td colspan="2">
        <input type="hidden" id="cedula_contratante" value="' . $cedulaTitular . '" onblur="verificarDocumento(this.value, \'cedula\', \'' . $cedulaTitular . '\', \'' . $opcion . '\')" />
      </td>
    </tr>
  ';
  
} elseif ($opcion == '2') {
    // Para Ente: se muestra el campo y el input para ingresar la cédula.
    echo '
      <tr>
        <td class="tdtitulos">Rif/Cédula del Ente:</td>
        <td class="tdcampos">
          <input type="text" id="cedula_contratante" class="campos" placeholder="Ingrese la cédula/rif" value="" onblur="verificarDocumento(this.value, \'rif\', \'' . $cedulaTitular . '\', \'' . $opcion . '\')">
        </td>
      </tr>
    ';
} elseif ($opcion == '3') {
    // Para Persona: se muestra el campo y el input para ingresar la cédula.
    echo '
      <tr>
        <td class="tdtitulos">Cédula de la Persona:</td>
        <td class="tdcampos">
          <input type="text" id="cedula_contratante" class="campos" placeholder="Ingrese la cédula" value="" onblur="verificarDocumento(this.value, \'cedula\', \'' . $cedulaTitular . '\', \'' . $opcion . '\')">
        </td>
      </tr>
    ';
}

?>