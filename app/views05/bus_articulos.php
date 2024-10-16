<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$lasdependencias=("select tbl_dependencias.id_dependencia,tbl_dependencias.dependencia 
    from tbl_dependencias order by tbl_dependencias.dependencia;");
$replasdependencias=ejecutar($lasdependencias);
$tiposinsumos=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo from
tbl_tipos_insumos
order by tbl_tipos_insumos.tipo_insumo;");
$quertipoinsumo=ejecutar($tiposinsumos);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Buscar art&iacute;culos por dependencia o por c&oacute;digo</td>  
         <td class="titulo_seccion"><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<br>
    <tr>
      <td class="tdtitulos">C&oacute;digo de art&iacute;culo &oacute; Aproximación de nombre:</td>
      <td class="tdcampos"><input type="text" id="codartic" class="campos" size="35"></td>
    </tr>
    <tr>
       <td class="tdtitulos">Dependencia:</td>
       <td class="tdcampos"  colspan="1">
       <select id="dependencias" class="campos"  style="width: 230px;" >
           <option value=""></option>
           <option value="0">TODAS</option>
           <?php
              while($lasdependencias=asignar_a($replasdependencias,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $lasdependencias[id_dependencia]?>">
                     <?php echo "$lasdependencias[dependencia]"?>
               </option>
             <?}?>
          </select>
        </td>
     </tr>
     <tr>
       <td class="tdtitulos">Tipo insumo:</td>
       <td class="tdcampos"  colspan="1">
       <select id="insumoid" class="campos"  style="width: 230px;" >
           <option value=""></option>
           <option value="0">TODOS</option>
           <?php
              while($losinsumos=asignar_a($quertipoinsumo,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $losinsumos[id_tipo_insumo]?>">
                     <?php echo "$losinsumos[tipo_insumo]"?>
               </option>
             <?}?>
          </select>
        </td>
     </tr>
   <tr>
     <td title="Busquedad de art&iacute;culos"><label class="boton" style="cursor:pointer" onclick="BusquedadArti(); return false;" >Buscar</label></td>
     <td title="Reporte de control de articulos"><label class="boton" style="cursor:pointer" onclick="ReportContArti(); return false;" >Reporte</label></td>
   </tr>
 
</table>   
<img alt="spinner" id="spinnerARTI" src="../public/images/esperar.gif" style="display:none;" />  
<div id="muestra_articulo"></div>
