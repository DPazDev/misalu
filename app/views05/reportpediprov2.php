<?php

include ("../../lib/jfunciones.php");
sesion();

$idordenpedido=$_REQUEST['idordepe'];
$buscacomen=("select tbl_ordenes_entregas.comentario where tbl_ordenes_entregas.id_orden_entrega=$idordenpedido");
$repbuscacomen=ejecutar($buscacomen);
$datacomen=assoc_a($repbuscacomen);
$elcoment=$datacomen['comentario'];
//Buscar los articulos del pedido//
$buscarproductos=("select tbl_insumos_ordenes_entregas.id_insumo,
       tbl_insumos_ordenes_entregas.cantidad,
       tbl_laboratorios.laboratorio,
       tbl_insumos.insumo
from   
       tbl_insumos_ordenes_entregas,tbl_laboratorios,tbl_insumos
where
   tbl_insumos_ordenes_entregas.id_orden_entrega=$idordenpedido and
   tbl_insumos.id_insumo=tbl_insumos_ordenes_entregas.id_insumo and 
   tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio order by insumo;");
$repbuscarproductos=ejecutar($buscarproductos);								   
//Fin de la Busquedad//
//Buscamos para quien es el pedido y quien lo hizo//
$buscarresponsa=("select admin.nombres,
       admin.apellidos,tbl_ordenes_entregas.hora_entrega,
       tbl_ordenes_entregas.fecha_emision,tbl_dependencias.dependencia,
       tbl_ordenes_entregas.no_orden_entrega
                from
                   tbl_ordenes_entregas,admin,tbl_dependencias
                where
                  tbl_ordenes_entregas.id_orden_entrega=$idordenpedido and
                  tbl_ordenes_entregas.id_admin=admin.id_admin and
                  tbl_ordenes_entregas.id_dependencia=tbl_dependencias.id_dependencia;");
$repbuscarespon=ejecutar($buscarresponsa);								 
$databuscarespon=assoc_a($repbuscarespon);
$quienlohizo="$databuscarespon[nombres] $databuscarespon[apellidos] ";
$paraquien="Inventario muerto";
$noorden=$databuscarespon['no_orden_entrega'];
$lahora=$databuscarespon['hora_entrega'];
$lafecha=$databuscarespon['fecha_emision'];
//fin de la busquedad//
list($anio,$mes,$dia)=explode("/",date("Y/m/d"));

function ver_mes($mes){

if($mes==1){ $mes="Enero"; }
else if($mes==2){ $mes="Febrero";}
else if($mes==3){ $mes="Marzo";}
else if($mes==4){ $mes="Abril";}
else if($mes==5){ $mes="Mayo";}
else if($mes==6){ $mes="Junio";}
else if($mes==7){ $mes="Julio";}
else if($mes==8){ $mes="Agosto";}
else if($mes==9){ $mes="Septiembre";}
else if($mes==10){ $mes="Octubre";}
else if($mes==11){ $mes="Noviembre";}
else if($mes==12){ $mes="Diciembre";}

return $mes;
}

$mes=ver_mes($mes);

list($hora,$minutos,$segundos)=explode(":",date("H:i:s"));
if($hora>12){
	$tiempo="PM";
	$hora-=12;
}else{
	$tiempo="AM";
}


list($anio_p,$mes_p,$dia_p)=explode("-",$lafecha);
$mes_p=ver_mes($mes_p);

list($hora_p,$minutos_p,$segundos_p)=explode(":",$lahora);
if($hora_p>12){
	$tiempo_p="PM";
	$hora_p-=12;
}else{
	$tiempo_p="AM";
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Sistema Administrativo de CliniSalud C.A.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
	body {
	font-family: verdana;
	margin-top: 0px;
	color: #000000;
	font-size: 12px;
	}
</style>
</head>
<body bgcolor="#ffffff;">
<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td colspan=2 align="center">
		<table border=0 cellpadding=0 cellspacing=0 width="100%">
		<tr>
		<td width="110" valign="top"><img src="../../public/images/head.png" width="104" height="74"><br>
		Rif: J-31180863-9<br>
		</td>
		<td class="titulo_seccion" align="right" valign="top" style="padding-top: 10px;padding-right: 10px;">
		M&eacute;rida, <?php echo "$dia de $mes de $anio"; ?><br>
		Hora: <?php echo "$hora:$minutos:$segundos $tiempo"; ?><br>
		<br>
		</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>		<td colspan=2 class="titulos" align="center">
			<br>
			Orden de Pedido de Insumos No. <?php echo $noorden;?> para <?php echo $paraquien; ?>
			</td>
	</tr>
	<tr>	
		<td colspan=2 class="titulos">
		<br><br>Por medio de la presente hago constar que se hace entrega de los siguientes insumos a <?php echo "<b>$paraquien</b>. </b>
		<br><br>
		Fecha del pedido: $dia_p de $mes_p de $anio_p<br>Hora del pedido: $hora_p:$minutos_p:$segundos_p $tiempo_p"; ?><br>
                Comentario: <?php echo $elcoment;?> <br><br>

		</td>	</tr>
	<tr>
		<td colspan=2>
		<br>
		<table border=0 cellpadding=2 cellspacing=2 width="100%">
		<tr>
			<td width="43%" class="titulos"><b>Nombre</b></td>
			<td width="23%" class="titulos"><b>Cantidad</b></td>
			<td width="33%" class="titulos"><b>Laboratorio</b></td>
		</tr>
	<?php
	while($f_orden2=asignar_a($repbuscarproductos,NULL,PGSQL_ASSOC)){
	echo "
		<tr>
			<td class=\"titulos\">$f_orden2[insumo]</td>
			<td class=\"titulos\">$f_orden2[cantidad]</td>
			<td class=\"titulos\">$f_orden2[laboratorio]</td>
		</tr>
		";
	}
	?>
		</table>
		</td>
	</tr>	
	<tr>
	<td colspan=2 style="padding-left: 10px; padding-right: 10px;">
	<br><br><br>
	<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td align="left">Solicitado por: <br><?php echo "$quienlohizo";?></td>
		<td align="right">Recibido por: <br><?php echo "$paraquien";?></td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
	<td colspan=2 align="center">
	<br><br><br>
		M&eacute;rida: Calle 25 entre Av. 7 y 8 Edif. El Cisne; Tlfs: (0274) 251.00.28 / 251.00.92 / 251.09.10<br>
		El Vig&iacute;a Av. Bol&iacute;var esquina con Av. 12 calle 6 Edif. Liegos; Tlfs: (0275) 881.20.17 / Fax: 881.34.36
	</td>
	</tr>
</table>
<script type="text/javascript" language="javascript">
	window.print();
	window.close();
</script>
<?php
echo pie();
?>
