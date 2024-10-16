<?php
/* Nombre del Archivo: ireporte_cliente_x_ente_titular.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación de los Clientes Titulares, de un determinado Ente
*/  


 include ("../../lib/jfunciones.php");
   sesion();

   list($estadot,$estado_clientet)=explode("@",$_REQUEST['estadot']);
	if($estadot==0)	        $condicion_estadot="";
	else
   $condicion_estadot="	and estados_t_b.id_estado_cliente='$estadot'";

   list($estadob,$estado_clienteb)=explode("@",$_REQUEST['estadob']);
	if($estadob==0)	        $condicion_estadob="";
	else
   $condicion_estadob="	and estados_t_b.id_estado_cliente='$estadob'";

   list($subdivi)=explode("@",$_REQUEST['subdivi']);
	if($subdivi==0)	        $condicion_subdivi="";
	else
   $condicion_subdivi="and titulares_subdivisiones.id_subdivision='$subdivi'";

   list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

list($id_ente,$ente)=explode("@",$_REQUEST['ente']);



if($id_ente==0)	        $condicion_ente="and entes.id_ente>0";
	
	else
	$condicion_ente="and entes.id_ente='$id_ente'";

if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}
?>

<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
 
	<tr>
		<td class="titulo_seccion" colspan="16">Reporte Relaci&oacute;n Clientes Titulares <?php 
if($estadot==0)	echo "Todos los Estados"; else echo "en Estado "."$estado_clientet";?>   <?php
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente ";?> </td>    
        </tr>
</table>
	
 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	
	<tr> 
	   	<td class="descrip_main">CLIENTE</td>   	   
		<td class="descrip_main">TITULAR</td>
	        <td class="descrip_main">CEDULA TITULAR</td> 
		<td class="descrip_main">ESTADO</td>      
		<td class="descrip_main">ENTE</td>        	           	      
	</tr>
<?php 
$q_titular=("select 
	clientes.id_cliente,
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	titulares.id_ente,
	titulares.tipocliente,
	entes.id_tipo_ente, entes.nombre 
	from 
	entes,clientes, estados_clientes, estados_t_b, titulares, titulares_subdivisiones 
	where 
	clientes.id_cliente=titulares.id_cliente  
	$condicion_ente $tipo_entes 
	and titulares.id_ente=entes.id_ente and 
	titulares.id_titular=estados_t_b.id_titular and 
	estados_t_b.id_beneficiario='0' 
	$condicion_estadot
	 and
	titulares.id_titular=titulares_subdivisiones.id_titular  
	$condicion_subdivi and
	estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente
	group by 
	clientes.id_cliente,	
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	titulares.id_ente,
	titulares.tipocliente,
	entes.id_tipo_ente,entes.nombre  
	order by estados_clientes.estado_cliente,clientes.apellidos,entes.nombre");
$r_titular=ejecutar($q_titular);

	$ta=0;
	$te=0;
	$ba=0;
	$be=0;

	while($f_titular=asignar_a($r_titular)){

if($f_titular[tipocliente]!='0'){
		if ($f_titular['id_estado_cliente']=='4'){
			$ta=$ta + 1;
		}else if ($f_titular['id_estado_cliente']=='5') {
			$te=$te + 1;
		}
	

echo"
		<tr> 
	        
		<td class=\"tdtitulos\">$f_titular[id_cliente]</td>
		<td class=\"tdtitulos\">$f_titular[nombres] $f_titular[apellidos]</td>   
		<td class=\"tdtitulos\">$f_titular[cedula]</td>
		<td class=\"tdcamposr\">$f_titular[estado_cliente]</td>
		
<td class=\"tdtituloss\">$f_titular[nombre]</td>";?>
	              
	        </tr>

<?php
}}
?>
	<tr><td colspan=16>&nbsp;</td></tr>
	<tr>
	        <td colspan=16 class="descrip_main" > &nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo  $ta+$te; ?> Clientes,   <?php echo  $ta; ?> Titulares Activos, <?php 
		
echo $te; ?> Titulares Excluidos.  </td>
	       
	</tr>
</table>
<table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 

	<tr>
	        <td  colspan=6 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=5 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=5 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>
	<tr><td colspan=16>&nbsp;</td></tr>
</table>
