<?php
header("Content-Type: text/html;charset=utf-8");
/* Nombre del Archivo: ireporte_paraente.php
   Descripción: Reporte de Impresión con los datos seleccionados: Relación Entes Privados
*/

   include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];
   $replogo=$_REQUEST['lgnue'];   

list($repente,$entenombre)=explode("@",$_REQUEST['enpriv']);
if($repente=="-04"){
	$condicion_ente="and entes.id_tipo_ente='4' and titulares.id_ente=entes.id_ente";
    $yente="entes,titulares";
    $gente="entes.nombre";
}else{
    $condicion_ente="and titulares.id_ente=$repente and titulares.id_ente=entes.id_ente";
    $yente="titulares,entes"; 
    $gente="entes.nombre";
 }  
list($restpro,$estnombre)=explode("@",$_REQUEST['estapro']);  
 if($restpro==0)
    $condicion_restpro="";
else
    $condicion_restpro="and procesos.id_estado_proceso=$restpro";

list($id_sucursal, $sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal=='-01')
$condicion_sucursal="and admin.id_sucursal!='2'";
else if($id_sucursal==0)
  $condicion_sucursal="";
else
$condicion_sucursal="and admin.id_sucursal=$id_sucursal";

list($id_servicio,$servicio)=explode("@",$_REQUEST['servic']);
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
procesos.fecha_ent_pri,
procesos.id_admin,
procesos.no_clave,
subdivisiones.subdivision,
servicios.servicio,
servicios.id_servicio,
entes.nombre,
count(gastos_t_b.id_proceso)
from 
  procesos,gastos_t_b,admin,subdivisiones,$yente,titulares_subdivisiones,servicios
where 
procesos.fecha_ent_pri between '$fecre1' and '$fecre2' and 
gastos_t_b.id_proceso=procesos.id_proceso  and
 admin.id_admin=procesos.id_admin  
$condicion_sucursal  $condicion_servicio $condicion_ente $condicion_restpro and 
procesos.id_titular=titulares.id_titular and 
titulares.id_titular=titulares_subdivisiones.id_titular and
titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and 
servicios.id_servicio=gastos_t_b.id_servicio

group by 
procesos.id_proceso,
procesos.id_titular,
procesos.id_beneficiario,
procesos.comentarios,
procesos.fecha_recibido,
procesos.fecha_ent_pri,
procesos.id_admin,titulares.id_ente,
procesos.no_clave,
subdivisiones.subdivision,
servicios.servicio,
servicios.id_servicio,
entes.nombre,
$gente
ORDER BY procesos.no_clave DESC");
$rreporte=ejecutar($qreporte);


?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css" >


<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 

	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
	
        	<td class="descrip_main" colspan="10">Reporte Relaci&oacute;n de <?php  echo $servicio?> en Estado <?php echo $estnombre ?> del Ente Privado <?php echo "$entenombre";?> </td>     
     	</tr>
	<tr> 
		<td class="fecha" colspan="10">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 	
	<tr> 
 		<td class="descrip_main" colspan="10"><?php  echo $sucursal;?></td>
	</tr>	
  <tr><td>&nbsp;</td></tr> 
	<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 	
	<tr> 
		<td class="descrip_main">ORDEN</td>   
		<td class="descrip_main">CLAVE</td> 
		<td class="descrip_main">ENTE</td> 
		<td class="descrip_main">SUBDIVISION</td>       
		<td class="descrip_main">TITULAR</td>   
		<td class="descrip_main">CEDULA TITULAR</td>   
		<td class="descrip_main">BENEFICIARIO</td>
		<td class="descrip_main">CEDULA BENEFICIARIO</td>   
		<td class="descrip_main">FECHA</td>   
		<td class="descrip_main">SERVICIO</td>             
		<td class="descrip_main">DIAGNOSTICO</td>   
		<td class="descrip_main">MONTO (Bs.S)</td>      
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
				  $rbenf=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,beneficiarios where beneficiarios.id_beneficiario=$freporte[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente;");
				  $qbenf=ejecutar($rbenf);
				  $databenf=asignar_a($qbenf);
				  $fbenf="$databenf[nombres] $databenf[apellidos]";  
         			  $fcedulab="$databenf[cedula]";  
			  }else{$fbenf='';  $fcedulab='';}


$qgasto=("select gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_proceso=$freporte[id_proceso]");
$rgasto=ejecutar($qgasto);

			$bsf=0;
		while($fgasto=asignar_a($rgasto,NULL,PGSQL_ASSOC)){
			  $bsf = $bsf+$fgasto[monto_aceptado];
			  $bsf1 = $bsf1+$fgasto[monto_aceptado];}
		  echo"
            <tr> 

		    <td class=\"tdtituloss\">$freporte[id_proceso]</td>   
	            <td class=\"tdtituloss\">$freporte[no_clave]</td>
	            <td class=\"tdtituloss\">$freporte[nombre]</td>           
	            <td class=\"tdtituloss\">$freporte[subdivision]</td>        
	            <td class=\"tdtituloss\">$ftitular</td>   
	            <td class=\"tdtituloss\">$fcedula</td>   	           
		    <td class=\"tdtituloss\">$fbenf</td>
		    <td class=\"tdtituloss\">$fcedulab</td>
                    <td class=\"tdtituloss\">$freporte[fecha_ent_pri]</td>
                    <td class=\"tdtituloss\">$freporte[servicio]</td>        
	            <td class=\"tdtituloss\">$freporte[comentarios]</td>   
	            <td class=\"tdtituloss\">".montos_print($bsf)."</td>      
	        </tr>";
		$i++;
		}
	?>

	<tr> 
		<td class="tdtituloss" colspan="10"> &nbsp;</td>
	</tr>
	<tr>
		<td colspan=10 >&nbsp; </td>
		<td  class="descrip_main">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Bs.S  </td>
	        <td  colspan=2 class="descrip_main">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo montos_print($bsf1); ?></td>
     	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		<td class="tdtituloss" colspan="10"> &nbsp;</td>
	</tr>
	<tr>
	        <td colspan=10 class="descrip_main" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo $i-1; ?> Ordenes </td>

     	</tr> 
	<tr> 
		<td class="tdtituloss" colspan="10"> &nbsp;</td>
	</tr> 

	 
	<tr>
	        <td  colspan=3 class="tdtituloss" >Elaborado Por:_______________________</td>
	        <td  colspan=2 class="tdtituloss" >Aprobado Por:________________________</td>
		<td  colspan=2 class="tdtituloss" >Recibido Por:________________________</td>
	        <td colspan=3 class="tdtituloss" >Firma del proveedor:_______________________________</td>
     	</tr>
	<tr>    
		<td colspan=10 >&nbsp; </td>
	</tr> 
