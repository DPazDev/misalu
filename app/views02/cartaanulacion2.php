<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' );
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$year=date("Y");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_REQUEST['loanulo'];
$contradoid=$_REQUEST['contrid'];
$numarti=$_REQUEST['artinumb'];
$cabezado=$_REQUEST['cabezado'];
$dataprin=("select 
tbl_caract_recibo_prima.id_titular,clientes.nombres,clientes.apellidos,clientes.cedula,polizas.nombre_poliza 
from  
tbl_caract_recibo_prima,clientes,titulares,polizas,titulares_polizas 
where 
tbl_caract_recibo_prima.id_recibo_contrato=$contradoid and 
tbl_caract_recibo_prima.id_titular=titulares.id_titular and
titulares.id_cliente=clientes.id_cliente and
titulares_polizas.id_titular=titulares.id_titular and
titulares_polizas.id_poliza=polizas.id_poliza
group by 
tbl_caract_recibo_prima.id_titular,clientes.nombres,clientes.apellidos,clientes.cedula,polizas.nombre_poliza");
$repprin=ejecutar($dataprin);
$dataprin=assoc_a($repprin);
$eltitulad=$dataprin['id_titular'];
//busco los datos principales del cliente como titular
$datcliente=("select clientes.cedula,clientes.nombres,clientes.apellidos from clientes,titulares
               where 
                clientes.id_cliente=titulares.id_cliente and
                titulares.id_titular=$eltitulad");
$repdatcliente=ejecutar($datcliente);  
$losdatcliente=assoc_a($repdatcliente);              
//fin de los datos basicos del cliente                
//guardo el registro de la exclusion
//busco si tiene beneficiarios
$datarticulo=("select * from articulocontrato where numarticulo='$numarti'");
$repdatarticulo=ejecutar($datarticulo);
$larticulo=assoc_a($repdatarticulo);
$elidarticulo=$larticulo['id_articulocon'];
$fecha=date("Y-m-d");
$hora=date("h:i:s");



$dia=date("d");
$mes=date("m");
$ano=date("Y");
if($mes == '01')
	$mes = "Enero";
elseif($mes == '02')
	$mes = "Febrero";
elseif($mes == '03')
	$mes = "Marzo";
elseif($mes == '04')
	$mes = "Abril";
elseif($mes == '05')
	$mes = "Mayo";
elseif($mes == '06')
	$mes = "Junio";
elseif($mes == '07')
	$mes = "Julio";
elseif($mes == '08')
	$mes = "Agosto";
elseif($mes == '09')
	$mes = "Septiembre";
elseif($mes == '10')
	$mes = "Octubre";
elseif($mes == '11')
	$mes = "Noviembre";
elseif($mes == '12')
	$mes = "Diciembre";

//$fecha=fecha_espanol(date("Y-M-d"));
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">
</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td  colspan=1 class="titulo1"><?php  echo date(" d"); echo " de "; echo ($mes); echo " de "; echo date("Y")?></td>
</tr>

<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
	<tr>
    <br>
    <br>
		<td colspan=6 align="center" class="titulo3"><strong>RESOLUCI&Oacute;N DEL CONTRATO</strong></td>
</tr>


<tr>	
			<td colspan=4 class="datos_cliente">
			<br> 
			</td>
			
		</tr>


<tr>
	<td colspan=4 ></td></tr>
<tr>
	<td colspan=4 class="datos_cliente"><br>
	
	
</td></tr>


		<tr>	
			<td colspan=4 class="datos_cliente"> <br>
Estimado Sr. (a) <b><?echo "$dataprin[nombres] $dataprin[apellidos]"?></b> titular de la C.I.  <b><?echo "$dataprin[cedula]"?></b>, N° de contrato:
<b><?echo $cabezado?></b>;  <b>Clinisalud Medicina Prepagada S.A. Rif: J-31180863-9</b>, cumple con comunicarle que su <b><?php echo "$dataprin[nombre_poliza]"; ?></b> y todos 
aquellos reembolsos que correspondan a Ud., y a sus beneficiarios, fueron anulados, de acuerdo con &nbsp;&nbsp;  <b><?echo $numarti?>, &nbsp;&nbsp; <?echo "$larticulo[nombre_articulo]"?></b> de <b><?echo "$larticulo[nombreley]"?></b>; 
el cual reza el siguiente enunciado:  
			</td>
			
		</tr>
<tr>	
			<td colspan=4 class="datos_cliente"><br>
			<?echo "$larticulo[concepto]"?>
		
			</td>
			
		</tr>
		


<tr>	
			<td colspan=4 >
			
<br><br>

			</td>
			
		</tr>
<tr>	
			<td colspan=4 >
			<br>
			</td>
			
		</tr>

<tr>	
			<td colspan=4 >
			<br><br>
Atentamente.
			</td>
			
		</tr>
<tr>
<br><br><br><br><br><br>
<td colspan=1 class="titulo3">
<br><br>
_____________________________
</td>
<td colspan=1  class="titulo3">

</td>
<td colspan=2 class="titulo3">
<br><br>
_____________________________
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
Operador: <?php echo "$elus"?> 
</td>
<td colspan=1 class="titulo3">

</td>
<td colspan=2 class="titulo3">
Titular: <?echo "$losdatcliente[nombres] $losdatcliente[apellidos]"?><br>
C.I <?echo "$losdatcliente[cedula]"?><br>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_FISCAL; ?>
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_VIGIA;?><br>
<?php echo  DIRECCION_QUIROFANO;?>
</td>

</tr>
</table>	
