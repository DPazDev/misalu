<?
include ("../../lib/jfunciones.php");
sesion();
$elnombarpro=strtoupper($_POST["nombaprox"]);
$elgruplab=$_POST["loslabora"];
$elgruparti=$_POST["elgruparti"];
$elid=$_SESSION['id_usuario_'.empresa];
$busdepar=("select admin.id_departamento from admin where id_admin=$elid");
$repdatdepar=ejecutar($busdepar);
$datbusdepar=assoc_a($repdatdepar);
$iddepart=$datbusdepar[id_departamento];
if($iddepart==6){
     $ladependencia=64;
    }else{
        $ladependencia=89;
    }

if((!empty($elnombarpro))and($elgruplab<=0)and($elgruparti<=0)){
      $queryb="and tbl_insumos.insumo like('%$elnombarpro%') order by tbl_insumos.insumo";
    }

  if(($elgruplab>=1) and (empty($elnombarpro)) and ($elgruparti<=0)){
      $queryb="and tbl_insumos.id_laboratorio=$elgruplab order by tbl_insumos.insumo";
  }
   if(($elgruparti>=1) and (empty($elnombarpro)) and ($elgruplab<=0)){
      $queryb="and tbl_insumos.id_tipo_insumo=$elgruparti order by tbl_insumos.insumo";
  }

  $busquedadarti=("select
  tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_laboratorios.laboratorio,
  tbl_tipos_insumos.tipo_insumo,tbl_insumos_almacen.monto_unidad_publico,tbl_insumos.codigo_barras,
  tbl_insumos_almacen.id_dependencia
  from
  tbl_insumos,tbl_laboratorios,tbl_tipos_insumos,tbl_insumos_almacen
  where
  tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
  tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
  tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
  (tbl_insumos_almacen.id_dependencia=$ladependencia or tbl_insumos_almacen.id_dependencia=2) $queryb;");
$repbusqarti=ejecutar($busquedadarti);
$cuantoshay=num_filas($repbusqarti);
if($cuantoshay==0){
   echo"<table class=\"tabla_cabecera3\" cellpadding=0 cellspacing=0>
         <tr>
	   <td colspan=4 class=\"titulo_seccion\">No existen art&iacute;culos cargados!!!</td>
         </tr>
       </table>";
}else{
?>
  <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
         <tr>
	   <td colspan=4 class="titulo_seccion">Art&iacute;culos existentes</td>
         </tr>
       </table>
  <table class="tabla_citas"  cellpadding=0 cellspacing=0>
		   <TR>
		        <TH>Art&iacute;culo</TH>
            <TH>C&oacute;digo</TH>
		        <TH>Laboratorio</TH>
		        <TH>Tipo insumo</TH>
				<TH>Monto</TH>
			<TH>Opci&oacute;n</TH>
		  </TR>
   <?while($losartison=asignar_a($repbusqarti,NULL,PGSQL_ASSOC)){
       $iddelinsumo=$losartison['id_insumo'];
       $nomdelarti=$losartison['insumo'];
       $nomlaborat=$losartison['laboratorio'];
	   $montoarti=$losartison['monto_unidad_publico'];
       $tipisumos=$losartison['tipo_insumo'];
       $codigoin=$losartison['codigo_barras'];
       $dep=$losartison['id_dependencia'];
    ?>
       <tr>
         <td class="tdcampos"><?echo $nomdelarti;?></td>
         <td class="tdcampos"><?echo $codigoin;?></td>
         <td class="tdcampos"><?echo $nomlaborat.'-'.$dep;?></td>
         <td class="tdcampos"><?echo $tipisumos;?></td>
		     <td class="tdcampos"><?echo $montoarti;?></td>
         <td  title='Editar el art&iacute;culo'><label class='boton' style='cursor:pointer' onclick="new Effect.Puff('respbusarti'), EditarticuloPre(<?echo $iddelinsumo?>,<?echo $dep?>); return false;">Editar</label></td>
       </tr>
<?}?>
 </table>
<?}?>
