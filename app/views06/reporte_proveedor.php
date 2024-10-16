<?php
header("Content-Type: text/html;charset=utf-8");
/* *** Nombre del Archivo: reporte_proveedor.php 
       Descripción: Realiza la busqueda en la Base de Datos, para el Reporte de Proveedores *** */

	include ("../../lib/jfunciones.php");
	sesion();

list($proveedor)=explode("@",$_REQUEST['proveedor']);
if($proveedor=="/") {
$w="personas_proveedores.*,s_p_proveedores.*,especialidades_medicas.especialidad_medica";
$condicion_proveedor="s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica and s_p_proveedores.activar='1'";
$prov="s_p_proveedores, proveedores, personas_proveedores,especialidades_medicas";
$b=" order by personas_proveedores.nombres_prov";
}

else if($proveedor=="INTRAMURAL"){
$w="personas_proveedores.*,s_p_proveedores.*,especialidades_medicas.especialidad_medica";
$condicion_proveedor= "s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='1' and s_p_proveedores.activar='1' and personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica";
$prov="s_p_proveedores, proveedores,personas_proveedores,especialidades_medicas";
$b=" order by personas_proveedores.nombres_prov";
}

else if($proveedor=="EXTRAMURAL"){
$w="personas_proveedores.*,s_p_proveedores.*,especialidades_medicas.especialidad_medica";
$condicion_proveedor= "s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='0' and s_p_proveedores.activar='1' and personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica";
$prov="s_p_proveedores, proveedores, personas_proveedores,especialidades_medicas";
$b=" order by personas_proveedores.nombres_prov";}


else if($proveedor=='*')  {
$w="clinicas_proveedores.*";
$condicion_proveedor="clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and clinicas_proveedores.activar='1'";
$prov="clinicas_proveedores, proveedores";
$d="order by clinicas_proveedores.nombre";}


else if($proveedor=='TODOS' )  {  $condicion_proveedor="";
$prov="proveedores";
}

else  {

$q_pro=("select proveedores.id_s_p_proveedor, proveedores.id_clinica_proveedor 
         from proveedores where proveedores.id_proveedor='$proveedor'");
$r_pro=ejecutar($q_pro);
$f_pro=asignar_a($r_pro);

if($f_pro[id_s_p_proveedor]>0){
$w="personas_proveedores.*,s_p_proveedores.*,especialidades_medicas.especialidad_medica";
$condicion_proveedor="proveedores.id_proveedor='$proveedor' and s_p_proveedores.activar='1' and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica";
$prov="s_p_proveedores, proveedores,personas_proveedores,especialidades_medicas";
}



else if($f_pro[id_clinica_proveedor]>0){
$w="clinicas_proveedores.*";
$condicion_proveedor="proveedores.id_proveedor='$proveedor' and                       clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and clinicas_proveedores.activar='1'";
$prov="clinicas_proveedores,proveedores";

}
}
?>

<table class="tabla_citas" cellpadding=0 cellspacing=0 > 

<tr>
	<td class="titulo_seccion">Reporte Proveedores</td>     
</tr>
</table>

<table class=tabla_citas"  cellpadding=0 cellspacing=0 rules="rows">
<tr><td>&nbsp;</td></tr>
<tr>
	<td colspan=1 class="tdcampos">NOMBRE Y APELLIDO</td>
	<td colspan=1 class="tdcampos">CEDULA / RIF</td>
	<td colspan=1 class="tdcampos">ESPECIALIDAD</td>
	<td colspan=1 class="tdcampos">TELEFONOS</td>
	<td colspan=1 class="tdcampos">DIRECCION</td>

</tr>

<?php	
$q_infor=("select $w from $prov where $condicion_proveedor  $b $d");
$r_infor=ejecutar($q_infor);
 while($f_infor=asignar_a($r_infor,NULL,PGSQL_ASSOC)){
echo"
	<tr> 
		<td class=\"tdtituloss\">$f_infor[nombres_prov] $f_infor[apellidos_prov] $f_infor[nombre]</td> 
		<td class=\"tdtituloss\">$f_infor[cedula_prov] $f_infor[rif]</td>
		<td class=\"tdtituloss\">$f_infor[especialidad_medica]</td>
		<td class=\"tdtituloss\">$f_infor[celular_prov] -- $f_infor[telefonos_prov] $f_infor[telefonos]</td>
		<td class=\"tdtituloss\">$f_infor[direccion_prov] $f_infor[direccion]</td>

  
	</tr>"; }?>
</table>

<br>
<table>
<tr>
	        <td  class="tdcamposs" title="Imprimir reporte">
			  <?php
			$url="'views06/ireporte_proveedor.php?proveedor=$proveedor'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>

<?php
		$url="'views06/excel_proveedor.php?proveedor=$proveedor'";

			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a>
		</td>
</tr>

<tr><td>&nbsp;</td></tr>
<br>

</table>
