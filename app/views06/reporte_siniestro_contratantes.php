<?php
/* Nombre del Archivo: reporte_siniestro_contratantes.php
   Descripción: Generar reporte para ver los siniestros según contratantes 
*/

include ("../../lib/jfunciones.php");
sesion();



$sql_servicios = 'SELECT servicios.id_servicio, servicios.servicio FROM servicios ORDER BY servicio';
$res_servicios = ejecutar($sql_servicios);

$sql_procesos = "SELECT id_estado_proceso, estado_proceso FROM estados_procesos ORDER BY estado_proceso";
$res_procesos = ejecutar($sql_procesos);

?>

<table class="tabla_cabecera3" cellpadding="0" cellspacing="0">
    <tr>
        <td colspan=4 class="titulo_seccion">Reporte Siniestros por Contratante</td>
    </tr>
</table>

    <table class="tabla_cabecera3" cellpadding="0" cellspacing="0">

        <tr>
            <td class="tdtitulos" colspan="1">Tipo de Contratante:</td>
            <td class="tdcampos" colspan="1">
                <select id="contratanteSelect" name="contratante" class="campos" style="width: 210px;" onchange="verContratante2(this.value)">
                <option value="" selected>Seleccione una Opción</option>
                <option value="2">Ente</option>
                <option value="3">Persona</option>
                </select>
            </td>
            <td class="tdtitulos" colspan="1">* Tipo de Servicio</td>
            <td class="tdcampos" colspan="1">
                <select name="servicio" id="servicio" class="campos" style="width: 210px;">
                    <option value="" disabled>Seleccione un Servicio</option>
                    <option value="0" selected>TODOS LOS SERVICIOS</option>

                    <?php while($servicios = asignar_a($res_servicios, NULL, PGSQL_ASSOC)) :
                        echo "<option value='$servicios[id_servicio]'> $servicios[servicio] </option>";
                    endwhile; ?>

                </select>
            </td>
        </tr>

        <tr> <td><br></td> </tr>
        <!-- Contenedor para actualizar el contenido según la opción del contratante -->
        <tr id="contenidocontratante2"></tr>
        <!-- Contenedor para mostrar la verificación del documento en caso de no ser válida-->
        <tr id="verificacionDocumento"></tr>


        <tr> <td><br></td> </tr>
        <tr> <td><br></td> </tr>
        <tr>
            <td class="tdtitulos" colspan="1">* Estado del Proceso:</td>
            <td class="tdcampos" colspan="1">
                <select name="proceso" id="proceso" class="campos" style="width: 210px;">
                    <option value="" disabled>Seleccione un Estado</option>
                    <option value="0" selected>TODOS LOS ESTADOS</option>

                    <?php while($procesos = asignar_a($res_procesos, NULL, PGSQL_ASSOC)) :
                        echo "<option value='$procesos[id_estado_proceso]'> $procesos[estado_proceso] </option>";
                    endwhile; ?>

                </select>
            </td>

            <td class="tdtitulos" colspan="1">* Tipo de Cliente</td>
            <td class="tdcampos" colspan="1">
                <select name="tipo_cliente" id="tipo_cliente" class="campos" style="width: 210px;">
                    <option value="" disabled>Seleccione una Opción</option>
                    <option value="0" selected>Todos</option>
                    <option value="1">Titulares</option>
                    <option value="2">Beneficiarios</option>
                </select>
            </td>
        </tr>

        <tr> <td><br></td> </tr>
        <tr> <td><br></td> </tr>

        <tr>

            <td class="tdtitulos" colspan="1">Fecha de la Orden</td>
            <td class="tdcampos" colspan="1">
                <select id="fecha_seleccionada" name="fecha_seleccionada" class="campos" style="width: 210px;" onchange="tipos_fechas(this.value)">
                    <option value="" disabled>Seleccione una Opción</option>
                    <option value="0" selected>Cualquier Fecha</option>
                    <option value="1">Seleccionar un Rango de Fechas</option>
                </select>
            </td>

        </tr>

        <tr> <td><br></td> </tr>

        <tr id="contenidoFechas"></tr>
            
        <tr> <td><br></td> </tr>

        <tr>
            <td colspan=4 class="tdcamposcc"><a href="#" OnClick="imp_siniestro_contratantes();" class="boton">Generar Reporte</a>
        </tr>

    </table>

    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 

