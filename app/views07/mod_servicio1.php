<?
/*  Nombre del Archivo: mod_servicio1.php
   Descripción: Busqueda en base de datos para MODIFICAR el servicio en una orden especifica
*/

	include ("../../lib/jfunciones.php");
	sesion();   
	$orden=$_REQUEST['orden'];

	$q_cambioser=("select gastos_t_b.id_proceso,gastos_t_b.id_gasto_t_b, gastos_t_b.id_servicio, gastos_t_b.id_tipo_servicio from gastos_t_b where gastos_t_b.id_proceso='$orden'");
	$r_cambioser=ejecutar($q_cambioser);
	$f_cambioser=asignar_a($r_cambioser);


	$q_serv=("select servicios.id_servicio, servicios.servicio from servicios order by servicios.servicio  ");
	$r_serv=ejecutar($q_serv);


	$q_tip_serv=("select tipos_servicios.tipo_servicio from tipos_servicios where tipos_servicios.id_tipo_servicio=$f_cambioser[id_tipo_servicio] ");
	$r_tip_serv=ejecutar($q_tip_serv);
	$f_tip_serv=asignar_a($r_tip_serv);




/*echo $f_cambioser['id_servicio'];
echo "-------";
echo $f_cambioser['id_tipo_servicio'];*/






?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr>
		<td colspan=4 class="titulo_seccion"> Modificar Servicio en Orden </td>
	</tr>	

	<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp;  Servicio en Orden</td>

		<td colspan=1><select name="ser" id="ser" class="campos" onchange="$('proser').hide(),tiposervicio(); return false;"  style="width:210px;">
		<option value="">-- Servicio --</opcion>
		<?php 
		while($f_serv=asignar_a($r_serv)){
			if($f_serv['id_servicio']==$f_cambioser['id_servicio'])
			echo "<option value=\"".$f_serv['id_servicio']."\"selected >".$f_serv['servicio']."</option>";
			else
			echo "<option value=".$f_serv['id_servicio'].">".$f_serv['servicio']."</opcion>";
}?> 
		</select>
	</td>
			<input type="hidden" name="orden" value="<?php echo $f_cambioser['id_proceso'];?>">

	 	 <td class="tdtitulos" colspan="1">Tipo de Servicio:</td>   
        	 <td class="tdcampos" colspan="1"><div id="proser"><select disabled="disabled" class="campos" style="width: 210px;" >

	                               <option value="0"><?echo $f_tip_serv['tipo_servicio']?></option>

				       </select>
</div> <div id="tipser"></div></td>
	</tr>

	<tr><td>&nbsp;</td></tr>


	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_servicio();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

	<tr><td>&nbsp;</td></tr>

</table>
<div id="guardar_servicio"></div>







