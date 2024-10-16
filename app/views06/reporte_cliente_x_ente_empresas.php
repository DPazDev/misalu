<?php
/* Nombre del Archivo: reporte_cliente_x_ente_empresas.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte Clientes por Ente para usuarios autorizados de otras Empresas
*/  


 include ("../../lib/jfunciones.php");
   sesion();

$id_ente=$_REQUEST['id_ente'];

 $q_ente=("select entes.id_ente, entes.nombre from entes,admin where entes.id_ente='$id_ente'");
   $r_ente = ejecutar($q_ente);
   $f_ente=asignar_a($r_ente);



   list($estadot,$estado_clientet)=explode("@",$_REQUEST['estadot']);
	if($estadot==0)	        $condicion_estadot="";
	else
   $condicion_estadot="	and estados_t_b.id_estado_cliente=$estadot";

   list($estadob,$estado_clienteb)=explode("@",$_REQUEST['estadob']);
	if($estadob==0)	        $condicion_estadob="";
else if($estadob=="-02"){
	 $condicion_estadob="and (estados_t_b.id_estado_cliente=1 or estados_t_b.id_estado_cliente=4)";}	
else
   $condicion_estadob="	and estados_t_b.id_estado_cliente=$estadob";




?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="16">Reporte Relaci&oacute;n Clientes Titulares <?php 
if($estadot==0)	echo "Todos los Estados"; else echo "en Estado "."$estado_clientet";?> y Beneficiarios <?php 
if($estadob==0) echo "Todos los Estados"; else if($estadob=="-01") echo "NINGUNO"; else echo "en Estado "."$estado_clienteb";?>,   <?php
 echo "del Ente "."$f_ente[nombre] ";?> </td>     
        </tr>
</table>

 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	
	<tr> 

		<td class="tdcampos">&nbsp;TITULAR</td>
	        <td class="tdcampos">CEDULA TITULAR</td> 
		<td class="tdcampos">ESTADO</td>
		<td class="tdcampos">&nbsp;FECHA NAC.</td> 

		<td class="tdcampos">&nbsp;BENEFICIARIO</td>
	        <td class="tdcampos">&nbsp;CEDULA BENEFICIARIO</td>
		<td class="tdcampos">&nbsp;ESTADO</td>
	        <td class="tdcampos">&nbsp;FECHA NAC.</td>
		<td class="tdcampos">&nbsp;PARENTESCO</td> 
		<td class="tdcampos">&nbsp;EDAD</td>

		<td class="tdcampos">&nbsp;TELEFONO</td>      
		<td class="tdcampos">&nbsp;CELULAR</td>      
		<td class="tdcampos">&nbsp;ENTE</td>        	           	      
	</tr>
<?php 
$q_titular=("select 
	clientes.id_cliente,
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	clientes.sexo,
	clientes.fecha_nacimiento,
	clientes.comentarios,clientes.telefono_hab, clientes.celular,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	titulares.id_ente,
	entes.nombre 
	from 
	entes,clientes, estados_clientes, estados_t_b, titulares, titulares_subdivisiones
	where 
	clientes.id_cliente=titulares.id_cliente and  
	entes.id_ente=$id_ente
	and titulares.id_ente=entes.id_ente and 
	titulares.id_titular=estados_t_b.id_titular and 
	estados_t_b.id_beneficiario='0' 
	$condicion_estadot
	 and
	titulares.id_titular=titulares_subdivisiones.id_titular  
	 and
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
	entes.nombre  
	order by estados_clientes.estado_cliente,clientes.apellidos,entes.nombre");
$r_titular=ejecutar($q_titular);


	$ta=0;
	$te=0;
	$ba=0;
	$be=0;



	while($f_titular=asignar_a($r_titular)){
		if ($f_titular['id_estado_cliente']=='4'){
			$ta=$ta + 1;
		}else if ($f_titular['id_estado_cliente']=='5') {
			$te=$te + 1;
		}
	
echo"
		<tr> 
	        

		<td class=\"tdtitulos\">$f_titular[nombres] $f_titular[apellidos]</td>   
		<td class=\"tdtitulos\">$f_titular[cedula]</td>
		<td class=\"tdcamposr\">$f_titular[estado_cliente]</td>
		<td class=\"tdtitulos\">$f_titular[fecha_nacimiento]</td>

		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\"> ";?><?php echo calcular_edad($f_titular['fecha_nacimiento']);
echo"		
		<td class=\"tdtitulos\">$f_titular[telefono_hab]</td>
		<td class=\"tdtitulos\">$f_titular[celular]</td>
<td class=\"tdtituloss\">$f_titular[nombre]</td>";?>
	              
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
		clientes.fecha_nacimiento,
		clientes.comentarios,clientes.telefono_hab, clientes.celular,
		estados_clientes.estado_cliente,
		estados_t_b.id_estado_cliente 
		from clientes, estados_clientes, beneficiarios, estados_t_b,parentesco, titulares 
		where 
		clientes.id_cliente=beneficiarios.id_cliente and 
		titulares.id_titular=beneficiarios.id_titular and 
		titulares.id_titular=$f_titular[id_titular] and 
		beneficiarios.id_beneficiario=estados_t_b.id_beneficiario  
	        $condicion_estadob and
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 		parentesco.id_parentesco=beneficiarios.id_parentesco order by clientes.apellidos");
		$r_beneficiario=ejecutar($q_beneficiario);



		while($f_beneficiario=asignar_a($r_beneficiario)){
			if ($f_beneficiario['id_estado_cliente']=='4'){
				$ba=$ba + 1;
			}else if ($f_beneficiario['id_estado_cliente']=='5') {
				$be=$be + 1;
			}





echo"
		<tr> 

		<td class=\"tdtitulos\">&nbsp;</td>   
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>
		<td class=\"tdtitulos\">&nbsp;</td>

		<td class=\"tdtitulos\">$f_beneficiario[nombres] $f_beneficiario[apellidos]</td>   
		<td class=\"tdtitulos\">$f_beneficiario[cedula]</td>
		<td class=\"tdcamposr\">$f_beneficiario[estado_cliente]</td>
		<td class=\"tdtitulos\">$f_beneficiario[fecha_nacimiento]</td>
		<td class=\"tdtitulos\">$f_beneficiario[parentesco]</td>
		<td class=\"tdtitulos\"> ";?><?php echo calcular_edad($f_beneficiario['fecha_nacimiento']);
echo" 		
		<td class=\"tdtitulos\">$f_beneficiario[telefono_hab]</td>
		<td class=\"tdtitulos\">$f_beneficiario[celular]</td>
		<td class=\"tdtitulos\">$f_titular[nombre]</td>"
;?>
</td>

	              
	        </tr>
<?php }}
?>			
	<tr><td colspan=16>&nbsp;</td></tr>
	<tr>
	        <td colspan=16 class="tdcampos" > &nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo  $ta+$te+$ba+$be; ?> Clientes,   <?php echo  $ta; ?> Titulares Activos, <?php 
		
echo $te; ?> Titulares Excluidos, <?php echo  $ba; ?> Beneficiarios Activos y <?php 
		
echo $be; ?> Beneficiarios Excluidos  </td>
	       
	</tr>
</table>
 
<br>

<br>
</table>


