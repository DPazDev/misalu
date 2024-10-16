<?php
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
$q_dependencia = "select * from tbl_dependencias where tbl_dependencias.dependencia = 'MEDICAMENTOS AMBULATORIO MERIDA' OR 
  tbl_dependencias.dependencia = 'ALMACEN TEMPORAL MERIDA'  order by tbl_dependencias.dependencia";
$r_dependencia = ejecutar($q_dependencia);
$q_tipo_insumo = "select * from tbl_tipos_insumos  where tipo_insumo='MEDICAMENTO' or tipo_insumo='SUMINISTRO HOSPITALARIO'  order by tipo_insumo";
$r_tipo_insumo = ejecutar($q_tipo_insumo);
$q_laboratorio = "select * from tbl_laboratorios  order by laboratorio";
$r_laboratorio = ejecutar($q_laboratorio);

?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0 >
	<tr align="center">
		<td colspan="3" class="titulo_seccion">Reporte de medicamentos o insumo proximos a vencer</td>
	</tr>
	
	<tr align="center">
		<td class="tdtitulos">
			Seleccione una Dependencia<br> 
			<select class="campos" id="dependencia" name="dependencia">
			<option value="null">DEPENDENCIA</option>
		<?php
		while($f_dependencia = asignar_a($r_dependencia)){
			echo "<option value=\"$f_dependencia[id_dependencia]\">$f_dependencia[dependencia]</option>";
		}
		?>
			</select>
		
		</td>
	
		<td class="tdtitulos">Seleccione el Tipo de Insumo<br>
		
		<select class="campos" id="tipo_insumo" name="tipo_insumo">
		<option value="null">TIPO</option>		
                <?php
                while($f_tipo_insumo = asignar_a($r_tipo_insumo)){
                echo "<option value=\"$f_tipo_insumo[id_tipo_insumo]\">$f_tipo_insumo[tipo_insumo]</option>";
                }
                ?>
			</select>		
		</td>
	
		<td class="tdtitulos">
		proximos a vencerce menor de:<br>
		
		<select class="campos" id="rango" name="rango">
				<option value="null">SELECCINE</option>
              <option value="1">1 MES</option>
              <option value="2">2 MESES</option>
              <option value="3">3 MESES</option>
              <option value="4">4 MESES</option>
      </select>		
		</td>
	</tr>
	
	
<tr align="center">
<td align="center" class="tdcampos" colspan="3" title="Buscar insumos o medicamentos proximos a Vencer">
 <div class="tdtitulos">Forzar mes completo</div>
 Si<input type="radio" name="fz" id="fz1" value="true"> 
 No<input type="radio" name="fz" id="fz2" value="false" CHECKED> 	


<center><label class="boton" style="cursor:pointer" onclick="fvencimiento_buscar()" >Buscar</label> 
<label id="imprimirxvencer" class="boton" style="cursor:pointer;display:none" onclick="impr_xvencer()" >Imprimir</label>
</center></td>

</tr>	
	
	
</table>
<img alt="spinner" id="spinnerARTI" src="../public/images/esperar.gif" style="display:none;" /> 
<div id="vencidos"></div>