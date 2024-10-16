<?php
/* Nombre del Archivo: reporte_consultas_medicas_prov.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Morbilidad de los Proveedores Persona y Clinicas
*/  

header("Content-Type: text/html;charset=utf-8");

 include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];

list($id_sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal==0)	        $condicion_sucursal="";
else
$condicion_sucursal="and admin.id_sucursal='$id_sucursal'";

list($id_servicio)=explode("@",$_REQUEST['serv']);
if($id_servicio==0)	        $condicion_servicio="and (gastos_t_b.id_servicio=2 or gastos_t_b.id_servicio=3 or gastos_t_b.id_servicio=4 or gastos_t_b.id_servicio=8 or gastos_t_b.id_servicio=11 or gastos_t_b.id_servicio=14)";
else
$condicion_servicio="and gastos_t_b.id_servicio='$id_servicio'";

list($id_estado)=explode("@",$_REQUEST['edo']);
if($id_estado==0)	        $condicion_estado="and (procesos.id_estado_proceso=2 or procesos.id_estado_proceso=7)";
else
$condicion_estado="and procesos.id_estado_proceso='$id_estado'";


list($proveedor)=explode("@",$_REQUEST['proveedor']);
if($proveedor=="/") {
$condicion_proveedor="and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor";
$prov="s_p_proveedores, proveedores, personas_proveedores";
$a=", personas_proveedores.nombres_prov";
$b=" order by personas_proveedores.nombres_prov";
$condicion_fecha="and procesos.fecha_recibido between '$fecre1' and '$fecre2'";}

else if($proveedor=="INTRAMURAL"){
$condicion_proveedor= "and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='1' and personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor";
$prov="s_p_proveedores, proveedores,personas_proveedores";
$a=", personas_proveedores.nombres_prov";
$b=" order by personas_proveedores.nombres_prov";
$condicion_fecha="and gastos_t_b.fecha_cita between '$fecre1' and '$fecre2'";}

else if($proveedor=="EXTRAMURAL"){
$condicion_proveedor= "and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='0' and personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor";
$prov="s_p_proveedores, proveedores, personas_proveedores";
$a=", personas_proveedores.nombres_prov";
$b=" order by personas_proveedores.nombres_prov";
$condicion_fecha="and procesos.fecha_recibido between '$fecre1' and '$fecre2'";} 

else if($proveedor=='*')  {
$condicion_proveedor="and clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor";
$prov="clinicas_proveedores, proveedores";
$c=",clinicas_proveedores.nombre";
$d="order by clinicas_proveedores.nombre";
$condicion_fecha="and procesos.fecha_recibido between '$fecre1' and '$fecre2'";}

else if($proveedor=='TODOS' )  {  $condicion_proveedor="";
$prov="proveedores";
$condicion_fecha="and procesos.fecha_recibido between '$fecre1' and '$fecre2'";}

else  {

$q_pro=("select proveedores.id_s_p_proveedor, proveedores.id_clinica_proveedor 
         from proveedores where proveedores.id_proveedor='$proveedor'");
$r_pro=ejecutar($q_pro);
$f_pro=asignar_a($r_pro);

if($f_pro[id_s_p_proveedor]>0){

$q_pper=("select s_p_proveedores.nomina from personas_proveedores,s_p_proveedores,
          proveedores where s_p_proveedores.id_s_p_proveedor=$f_pro[id_s_p_proveedor] and 
          personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor");
$r_pper=ejecutar($q_pper);
$f_pper=asignar_a($r_pper);

if($f_pper[nomina]=='1'){
$condicion_proveedor="and proveedores.id_proveedor='$proveedor'";
$prov="proveedores";
$condicion_fecha="and gastos_t_b.fecha_cita between '$fecre1' and '$fecre2'";}

if($f_pper[nomina]=='0'){
$condicion_proveedor="and proveedores.id_proveedor='$proveedor'";
$prov="proveedores";
$condicion_fecha="and procesos.fecha_recibido between '$fecre1' and '$fecre2'";}
}

else if($f_pro[id_clinica_proveedor]>0){

$condicion_proveedor="and proveedores.id_proveedor='$proveedor' and 
                      clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor";
$prov="clinicas_proveedores,proveedores";
$condicion_fecha="and procesos.fecha_recibido between '$fecre1' and '$fecre2'";
}


}


$q_servicio=("select servicios.id_servicio,servicios.servicio from servicios where servicios.id_servicio='$id_servicio'");
$r_servicio=ejecutar($q_servicio);
$f_servicio=asignar_a($r_servicio);

$q_sucur=("select sucursales.id_sucursal, sucursales.sucursal from sucursales where sucursales.id_sucursal='$id_sucursal'");
$r_sucur=ejecutar($q_sucur);
$f_sucur=asignar_a($r_sucur);

$q_edo=("select estados_procesos.id_estado_proceso, estados_procesos.estado_proceso from estados_procesos where estados_procesos.id_estado_proceso='$id_estado'");
$r_edo=ejecutar($q_edo);
$f_edo=asignar_a($r_edo);

?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="titulo_seccion" colspan="19">Consultas M&eacute;dicas Proveedores, Relaci&oacute;n <?php if ($id_servicio==0) echo "Todos los Servicios".","; else echo $f_servicio[servicio].",";?> Sucursal <?php if($id_sucursal==0) echo "Todas las Sucursales"; else echo $f_sucur[sucursal].",";?> Estado <?php if($id_estado==0) echo "Todos los Estados"; else echo $f_edo[estado_proceso];?> </td>
</tr>
</table>	
 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		<td class="tdtitulosd" colspan=19> Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
<br>
<table class="tabla_citas"  colspan=19 cellpadding=0 cellspacing=0 border=0> 
	<tr> 
	   	<td class="tdcampos" >M&Eacute;DICO </td>
	   	<td class="tdcampos" colspan=2> </td>
		<td class="tdcampos" colspan=5>DIRECCI&Oacute;N</td> 
		<td class="tdcampos" colspan=3>ESPECIALIDAD</td>
		<td class="tdcampos" colspan=3>TOTAL</td>
</tr>


<?php

$qreporte=("select gastos_t_b.id_proveedor,count(gastos_t_b.id_proveedor) $c $a
            from procesos,gastos_t_b, admin, $prov where
            gastos_t_b.id_proceso=procesos.id_proceso $condicion_fecha 
            $condicion_servicio and procesos.id_admin=admin.id_admin 
            $condicion_sucursal $condicion_estado  and 
            gastos_t_b.id_proveedor=proveedores.id_proveedor $condicion_proveedor 
            group by gastos_t_b.id_proveedor $c $a $b $d");

 $contador=0;
$rreporte=ejecutar($qreporte);

	     while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{


$q_proper=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov,
            s_p_proveedores.direccion_prov, s_p_proveedores.id_especialidad,
            especialidades_medicas.especialidad_medica 
            from personas_proveedores,s_p_proveedores,proveedores,
            especialidades_medicas where proveedores.id_proveedor='$freporte[id_proveedor]' and
            s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
            personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and 
            s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica");
$r_proper=ejecutar($q_proper);
$f_proper=asignar_a($r_proper);


$q_procli=("select clinicas_proveedores.nombre,clinicas_proveedores.direccion from clinicas_proveedores,proveedores where proveedores.id_proveedor='$freporte[id_proveedor]' and clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor");
$r_procli=ejecutar($q_procli);
$f_procli=asignar_a($r_procli);

?>
<tr>
<?php echo" 
	<td  colspan=3 class=\"tdtitulos\">$f_proper[nombres_prov] $f_proper[apellidos_prov] $f_procli[nombre]</td>
	<td colspan=5 class=\"tdtitulos\">$f_proper[direccion_prov] $f_procli[direccion]</td>
	<td colspan=3  class=\"tdtitulos\">$f_proper[especialidad_medica]</td>
	<td colspan=3  class=\"tdtitulos\">$freporte[count]</td>";?></tr>

      <?php  $contador=$contador+$freporte[count];}?>
<br>
<tr>
	<td  class=\"tdtitulos\">&nbsp;</td>
	<td  class=\"tdtitulos\">&nbsp;</td>
        <td colspan=2 class="tdcampos"> TOTAL CONSULTAS &nbsp;&nbsp;<?php echo $contador;?></td>
</tr>
</table>

<table>
<br>
<tr><td colspan=4>&nbsp;</td></tr>
<br> 
	<tr>
	        <td  colspan=2 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=3 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=3 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>
<tr><td colspan=4>&nbsp;</td></tr>

</table>
