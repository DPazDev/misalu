<?php

$tipoFecha = $_POST['tipoFecha'];
// tipoFecha = 0: Todas las fechas
// tipoFecha = 1: Rango de Fechas


if ($tipoFecha == '0') {
    echo '
        <input type="hidden" id="fecha_seleccionada" name="fecha_seleccionada" value="0">
    ';
} elseif ($tipoFecha == '1') {
    echo '
        <td colspan=2 class="tdtitulos">* Seleccione Fecha Inicio:
            <input readonly type="text" size="10" id="dateField1" name="fecha1" class="campos" maxlength="10" >
            <a href="javascript:void(0);" onclick="g_Calendar.show(event, \'dateField1\', \'yyyy-mm-dd\')" title="Show popup calendar">
            <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>

            <td colspan=2 class="tdtitulos">* Seleccione Fecha Final:
            <input readonly type="text" size="10" id="dateField2" name="fecha2" class="campos" maxlength="10">
            <a href="javascript:void(0);" onclick="g_Calendar.show(event, \'dateField2\', \'yyyy-mm-dd\')" title="Show popup calendar">
            <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
        </td>
        <input type="hidden" id="fecha_seleccionada" name="fecha_seleccionada" value="1">
    ';
}