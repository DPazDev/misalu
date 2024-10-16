<?
include ("../../lib/jfunciones.php");
sesion();
$idproducto=$_POST['idarticulo'];
$buscarelprod=("select tbl_insumos.insumo,tbl_laboratorios.laboratorio,tbl_laboratorios.id_laboratorio,tbl_tipos_insumos.tipo_insumo,
tbl_tipos_insumos.id_tipo_insumo,tbl_insumos.activo,tbl_insumos.codigo_barras
from
  tbl_insumos,tbl_laboratorios,tbl_tipos_insumos
 where
tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
tbl_insumos.id_insumo=$idproducto;");
$repbuscarlpro=ejecutar($buscarelprod);
$datadelpro=assoc_a($repbuscarlpro);
$nopro=$datadelpro['insumo'];
$nopro1=$datadelpro['insumo'];
$labora=$datadelpro['laboratorio'];
$elcodbar=$datadelpro['codigo_barras'];
$grupo=$datadelpro['tipo_insumo'];
$estado=$datadelpro['activo'];
$idtipoinsumo=$datadelpro['id_tipo_insumo'];
$idlabinsumo=$datadelpro['id_laboratorio'];
$dependenciasarti=("select tbl_dependencias.dependencia from tbl_dependencias,tbl_insumos_almacen where
tbl_insumos_almacen.id_insumo=$idproducto and tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and tbl_dependencias.esalmacen=1;");
$repdepenarti=ejecutar($dependenciasarti);
$querytipoinsumos=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo
  from tbl_tipos_insumos
order by tbl_tipos_insumos.tipo_insumo;");
$repquerytipoinsumos=ejecutar($querytipoinsumos);
$laboratorios=("select tbl_laboratorios.id_laboratorio,tbl_laboratorios.laboratorio from tbl_laboratorios order by tbl_laboratorios.laboratorio;");
$replaboratorios=ejecutar($laboratorios);
?>
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
   <tr>
     <td colspan=4 class="titulo_seccion">Informaci&oacute;n del art&iacute;culo</td>
   </tr>
</table>
<input type="hidden" id="nombreactual" value="<?echo $nopro1?>" >
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
   <tr>
    <td class="tdtitulos">Nombre:</td>
    <td class="tdcampos"  colspan="1"><?echo $nopro?></td>
    <td class="tdtitulos">Laboratorio:</td>
    <td class="tdcampos"  colspan="1"><?echo $labora?></td>
    <td class="tdtitulos">Grupo del art&iacute;culo:</td>
    <td class="tdcampos"  colspan="1"><?echo $grupo?></td>
    <td class="tdtitulos">Estatus:</td>
    <td class="tdcampos"  colspan="1"><?
         if($estado==1)
            {echo"Activo";
                $bloqueo='';
                $activo='checked';
            }
         else
           {echo"Bloqueado";
             $bloqueo='checked';
             $activo='';
           }
         ?></td>
   </tr>
   <tr>
     <td class="tdtitulos">Bloquear art&iacute;culo?</td>
      <td class="tdcampos"  colspan="1">
        <input type="radio" name="bloq" id="bloqueo" value="0" <?php echo $bloqueo; ?> >Si
        <input type="radio" name="bloq" id="activo" value="1" <?php echo $activo; ?> >No</td>
   </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
   <tr>
    <td class="tdtitulos">C&oacute;digo de barra:</td>
    <td class="tdcampos"><input type="text" id="cobarra" class="campos" size="45" value="<?echo $elcodbar?>"></td>
   </tr>
   <tr>
    <td class="tdtitulos">Nombre:</td>
    <td class="tdcampos"><input type="text" id="nombrearti" class="campos" size="45" value="<?echo $nopro?>"></td>
   </tr>
   <tr>
    <td class="tdtitulos">Laboratorio:</td>
    <td class="tdcampos"  colspan="1">
        <select id="laboratorio" class="campos"  style="width: 230px;" >
        <option value="<?echo $idlabinsumo?>"><?echo $labora ?></option>
           <?php
              while($labora=asignar_a($replaboratorios,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $labora[id_laboratorio]?>">
                     <?php echo "$labora[laboratorio]"?>
               </option>
             <?}?>
          </select>
    </td>
   </tr>
   <tr>
    <td class="tdtitulos">Grupo del art&iacute;culo:</td>
    <td class="tdcampos"  colspan="1">
      <select id="grupoarticulo" class="campos"  style="width: 230px;" >
           <option value="<?echo $idtipoinsumo?>"><?echo $grupo?></option>
           <?php
              while($grupoarti=asignar_a($repquerytipoinsumos,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $grupoarti[id_tipo_insumo]?>">
                     <?php echo "$grupoarti[tipo_insumo]"?>
               </option>
             <?}?>
          </select>
    </td>
   </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
   <tr>
        <td  title="Guardar cambios en art&iacute;culo"><label class="boton" style="cursor:pointer" onclick="Aptuarti2(); return false;" >Guardar</label></td>
        <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
   </tr>
</table>
<BR>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
<input type=hidden id="elarti" value="<?echo $idproducto?>">
