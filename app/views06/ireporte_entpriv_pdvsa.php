<?php
header("Content-Type: text/html;charset=utf-8");
/* Nombre del Archivo: reporte_entpriv.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación Entes Privados
*/ 

   include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];
   $partida=$_REQUEST['partida'];   

list($repente)=explode("@",$_REQUEST['enpriv']);
if($repente=="-04"){
	$condicion_ente="and entes.id_tipo_ente='4' and titulares.id_ente=entes.id_ente";
    $yente="entes,titulares";
    $gente="entes.nombre";
}else{
    $condicion_ente="and titulares.id_ente=$repente and titulares.id_ente=entes.id_ente";
    $yente="titulares,entes"; 
    $gente="entes.nombre";
 }  
list($restpro)=explode("@",$_REQUEST['estapro']);  
 if($restpro==0)
    $condicion_restpro="";
else
    $condicion_restpro="and procesos.id_estado_proceso=$restpro";

list($id_sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal==0)
  $condicion_sucursal="";
else
$condicion_sucursal="and admin.id_sucursal=$id_sucursal";

list($id_servicio)=explode("@",$_REQUEST['servic']);
if($id_servicio==0)	       
  $condicion_servicio="and gastos_t_b.id_servicio>0";

else if($id_servicio=="-01"){
	$condicion_sevicio="and gastos_t_b.id_servicio=4 and gastos_t_b.id_servicio=6";
}
else
$condicion_servicio="and gastos_t_b.id_servicio=$id_servicio";



/*echo "******************* <br>";
echo $sucrepo."<br>"; 
echo "******************* <br>";
echo $fecre1."<br>"; 
echo "******************* <br>";
echo $fecre2."<br>"; 
echo "******************* <br>";
echo $repservi."<br>"; 
echo "******************* <br>";
echo $repente."<br>"; 
echo "******************* <br>";
echo $restpro."<br>"; 
echo "******************* <br>";*/


   $qreporte=("select
procesos.id_proceso,
procesos.id_titular,
procesos.id_beneficiario,
procesos.comentarios,
procesos.fecha_recibido, 
procesos.id_admin,
procesos.no_clave,
subdivisiones.subdivision,
servicios.servicio,
gastos_t_b.fecha_cita,
procesos.fecha_recibido,
tbl_partidas.tipo_partida,
count(gastos_t_b.id_proceso)
from 
  procesos,gastos_t_b,admin,subdivisiones,$yente,titulares_subdivisiones,tbl_partidas,servicios
where 
procesos.fecha_ent_pri between '$fecre1' and '$fecre2' and 
gastos_t_b.id_proceso=procesos.id_proceso  and
 admin.id_admin=procesos.id_admin  
$condicion_sucursal  $condicion_servicio $condicion_ente $condicion_restpro and 
procesos.id_titular=titulares.id_titular and 
titulares.id_titular=titulares_subdivisiones.id_titular and
titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and 
titulares.tipo_partida='$partida' and
servicios.id_servicio=gastos_t_b.id_servicio and
tbl_partidas.id_partida=titulares.tipo_partida

group by 
procesos.id_proceso,
procesos.id_titular,
procesos.id_beneficiario,
procesos.comentarios,
procesos.fecha_recibido,
procesos.id_admin,titulares.id_ente,
procesos.no_clave,
servicios.servicio,
gastos_t_b.fecha_cita,
procesos.fecha_recibido,
tbl_partidas.tipo_partida,
subdivisiones.subdivision,
$gente
ORDER BY procesos.no_clave DESC");
$rreporte=ejecutar($qreporte);


$qservicio=("select servicios.servicio from servicios where servicios.id_servicio=$id_servicio");
$rservicio=ejecutar($qservicio);
$dataservicio=asignar_a($rservicio);
$fservicio=$dataservicio[servicio];

$qestado=("select estados_procesos.estado_proceso from estados_procesos where estados_procesos.id_estado_proceso=$restpro");
$restado=ejecutar($qestado);
$dataestado=asignar_a($restado);
$festado=$dataestado[estado_proceso];

$qente=("select nombre from entes where id_ente=$repente");
$rente=ejecutar($qente);
$dataente=asignar_a($rente);
$fente=$dataente[nombre];

$qsucur=("select sucursal from sucursales where id_sucursal=$id_sucursal");
$rsucur=ejecutar($qsucur);
$datasucur=asignar_a($rsucur);
$fsucur=$datasucur[sucursal];

?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css" >
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 

  <tr><td>&nbsp;</td></tr>
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>

		<td class="descrip_main"> PLANES DE SALUD COSTA ORIENTAL DEL LAGO </td>     
	</tr>
  <tr><td>&nbsp;</td></tr>

	<tr>
		<td class="descrip_main" colspan="15">Reporte Relaci&oacute;n de <?php if($id_servicio==0) echo "TODOS LOS SERVICIOS";
if($id_servicio=="4") echo "CERTIFICACIONES";
if($id_servicio=="6") echo "PRESUPUESTO INICIAL";
if($id_servicio=="9") echo "PRESUPUESTO INICIAL"; ?> del Ente <?php echo "$fente ";?> </td>     
	</tr>

</table>	
  <tr><td>&nbsp;</td></tr>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 


	<tr> 
		<td class="descrip_main"><?php if ($id_sucursal==0) echo "TODAS LAS SUCURSALES" ; else echo $fsucur;?></td>
	</tr>
  <tr><td>&nbsp;</td></tr>
	<tr> 
		<td class="descrip_main">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>&nbsp;</td></tr>
	
</table>	 	

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 

		<td colspan=1 class="descrip_main">ORDEN</td>   
		<td colspan=1 class="descrip_main">CLAVE</td>     
		<td colspan=1 class="descrip_main">FILIALES</td> 
		<td colspan=1 class="descrip_main">TITULAR</td>  
		<td colspan=1 class="descrip_main">CEDULA TITULAR</td> 
		<td colspan=1 class="descrip_main">BENEFICIARIO</td> 
		<td colspan=1 class="descrip_main">FECHA INGRESO</td> 
		<td colspan=1 class="descrip_main">FECHA EGRESO</td> 
		<td colspan=1 class="descrip_main">PLAN</td>   
		<td colspan=1 class="descrip_main">SERVICIO</td>            
		<td colspan=1 class="descrip_main">DIAGNOSTICO</td>   
		<td colspan=1 class="descrip_main">MONTO (Bs.S)</td>      
	</tr>
	<?php
	     $i=1;
		  $bsf=0; 

	     while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{

			$rtitular=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where titulares.id_titular=$freporte[id_titular] and titulares.id_cliente=clientes.id_cliente"); 
			$qtitular=ejecutar($rtitular);
			$datatitular=asignar_a($qtitular);
			$ftitular="$datatitular[nombres] $datatitular[apellidos]";
			$fcedula="$datatitular[cedula]";
			   if ($freporte[id_beneficiario]>0){
				  $rbenf=("select clientes.nombres,clientes.apellidos from clientes,beneficiarios where beneficiarios.id_beneficiario=$freporte[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente;");
				

				  $qbenf=ejecutar($rbenf);
				  $databenf=asignar_a($qbenf);
				  $fbenf="$databenf[nombres] $databenf[apellidos]";  
				    
			  }else{$fbenf='';}


$qgasto=("select gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_proceso=$freporte[id_proceso]");
$rgasto=ejecutar($qgasto);

		$bsf=0;

	while($fgasto=asignar_a($rgasto,NULL,PGSQL_ASSOC))
		{
		  $bsf= $bsf + ($fgasto[monto_aceptado]);
		  $bsf1= $bsf1 + ($fgasto[monto_aceptado]);}

		  echo"
            <tr> 

		    <td colspan=1  class=\"tdtituloss\">$freporte[id_proceso] &nbsp;&nbsp;&nbsp;</td>   
	            <td colspan=1 class=\"tdtituloss\"> $freporte[no_clave] &nbsp;&nbsp;&nbsp;</td>
	            <td colspan=1 class=\"tdtituloss\"> $freporte[subdivision] &nbsp;&nbsp;&nbsp;</td> 	                 
	            <td colspan=1 class=\"tdtituloss\"> $ftitular &nbsp;&nbsp;&nbsp;</td>   
		    <td colspan=1 class=\"tdtituloss\"> $fcedula &nbsp;&nbsp;&nbsp;</td>
	            <td colspan=1 class=\"tdtituloss\"> $fbenf &nbsp;&nbsp;&nbsp;</td> 

	            <td colspan=1 class=\"tdtituloss\"> ";
                    
			if($id_servicio=="4"){ 
                          echo "$freporte[fecha_cita]";}
			else if ($id_servicio=="6" || $id_servicio=="9" ){ 
                          echo "$freporte[fecha_recibido]";}
		   echo "&nbsp;&nbsp;&nbsp;</td> 

	            <td colspan=1 class=\"tdtituloss\"> ";

if($freporte[fecha_cita] > "1900-01-01"){
                       if($id_servicio=="4"){ 
                          echo "$freporte[fecha_cita]";}
			else if ($id_servicio=="6" || $id_servicio=="9" ){ 
                          echo "$freporte[fecha_cita]";}}
		   echo " &nbsp;&nbsp;&nbsp;</td> 

      	            <td colspan=1 class=\"tdtituloss\"> $freporte[tipo_partida] &nbsp;&nbsp;&nbsp;</td> 
      	            <td colspan=1 class=\"tdtituloss\"> $freporte[servicio] &nbsp;&nbsp;&nbsp;</td> 
	            <td colspan=1 class=\"tdtituloss\"> $freporte[comentarios] &nbsp;&nbsp;&nbsp;</td>   
	            <td colspan=1 class=\"tdtituloss\">".montos_print($bsf)." </td>      
	        </tr>";
		$i++;
		}
	?>
	<tr>
  <tr><td>&nbsp;</td></tr>
	        <td colspan=4 class="tdcampos" >&nbsp; &nbsp;</td>
	        <td colspan=5 class="tdcampos" >&nbsp; &nbsp; Hay un total de <?php echo $i-1; ?> Ordenes </td>
	        <td colspan=2 class="tdcampos" >Total Bs.&nbsp;&nbsp;  </td>
	        <td colspan=2 class="tdcampos"><?php echo montos_print($bsf1); ?></td>
	</tr>

</table>

  <tr><td>&nbsp;</td></tr>

 
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>

