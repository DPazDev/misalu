<?php

/* Nombre del Archivo: ireporte_cliente_edad.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación de Clientes entre A y B años, de un Ente en Particular.
*/ 

   include ("../../lib/jfunciones.php");
   sesion();
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


  list($estado,$estado_cliente)=explode("@",$_REQUEST['estado']);
	if($estado==0)	        $condicion_estado="";
	else
   $condicion_estado="and estados_t_b.id_estado_cliente=$estado";
   $inicio=$_REQUEST['inicio'];
   $fin=$_REQUEST['fin'];
   $tipcliente=$_REQUEST['tipcliente'];


$q_titular=("select clientes.apellidos,clientes.nombres,titulares.tipocliente,clientes.cedula,clientes.fecha_nacimiento,clientes.sexo,
titulares.id_titular,titulares.id_cliente,estados_clientes.estado_cliente,entes.id_tipo_ente, estados_t_b.id_estado_cliente,entes.nombre from entes, clientes,titulares,estados_t_b,estados_clientes  where clientes.id_cliente=titulares.id_cliente  $condicion_ente $tipo_entes and titulares.id_ente=entes.id_ente and  estados_t_b.id_titular=titulares.id_titular $condicion_estado and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and estados_t_b.id_beneficiario='0' group by clientes.apellidos,clientes.nombres,titulares.tipocliente,clientes.cedula,clientes.fecha_nacimiento,clientes.sexo,
titulares.id_titular,titulares.id_cliente,estados_clientes.estado_cliente,estados_t_b.id_estado_cliente,entes.nombre,entes.id_tipo_ente order by estados_clientes.estado_cliente,entes.nombre,clientes.fecha_nacimiento");
$r_titular=ejecutar($q_titular);


$q_beneficiario=("select clientes.apellidos,clientes.nombres,clientes.cedula,clientes.fecha_nacimiento,clientes.sexo,parentesco.parentesco,beneficiarios.id_titular,beneficiarios.id_beneficiario,estados_clientes.estado_cliente,entes.id_tipo_ente, estados_t_b.id_estado_cliente,entes.nombre from entes,clientes,parentesco,beneficiarios,estados_t_b,estados_clientes,titulares where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente $condicion_ente $tipo_entes  and beneficiarios.id_parentesco=parentesco.id_parentesco and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario $condicion_estado and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente order by estados_clientes.estado_cliente,entes.nombre,clientes.fecha_nacimiento,parentesco.parentesco");
$r_beneficiario=ejecutar($q_beneficiario);



?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="descrip_main" colspan="9"> Reporte Relaci&oacute;n 
<?php if($tipcliente=='TITULARES') echo "Clientes "."TITULARES"  ;
      else if($tipcliente=='BENEFICIARIOS') echo "Clientes "."BENEFICIARIOS";
	else echo "TODOS LOS CLIENTES";?> entre <?php echo "$inicio y $fin";?> a&ntilde;os,  <?php 
if($estado==0)	echo "Todos los Estados, "; else echo "en Estado "."$estado_cliente, ";
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php 
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente ";?>  </td>     
	</tr>
 <br>

<?php 
	if($tipcliente=='TITULARES' or $tipcliente=='BENEFICIARIOS' or $tipcliente=='TODOS LOS CLIENTES'){?>
<tr>
		<td class="descrip_main">CLIENTE</td>   
		<td class="descrip_main">NOMBRES</td>     
		<td class="descrip_main">APELLIDOS</td> 
		<td class="descrip_main">CEDULA</td>  
		<td class="descrip_main">&nbsp;ESTADO</td> 
		<td class="descrip_main">&nbsp; FECHA DE NACIMIENTO</td> 
		<td class="descrip_main">&nbsp; GENERO</td> 
		<td class="descrip_main">&nbsp; EDAD</td> 
		<td class="descrip_main">ENTE</td> 
      
	<?php if($tipcliente=='BENEFICIARIOS' or $tipcliente=='TODOS LOS CLIENTES'){?>
	  
		<td class="descrip_main">&nbsp; PARENTESCO</td>      
		      
	</tr>
<?php }}
	     $t=0;$b=0;
		 
	     while($f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC))
		{

if  (calcular_edad($f_titular['fecha_nacimiento'])>=$inicio &&  calcular_edad($f_titular['fecha_nacimiento'])<=$fin) { 
if($tipcliente=='TITULARES' or $tipcliente=='TODOS LOS CLIENTES'){
if($f_titular['tipocliente']!='0'){
if($f_titular['sexo']==1){
	$sexot="MASCULINO";
	}
 	else 
	{
	$sexot="FEMENINO";
	}

 echo"
            <tr> 
		    <td class=\"tdtituloss\">$f_titular[id_titular]</td>   
	            <td class=\"tdtituloss\">$f_titular[nombres]</td>  
	            <td class=\"tdtituloss\">$f_titular[apellidos]</td>      
	            <td class=\"tdtituloss\">$f_titular[cedula]</td>   
	            <td class=\"tdtituloss\">&nbsp; $f_titular[estado_cliente]</td>
	            <td class=\"tdtituloss\">&nbsp; $f_titular[fecha_nacimiento]</td>
	            <td class=\"tdtituloss\">&nbsp; $sexot </td>
	            <td class=\"tdtituloss\">";?><?php echo calcular_edad($f_titular['fecha_nacimiento']);
	    echo"   <td class=\"tdtituloss\">$f_titular[nombre]</td>"; ?>      
	                 
	        </tr>
<?php

	$t++;}
		}
		}}

     while($f_beneficiario=asignar_a($r_beneficiario,NULL,PGSQL_ASSOC))
		{

if  (calcular_edad($f_beneficiario['fecha_nacimiento'])>=$inicio &&  calcular_edad($f_beneficiario['fecha_nacimiento'])<=$fin) {
if($tipcliente=='BENEFICIARIOS' && $f_beneficiario['id_beneficiario']>0 or $tipcliente=='TODOS LOS CLIENTES' or $f_titular['id_titular']>0){

if($f_beneficiario['sexo']==1){
	$sexob="MASCULINO";
	}
 	else 
	{
	$sexob="FEMENINO";
	}

	  echo"
	<tr> 
		    <td class=\"tdtituloss\">$f_beneficiario[id_beneficiario]</td>   
	            <td class=\"tdtituloss\">$f_beneficiario[nombres]</td>  
	            <td class=\"tdtituloss\">$f_beneficiario[apellidos]</td>      
	            <td class=\"tdtituloss\">$f_beneficiario[cedula]</td>   
	            <td class=\"tdtituloss\">&nbsp; $f_beneficiario[estado_cliente]</td>
	            <td class=\"tdtituloss\">&nbsp; $f_beneficiario[fecha_nacimiento]</td>
	            <td class=\"tdtituloss\">&nbsp; $sexob &nbsp;&nbsp;</td>
	            <td class=\"tdtituloss\">";?><?php echo calcular_edad($f_beneficiario['fecha_nacimiento']);?>
<?php            echo "<td class=\"tdtituloss\">$f_beneficiario[nombre]</td>
<td class=\"tdtituloss\">&nbsp; $f_beneficiario[parentesco]</td>" ?>
			</td>       
	                 
	</tr>
<?php
		$b++;}
		}
		}
	?>
	<tr> <td colspan=9 >&nbsp;</td></tr>
	<tr> <td colspan=9 >&nbsp;</td></tr>
	<tr> <td colspan=9 >&nbsp;</td></tr>
	<tr>
	        <td colspan=9 class="descrip_main" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HAY UN TOTAL DE <?php if($tipcliente=='TITULARES') echo $t." $tipcliente"; 
	       else if($tipcliente=='BENEFICIARIOS') echo $b." $tipcliente"; 
               else echo $t+$b." CLIENTES, "."$t"." TITULARES"." Y "."$b"." BENEFICIARIOS"; ?>  
		
     	</tr> 
	<tr> <td colspan=9 >&nbsp;</td></tr>
	<tr> <td colspan=9 >&nbsp;</td></tr>
	<tr> <td colspan=9 >&nbsp;</td></tr>

	<tr>
	        <td  colspan=3 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=3 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=3 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>
 
	<tr> <td colspan=9 >&nbsp;</td></tr>
	<tr> <td colspan=9 >&nbsp;</td></tr>
</table>
