<?php
include ("../../lib/jfunciones.php");
sesion();
$codbarra=trim($_POST['codbarra']);// la funcion trim($str, $charlist = null) elimina espacios en blanco al final y inicio de una cadena
$nomarticulo=trim($_POST['artinombre']);
$grupoarticulo=$_POST['grupoarte'];
$marcaarti=$_POST['marcarti'];
$psicotropi=$_POST['psicotro'];
$elus=$_SESSION['nombre_usuario_'.empresa];

//consultar el id tipo insumo es de un medicamento
$idmedicamento="select tbl_tipos_insumos.id_tipo_insumo from tbl_tipos_insumos where tbl_tipos_insumos.tipo_insumo='MEDICAMENTO' and tbl_tipos_insumos.id_tipo_insumo='$grupoarticulo';";
$idmedica=ejecutar($idmedicamento);
$idmedicamigual=num_filas($idmedica);

if($idmedicamigual>='1') {//recibir caracteristicas extras
$registrar_caracteristicas='true';//activa para registrar las caracteristicas al medicamento
$nom_comercial=mb_strtoupper(trim($_POST['noncomer']));
$nom_generico=mb_strtoupper(trim($_POST['generico']));
$prin_activo=mb_strtoupper(trim($_POST['princiactivo']));
$minimo_stock=trim($_POST['stok']);
$farmacologia=mb_strtoupper(trim($_POST['farmacologia']));
$extradescric=mb_strtoupper(trim($_POST['vardescr']));
}
else{$registrar_caracteristicas='false';}


if($psicotropi=='null')
  $vpsi=0;
else
  $vpsi=1;

$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];

$verexistente=("select tbl_insumos.id_insumo,tbl_insumos.insumo from tbl_insumos where tbl_insumos.codigo_barras='$codbarra' and tbl_insumos.codigo_barras<>'';");
//$verexistente=("select tbl_insumos.id_insumo,tbl_insumos.insumo from tbl_insumos where tbl_insumos.codigo_barras='$codbarra' and tbl_insumos.id_laboratorio=$marcaarti and tbl_insumos.codigo_barras<>'';");

$repverexistente=ejecutar($verexistente);
$cuantosexistente=num_filas($repverexistente);
if($cuantosexistente>=1){
  $datq=assoc_a($repverexistente);
  $noq=$datq['insumo'];
  $mensajexitente="El c&oacute;digo de barra ya esta registrado al producto $noq!!!";
}else{
		$guardararticulo=("insert into tbl_insumos(id_tipo_insumo,id_laboratorio,insumo,codigo_barras,psicotropico,fecha_hora_creado)
                               values ($grupoarticulo,$marcaarti,upper('$nomarticulo'),'$codbarra','$vpsi','$fecha');");

      //recuperar el id del registro insertado
		$repguardararticulo=ejecutar($guardararticulo);
		//verificaion si que los datos se insertaron y no hay errores
		 $nunafec=pg_affected_rows($repguardararticulo);
if($nunafec < 1) {
 $mensajexitente="Erro: el producto no pudo ser insertado $nunafec !!!";
}
else{
	$idselect="SELECT LASTVAL() AS id";
	$idcons=ejecutar($idselect);
	$id=asignar_a($idcons,NULL,PGSQL_ASSOC);
	if($registrar_caracteristicas=='true') {
			//insertar caracteristicas si
			$sqlcarcteristicas="insert into tbl_caract_medicamentos
				(id_insumo,nombre_comercial,nombre_generico,principio_activo, bajo_minimo,farmacologia,otras_caracteristicas)
			values ('$id[id]','$nom_comercial','$nom_generico','$prin_activo','$minimo_stock','$farmacologia','$extradescric');";
			$idcons=ejecutar($sqlcarcteristicas);

		}
////////////////////////////////INSERTAR EN EL ALMACEN DE PRECIOS en 0 ////
$sqlcontrolprecio="insert into tbl_insumos_almacen
(id_insumo,cantidad,monto_unidad_publico,monto_publico,fecha_hora_creado,id_dependencia,comentario)
VALUES ('$id[id]',0,0,0,'$fecha','2','CONTROL DE PRECIO');
";
$idControlprecio=ejecutar($sqlcontrolprecio);

$mensaje="El usuario $elus ha registrado el medicamento $nomarticulo";
$guardarlogs=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values(upper('$mensaje'),$elid,'$fecha','$hora','$ip');");
$repguardalogs=ejecutar($guardarlogs);
  $mensajexitente="El art&iacute;culo ($nomarticulo) ha sido registrado exitosamente!!";
	}

}

?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
 <tr>
    <td colspan=8 class="titulo_seccion"> <?echo $mensajexitente; ?></td>
   </tr>
</table>
