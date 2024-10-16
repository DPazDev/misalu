<?php
/* Nombre del Archivo: ireporte_cliente_x_ente.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación de los Clientes Totales, de un determinado Ente
*/  


 include ("../../lib/jfunciones.php");
   sesion();

   list($estadot,$estado_clientet)=explode("@",$_REQUEST['estadot']);
	if($estadot==0)	        $condicion_estadot="";
	else if($estadot=="-01"){// activo con beneficiario //
$condicion_estadot="and estados_t_b.id_estado_cliente=4 and estados_t_b.id_beneficiario>'0'";

}

else if($estadot=="-02"){// activo sin beneficiario //
$condicion_estadot="and estados_t_b.id_estado_cliente=4";
$condicion_count="count(estados_t_b.id_titular),";}

	else{
   $condicion_estadot="	and estados_t_b.id_estado_cliente=$estadot and estados_t_b.id_beneficiario='0' ";
}






   list($estadob,$estado_clienteb)=explode("@",$_REQUEST['estadob']);
	if($estadob==0)	        $condicion_estadob="";
	else if($estadob=="-02"){// beneficiario activo + lapso de espera //
	 $condicion_estadob="and (estados_t_b.id_estado_cliente=1 or estados_t_b.id_estado_cliente=4)";}
	else
   $condicion_estadob="	and estados_t_b.id_estado_cliente=$estadob";

   list($subdivi)=explode("@",$_REQUEST['subdivi']);
	if($subdivi==0)	        $condicion_subdivi="";
	else
   $condicion_subdivi="and titulares_subdivisiones.id_subdivision=$subdivi";

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
		<td class="descrip_main" colspan="16">Reporte Relaci&oacute;n Clientes Titulares <?php 
if($estadot==0)	echo "Todos los Estados"; else if($estadot=="-01") echo "ACTIVO CON BENEFICIARIO"; else if($estadot=="-02") echo "ACTIVO SIN BENEFICIARIO"; else echo "en Estado "."$estado_clientet";?> y Beneficiarios <?php 
if($estadob==0) echo "Todos los Estados"; else if($estadob=="-01") echo "NINGUNO"; else echo "en Estado "."$estado_clienteb";?>,  <?php
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente ";?> </td>     
        </tr>	
 <br>
	<tr> 
	   	<td class="descrip_main">CLIENTE</td>   	   
		<td class="descrip_main">TITULAR</td>
	        <td class="descrip_main">CEDULA TITULAR</td> 
		<td class="descrip_main">ESTADO</td>
		<td class="descrip_main">TIPO CLIENTE</td>
		<td class="descrip_main">FECHA NAC.</td> 
		<td class="descrip_main">GENERO</td>               
		<td class="descrip_main">CLIENTE</td>        
		<td class="descrip_main">&nbsp;BENEFICIARIO</td>
	        <td class="descrip_main">CEDULA BENEFICIARIO</td>
		<td class="descrip_main">ESTADO</td>
	        <td class="descrip_main">FECHA NAC.</td>
		<td class="descrip_main">GENERO</td>               
		<td class="descrip_main">PARENTESCO</td> 
		<td class="descrip_main">&nbsp;EDAD</td> 
		<td class="descrip_main">COMENTARIO</td>      
		<td class="descrip_main">TELEFONO</td>      
		<td class="descrip_main">CELULAR</td>
		<td class="descrip_main">ENTE</td> 
		      	             	             	             	       	             	             	             	      
	</tr>		
<?php 
	$q_titular=("select 
	clientes.id_cliente,
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	clientes.sexo,
	clientes.fecha_nacimiento,clientes.comentarios,clientes.telefono_hab, clientes.celular,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	$condicion_count
	titulares.id_ente,
	titulares.tipocliente,
	entes.id_tipo_ente,entes.nombre  
	from 
	clientes, estados_clientes, estados_t_b,entes, titulares, titulares_subdivisiones
	where 
	clientes.id_cliente=titulares.id_cliente  
	$condicion_ente $tipo_entes and 
	titulares.id_ente=entes.id_ente and 
	titulares.id_titular=estados_t_b.id_titular
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
	clientes.sexo,
	clientes.fecha_nacimiento,clientes.comentarios,clientes.telefono_hab, clientes.celular,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	titulares.id_ente,
	titulares.tipocliente,
	entes.id_tipo_ente,entes.nombre  
	order by estados_clientes.estado_cliente,clientes.apellidos");
$r_titular=ejecutar($q_titular);

	$ta=0;
	$te=0;
	$ba=0;
	$be=0;
	$tasb=0;
	$sexot=0;

	while($f_titular=asignar_a($r_titular)){
		if ($f_titular['id_estado_cliente']=='4'){
			$ta=$ta + 1;
		}if ($f_titular['id_estado_cliente']=='4' and $f_titular['count']=='1') {//titular activo sin beneficiario
			$tasb=$tasb + 1;
		

		}else if ($f_titular['id_estado_cliente']=='5') {
			$te=$te + 1;
		}
	
if($f_titular[sexo]==1){
	$sexot="MASCULINO";
	}
 	else 
	{
	$sexot="FEMENINO";
	}

if($estadot=='-02' && $f_titular['count']=='1')   { 

if($f_titular[tipocliente]=='0'){
$tip="TOMADOR"; $conto++;}
else{
$tip="TITULAR"; $conti++;}

echo"
		<tr> 
		<td class=\"tdtitulos\">$f_titular[id_cliente]</td>
		<td class=\"tdtitulos\">$f_titular[nombres] $f_titular[apellidos]</td>   
		<td class=\"tdtitulos\">$f_titular[cedula]</td>
		<td class=\"tdtitulos\">$f_titular[estado_cliente]</td>
		<td class=\"tdtitulos\">$tip</td>
		<td class=\"tdtitulos\">$f_titular[fecha_nacimiento]</td>
		<td class=\"tdtitulos\">$sexot</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>   
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>		
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\"> ";?><?php echo calcular_edad($f_titular['fecha_nacimiento']);
echo"  		<td class=\"tdtitulos\">$f_titular[comentarios]</td>
		<td class=\"tdtitulos\">$f_titular[telefono_hab]</td>
		<td class=\"tdtitulos\">$f_titular[celular]</td>
		<td class=\"tdtitulos\">$f_titular[nombre]</td>
	";?>
	              
	        </tr>

<?php  


}

else if($estadot!='-02')  { 
if($f_titular[tipocliente]=='0'){
$tip="TOMADOR"; $conto++;}
else{
$tip="TITULAR"; $conti++;}

echo"
		<tr> 
		<td class=\"tdtitulos\">$f_titular[id_cliente]</td>
		<td class=\"tdtitulos\">$f_titular[nombres] $f_titular[apellidos]</td>   
		<td class=\"tdtitulos\">$f_titular[cedula]</td>
		<td class=\"tdtitulos\">$f_titular[estado_cliente]</td>
		<td class=\"tdtitulos\">$tip</td>
		<td class=\"tdtitulos\">$f_titular[fecha_nacimiento]</td>
		<td class=\"tdtitulos\">$sexot</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>   
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>		
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\"> ";?><?php echo calcular_edad($f_titular['fecha_nacimiento']);
echo"  		<td class=\"tdtitulos\">$f_titular[comentarios]</td>
		<td class=\"tdtitulos\">$f_titular[telefono_hab]</td>
		<td class=\"tdtitulos\">$f_titular[celular]</td>
		<td class=\"tdtitulos\">$f_titular[nombre]</td>
	";?>	              
	        </tr>

<?php

		$q_beneficiario=("
		select 
		beneficiarios.id_cliente,
		beneficiarios.id_parentesco,
		beneficiarios.id_beneficiario,
		parentesco.parentesco,
		clientes.apellidos,
		clientes.nombres,
		clientes.cedula,
		clientes.sexo,
		clientes.fecha_nacimiento,clientes.comentarios,clientes.telefono_hab, clientes.celular,
		estados_clientes.estado_cliente,
		estados_t_b.id_estado_cliente 
		from clientes, estados_clientes, beneficiarios, estados_t_b, parentesco, titulares 
		where 
		clientes.id_cliente=beneficiarios.id_cliente and 
		titulares.id_titular=beneficiarios.id_titular and 
		titulares.id_titular=$f_titular[id_titular] and 
		beneficiarios.id_beneficiario=estados_t_b.id_beneficiario  
	        $condicion_estadob and
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 		parentesco.id_parentesco=beneficiarios.id_parentesco order by clientes.apellidos");
		$r_beneficiario=ejecutar($q_beneficiario);

$sexob=0;

		while($f_beneficiario=asignar_a($r_beneficiario)){
			if ($f_beneficiario['id_estado_cliente']=='4'){
				$ba=$ba + 1;
			}else if ($f_beneficiario['id_estado_cliente']=='5') {
				$be=$be + 1;
			}


if($f_beneficiario[sexo]==1){
	$sexob="MASCULINO";
	}
 	else 
	{
	$sexob="FEMENINO";
	}




echo"
		<tr> 
	        <td class=\"tdtitulos\">&nbsp;</td>
	        <td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>   
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">$f_beneficiario[id_cliente]</td>
		<td class=\"tdtitulos\">$f_beneficiario[nombres] $f_beneficiario[apellidos]</td>   
		<td class=\"tdtitulos\">$f_beneficiario[cedula]</td>
		<td class=\"tdtitulos\">$f_beneficiario[estado_cliente]</td>
		<td class=\"tdtitulos\">$f_beneficiario[fecha_nacimiento]</td>
		<td class=\"tdtitulos\">$sexob</td>
		<td class=\"tdtitulos\">$f_beneficiario[parentesco]</td>
		<td class=\"tdtitulos\"> ";?><?php echo calcular_edad($f_beneficiario['fecha_nacimiento']);
echo"  		<td class=\"tdtitulos\">$f_beneficiario[comentarios]</td>
		<td class=\"tdtitulos\">$f_beneficiario[telefono_hab]</td>
		<td class=\"tdtitulos\">$f_beneficiario[celular]</td>		
		<td class=\"tdtitulos\">$f_titular[nombre]</td>
		";?>

	              
	        </tr>
<?php }}}



	
if ($estadot=='-02'  ){?>


<tr><td colspan=16>&nbsp;</td></tr>
	<tr>
	        <td colspan=16 class="tdcampos" > &nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo  $tasb; ?> Clientes Titulares Activos sin Beneficiarios <?php echo  "( $conti son TITULARES y $conto son TOMADORES ) ";?>
	<tr> <td colspan=4>&nbsp;</td></tr>
<?php
}

else if($estadot!='-02')  {
?>			
	<tr><td colspan=16>&nbsp;</td></tr>
	<tr>
	        <td colspan=16 class="descrip_main" >&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo  $ta+$te+$ba+$be; ?> Clientes,   <?php echo  $ta; ?> Titulares Activos <?php echo  "( $conti son TITULARES y $conto son TOMADORES ) ";   
		
echo $te; ?> Titulares Excluidos, <?php echo  $ba; ?> Beneficiarios Activos y <?php 
		
echo $be; ?> Beneficiarios Excluidos  </td>
	 
		
     	</tr> 


<?php
}?>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr><td colspan=16>&nbsp;</td></tr>
	<br> 
	<tr><td colspan=16>&nbsp;</td></tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
	        <td  colspan=6 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=5 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=5 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>


</table>
 

