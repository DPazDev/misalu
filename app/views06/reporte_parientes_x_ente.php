<?php
/* Nombre del Archivo: reporte_parientes_x_estado.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación de los Parientes, de un determinado Ente
*/  


 include ("../../lib/jfunciones.php");
   sesion();

   list($estado,$estado_cliente)=explode("@",$_REQUEST['estado']);
	if($estado==0)	        $condicion_estado="";
	else
   $condicion_estado="and estados_t_b.id_estado_cliente=$estado";

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

	$signo=$_REQUEST['signo'];
	$cantidad=$_REQUEST['cantidad'];
	$nu_parentesco = $_REQUEST['nu_parentesco'];
	$valor1 = $_REQUEST['valor1'];
	$valor2=split("@",$valor1);

/*echo $estado."ee";
echo $subdivi."ss";
echo $ente."nn";
echo $signo."**";
echo $cantidad."--";
echo $nu_parentesco."////";
echo $valor1;*/
?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="10">Reporte Relaci&oacute;n Parientes Por Entes. Beneficiarios <?php 
if($estado==0)	echo "Todos los Estados"; else echo "en Estado "."$estado_cliente";?>,  <?php
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente ";?> </td>     
        </tr>


</table>

<table class="tabla_citas" colspan="14" cellpadding=0 cellspacing=0 border=0> 

<tr><td>&nbsp;</td></tr>
	
	<tr> 

		<td class="tdcampos">&nbsp;BENEFICIARIO</td>
	        <td class="tdcampos">&nbsp;CEDULA BENEFICIARIO</td>
		<td class="tdcampos">&nbsp;PARENTESCO</td> 
		<td class="tdcampos">&nbsp;SEXO</td> 
	        <td class="tdcampos">&nbsp;FECHA NAC.</td>
		<td class="tdcampos">&nbsp;EDAD</td>
		<td class="tdcampos">&nbsp;ESTADO</td>
		<td class="tdcampos">&nbsp;COMENTARIO</td>            
		<td class="tdcampos">&nbsp;ENTE</td>        	           	      
	</tr>

<?php
$apun=0;
for($i=0;$i<=$nu_parentesco;$i++){//este FOR lee el vector con la informacion de la cantidad de parentesco asignados, para luego realizar la busqueda  
    $valor=$valor2[$i];
    if($valor>0){
       if($apun==0){
         $cadenaparen="beneficiarios.id_parentesco=$valor"; 
       }else{
              $cadenaparen="$cadenaparen or beneficiarios.id_parentesco=$valor";//para realizar una cadena de busquedas con and y or y todas sean en el mismo tiempo
            }    
      $apun++;
    }
}
$q_parentesco=("select clientes.nombres, clientes.apellidos, clientes.cedula, clientes.sexo, clientes.fecha_nacimiento,clientes.edad,parentesco.parentesco,estados_clientes.estado_cliente,entes.nombre,clientes.comentarios 
from beneficiarios,titulares,clientes,estados_t_b,parentesco,estados_clientes,entes 
where 
($cadenaparen) and 
entes.id_ente=titulares.id_ente $condicion_ente $tipo_entes and 
titulares.id_titular=beneficiarios.id_titular and 
beneficiarios.id_cliente=clientes.id_cliente and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario $condicion_estado and 
parentesco.id_parentesco=beneficiarios.id_parentesco and
estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente
order by parentesco.parentesco, clientes.fecha_nacimiento");
/*echo $q_parentesco;*/

$r_parentesco=ejecutar($q_parentesco);
$cont=0;
	     while($f_parentesco=asignar_a($r_parentesco,NULL,PGSQL_ASSOC)){
               $edadusuario=calcular_edad($f_parentesco['fecha_nacimiento']); 
if($f_parentesco['sexo']==1){
	$sexo="MASCULINO";
	}
 	else 
	{
	$sexo="FEMENINO";
	}

if($signo=="<"){
	if($edadusuario<$cantidad){

	echo  " 
		<tr> 
		<td class=\"tdtitulos\">$f_parentesco[nombres] $f_parentesco[apellidos] </td>
		<td class=\"tdtitulos\">$f_parentesco[cedula] </td> 
		<td class=\"tdtitulos\">$f_parentesco[parentesco] </td> 
		<td class=\"tdtitulos\">$sexo </td> 
		<td class=\"tdtitulos\">&nbsp;&nbsp;$f_parentesco[fecha_nacimiento] </td>
		<td class=\"tdtitulos\">&nbsp;&nbsp;&nbsp;&nbsp; $edadusuario </td>	 
		<td class=\"tdtitulos\">$f_parentesco[estado_cliente] </td>
		<td class=\"tdtitulos\">$f_parentesco[comentarios] </td>
		<td class=\"tdtitulos\">$f_parentesco[nombre] </td>";
	    $cont++; } }

if($signo=="="){
	if($edadusuario==$cantidad){
	echo  " 
		<tr> 
		<td class=\"tdtitulos\">$f_parentesco[nombres] $f_parentesco[apellidos] </td>  
		<td class=\"tdtitulos\">$f_parentesco[cedula] </td>
		<td class=\"tdtitulos\">$f_parentesco[parentesco] </td> 
		<td class=\"tdtitulos\">$sexo </td>
		<td class=\"tdtitulos\">&nbsp;&nbsp;$f_parentesco[fecha_nacimiento] </td>
		<td class=\"tdtitulos\">&nbsp;&nbsp;&nbsp;&nbsp;$edadusuario </td>	 
		<td class=\"tdtitulos\">$f_parentesco[estado_cliente] </td>		
		<td class=\"tdtitulos\">$f_parentesco[comentarios] </td>
		<td class=\"tdtitulos\">$f_parentesco[nombre] </td>";
            $cont++; }}
             
if($signo==">"){
	if($edadusuario>$cantidad){
	echo  " 
		<tr> 
	        <td class=\"tdtitulos\">$f_parentesco[nombres] $f_parentesco[apellidos] </td>  
		<td class=\"tdtitulos\">$f_parentesco[cedula] </td>
		<td class=\"tdtitulos\">$f_parentesco[parentesco] </td> 		
		<td class=\"tdtitulos\">$sexo </td>		
		<td class=\"tdtitulos\">&nbsp;&nbsp;$f_parentesco[fecha_nacimiento] </td>		
		<td class=\"tdtitulos\">&nbsp;&nbsp;&nbsp;&nbsp;$edadusuario </td>	 
		<td class=\"tdtitulos\">$f_parentesco[estado_cliente] </td>		
		<td class=\"tdtitulos\">$f_parentesco[comentarios] </td>
		<td class=\"tdtitulos\">$f_parentesco[nombre] </td>";				
             	 $cont++;}}               			        
             }
?>
<tr><td colspan=14>&nbsp;</td></tr>
<tr><td colspan=14>&nbsp;</td></tr>
<tr>
	        <td colspan=14 class="tdtituloss" >&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo $cont; ?> Beneficiarios </td>
</tr>
<tr><td colspan=14>&nbsp;</td></tr>
<tr><td colspan=14>&nbsp;</td></tr>






</table>

<table>
	<tr> <td colspan=4>&nbsp;</td></tr>
 
	<tr> <td colspan=4>&nbsp;</td></tr>


	<tr>
	        <td colspan=9 class="tdcamposs" title="Excel">
	<?php

		$url="'views06/excel_parientes_x_ente.php?estado=$estado@$estado_cliente&subdivi=$subdivi&tipo_ente=$tipo_ente@$nom_tipo_ente&ente=$id_ente@$ente&signo=$signo&cantidad=$cantidad&nu_parentesco=$nu_parentesco&valor1=$valor1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a> 

	</td>
	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
</table>
