<?php
include ("../../lib/jfunciones.php");
sesion();
$q_dependencia = "select * from tbl_dependencias order by tbl_dependencias.dependencia";
$r_dependencia = ejecutar($q_dependencia);
$q_tipo_insumo = "select * from tbl_tipos_insumos  order by tipo_insumo";
$r_tipo_insumo = ejecutar($q_tipo_insumo);
$q_laboratorio = "select * from tbl_laboratorios  order by laboratorio";
$r_laboratorio = ejecutar($q_laboratorio);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="inventario">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Reporte de Inventarios  </td>	</tr>	
<tr>
<td colspan=1 class="tdtitulos">* Seleccione la Fecha Inicio:   </td>
<td colspan=1 class="tdtitulos">
<input class="campos" type="hidden"  id="dateField1" name="fechainicio" maxlength=128 size=20 value="2011-01-01" >
2011-01-01
</td>
<td colspan=1 class="tdtitulos">* Seleccione la Fecha final:   </td>
<td colspan=1 class="tdtitulos">
<input readonly type="text" size="10" id="dateField2" name="fechafin" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>

                </td>
		</tr>

	<tr> 
            <td class="tdtitulos" colspan="1">Seleccione una Dependencia </td>
	        <td class="tdcampos" colspan="1" >
		<select class="campos" style="width: 200px;" id="dependencia" name="dependencia">
		<?php
		while($f_dependencia = asignar_a($r_dependencia)){
			echo "<option value=\"$f_dependencia[id_dependencia]\">$f_dependencia[dependencia]</option>";
		}
		?>
		</select>
		</td>
	</tr>


   <tr>
            <td class="tdtitulos" colspan="1">Seleccione el Tipo de Insumo </td>
        <td class="tdcampos" colspan="1" >
                <select class="campos"style="width: 200px;"  id="tipo_insumo" name="tipo_insumo">
                <?php
                while($f_tipo_insumo = asignar_a($r_tipo_insumo)){
                echo "<option value=\"$f_tipo_insumo[id_tipo_insumo]\">$f_tipo_insumo[tipo_insumo]</option>";
                }
                ?>
                </select>
                </td>
        </tr>
        <tr> 
          <td colspan="1" class="tdtitulos">Que Comiencen por la Letra</td>
                <td colspan="1" class="tdcampos" >
                <select name="letra" id="letra" class="campos">
                <option value="0">--Todas--</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
                <option value="I">I</option>
                <option value="J">J</option>
                <option value="K">K</option>
                <option value="L">L</option>
                <option value="M">M</option>
                <option value="N">N</option>
                <option value="Ñ">Ñ</option>
                <option value="O">O</option>
                <option value="P">P</option>
                <option value="Q">Q</option>
                <option value="R">R</option>
                <option value="S">S</option>
                <option value="T">T</option>
                <option value="U">U</option>
                <option value="V">V</option>
                <option value="W">W</option>
                <option value="X">X</option>
                <option value="Y">Y</option>
                <option value="Z">Z</option>
                </select>
		</td>
 <td class="tdcampos" title="Buscar Rep Inventarios">
<label class="boton" style="cursor:pointer" onclick="bus_inv_xcorte()" >Buscar</label> </td>
		 
		</tr>
</table>
<div id="bus_inv_xcorte"></div>
