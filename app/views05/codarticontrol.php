<?
include ("../../lib/jfunciones.php");
sesion();
$query=("select tbl_insumos.insumo,tbl_insumos.codigo_barras,tbl_laboratorios.laboratorio,tbl_tipos_insumos.tipo_insumo from tbl_insumos,tbl_laboratorios,tbl_tipos_insumos where
tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
tbl_laboratorios.id_laboratorio=tbl_insumos.id_laboratorio and
tbl_insumos.fecha_hora_creado>='2012-04-25' order by tipo_insumo,insumo;");
$rquery=ejecutar($query);
?>

<html>
<head>
<title>Sistema Administrativo de CliniSalud C.A.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
	body {
	font-family: verdana;
	margin-top: 0px;
	font-size: 12px;
	}
</style>
</head>

<table border=0 cellpadding=2 cellspacing=2 width="100%">
		<tr>
            <td ><b>Codigo de barra.</b></td> 
			<td ><b>Articulo</b></td>
			<td ><b>Marca</b></td>
			<td ><b>Tipo insumo</b></td>
            <td ><b>Cantidad</b></td>
		</tr>
  <?php
	while($mosarti=asignar_a($rquery,NULL,PGSQL_ASSOC)){  ?>      
    <tr>
           <td ><?echo  $mosarti['codigo_barras']?></td>
			<td ><?echo $mosarti['insumo']?></td>
			<td ><?echo $mosarti['laboratorio']?></td>
			<td ><?echo $mosarti['tipo_insumo']?></td>
            <td >__________</td>
		</tr>
	<?
	}?>
 </table>   
