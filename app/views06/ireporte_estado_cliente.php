<?php
/* Nombre del Archivo: ireporte_estado_cliente.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación Estado de los Clientes, de un determinado Ente
*/  


 include ("../../lib/jfunciones.php");
   sesion();

list($estado,$estado_cliente)=explode("@",$_REQUEST['estado']);
if($estado==0)	        $condicion_estado="";
	else
   $condicion_estado="	and estados_t_b.id_estado_cliente=$estado";

   $tipcliente=$_REQUEST['tipcliente'];

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

/*echo $estado."//";
echo $tipcliente."//";
echo $ente."//";*/

if($tipcliente=='TODOS LOS CLIENTES'){
	//se busca titulares y beneficiarios
	$tc="and estados_t_b.id_titular>0 and 
	     (estados_t_b.id_beneficiario>0 or estados_t_b.id_beneficiario=0) 
	     ";
}else if($tipcliente=='TITULARES'){
	//solo titulares
	$tc="and estados_t_b.id_titular>0 and 
	      estados_t_b.id_beneficiario=0";
}else if($tipcliente=='BENEFICIARIOS'){
	//solo beneficiarios
	$tc="and estados_t_b.id_titular>0 and 
	      estados_t_b.id_beneficiario>0";
}

$q_reporte=("select
estados_t_b.id_estado_cliente,
estados_t_b.id_titular,
estados_t_b.id_beneficiario,
estados_clientes.estado_cliente,
titulares.id_ente,
entes.id_tipo_ente,entes.nombre,clientes.nombres,
titulares.tipocliente
from estados_t_b,entes,estados_clientes,titulares,clientes
where

estados_t_b.id_titular=titulares.id_titular and
estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente
$tc $condicion_estado
$condicion_ente $tipo_entes and titulares.id_ente=entes.id_ente and
titulares.id_cliente=clientes.id_cliente

ORDER BY estado_cliente ,entes.nombre,clientes.nombres,titulares.tipocliente");
$r_reporte=ejecutar($q_reporte);

/*$q_estado=("select estados_clientes.estado_cliente from estados_clientes where estados_clientes.id_estado_cliente=$estado");
$r_estado=ejecutar($q_estado);
$f_estado=asignar_a($r_estado);


$q_ente=("select entes.nombre from entes where id_ente=$ente");
$r_ente=ejecutar($q_ente);
$f_ente=asignar_a($r_ente);*/


?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="descrip_main" colspan="7">Reporte Relaci&oacute;n de <?php echo "$tipcliente";?>, <?php if('$estado'=="0") echo "Todos los Estados"; else echo "en Estado "."$estado_cliente";?>, <?php
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente " ;?> </td>     
        </tr>	
 <br>	
	<tr> 
	   	<td class="descrip_main">CLIENTE</td>   
	   
<?php 
	if($tipcliente=='TITULARES'){?>
	
		<td class="descrip_main">TITULAR</td>
		<td class="tdcampos">TIPO CLIENTE</td>
	        <td class="descrip_main">CEDULA TITULAR</td> 
		<td class="descrip_main">ESTADO</td>
		<td class="descrip_main">COMENTARIOS</td>
		<td class="descrip_main">ENTE</td>        
		     
	</tr>

<?php } if($tipcliente=='BENEFICIARIOS' or $tipcliente=='TODOS LOS CLIENTES') { ?>
		<td class="descrip_main">TITULAR</td>
		<td class="tdcampos">TIPO CLIENTE</td>
	        <td class="descrip_main">CEDULA TITULAR</td>
		<td class="descrip_main">BENEFICIARIO</td>
	        <td class="descrip_main">CEDULA BENEFICIARIO</td>
		<td class="descrip_main">ESTADO</td> 
		<td class="descrip_main">COMENTARIOS</td>
		<td class="descrip_main">ENTE</td>        
		      
	</tr>
 
<?php } 
	     $t=0;$b=0;$t1=0;
		  $bsf=0; 

	     while($f_reporte=asignar_a($r_reporte,NULL,PGSQL_ASSOC))
		{

			$rtitular=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,clientes.comentarios from clientes,titulares where titulares.id_titular=$f_reporte[id_titular] and titulares.id_cliente=clientes.id_cliente"); 
			$qtitular=ejecutar($rtitular);
			$ftitular=asignar_a($qtitular);

			   
				if ($f_reporte['id_beneficiario']>0){
				  $rbenf=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,clientes.comentarios from clientes,beneficiarios where beneficiarios.id_beneficiario=$f_reporte[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente;");
				

				  $qbenf=ejecutar($rbenf);
				  $fbenf=asignar_a($qbenf);
				  
			  }else{$fbenf='';}
  

$qentes=("select entes.nombre from entes where entes.id_ente=$ente");
$rentes=ejecutar($qentes);
$dataentes=asignar_a($rentes);
$fentes="$dataentes[nombre]";

$qestado=("select estados_clientes.estado_cliente from estados_clientes where estados_clientes.id_estado_cliente=$estado");
$restado=ejecutar($qestado);
$dataestado=asignar_a($restado);
$festado="$dataestado[estado_cliente]";

if($f_reporte[tipocliente]=='0'){
$tip="TOMADOR"; $conto++;}
else{
$tip="TITULAR"; $conti++;}
	 
	if($tipcliente=='TITULARES' && $f_reporte['id_beneficiario'==0]){
 echo"
		<tr> 
	        
		<td class=\"tdtituloss\">$ftitular[id_cliente]</td>
		<td class=\"tdtituloss\">$ftitular[nombres] $ftitular[apellidos]</td> 
		<td class=\"tdcamposr\">$tip</td>    
		<td class=\"tdtituloss\">$ftitular[cedula]</td>
		<td class=\"tdcamposr\">$f_reporte[estado_cliente]</td>
		<td class=\"tdtituloss\">$ftitular[comentarios]</td>
		<td class=\"tdtituloss\">$f_reporte[nombre]</td>

	              
	        </tr>";
$t++;

} 
 
	if($tipcliente=='BENEFICIARIOS' && $f_reporte['id_beneficiario']>0 or $tipcliente=='TODOS LOS CLIENTES'){?>
		<tr> 
	          
		<td class="tdtituloss"> <?php if ($f_reporte['id_beneficiario']>0) echo "$fbenf[id_cliente]"; else echo "$ftitular[id_cliente]";?> </td>

<?php if($f_reporte['id_beneficiario']==0) $t1++;
if($f_reporte[tipocliente]=='0' && $f_reporte['id_beneficiario']==0){
$conto1++;}
else{
if($f_reporte[tipocliente]!='0' && $f_reporte['id_beneficiario']==0)
$conti1++;}
if ($f_reporte['id_beneficiario']>0) $b++;
echo"
		<td class=\"tdtituloss\">$ftitular[nombres] $ftitular[apellidos]</td> 
		<td class=\"tdcamposr\">$tip</td>    
		<td class=\"tdtituloss\">$ftitular[cedula]</td>
	        <td class=\"tdtituloss\">$fbenf[nombres] $fbenf[apellidos]</td> 
		<td class=\"tdtituloss\">$fbenf[cedula]</td> 
		<td class=\"tdcamposr\">$f_reporte[estado_cliente]</td>
		<td class=\"tdtituloss\">$ftitular[comentarios]</td>
		<td class=\"tdtituloss\">$f_reporte[nombre]</td>   
	        </tr>";
}
} ?>  
	<tr>
		<td colspan=8>&nbsp; </td>
	</tr>

	<tr>
	        <td colspan=8 class="descrip_main" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php  if($tipcliente=='TITULARES') echo $t." $tipcliente" ."  " . "  ($conti son TITULARES y $conto son TOMADORES)" ; 
	       else if($tipcliente=='BENEFICIARIOS') echo $b." $tipcliente"; 
               else echo $t1+$b." CLIENTES, "."$t1"." TITULARES"."  " . "  ($conti1 son TITULARES y $conto1 son TOMADORES)"." y "."$b"." BENEFICIARIOS" ?></td>

     	</tr>
<tr> <td>&nbsp; </td></tr>
	<tr>
		<td colspan=8>&nbsp; </td>
	</tr>
<tr> <td>&nbsp; </td></tr>
	<tr>
	        <td  colspan=2 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=3 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=3 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>    
<tr> <td>&nbsp; </td></tr>
</table>
  

