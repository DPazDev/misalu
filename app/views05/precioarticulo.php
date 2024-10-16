<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$laboratorios=("select tbl_laboratorios.id_laboratorio,tbl_laboratorios.laboratorio from tbl_laboratorios order by tbl_laboratorios.laboratorio;");
$replaboratorios=ejecutar($laboratorios);
$grupoarticulo=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo 
from tbl_tipos_insumos order by tbl_tipos_insumos.tipo_insumo;");
$repgrupoarticulo=ejecutar($grupoarticulo);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=4 class="titulo_seccion">Control de precios para art&iacute;culos</td>  
     </tr>
     <tr>
<td colspan=8 class="titulo_seccion">Buscar art&iacute;culo por una de las siguientes opciones.</td> 
   </tr> 
</table>	
<table class="tabla_cita"  cellpadding=0 cellspacing=0>
<br>
    <tr> 
         <td class="tdtitulos">Por aproximaci&oacute;n de nombre:</td>
         <td class="tdcampos"><input type="text" id="aroxnombre" class="campos" size="10"></td>

        <td class="tdtitulos">Grupo de art&iacute;culo:</td>
         <td class="tdcampos"  colspan="1">
       <select id="grupoarti" class="campos"  style="width: 200px;" >
           <option value=""></option>
           <?php
              while($grupoarti=asignar_a($repgrupoarticulo,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $grupoarti[id_tipo_insumo]?>">
                     <?php echo "$grupoarti[tipo_insumo]"?>
               </option>
             <?}?>
          </select>
        </td>  
        <td class="tdtitulos">Laboratorio:</td>
         <td class="tdcampos"  colspan="1">
       <select id="grupolabor" class="campos"  style="width: 200px;" >
           <option value=""></option>
           <?php
              while($grupoarti=asignar_a($replaboratorios,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $grupoarti[id_laboratorio]?>">
                     <?php echo "$grupoarti[laboratorio]"?>
               </option>
             <?}?>
          </select>
        </td> 
     </tr>
     <tr> <br></tr> 
     <tr> 
     
             <td class="tdtitulos"></td>
             <td class="tdtitulos"></td>
             <td class="tdtitulos"></td> 
             <td  title="Buscar art&iacute;culo seg&uacute;n opci&oacute;n"><label class="boton" style="cursor:pointer" onclick="Precioarti(); return false;" >Buscar</label></td>
             <td class="tdtitulos"></td>
     </tr>
</table>
<div id='respbusarti'></div>
<div id='articuloson'></div>