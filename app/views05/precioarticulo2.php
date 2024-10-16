<?
include ("../../lib/jfunciones.php");
sesion();
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
$idproducto=$_POST['idarticulo'];
//$ladependencia=$_POST['dependencia'];

$buscarelprod=("select
tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_laboratorios.laboratorio,
tbl_laboratorios.id_laboratorio,tbl_tipos_insumos.tipo_insumo,
tbl_tipos_insumos.id_tipo_insumo,tbl_insumos_almacen.id_dependencia,
tbl_insumos_almacen.monto_unidad_publico,tbl_dependencias.dependencia
from
   tbl_insumos,tbl_laboratorios,tbl_tipos_insumos,tbl_insumos_almacen,tbl_dependencias
where
tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
tbl_insumos.id_insumo=$idproducto and tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
(tbl_insumos_almacen.id_dependencia=$ladependencia or tbl_insumos_almacen.id_dependencia=2) and
tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia order by tbl_insumos_almacen.id_dependencia DESC;");
$repbuscarlpro=ejecutar($buscarelprod);
$datbuscapro=assoc_a($repbuscarlpro);
?>
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
   <tr>
     <td colspan=4 class="titulo_seccion">Informaci&oacute;n del art&iacute;culo</td>
   </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
    <tr>
         <td class="tdtitulos">Nombre del art&iacute;culo:</td>
         <td class="tdcampos"><?echo $datbuscapro['insumo']?></td>
   </tr>
   <tr>
         <td class="tdtitulos">Laboratorio:</td>
         <td class="tdcampos"><?echo $datbuscapro['laboratorio']?></td>
   </tr>
   <tr>
         <td class="tdtitulos">Tipo de insumo:</td>
         <td class="tdcampos"><?echo $datbuscapro['tipo_insumo']?></td>
   </tr>
   <tr>
         <td class="tdtitulos">Dependencia:</td>
         <td class="tdcampos"><?echo $datbuscapro['dependencia']?></td>
   </tr>
   <tr>
         <td class="tdtitulos">Monto actual:</td>
         <td class="tdcampos"><?echo $datbuscapro['monto_unidad_publico'];
                                                if($datbuscapro['monto_unidad_publico']==0){
                                                     $datbuscapro[monto_unidad_publico]="1";
                                                    }?></td>
   </tr>
   <tr>
         <td class="tdtitulos">Nuevo monto:</td>
         <td class="tdcampos"><input type="text" id="nuevomonto" class="campos" size="10"></td>
   </tr>
   <tr>
         <td  title='Editar el art&iacute;culo'><label id='cambiapre' class='boton' style='cursor:pointer' onclick="CambiarPre(<?php echo $datbuscapro[id_insumo]?>,<?php echo $ladependencia?>,<?php echo $datbuscapro[monto_unidad_publico]?>,document.getElementById('nuevomonto').value); return false;">Guardar</label></td>
   </tr>

 </table>
 <img alt="spinner" id="spinner1" src="../public/images/esperar.gif" style="display:none;" />
 <div id='cambiaprecio'></div>
