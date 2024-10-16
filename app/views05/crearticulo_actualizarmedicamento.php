<?php

include ("../../lib/jfunciones.php");
sesion();

$idinsumo=$_POST['idinsumo'];
$ps=$_POST['psi'];
$comer=mb_strtoupper(trim($_POST['nomcomer']));
$gene=mb_strtoupper(trim($_POST['nomgener']));
$prinAct=mb_strtoupper(trim($_POST['prinacti']));
$bajoM=trim($_POST['ministock']);
$farmacologia=mb_strtoupper($_POST['famaco']);
$caracteristicas=mb_strtoupper(trim($_POST['descri']));

$sqlinsum="SELECT   
						tbl_insumos.id_tipo_insumo,
						tbl_insumos.id_laboratorio, 
  						tbl_insumos.insumo, 
  						tbl_insumos.registro, 
  						tbl_insumos.codigo_barras, 
  						tbl_insumos.psicotropico, 
  						tbl_caract_medicamentos.nombre_comercial, 
  						tbl_caract_medicamentos.nombre_generico, 
  						tbl_caract_medicamentos.principio_activo, 
  						tbl_caract_medicamentos.bajo_minimo, 
  						tbl_caract_medicamentos.farmacologia, 
  						tbl_caract_medicamentos.otras_caracteristicas, 
  						tbl_caract_medicamentos.id_insumo,
  						tbl_caract_medicamentos.id_caract_medicamento
				FROM 
  						public.tbl_caract_medicamentos,public.tbl_insumos
				WHERE 
  						tbl_insumos.id_insumo = tbl_caract_medicamentos.id_insumo and tbl_insumos.id_insumo='$idinsumo';";

$insumo=ejecutar($sqlinsum);
$numfilas=num_filas($insumo);
$in=asignar_a($insumo,NULL,PGSQL_ASSOC);

$id_insumo=$in[id_insumo];
$id_carac=$in[id_caract_medicamento];
$tipo=$in[id_tipo_insumo];
$laboratorio=$in[id_laboratorio];
$insumo=$in[insumo];
$registro=$in[registro];
$cod_barra=$in[codigo_barras];

$psco=$in[psicotropico];
$ncomerc=$in[nombre_comercial];
$ngenerc=$in[nombre_generico];
$princip=$in[principio_activo];
$bajo_m=$in[bajo_minimo];
$farmaco=$in[farmacologia];
$caracte=$in[otras_caracteristicas];

/*
echo"nuevas caraceriticas<br>
$id_insumo <br>
$tipo <br>$laboratorio<br>$insumo<br>$registro<br>$cod_barra<br>$psco<br>$ncomerc<br>$ngenerc<br>$princip<br>$bajo_m<br>$farmaco<br>$caracte";
*/

if($numfilas>=1) {
//vrificar que se va a actualizar
if($psco==$ps){
$sqlps="UPDATE public.tbl_insumos SET psicotropico='$ps' WHERE id_insumo='$idinsumo'";	
	$actulicepsi=ejecutar($sqlps);
	}

$actualiza="UPDATE
public.tbl_caract_medicamentos 
SET 
nombre_comercial='$comer',nombre_generico='$gene',principio_activo='$prinAct',bajo_minimo='$bajoM',farmacologia='$farmacologia',otras_caracteristicas='$caracteristicas'
 WHERE id_caract_medicamento='$id_carac' and tbl_caract_medicamentos.id_insumo='$id_insumo'";
 $actualcaracteris=ejecutar($actualiza);
 if($num=pg_affected_rows($actualcaracteris)>=1)
 	{echo"<script>alert('Actualizacion de caracteristicas Exitos')</script> ";}else {echo"<script>alert('lo siento a ocurrido un error en el registro')</script>";}
}
else {
$sqlinsertar="
INSERT INTO public.tbl_caract_medicamentos (id_insumo,nombre_comercial,nombre_generico,principio_activo,bajo_minimo,farmacologia,otras_caracteristicas)
					VALUES ('$idinsumo','$comer','$gene','$prinAct','$bajoM','$farmacologia','$caracteristicas')";	
 $incercaracte=ejecutar($sqlinsertar);
 if($num=pg_affected_rows($incercaracte)>=1)
 	{echo"<script>alert('Reguistro de caracteristicas Exitos')</script>";}else {echo"<script>alert('lo siento a ocurrido un error en el registro')</script>";}
	
	}//insertar


 ?>
