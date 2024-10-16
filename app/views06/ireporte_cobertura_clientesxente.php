<?php
/* Nombre del Archivo: ireporte_cobertura_clientesxente.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación de Cobertura de los Clientes Totales, de un determinado Ente
*/  

include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 
   list($estadot)=explode("@",$_REQUEST['estadot']);
	if($estadot=="0")	        $condicion_estadot="and estados_clientes.id_estado_cliente>0";
	else
   $condicion_estadot="	and estados_clientes.id_estado_cliente=$estadot";

   list($estadob)=explode("@",$_REQUEST['estadob']);
	if($estadob=="0")	        $condicion_estadob="and estados_clientes.id_estado_cliente>0";
	else
   $condicion_estadob="	and estados_clientes.id_estado_cliente=$estadob";

   list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

list($id_ente,$ente)=explode("@",$_REQUEST['ente']);


if($id_ente==0)	        $condicion_ente="and entes.id_ente>0";
	
	else
	$condicion_ente="and entes.id_ente='$id_ente'";

if  ($tipo_ente==0){
	$tipo_entes="and tbl_tipos_entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and tbl_tipos_entes.id_tipo_ente=$tipo_ente";
	}

/*echo $estadot."//";
echo $estadob."//";
echo $ente."//";*/

$q_estadot=("select estados_clientes.estado_cliente from estados_clientes where estados_clientes.id_estado_cliente=$estadot");
$r_estadot=ejecutar($q_estadot);
$f_estadot=asignar_a($r_estadot);

$q_estadob=("select estados_clientes.estado_cliente from estados_clientes where estados_clientes.id_estado_cliente=$estadob");
$r_estadob=ejecutar($q_estadob);
$f_estadob=asignar_a($r_estadob);

$codigot=time();
$codigo=$admin . $codigot;
/* **** creamos la tabla temporal para realizar la consulta**** */
/* $q_tabla_tem = " create table tabla_coberturas_tmp_$codigo as select 
  clientes.nombres,
  clientes.apellidos,
  clientes.cedula,
  clientes.sexo,
  clientes.fecha_nacimiento,
  clientes.id_cliente,
  entes.nombre,
  entes.id_ente,
  tbl_tipos_entes.id_tipo_ente,
  estados_clientes.id_estado_cliente,
  estados_clientes.estado_cliente,
  propiedades_poliza.cualidad,
  propiedades_poliza.monto,
  coberturas_t_b.monto_actual,
  coberturas_t_b.id_titular,
  coberturas_t_b.id_beneficiario 
from 
  clientes,
  entes,
  tbl_tipos_entes,
  titulares,
  propiedades_poliza,
  coberturas_t_b,
  estados_t_b,
  estados_clientes 
where 
  coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
  coberturas_t_b.id_titular=titulares.id_titular and 
  titulares.id_cliente=clientes.id_cliente and 
  titulares.id_ente=entes.id_ente  
 $condicion_ente and
  entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and 
  coberturas_t_b.id_titular=estados_t_b.id_titular and 
  coberturas_t_b.id_beneficiario=estados_t_b.id_beneficiario and 
  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente 
  $condicion_estadob
  $tipo_entes
order by
  entes.nombre,
  propiedades_poliza.cualidad,
  titulares.id_titular";
$r_tabla_tem = ejecutar($q_tabla_tem);
*/


?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<LINK REL="StyleSheet" HREF="../../public/stylesheets/estilos.css">
<table class="tabla_cabecera"  cellpadding=0 cellspacing=0 >
 
	<tr>
		<td class="tdcampos" colspan="17"> <img src="../../public/images/MSS2.png" alt="logo"><br>RIF J-31180863-9</td>
		</tr>
		<td class="titulo_seccion" colspan="17">Reporte Relaci&oacute;n Clientes Titulares <?php
if($estadot=="0") echo "Todos los Estados"; else echo "en Estado "."$f_estadot[estado_cliente]";?> y Beneficiarios <?php 
if($estadob=="0") echo "Todos los Estados"; else if($estadob=="-01") echo "NINGUNO"; else echo "en Estado "."$f_estadob[estado_cliente]";?>,  <?php
if($tipo_ente=="0") echo "TODOS LOS TIPOS DE ENTES";
 
else echo "del Tipo de Ente "."$nom_tipo_ente";  ?>, <?php
if($id_ente=="0") echo "TODOS LOS ENTES";
 
else echo "del Ente "."$ente ";?> </td>     
        </tr>
	
<tr>
	<td class=\"tdtituloss\" colspan=17 ><hr></hr></td>
		</tr>
	<tr> 
	   	<td class="tdcampos">CLIENTE</td> 
		<td class="tdcamposr">ENTE</td>  
		<td class="tdcampos">TITULAR</td>
	    <td class="tdcampos">CEDULA TITULAR</td> 
		<td class="tdcampos">ESTADO</td>
		<td class="tdcampos">FECHA NAC.</td>        
		<td class="tdcampos">PROPIEDAD POLIZA.</td>  
		<td class="tdcamposr">MONTO</td>  
		<td class="tdcampos">CLIENTE</td>        
		<td class="tdcampos">BENEFICIARIO</td>
	    <td class="tdcampos">CEDULA BENEFICIARIO</td>
		<td class="tdcampos">ESTADO</td>
	    <td class="tdcampos">FECHA NAC.</td>
		<td class="tdcampos">PARENTESCO</td> 
		<td class="tdcampos">EDAD</td> 
		<td class="tdcampos">PROPIEDAD POLIZA.</td>  
		<td class="tdcamposr">MONTO</td>  
	</tr>
	<tr>
	<td class=\"tdtituloss\" colspan=17 ><hr></hr></td>
		</tr>
<?php 
/* $q_titular=("select 
							tabla_coberturas_tmp_$codigo.nombres,
							tabla_coberturas_tmp_$codigo.apellidos,
							tabla_coberturas_tmp_$codigo.nombre,
							tabla_coberturas_tmp_$codigo.id_cliente,
							tabla_coberturas_tmp_$codigo.cedula,
							tabla_coberturas_tmp_$codigo.fecha_nacimiento,
							tabla_coberturas_tmp_$codigo.estado_cliente,
							tabla_coberturas_tmp_$codigo.id_titular,
							count(tabla_coberturas_tmp_$codigo.id_titular) 
					from 
							tabla_coberturas_tmp_$codigo
					where 
							tabla_coberturas_tmp_$codigo.id_beneficiario=0 
					group by 
							tabla_coberturas_tmp_$codigo.nombres,
							tabla_coberturas_tmp_$codigo.apellidos,
							tabla_coberturas_tmp_$codigo.nombre,
							tabla_coberturas_tmp_$codigo.id_cliente,
							tabla_coberturas_tmp_$codigo.cedula,
							tabla_coberturas_tmp_$codigo.fecha_nacimiento,
							tabla_coberturas_tmp_$codigo.estado_cliente,
							tabla_coberturas_tmp_$codigo.id_titular
					order by
							tabla_coberturas_tmp_$codigo.nombre
");
$r_titular=ejecutar($q_titular);
*/


$q_titular=("select 
							clientes.nombres,
							clientes.apellidos,
							clientes.cedula,
							clientes.sexo,
							clientes.fecha_nacimiento,
							clientes.id_cliente,
							entes.nombre,
							entes.id_ente,
							tbl_tipos_entes.id_tipo_ente,
							estados_clientes.id_estado_cliente,
							estados_clientes.estado_cliente,
							titulares.tipocliente,
							titulares.id_titular
					from 
						  clientes,
						  entes,
						  tbl_tipos_entes,
						  titulares,
						  estados_t_b,
						  estados_clientes 
					where 
						  titulares.id_cliente=clientes.id_cliente and 
						  titulares.id_ente=entes.id_ente  
						  $condicion_ente and
						  entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and   
						  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente 
					      $condicion_estadot
						  $tipo_entes and
					      titulares.id_titular=estados_t_b.id_titular and 
					      estados_t_b.id_beneficiario=0
				order by
						  entes.nombre,
						  titulares.id_titular;

");
$r_titular=ejecutar($q_titular);


$t=0;
$b=0;
	while($f_titular=asignar_a($r_titular)){
	$t=$t+1;
			if ($f_titular[tipocliente]=='0'){
			$tomador="TOMADOR";
			}
			else
			{
				$tomador="";
				}
			
	echo"
		<tr> 	        
		<td class=\"tdtituloss\">$f_titular[id_cliente]</td>
		<td class=\"tdcamposr\">$f_titular[nombre]</td>
		<td class=\"tdtituloss\">$f_titular[nombres] $f_titular[apellidos] <a class=\"tdcamposr\"> $tomador</a>
	
		</td>   
		<td class=\"tdtituloss\">$f_titular[cedula]</td>
		<td class=\"tdtituloss\">$f_titular[estado_cliente]</td>
		<td class=\"tdtituloss\">$f_titular[fecha_nacimiento]</td>";
		
		/* $q_propiedades_poliza=("select 
									 tabla_coberturas_tmp_$codigo.monto_actual,
									 tabla_coberturas_tmp_$codigo.cualidad

							from 
									 tabla_coberturas_tmp_$codigo
							where 
									 tabla_coberturas_tmp_$codigo.id_titular=$f_titular[id_titular] and
									 tabla_coberturas_tmp_$codigo.id_beneficiario=0 and
									 tabla_coberturas_tmp_$codigo.monto>'0'");
$r_propiedades_poliza=ejecutar($q_propiedades_poliza); */
$q_propiedades_poliza=("select 
									coberturas_t_b.monto_actual,
									 propiedades_poliza.cualidad

							from 
									coberturas_t_b,
									propiedades_poliza
							where 
									 coberturas_t_b.id_titular=$f_titular[id_titular] and
									 coberturas_t_b.id_beneficiario=0 and
									 coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza");
$r_propiedades_poliza=ejecutar($q_propiedades_poliza); 

	while($f_propiedades_poliza=asignar_a($r_propiedades_poliza)){
		
?>
	<tr> 
		<td class="tdtituloss" colspan=6></td>
		<td class="tdcampos" colspan=1><?php echo $f_propiedades_poliza[cualidad];?></td>
		<td class="titulo_seccion" colspan=1><?php echo montos_print($f_propiedades_poliza[monto_actual]);?></td>
	 	</tr>              
	        

	<?php
	}

		$q_beneficiario=("select 
											beneficiarios.id_cliente,
											beneficiarios.id_parentesco,
											beneficiarios.id_beneficiario,
											parentesco.parentesco,
											clientes.apellidos,
											clientes.nombres,
											clientes.cedula,
											clientes.fecha_nacimiento,
											estados_clientes.estado_cliente
									from 
											clientes,
											estados_clientes, 
											beneficiarios, 
											estados_t_b,
											parentesco 
									where 
											clientes.id_cliente=beneficiarios.id_cliente and 
											beneficiarios.id_titular=$f_titular[id_titular] and
											beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
											estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente
											$condicion_estadob and 					 		
											parentesco.id_parentesco=beneficiarios.id_parentesco 
								order by 
											estados_clientes.estado_cliente,
											clientes.apellidos");
		$r_beneficiario=ejecutar($q_beneficiario);


		while($f_beneficiario=asignar_a($r_beneficiario)){
		$b=$b+1;	
	echo"
		<tr> 
	        
		<td class=\"tdtituloss\" colspan=8 >&nbsp;</td>
		<td class=\"tdtituloss\">$f_beneficiario[id_cliente]</td>
		<td class=\"tdtituloss\">$f_beneficiario[nombres] $f_beneficiario[apellidos]</td>   
		<td class=\"tdtituloss\">$f_beneficiario[cedula]</td>
		<td class=\"tdtituloss\">$f_beneficiario[estado_cliente]</td>
		<td class=\"tdtituloss\">$f_beneficiario[fecha_nacimiento]</td>
		<td class=\"tdtituloss\">$f_beneficiario[parentesco]</td>
		<td class=\"tdtituloss\"> ";?><?php echo calcular_edad($f_beneficiario['fecha_nacimiento']);

?></td>
<?php	     	
/* $q_propiedades_polizab=("select 
									 tabla_coberturas_tmp_$codigo.monto_actual,
									 tabla_coberturas_tmp_$codigo.cualidad
							from 
									 tabla_coberturas_tmp_$codigo
							where 
									 tabla_coberturas_tmp_$codigo.id_beneficiario=$f_beneficiario[id_beneficiario] and
									 tabla_coberturas_tmp_$codigo.monto>'0'");
		$r_propiedades_polizab=ejecutar($q_propiedades_polizab); */
		$q_propiedades_polizab=("select 
									 coberturas_t_b.monto_actual,
									 propiedades_poliza.cualidad
							from 
									coberturas_t_b,
									 propiedades_poliza
							where 
									 coberturas_t_b.id_beneficiario=$f_beneficiario[id_beneficiario] and
									 coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza");
		$r_propiedades_polizab=ejecutar($q_propiedades_polizab);
		
		
while($f_propiedades_polizab=asignar_a($r_propiedades_polizab)){
		
?>
<tr>
		<td class="tdtituloss" colspan=15></td>
		<td class="tdcampos" colspan=1><?php echo $f_propiedades_polizab[cualidad];?></td>
		<td class="titulo_seccion" colspan=1><?php echo montos_print($f_propiedades_polizab[monto_actual]);?></td>
</tr>	              
	        

	<?php
	}  
?>
		

	<?php
	}
	?>
	<tr>
	<td class=\"tdtituloss\" colspan=17 ><hr></hr></td>
		</tr>
	<?php
	}
	echo $t;
		echo "**";
	echo $b;
	 /* **** Eliminar tabla temporal**** */
/*
  $e_tabla_tem = "drop table tabla_coberturas_tmp_$codigo";
   $re_tabla_tem = ejecutar($e_tabla_tem);
*/
?>			

	<tr> 
		<td class="tdtitulos" colspan=17>final</td>
	 	
	</tr>
    
 
</table>
