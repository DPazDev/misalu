<?php
header("Content-Type: text/html;charset=utf-8");
/* Nombre del Archivo: reporte_paraente.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: por Parámetros (para Entes)
*/  


 include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];

   $tipocliente=$_REQUEST['tipcliente'];


list($id_sucursal,$sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal==0)	        $condicion_sucursal="and admin.id_sucursal>0";
else
$condicion_sucursal="and admin.id_sucursal='$id_sucursal'";



list($id_servicio,$servicio)=explode("@",$_REQUEST['servic']);
if($id_servicio==0)	        $condicion_servicio="and gastos_t_b.id_servicio>0";
	else if($id_servicio=="-01"){
	$condicion_servicio="and (gastos_t_b.id_servicio!=6 and gastos_t_b.id_servicio!=9 ) ";}
	else if($id_servicio=="-02"){
	$condicion_servicio="and (gastos_t_b.id_servicio=6 or gastos_t_b.id_servicio=9 ) ";}
else
$condicion_servicio="and gastos_t_b.id_servicio='$id_servicio'";


list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

list($id_ente,$ente)=explode("@",$_REQUEST['ente']);


if($id_ente==0)	        $condicion_ente="and entes.id_tipo_ente>0";
	
	else
	$condicion_ente="and entes.id_ente='$id_ente'";

if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}
	
	


list($id_estado,$estado)=explode("@",$_REQUEST['estapro']);
if($id_estado==0)	        $condicion_estado="and procesos.id_estado_proceso>0";

else if($id_estado=="-01"){
	$condicion_estado="and (procesos.id_estado_proceso!=1 and procesos.id_estado_proceso!=6 and procesos.id_estado_proceso!=13 and procesos.id_estado_proceso!=14 ) ";}


else if($id_estado=="-02"){
	$condicion_estado="and (procesos.id_estado_proceso=7 or procesos.id_estado_proceso=16  ) ";}

else
$condicion_estado="and procesos.id_estado_proceso='$id_estado'";


list($proveedor)=explode("@",$_REQUEST['proveedor']);
if($proveedor=="/"){
$condicion_proveedor="and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",s_p_proveedores,proveedores,personas_proveedores";}

else {
$condicion_proveedor="and proveedores.id_proveedor='$proveedor' and 
                      proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",proveedores";}

if($proveedor=='INTRAMURAL'){
$condicion_proveedor= "and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='1' and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",s_p_proveedores,proveedores";}

if($proveedor=='EXTRAMURAL'){
$condicion_proveedor= "and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
s_p_proveedores.nomina='0' and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",s_p_proveedores,proveedores";}

if($proveedor=='*'){
$condicion_proveedor="and clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=gastos_t_b.id_proveedor";
$prov=",clinicas_proveedores, proveedores";}

if($proveedor=='TODOS' )   { $condicion_proveedor="and gastos_t_b.id_proveedor>=0";
$prov="";}

if($proveedor=='NINGUNO' )  {  $condicion_proveedor="and gastos_t_b.id_proveedor=0";
$prov="";}


if($tipocliente=='TODOS'){
	//se busca titulares y beneficiarios (procesos.id_titular>0 and)
	$tc="and  
	     (procesos.id_beneficiario>0 or procesos.id_beneficiario=0) 
	     ";}
else if($tipocliente=='TITULAR'){
	//solo titulares (procesos.id_titular>0 and)
	$tc="and  
	      procesos.id_beneficiario=0";}
else if($tipocliente=='BENEFICIARIO'){
	//solo beneficiarios (procesos.id_titular>0 and)
	$tc="and  
	      procesos.id_beneficiario>0";}






$codigo=time();
   $qreporte=("create table para_entes_tmp_$codigo AS select
procesos.id_proceso,
procesos.nu_planilla,
procesos.id_titular,
procesos.id_beneficiario,
procesos.fecha_recibido, 
procesos.id_admin,
procesos.id_estado_proceso,
procesos.hora_creado,
gastos_t_b.id_proveedor,
gastos_t_b.id_servicio,
titulares.id_ente,
admin.nombres,
admin.apellidos,
clientes.id_cliente,
clientes.sexo,

gastos_t_b.monto_aceptado,
gastos_t_b.monto_reserva,
gastos_t_b.id_gasto_t_b,
(clientes.nombres) AS n,
(clientes.apellidos) AS a,
clientes.cedula,
clientes.fecha_nacimiento,
entes.nombre,
estados_procesos.estado_proceso,
gastos_t_b.nombre AS nom
from procesos,gastos_t_b,admin,titulares,clientes,entes,estados_procesos $prov
where 
procesos.fecha_recibido between '$fecre1' and '$fecre2' and 
gastos_t_b.id_proceso=procesos.id_proceso $condicion_estado $tc and
 admin.id_admin=procesos.id_admin $condicion_sucursal $condicion_servicio and
procesos.id_titular=titulares.id_titular $condicion_ente $tipo_entes and titulares.id_ente=entes.id_ente $condicion_proveedor and
titulares.id_cliente=clientes.id_cliente and procesos.id_estado_proceso=estados_procesos.id_estado_proceso 
ORDER BY procesos.id_proceso DESC");




/* **** Si registra el nombre de la tabla creada en la tabla tbl_eliminar_tabla por si el proceso no se termina de ejucutar desde el sistema en seguridad ejecutar auditar tablas temporada creadas y eliminar las que hayan quedado **** */

$r_tbl_tabla_eli="insert 
									into 
							tbl_eliminar_tablas 
									(nombre_tbl_eli) 
							values 
									('table para_entes_tmp_$codigo');";
$f_tbl_tabla_eli=ejecutar($r_tbl_tabla_eli);


/*echo $qreporte;




/*if($id_servicio="-01"){
$rreporte=ejecutar($qreporte);
$qreporte1=("select para_entes_tmp_$codigo.id_proceso,para_entes_tmp_$codigo.nu_planilla,para_entes_tmp_$codigo.id_beneficiario,para_entes_tmp_$codigo.fecha_recibido,para_entes_tmp_$codigo.nombre,para_entes_tmp_$codigo.id_proveedor,para_entes_tmp_$codigo.n,para_entes_tmp_$codigo.a,para_entes_tmp_$codigo.cedula,para_entes_tmp_$codigo.id_estado_proceso,para_entes_tmp_$codigo.fecha_nacimiento,para_entes_tmp_$codigo.sexo,para_entes_tmp_$codigo.hora_creado,para_entes_tmp_$codigo.fecha_cita,para_entes_tmp_$codigo.h,count(para_entes_tmp_$codigo.nu_planilla) from para_entes_tmp_$codigo group by para_entes_tmp_$codigo.id_proceso,para_entes_tmp_$codigo.nu_planilla,para_entes_tmp_$codigo.id_beneficiario,para_entes_tmp_$codigo.fecha_recibido,para_entes_tmp_$codigo.nombre,para_entes_tmp_$codigo.id_proveedor,para_entes_tmp_$codigo.n,para_entes_tmp_$codigo.a,para_entes_tmp_$codigo.cedula,para_entes_tmp_$codigo.id_estado_proceso,para_entes_tmp_$codigo.fecha_nacimiento,para_entes_tmp_$codigo.sexo,para_entes_tmp_$codigo.hora_creado,para_entes_tmp_$codigo.fecha_cita,para_entes_tmp_$codigo.h ORDER BY para_entes_tmp_$codigo.nu_planilla DESC");
$rreporte1=ejecutar($qreporte1);

}
else  {*/
$rreporte=ejecutar($qreporte);

$qreporte1=("select para_entes_tmp_$codigo.id_proceso,
para_entes_tmp_$codigo.id_beneficiario,
para_entes_tmp_$codigo.fecha_recibido,
para_entes_tmp_$codigo.nombre,
para_entes_tmp_$codigo.id_proveedor,
para_entes_tmp_$codigo.n,
para_entes_tmp_$codigo.a,
para_entes_tmp_$codigo.cedula,
para_entes_tmp_$codigo.id_estado_proceso,
para_entes_tmp_$codigo.fecha_nacimiento,
para_entes_tmp_$codigo.estado_proceso,
para_entes_tmp_$codigo.sexo,
para_entes_tmp_$codigo.id_servicio,
para_entes_tmp_$codigo.nombres,
para_entes_tmp_$codigo.apellidos,
para_entes_tmp_$codigo.id_cliente,
count(para_entes_tmp_$codigo.id_proceso) 
from para_entes_tmp_$codigo group by 
para_entes_tmp_$codigo.id_proceso,
para_entes_tmp_$codigo.id_beneficiario,
para_entes_tmp_$codigo.fecha_recibido,
para_entes_tmp_$codigo.nombre,
para_entes_tmp_$codigo.id_proveedor,
para_entes_tmp_$codigo.n,
para_entes_tmp_$codigo.a,
para_entes_tmp_$codigo.cedula,
para_entes_tmp_$codigo.id_estado_proceso,
para_entes_tmp_$codigo.fecha_nacimiento,para_entes_tmp_$codigo.estado_proceso,
para_entes_tmp_$codigo.sexo,para_entes_tmp_$codigo.id_servicio,para_entes_tmp_$codigo.nombres,para_entes_tmp_$codigo.apellidos,para_entes_tmp_$codigo.id_cliente
ORDER BY para_entes_tmp_$codigo.id_proceso DESC");
$rreporte1=ejecutar($qreporte1);






?>


<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="24">Reporte Relaci&oacute;n <?php if($id_servicio==0) echo "TODOS LOS SERVICIOS";
else echo "$servicio";?>, <?php if ($tipocliente=="TODOS") echo "TODOS LOS CLIENTES";
				else if($tipocliente=="TITULAR") echo "TITULARES";
				else if($tipocliente=="BENEFICIARIO") echo "BENEFICIARIOS";?> en estado <?php if($id_estado==0) echo"TODOS LOS ESTADOS";
else echo "$estado";?>, <?php
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente ";  ?></td>     
        </tr>
</table>



<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=0> 

 
	<tr> 
		<td class="tdtitulosd" colspan=13>Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
	
	<tr> 
		<td class="tdcamposc" colspan=13><?php echo $sucursal?></td>
	</tr>
</table>


<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=0> 
	<tr> 
	   	<td class="tdcampos">ORDEN</td>   	   
<?php 
	if($tipocliente=='TITULAR' or $tipocliente=='BENEFICIARIO'or $tipocliente=='TODOS'){?>
	
		<td class="tdcampos">TITULAR</td>
	        <td class="tdcampos">CEDULA TITULAR</td> 
	        <td class="tdcampos">ID TITULAR</td> 
	       
<?php }

	if($tipocliente=='BENEFICIARIO' or $tipocliente=='TODOS') { ?> 
		<td class="tdcampos">BENEFICIARIO</td>
	        <td class="tdcampos">CEDULA BENEFICIARIO</td>
	        <td class="tdcampos">ID BENEFICIARIO</td>		
	   
<?php } 
	if($tipocliente=='TITULAR' or $tipocliente=='BENEFICIARIO' or $tipocliente=='TODOS') { ?>
		<td class="tdcampos">FECHA RECIBIDO</td> 
		<td class="tdcampos">ENTE</td>        
		<td class="tdcampos">ESTADO PROCESO</td> 
		<td class="tdcampos">ANALISTA</td> 
        	<td class="tdcampos">PROVEEDOR</td>               
		<td class="tdcampos">DIAGNOSTICO</td>
		<td class="tdcampos">MONTO ACEPTADO</td>  

	</tr>
<?php }


 $i=1;
		  $bsf=0; 
		     $fsexot='';


	     while($freporte1=asignar_a($rreporte1,NULL,PGSQL_ASSOC))
		{
$i++;

		     $fcedula="";
		     $fbenf="";  
	             $fcedulab="";
		     $fidb="";
		     $fpropersona="";
		     $fproclinica="";
		     $fsexot="";
		     $fsexob="";
		     $ffecht="";
		     $ffechb="";
$rem="";
			   
				if ($freporte1[id_beneficiario]>0){
				  $rbenf=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.id_cliente,clientes.fecha_nacimiento,clientes.sexo from clientes,beneficiarios where beneficiarios.id_beneficiario='$freporte1[id_beneficiario]' and beneficiarios.id_cliente=clientes.id_cliente;");
			
				  $qbenf=ejecutar($rbenf);
				  $databenf=asignar_a($qbenf);
				  $fbenf="$databenf[nombres] $databenf[apellidos]";  
				  $fcedulab="$databenf[cedula]";
				  $fidb="$databenf[id_cliente]";
				  $fsexob="$databenf[sexo]";
				  $ffechb="$databenf[fecha_nacimiento]";
			  }else{$fbenf='';}

if($fsexot==1){
	$sexot="MASCULINO";
	}
 	else 
	{
	$sexot="FEMENINO";
	}

	if($fsexob==1){
	$sexob="MASCULINO";
	}
 	if($fsexob==0) 
	{
	$sexob="FEMENINO";
	}

/*$qestado_pro=("select estados_procesos.estado_proceso from estados_procesos where estados_procesos.id_estado_proceso='$freporte1[id_estado_proceso]'");
$restado_pro=ejecutar($qestado_pro);
$dataestado=asignar_a($restado_pro);
$festado_pro="$dataestado[estado_proceso]";*/


$qpropersona=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov from personas_proveedores,s_p_proveedores,proveedores where proveedores.id_proveedor='$freporte1[id_proveedor]' and
s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor");
$rpropersona=ejecutar($qpropersona);
$dataproper=asignar_a($rpropersona);
$fpropersona="$dataproper[nombres_prov] $dataproper[apellidos_prov]";

$qproclinica=("select clinicas_proveedores.nombre from clinicas_proveedores,proveedores where proveedores.id_proveedor='$freporte1[id_proveedor]' and clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor");
$rproclinica=ejecutar($qproclinica);
$dataprocli=asignar_a($rproclinica);
$fproclinica="$dataprocli[nombre]";


if($freporte1[id_servicio]=='1'){
	$rem="REEMBOLSO";}


 echo"    <tr>
		<td class=\"tdtituloss\">$freporte1[id_proceso] </td>";
	if($tipocliente=='TITULAR' or $tipocliente=='BENEFICIARIO' or $tipocliente=='TODOS'){
 echo"
		<td class=\"tdtituloss\">$freporte1[n] $freporte1[a]</td>   
		<td class=\"tdtituloss\">$freporte1[cedula]</td>
		<td class=\"tdtituloss\">$freporte1[id_cliente]</td>";
}

	if($tipocliente=='BENEFICIARIO' or $tipocliente=='TODOS') { 
 echo "
		<td class=\"tdtituloss\">$fbenf</td> 
		<td class=\"tdtituloss\">$fcedulab</td>
		<td class=\"tdtituloss\">$fidb</td>";
}
if($tipocliente=='TITULAR' or $tipocliente=='BENEFICIARIO' or $tipocliente=='TODOS') {
 echo "
		<td class=\"tdtituloss\">$freporte1[fecha_recibido]</td>
		<td class=\"tdtituloss\">$freporte1[nombre]</td>
		<td class=\"tdtituloss\">$freporte1[estado_proceso]</td>
		<td class=\"tdtituloss\">$freporte1[nombres] $freporte1[apellidos] </td>
		<td class=\"tdtituloss\">$fpropersona $fproclinica $rem</td>
";




$qreportegasto=("select para_entes_tmp_$codigo.monto_aceptado,para_entes_tmp_$codigo.monto_reserva,para_entes_tmp_$codigo.nom from para_entes_tmp_$codigo where para_entes_tmp_$codigo.id_proceso='$freporte1[id_proceso]'");
$rqreportegasto=ejecutar($qreportegasto);

while($frqreportegasto=asignar_a($rqreportegasto,NULL,PGSQL_ASSOC))
{		
$bsf= $bsf+($frqreportegasto[monto_aceptado]);
$bsfr= $bsf+($frqreportegasto[monto_reserva]); 
?>
<tr>
<?php
 if($tipocliente=='BENEFICIARIO' or $tipocliente=='TODOS') {

			
echo "		<td class=\"tdtituloss\" colspan=12></td>";}
else {
echo"		<td class=\"tdtituloss\" colspan=9></td>";}

echo "			
	<td class=\"tdtituloss\">$frqreportegasto[nom]</td>

	<td colspan=2 class=\"tdtituloss\">".montos_print($frqreportegasto[monto_reserva])."</td>   

	        </tr>";


$bsf1= $bsf1+($frqreportegasto[monto_aceptado]);	
$bsf2= $bsf2+($frqreportegasto[monto_reserva]);

}                             

} 



?>
<tr><td colspan=14><hr></td></tr>
<?php

}

?>
<tr><td colspan=14>&nbsp;</td></tr>

	   <tr>
	        <td colspan=6 class="tdtituloss" >&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo $i-1; ?> Ordenes </td>

<?php if($tipocliente=='TITULAR'){?>
	        <td colspan=3 class="tdtitulosd" >Total Bs.S.&nbsp;</td>
	        <td colspan=3 class="tdtituloss">&nbsp;&nbsp; <?php echo montos_print($bsf1); ?>&nbsp;&nbsp;</td>

<?php }
else { ?>
		<td colspan=7 class="tdtitulosd" >Total Bs.S.&nbsp;&nbsp;&nbsp;</td>
	        <td colspan=6 class="tdtituloss"> <?php echo montos_print($bsf1); ?></td>

<?php }


$eli_tab_tem=("drop table para_entes_tmp_$codigo");
$reli_tab_tem=ejecutar($eli_tab_tem);

?>  

<tr><td colspan=14>&nbsp;</td></tr>
</table>
<table>
<tr><td colspan=14>&nbsp;</td></tr>

<tr>
	        <td colspan=11 class="tdcamposs" title="Imprimir reporte">
			  <?php
			$url="'views06/ireporte_paraente.php?sucur=$id_sucursal@$sucursal&servic=$id_servicio@$servicio&ente=$id_ente@$ente&tipo_ente=$tipo_ente@$nom_tipo_ente&estapro=$id_estado@$estado_proceso&lgnue=$replogo&fecha1=$fecre1&fecha2=$fecre2&tipcliente=$tipocliente&proveedor=$proveedor'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
<? $url="'views06/excel_paraente.php?sucur=$id_sucursal@$sucursal&servic=$id_servicio@$servicio&ente=$id_ente@$ente&tipo_ente=$tipo_ente@$nom_tipo_ente&estapro=$id_estado@$estado_proceso&lgnue=$replogo&fecha1=$fecre1&fecha2=$fecre2&tipcliente=$tipocliente&proveedor=$proveedor'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a>
<? $url="'views06/excel_paraente1.php?sucur=$id_sucursal@$sucursal&servic=$id_servicio@$servicio&ente=$id_ente@$ente&tipo_ente=$tipo_ente@$nom_tipo_ente&estapro=$id_estado@$estado_proceso&lgnue=$replogo&fecha1=$fecre1&fecha2=$fecre2&tipcliente=$tipocliente&proveedor=$proveedor'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel 2</a>
			</td>


     </tr> 
<tr><td colspan=14>&nbsp;</td></tr>
</table>






